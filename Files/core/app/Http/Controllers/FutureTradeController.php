<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Constants\Status;
use App\Models\FutureTrade;
use App\Models\FavoritePair;
use App\Models\FutureQueueOrder;
use App\Models\FutureTradeConfig;
use Illuminate\Support\Facades\DB;

class FutureTradeController extends Controller
{
    public function __construct()
    {
        if(!gs('future_trade')){
            abort(404);
        }
    }

    public function trade($symbol = null)
    {
        $futurePair = FutureTradeConfig::active()->with('coinPair');

        if ($symbol) {
            $futurePair = $futurePair->whereHas('coinPair', function ($coinPair) use ($symbol) {
                $coinPair->where('symbol', $symbol);
            })->first();
        } else {
            $futurePair = $futurePair->where('is_default', Status::YES)->first();
        }

        if (!$futurePair) {
            $notify[] = ['error', 'No future trade pair found'];
            return to_route('home')->withNotify($notify);
        }

        $pair   = $futurePair->coinPair;
        $userId = auth()->id();

        $coinWallet = Wallet::where('user_id', $userId)->where('currency_id', $pair->coin->id)->future()->first();

        $otherPairs    = FutureTradeConfig::active()->with('coinPair')->where('id', '!=', $futurePair->id)->get();
        $favoritePairs = FavoritePair::where('user_id', $userId)->where('future_trade_config_id', '>', 0)->pluck('future_trade_config_id')->toArray();
        $recentTrades = FutureTrade::where('future_trade_config_id', $futurePair->id)->orderBy('id', 'desc')->take(6)->get();
        $asset['wallet_balance'] = Wallet::future()->where('user_id', $userId)->join('currencies', 'wallets.currency_id', 'currencies.id')->sum(DB::raw('currencies.rate * wallets.balance'));

        $pageTitle = showAmount($pair->marketData->price, currencyFormat: false) . ' | ' . $pair->symbol;

        return view('Template::future.trade', compact('pageTitle', 'futurePair', 'pair', 'coinWallet', 'otherPairs', 'favoritePairs', 'recentTrades', 'asset'));
    }

    public function orderBook($symbol = null)
    {
        $futurePair = $this->findPair($symbol);

        if (!$futurePair) {
            return response()->json([
                'success' => false,
                'message' => "Coin Pair not found",
            ]);
        }

        $orderType = request()->order_type;
        $query     = FutureQueueOrder::open()->where('future_queue_orders.future_trade_config_id', $futurePair->id)
            ->select('future_queue_orders.*')
            ->leftJoin('future_trades', 'future_queue_orders.id', 'future_trades.future_order_id')
            ->selectRaw("SUM(future_queue_orders.coin_amount) as total_amount")
            ->selectRaw("SUM(future_queue_orders.size) as total_size")
            ->selectRaw("COUNT(DISTINCT future_queue_orders.id) as total_order")
            ->selectRaw("COUNT(DISTINCT future_trades.id) as total_trade")
            ->selectRaw('MAX(CASE WHEN future_queue_orders.user_id = ? THEN 1 ELSE 0 END)  AS has_my_order', [auth()->id()])
            ->groupBy('future_queue_orders.rate')
            ->orderBy('future_queue_orders.rate', 'DESC');

        if ($orderType == 'all' || $orderType == 'sell') {
            $sellSideOrders = (clone $query)->sellSideOrder()->take(6)->get();
        }
        if ($orderType == 'all' || $orderType == 'buy') {
            $buySideOrders = (clone $query)->buySideOrder()->take(6)->get();
        }

        return response()->json([
            'success'          => true,
            'sell_side_orders' => @$sellSideOrders ?? [],
            'buy_side_orders'  => @$buySideOrders ?? [],
        ]);
    }

    private function findPair($symbol = null)
    {
        $futurePair = FutureTradeConfig::active()->with('coinPair');
        if ($symbol) {
            $futurePair = $futurePair->whereHas('coinPair', function ($coinPair) use ($symbol) {
                $coinPair->where('symbol', $symbol);
            })->first();
        } else {
            $futurePair = $futurePair->where('is_default', Status::YES)->first();
        }
        return $futurePair;
    }
}
