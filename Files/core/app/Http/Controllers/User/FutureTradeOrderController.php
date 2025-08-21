<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\Wallet;
use App\Constants\Status;
use App\Models\FutureOrder;
use App\Models\FutureTrade;
use App\Models\Transaction;
use App\Models\FavoritePair;
use Illuminate\Http\Request;
use App\Models\FutureQueueOrder;
use App\Models\FutureTradeConfig;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Events\FutureOrder as EventsFutureOrder;

class FutureTradeOrderController extends Controller
{
    public function __construct()
    {
        if (!gs('future_trade')) {
            abort(404);
        }
    }

    public function availableBalance($currencyId)
    {
        $userId               = auth()->id();
        $marketCurrencyWallet = Wallet::where('user_id', $userId)->where('currency_id', $currencyId)->future()->first();

        if (!$marketCurrencyWallet) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Your market currency wallet not found',
            ]);
        }

        $runningCrossPositions = FutureOrder::positioned()->where('user_id', $userId)->where('margin_mode', Status::MARGIN_MODE_CROSS)->get();

        $totalPnl = 0;

        if ($runningCrossPositions->count()) {
            foreach ($runningCrossPositions as $position) {
                $currentPrice = $position->futureTradeConfig->coinPair->marketData->price;
                $pnl          = ($currentPrice - $position->rate) * ($position->size / $currentPrice);
                if ($position->order_side == Status::SELL_SIDE_ORDER) {
                    $pnl = $pnl * -1;
                }
                $totalPnl += $pnl;
            }
        }

        $availableBalance = $marketCurrencyWallet->balance + $totalPnl;
        $availableBalance = $availableBalance < 0 ? 0 : $availableBalance;
        return response()->json([
            'status'  => 'success',
            'message' => 'Available balance',
            'data'    => [
                'available_balance' => getAmount($availableBalance),
            ],
        ]);
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pair_id'     => 'required|integer',
            'leverage'    => 'required|integer',
            'margin_mode' => 'required|in:' . Status::MARGIN_MODE_ISOLATED . ',' . Status::MARGIN_MODE_CROSS,
            'rate'        => 'required|numeric|gt:0',
            'size'        => 'required|numeric|gt:0',
            'order_side'  => 'required|in:' . Status::BUY_SIDE_ORDER . ',' . Status::SELL_SIDE_ORDER,
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors()->all());
        }

        $futureTradeConfig = FutureTradeConfig::active()->where('pair_id', $request->pair_id)->whereHas('coinPair', function ($query) {
            $query->active();
        })->first();

        if (!$futureTradeConfig) {
            return $this->response('Invalid pair');
        }

        $pair = $futureTradeConfig->coinPair;
        $rate = getAmount($request->rate, 7);

        if ($request->size_coin == 2) {
            $coinAmount = $request->size;
        } else {
            $coinAmount = $request->size / $rate;
        }

        $coinAmount = getAmount($coinAmount);
        $size       = $coinAmount * $rate;
        $margin     = $size / $request->leverage;

        $coin           = $pair->coin;
        $marketCurrency = $pair->market->currency;
        $user           = auth()->user();

        $userMarketCurrencyWallet = Wallet::where('user_id', $user->id)->where('currency_id', $marketCurrency->id)->future()->first();
        if (!$userMarketCurrencyWallet) {
            return $this->response('Your market currency wallet not found');
        }

        $availableBalance     = $this->availableBalance($marketCurrency->id);
        $availableBalanceData = json_decode($availableBalance->getContent(), true);

        if ($margin > ($availableBalanceData['data']['available_balance'] ?? 0)) {
            return $this->response('You don\'t have sufficient ' . $marketCurrency->symbol . ' wallet balance');
        }

        $coinSymbolText = $coin->symbol;

        if ($request->order_side == Status::BUY_SIDE_ORDER) {
            if ($coinAmount < $futureTradeConfig->min_buy_amount) {
                return $this->response("Minimum buy amount " . showAmount($futureTradeConfig->min_buy_amount, currencyFormat: false) . ' ' . $coinSymbolText);
            }

            if ($coinAmount > $futureTradeConfig->max_buy_amount && $futureTradeConfig->max_buy_amount != -1) {
                return $this->response("Maximum buy amount " . showAmount($futureTradeConfig->max_buy_amount, currencyFormat: false) . ' ' . $coinSymbolText);
            }
        } else {

            if ($coinAmount < $futureTradeConfig->min_sell_amount) {
                return $this->response("Minimum sell amount " . showAmount($futureTradeConfig->min_sell_amount, currencyFormat: false) . ' ' . $coinSymbolText);
            }
            if ($coinAmount > $futureTradeConfig->max_sell_amount && $futureTradeConfig->max_sell_amount != -1) {
                return $this->response("Maximum sell amount " . showAmount($futureTradeConfig->max_sell_amount, currencyFormat: false) . ' ' . $coinSymbolText);
            }
        }

        $previousRunningPosition = FutureOrder::positioned()->where('user_id', $user->id)->first();

        if ($previousRunningPosition && $previousRunningPosition->margin_mode != $request->margin_mode) {
            return $this->response('You have already running position with different margin mode');
        }

        $previousRunningQueueOrder = FutureQueueOrder::open()->where('margin_mode', '!=', 0)->where('user_id', $user->id)->first();
        if ($previousRunningQueueOrder && $previousRunningQueueOrder->margin_mode != $request->margin_mode) {
            return $this->response('You have already running order with different margin mode');
        }

        if ($previousRunningPosition && $previousRunningPosition->margin_mode == Status::MARGIN_MODE_CROSS && $futureTradeConfig->id != $previousRunningPosition->future_trade_config_id) {
            return $this->response('Other pair cross margin mode position is running');
        }

        $futureQueueOrder                         = new FutureQueueOrder();
        $futureQueueOrder->user_id                = $user->id;
        $futureQueueOrder->pair_id                = $request->pair_id;
        $futureQueueOrder->future_trade_config_id = $futureTradeConfig->id;
        $futureQueueOrder->order_side             = $request->order_side;
        $futureQueueOrder->order_type             = $request->order_type;

        $futureQueueOrder->rate                  = $request->rate;
        $futureQueueOrder->size                  = $size;
        $futureQueueOrder->remaining_size        = $size;
        $futureQueueOrder->margin                = $margin;
        $futureQueueOrder->remaining_margin      = $margin;
        $futureQueueOrder->leverage              = $request->leverage;
        $futureQueueOrder->coin_amount           = $coinAmount;
        $futureQueueOrder->remaining_coin_amount = $futureQueueOrder->coin_amount;

        $futureQueueOrder->coin_id            = $coin->id;
        $futureQueueOrder->market_currency_id = $marketCurrency->id;
        $futureQueueOrder->margin_mode        = $request->margin_mode;
        $futureQueueOrder->type               = $request->order_type;

        if ($request->order_type == Status::ORDER_TYPE_STOP_LIMIT) {
            $futureQueueOrder->stop_rate = $request->stop_rate;
        }

        $futureQueueOrder->save();

        $walletBalance = $this->createTrx($userMarketCurrencyWallet, 'future_trade_order', $margin, 0, 'Future Trade Order for ' . $pair->symbol, $user, '-');

        $data = [
            'wallet_balance' => showAmount($walletBalance, currencyFormat: false),
            'order'          => $futureQueueOrder,
            'pair_symbol'    => $pair->symbol,
        ];

        try {
            event(new EventsFutureOrder($futureQueueOrder, $pair->symbol));
        } catch (Exception $ex) {
        }

        return $this->response("Your order open successfully", 'success', $data);
    }

    public function positioned()
    {
        $user             = auth()->user();
        $positionedOrders = FutureOrder::where('user_id', $user->id)->positioned()->withSum(['futureQueueOrders as pendingCoinAmount' => function ($query) {
            $query->open();
        }], 'remaining_coin_amount')
        ->having('coin_amount', '>', DB::raw('COALESCE(pendingCoinAmount, 0)'))
        ->orderBy('id', 'desc')->get();
        

        $html                 = view('Template::future.positioned_table', compact('positionedOrders'))->render();
        $totalPositionedCount = $positionedOrders->count();

        if ($totalPositionedCount) {
            $runningMarginMode = $positionedOrders->first()->margin_mode;
            $leverage          = $positionedOrders->sortByDesc('id')->first()->leverage;
        } else {
            $runningMarginMode = Status::MARGIN_MODE_ISOLATED;
            $leverage          = 0;
        }

        return $this->response('Positioned orders', 'success', ['html' => $html, 'totalPositionedCount' => $totalPositionedCount, 'runningMarginMode' => $runningMarginMode, 'leverage' => $leverage]);
    }

    public function pendingQueue()
    {
        $user          = auth()->user();
        $pendingQueues = FutureQueueOrder::open()->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        $html              = view('Template::future.open_order_table', compact('pendingQueues'))->render();
        $pendingQueueCount = $pendingQueues->count();

        return $this->response('Open orders', 'success', ['html' => $html, 'pendingQueueCount' => $pendingQueueCount]);
    }

    public function favorite($pairId)
    {
        $futureTradeConfig = FutureTradeConfig::find($pairId);

        if (!$futureTradeConfig) {
            return $this->response('Invalid pair id', 'error');
        }

        $user = auth()->user();

        $favorite     = FavoritePair::where('user_id', $user->id)->where('future_trade_config_id', $futureTradeConfig->id)->first();
        $favoriteList = FavoritePair::with('futureTradeConfig.coinPair.marketData')->where('user_id', $user->id)->where('future_trade_config_id', '>', 0);

        if ($favorite) {
            $favorite->delete();
            return $this->response('Pair removed from favorite', 'success', ['is_favorite' => 0, 'favorite_list' => $favoriteList->get()]);
        } else {
            $favorite                         = new FavoritePair();
            $favorite->user_id                = $user->id;
            $favorite->future_trade_config_id = $futureTradeConfig->id;
            $favorite->save();
            return $this->response('Pair added to favorite', 'success', ['is_favorite' => 1, 'favorite_list' => $favoriteList->get()]);
        }
    }

    public function createTrx($wallet, $remark, $amount, $charge, $details, $user, $type = "-")
    {
        if ($type == '-') {
            $wallet->balance -= $amount;
        } else {
            $wallet->balance += $amount;
        }
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $type;
        $transaction->details      = $details;
        $transaction->trx          = getTrx();
        $transaction->remark       = $remark;
        $transaction->save();

        if (getAmount($charge) <= 0) {
            return $wallet->balance;
        }

        if ($type == '-') {
            $wallet->balance -= $charge;
        } else {
            $wallet->balance += $charge;
        }

        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $charge;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $type;
        $transaction->details      = "Charge for " . $details;
        $transaction->trx          = getTrx();
        $transaction->remark       = "charge_" . $remark;
        $transaction->save();

        return $wallet->balance;
    }

    public function takeProfitLoss(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'future_order_id' => 'required|integer',
            'coin_amount'     => 'required|numeric|gt:0',
            'trigger_price'   => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors()->all());
        }

        $user = auth()->user();

        $futureOrder = FutureOrder::where('user_id', $user->id)->where('id', $request->future_order_id)->first();

        if (!$futureOrder) {
            return $this->response('Invalid future order id', 'error');
        }

        $orderSide = $futureOrder->order_side == Status::BUY_SIDE_ORDER ? Status::SELL_SIDE_ORDER : Status::BUY_SIDE_ORDER;

        $pendingCoinAmount = FutureQueueOrder::open()->queueTakeProfitLossOrder()->where('future_order_id', $futureOrder->id)->where('user_id', $user->id)->sum('coin_amount');

        if ($pendingCoinAmount + $request->coin_amount > $futureOrder->coin_amount) {
            return $this->response('Insufficient balance', 'error');
        }

        $futureQueueOrder = new FutureQueueOrder();

        $futureQueueOrder->future_order_id        = $futureOrder->id;
        $futureQueueOrder->future_trade_config_id = $futureOrder->future_trade_config_id;
        $futureQueueOrder->pair_id                = $futureOrder->futureTradeConfig->pair_id;
        $futureQueueOrder->user_id                = $user->id;
        $futureQueueOrder->market_currency_id     = $futureOrder->market_currency_id;
        $futureQueueOrder->order_side             = $orderSide;
        $futureQueueOrder->rate                   = $request->trigger_price;
        $futureQueueOrder->size                   = $request->coin_amount * $request->trigger_price;
        $futureQueueOrder->remaining_size         = $futureQueueOrder->size;
        $futureQueueOrder->coin_amount            = getAmount($request->coin_amount, 7);
        $futureQueueOrder->remaining_coin_amount  = $futureQueueOrder->coin_amount;
        $futureQueueOrder->margin                 = $futureOrder->margin / $futureOrder->coin_amount * $futureQueueOrder->coin_amount;
        $futureQueueOrder->remaining_margin       = $futureQueueOrder->margin;
        $futureQueueOrder->type                   = Status::QUEUE_TAKE_PROFIT_LOSS;
        $futureQueueOrder->save();

        try {
            event(new EventsFutureOrder($futureQueueOrder, $futureQueueOrder->futureTradeConfig->coinPair->symbol));
        } catch (Exception $ex) {
        }

        return $this->response('Request submitted successfully', 'success');
    }

    public function closeAllPosition()
    {
        $userId       = auth()->id();
        $futureOrders = FutureOrder::positioned()->where('user_id', $userId)->withSum(['futureQueueOrders as pendingCoinAmount' => function ($query) {
            $query->open()->queueTakeProfitLossOrder();
        }], 'coin_amount')
        ->having('coin_amount', '>', DB::raw('COALESCE(pendingCoinAmount, 0)'))
        ->get();

        if(!$futureOrders->count()){
            return $this->response('No position found', 'error');
        }

        foreach ($futureOrders as $futureOrder) {
            $orderSide = $futureOrder->order_side == Status::BUY_SIDE_ORDER ? Status::SELL_SIDE_ORDER : Status::BUY_SIDE_ORDER;
            $coinAmount = $futureOrder->coin_amount - $futureOrder->pendingCoinAmount;

            $futureQueueOrder = new FutureQueueOrder();
            $futureQueueOrder->future_order_id        = $futureOrder->id;
            $futureQueueOrder->future_trade_config_id = $futureOrder->future_trade_config_id;
            $futureQueueOrder->pair_id                = $futureOrder->futureTradeConfig->pair_id;
            $futureQueueOrder->user_id                = $userId;
            $futureQueueOrder->market_currency_id     = $futureOrder->market_currency_id;
            $futureQueueOrder->order_side             = $orderSide;
            $futureQueueOrder->rate                   = $futureOrder->futureTradeConfig->coinPair->marketData->price;
            $futureQueueOrder->size                   = $coinAmount * $futureQueueOrder->rate;
            $futureQueueOrder->remaining_size         = $futureQueueOrder->size;
            $futureQueueOrder->coin_amount            = getAmount($coinAmount, 7);
            $futureQueueOrder->remaining_coin_amount  = $futureQueueOrder->coin_amount;
            $futureQueueOrder->margin                 = $futureOrder->margin / $futureOrder->coin_amount * $futureQueueOrder->coin_amount;
            $futureQueueOrder->remaining_margin       = $futureQueueOrder->margin;
            $futureQueueOrder->type                   = Status::QUEUE_TAKE_PROFIT_LOSS;
            $futureQueueOrder->save();

            try {
                event(new EventsFutureOrder($futureQueueOrder, $futureQueueOrder->futureTradeConfig->coinPair->symbol));
            } catch (Exception $ex) {
            }
        }
        
        return $this->response('All position closed successfully', 'success');
    }

    public function cancelOrder($id)
    {
        $futureQueueOrder = FutureQueueOrder::open()->where('user_id', auth()->id())->find($id);

        if (!$futureQueueOrder) {
            if (request()->ajax()) {
                return $this->response('Invalid order', 'error');
            }
            abort(404);
        }

        if ($futureQueueOrder->type != Status::QUEUE_TAKE_PROFIT_LOSS) {
            $userWallet = Wallet::future()->where('user_id', auth()->id())->where('currency_id', $futureQueueOrder->market_currency_id)->first();
            $userWallet->balance += $futureQueueOrder->remaining_margin;
            $userWallet->save();
        }

        $futureQueueOrder->status = Status::FUTURE_QUEUE_CANCELED;
        $futureQueueOrder->save();

        if (request()->ajax()) {
            $totalQueueOrderCount = FutureQueueOrder::open()->where('user_id', auth()->id())->count();
            return $this->response('Order canceled successfully', 'success', ['id' => $futureQueueOrder->id, 'totalQueueOrderCount' => $totalQueueOrderCount]);
        }
        $notify[] = ['success', 'Order canceled successfully'];
        return back()->withNotify($notify);
    }

    public function orderHistory()
    {
        $orderHistories = FutureQueueOrder::where(function ($query) {
            $query->where('status', Status::FUTURE_QUEUE_CANCELED)->orWhere('status', Status::FUTURE_QUEUE_COMPLETED);
        })->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));
        $html = view('Template::future.order_history_table', compact('orderHistories'))->render();
        return $this->response('Order history', 'success', ['html' => $html, 'orderHistories' => $orderHistories]);
    }

    public function tradeHistory()
    {
        $tradeHistories = FutureTrade::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));
        $html           = view('Template::future.trade_history_table', compact('tradeHistories'))->render();
        return $this->response('Trade history', 'success', ['html' => $html, 'tradeHistories' => $tradeHistories]);
    }

    public function positionHistory()
    {
        $positionHistories = FutureOrder::withAvg(['futureQueueOrders as avg_closing' => function ($queueOrder) {
            $queueOrder->completed();
        }], 'rate')->where(function ($query) {
            $query->closed()->orWhere(function ($query) {
                $query->liquidated();
            });
        })->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate(10));

        $html = view('Template::future.position_history_table', compact('positionHistories'))->render();
        return $this->response('Position history', 'success', ['html' => $html, 'positionHistories' => $positionHistories]);
    }

    private function response($message, $status = 'error', $data = [])
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ]);
    }

}
