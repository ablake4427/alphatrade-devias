<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\CoinPair;
use App\Models\FavoritePair;
use App\Models\GatewayCurrency;
use App\Models\Market;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TradeController extends Controller {
    public function trade($symbol = null) {
        $pair = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::SPOT_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('market', 'coin', 'marketData');

        if ($symbol) {
            $pair = $pair->where('symbol', $symbol)->first();
        } else {
            $pair = $pair->where('is_default', Status::YES)->first();
        }

        if (!$pair) {
            $notify[] = ['error', 'No pair found'];
            return back()->withNotify($notify);
        }

        $markets              = Market::with('currency:id,name,symbol')->active()->get();
        $userId               = auth()->id() ?? 0;
        $coinWallet           = Wallet::where('user_id', $userId)->where('currency_id', $pair->coin->id)->spot()->first();
        $marketCurrencyWallet = Wallet::where('user_id', $userId)->where('currency_id', $pair->market->currency->id)->spot()->first();

        $orders = Order::where('user_id', auth()->id())->take(30)->get();

        $gateways = GatewayCurrency::where(function ($q) use ($pair) {
            $q->where('currency', @$pair->coin->symbol)->orWhere('currency', $pair->market->currency->symbol);
        })->whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method:id,code,crypto')->get();

        $pageTitle = showAmount($pair->marketData->price, currencyFormat: false) . ' | ' . $pair->symbol;

        return view('Template::trade.index', compact('pageTitle', 'pair', 'markets', 'coinWallet', 'marketCurrencyWallet', 'gateways'));
    }

    public function history($symbol) {
        $pair = $this->findPair($symbol);

        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => "Coin Pair not found",
            ]);
        }
        $trades = Trade::where('pair_id', $pair->id)->orderBy('id', 'desc')->take(50)->get();
        return response()->json([
            'success' => true,
            'trades'  => $trades,
        ]);
    }
    public function orderList(Request $request, $symbol) {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:all,open,canceled,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $query = Order::with('pair')->where('user_id', auth()->id());

        if ($request->status && $request->status != 'all') {
            $scope = $request->status;
            $query->$scope();
        }

        $orders = $query->orderBy('id', 'desc')->take(20)->get();

        return response()->json([
            'success' => true,
            'orders'  => $orders,
        ]);
    }

    public function orderBook($symbol = null) {
        $pair = $this->findPair($symbol);

        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => "Coin Pair not found",
            ]);
        }

        $orderType = request()->order_type;
        $query     = Order::open()->where('orders.pair_id', $pair->id)
            ->select('orders.*')
            ->leftJoin('trades', 'orders.id', 'trades.order_id')
            ->selectRaw("SUM(orders.amount) as total_amount")
            ->selectRaw("COUNT(DISTINCT orders.id) as total_order")
            ->selectRaw("COUNT(DISTINCT trades.id) as total_trade")
            ->selectRaw('MAX(CASE WHEN orders.user_id = ? THEN 1 ELSE 0 END)  AS has_my_order', [auth()->id()])
            ->groupBy('orders.rate')
            ->orderBy('orders.rate', 'DESC');

        if ($orderType == 'all' || $orderType == 'sell') {
            $sellSideOrders = (clone $query)->sellSideOrder()->take(13)->get();
        }
        if ($orderType == 'all' || $orderType == 'buy') {
            $buySideOrders = (clone $query)->buySideOrder()->take(13)->get();
        }

        return response()->json([
            'success'          => true,
            'sell_side_orders' => @$sellSideOrders ?? [],
            'buy_side_orders'  => @$buySideOrders ?? [],
        ]);
    }

    private function findPair($symbol = null) {
        $pair = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::SPOT_TRADE)->orWhere('type', Status::BOTH_TRADE);
        });
        if ($symbol) {
            $pair = $pair->where('symbol', $symbol)->first();
        } else {
            $pair = $pair->where('is_default', Status::YES)->first();
        }
        return $pair;
    }

    public function pairs() {
        $query = CoinPair::active()->activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::SPOT_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('coin:name,id,symbol', 'market:id,name,currency_id', 'market.currency:id,symbol', 'marketData:id,pair_id,price,html_classes,percent_change_1h');

        if (request()->marketId) {
            $query->where('market_id', request()->marketId);
        }

        if (request()->search) {
            $query->where('symbol', 'Like', "%" . request()->search . "%");
        }

        $pairs          = $query->orderBy('id', 'desc')->take(50)->get();
        $favoritePairId = FavoritePair::where('user_id', auth()->id() ?? 0)->where('pair_id', '>=', 0)->pluck('pair_id')->toArray();

        return response()->json([
            'success'        => true,
            'pairs'          => $pairs,
            'favoritePairId' => $favoritePairId,
        ]);
    }
}
