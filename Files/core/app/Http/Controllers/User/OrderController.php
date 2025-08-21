<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Events\Order as EventsOrder;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {

    public function open() {
        $pageTitle = "Open Order";
        $orders    = $this->orderData('open');
        return view('Template::user.order.list', compact('pageTitle', 'orders'));
    }

    public function completed() {
        $pageTitle = "Completed Order";
        $orders    = $this->orderData('completed');
        return view('Template::user.order.list', compact('pageTitle', 'orders'));
    }

    public function canceled() {
        $pageTitle = "Canceled Order";
        $orders    = $this->orderData('canceled');
        return view('Template::user.order.list', compact('pageTitle', 'orders'));
    }
    public function pending() {
        $pageTitle = "Pending Order";
        $orders    = $this->orderData('pending');
        return view('Template::user.order.list', compact('pageTitle', 'orders'));
    }

    public function history() {
        $pageTitle = "My Order";
        $orders    = $this->orderData();
        return view('Template::user.order.list', compact('pageTitle', 'orders'));
    }

    protected function orderData($scope = null) {
        $query = Order::where('user_id', auth()->id())
            ->filter(['order_side', 'order_type'])
            ->searchable(['pair:symbol', 'pair.coin:symbol', 'pair.market.currency:symbol'])
            ->with('pair', 'pair.coin', 'pair.market.currency')
            ->orderBy('id', 'desc');

        if ($scope) {
            $query->$scope();
        }
        if (request()->currency) {
            $currency = Currency::active()->where('symbol', strtoupper(request()->currency))->firstOrFail();
            $query    = currencyWiseOrderQuery($query, $currency);
        }
        return $query->paginate(getPaginate());
    }

    public function tradeHistory() {
        $pageTitle = "Trade History";
        $trades    = Trade::where('trader_id', auth()->id())->filter(['trade_side'])->searchable(['order.pair:symbol', 'order.pair.coin:symbol', 'order.pair.market.currency:symbol'])->with('order.pair.coin', 'order.pair.market.currency')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.order.trade_history', compact('pageTitle', 'trades'));
    }

    public function save(Request $request, $symbol) {

        $validator = Validator::make($request->all(), [
            'rate'       => 'required|numeric|gt:0',
            'amount'     => 'required|numeric|gt:0',
            'order_side' => 'required|in:' . Status::BUY_SIDE_ORDER . ',' . Status::SELL_SIDE_ORDER,
            'order_type' => 'required|in:' . Status::ORDER_TYPE_LIMIT . ',' . Status::ORDER_TYPE_MARKET . ',' . Status::ORDER_TYPE_STOP_LIMIT,
            'stop_rate'  => 'required_if:order_type,' . Status::ORDER_TYPE_STOP_LIMIT,
        ], [
            'stop_rate.required_if' => "The stop field is required when the order type stop-limit.",
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors()->all());
        }

        $pair = CoinPair::activeMarket()->activeCoin()->where(function ($query) {
            $query->where('type', Status::SPOT_TRADE)->orWhere('type', Status::BOTH_TRADE);
        })->with('market.currency', 'coin', 'marketData')->where('symbol', $symbol)->first();
        if (!$pair) {
            return $this->response('Pair not found');
        }

        $amount = $request->amount;
        if ($request->order_type == Status::ORDER_TYPE_MARKET) {
            $rate = $pair->marketData->price;
        } else {
            $rate = $request->rate;
        }

        $totalAmount    = $amount * $rate;
        
        $coin           = $pair->coin;
        $marketCurrency = $pair->market->currency;
        $user           = auth()->user();

        if ($request->order_side == Status::BUY_SIDE_ORDER) {

            $userMarketCurrencyWallet = Wallet::where('user_id', $user->id)->where('currency_id', $marketCurrency->id)->spot()->first();

            if (!$userMarketCurrencyWallet) {
                return $this->response('Your market currency wallet not found');
            }

            if ($amount < $pair->minimum_buy_amount) {
                return $this->response("Minimum buy amount " . showAmount($pair->minimum_buy_amount, currencyFormat: false) . ' ' . $coin->symbol);
            }

            if ($amount > $pair->maximum_buy_amount && $pair->maximum_buy_amount != -1) { //-1 for unlimited maximum amount
                return $this->response("Maximum buy amount " . showAmount($pair->maximum_buy_amount, currencyFormat: false) . ' ' . $coin->symbol);
            }

            $charge = ($totalAmount / 100) * $pair->percent_charge_for_buy;
            if (($charge + $totalAmount) > $userMarketCurrencyWallet->balance) {
                return $this->response('You don\'t have sufficient ' . $marketCurrency->symbol . ' wallet balance for buy coin.');
            }
            $orderSide = "Buy";
        }

        if ($request->order_side == Status::SELL_SIDE_ORDER) {
            $userCoinWallet = Wallet::where('user_id', $user->id)->where('currency_id', $coin->id)->spot()->first();

            if (!$userCoinWallet) {
                return $this->response('Your coin wallet not found');
            }
            if ($request->amount < $pair->minimum_sell_amount) {
                return $this->response("Minimum sell amount " . showAmount($pair->minimum_sell_amount, currencyFormat: false) . ' ' . $coin->symbol);
            }
            if ($request->amount > $pair->maximum_sell_amount && $pair->maximum_sell_amount != -1) {
                return $this->response("Maximum sell amount " . showAmount($pair->maximum_sell_amount, currencyFormat: false) . ' ' . $coin->symbol);
            }
            $charge = ($totalAmount / 100) * $pair->percent_charge_for_sell;
            if ($request->amount > $userCoinWallet->balance) {
                return $this->response('You don\'t have sufficient ' . $userCoinWallet->symbol . ' wallet balance for sell coin.');
            }
            $orderSide = "Sell";
        }

        $order                     = new Order();
        $order->trx                = getTrx();
        $order->user_id            = $user->id;
        $order->pair_id            = $pair->id;
        $order->order_side         = $request->order_side;
        $order->order_type         = $request->order_type;
        $order->rate               = $rate;
        $order->amount             = $amount;
        $order->price              = $pair->marketData->price;
        $order->total              = $totalAmount;
        $order->charge             = $charge;
        $order->coin_id            = $coin->id;
        $order->market_currency_id = $marketCurrency->id;

        if ($order->order_type == Status::ORDER_TYPE_STOP_LIMIT) {
            $order->is_draft  = Status::YES;
            $order->stop_rate = $request->stop_rate;
            $order->status    = Status::ORDER_PENDING;
        }

        $order->save();

        if ($order->order_type != Status::ORDER_TYPE_STOP_LIMIT) {
            if ($request->order_side == Status::BUY_SIDE_ORDER) {
                $details       = "Open order for buy coin on " . $pair->symbol . " pair";
                $walletBalance = $this->createTrx($userMarketCurrencyWallet, 'order_buy', $totalAmount, $charge, $details, $user);
            } else {
                $details       = "Open order for sell coin on " . $pair->symbol . " pair";
                $walletBalance = $this->createTrx($userCoinWallet, 'order_sell', $amount, 0, $details, $user);
            }

            try {
                event(new EventsOrder($order, $pair->symbol));
            } catch (Exception $ex) {
            }

            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $user->id;
            $adminNotification->title     = $user->username . $details;
            $adminNotification->click_url = urlPath('admin.order.history');
            $adminNotification->save();

            notify($user, 'ORDER_OPEN', [
                'pair'                   => $pair->symbol,
                'amount'                 => showAmount($order->amount, currencyFormat: false),
                'total'                  => showAmount($order->total, currencyFormat: false),
                'rate'                   => showAmount($order->rate, currencyFormat: false),
                'price'                  => showAmount($order->price, currencyFormat: false),
                'coin_symbol'            => @$coin->symbol,
                'order_side'             => $orderSide,
                'market_currency_symbol' => @$marketCurrency->symbol,
                'market'                 => $pair->market->name,
            ]);
        }

        $data = [
            'wallet_balance' => @$walletBalance,
            'order'          => $order,
            'pair_symbol'    => $pair->symbol,
        ];

        return $this->response("Your order open successfully", true, $data);
    }

    private function response($message, $status = false, $data = []) {
        return response()->json([
            'success' => $status,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    public function createTrx($wallet, $remark, $amount, $charge, $details, $user, $type = "-") {
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

    public function cancel($id) {

        $user             = auth()->user();
        $order            = Order::where('user_id', $user->id)->where('id', $id)->whereIn('status', [Status::ORDER_PENDING, Status::ORDER_OPEN])->with('pair', 'pair.coin', 'pair.market.currency')->firstOrFail();
        $cancelAmount     = orderCancelAmount($order);
        $amount           = $cancelAmount['amount'];
        $chargeBackAmount = $cancelAmount['charge_back_amount'];

        if ($order->is_draft != Status::YES) {

            if ($order->order_side == Status::BUY_SIDE_ORDER) {
                $symbol  = @$order->pair->market->currency->symbol;
                $wallet  = Wallet::where('user_id', $user->id)->where('currency_id', $order->pair->market->currency->id)->spot()->first();
                $details = "Return $amount $symbol for order cancel";
            } else {
                $symbol  = @$order->pair->coin->symbol;
                $wallet  = Wallet::where('user_id', $user->id)->where('currency_id', $order->pair->coin->id)->spot()->first();
                $details = "Return $amount $symbol for order cancel";
            }

            if ($amount <= 0 || !$wallet) {
                $notify[] = ['error', "Something went to wrong"];
                return back()->withNotify($notify);
            }
        }

        $order->status = Status::ORDER_CANCELED;
        $order->save();

        if ($order->is_draft != Status::YES) {
            $wallet->balance += $amount;
            $wallet->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->wallet_id    = $wallet->id;
            $transaction->amount       = $amount;
            $transaction->post_balance = $wallet->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = $details;
            $transaction->trx          = getTrx();
            $transaction->remark       = 'order_canceled';
            $transaction->save();

            if ($chargeBackAmount > 0) {

                $wallet->balance += $chargeBackAmount;
                $wallet->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $user->id;
                $transaction->wallet_id    = $wallet->id;
                $transaction->amount       = $chargeBackAmount;
                $transaction->post_balance = $wallet->balance;
                $transaction->charge       = 0;
                $transaction->trx_type     = '+';
                $transaction->details      = "Charge back for " . $details;
                $transaction->trx          = getTrx();
                $transaction->remark       = 'order_canceled';
                $transaction->save();
            }

            notify($user, 'ORDER_CANCEL', [
                'pair'                   => $order->pair->symbol,
                'amount'                 => showAmount($order->amount, currencyFormat: false),
                'coin_symbol'            => @$order->pair->coin->symbol,
                'market_currency_symbol' => @$order->pair->market->currency->symbol,
            ]);
        }

        $notify[] = ['success', "Order canceled successfully"];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'update_filed' => 'required|in:rate,amount',
            'amount'       => 'required_if:update_filed,amount|numeric|gt:0',
            'rate'         => 'required_if:update_filed,rate|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors()->all());
        }

        $user  = auth()->user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->whereIn('status', [Status::ORDER_OPEN, Status::ORDER_PENDING])->whereHas('pair', function ($q) {
            $q->activeMarket()->activeCoin();
        })->with('pair', 'pair.coin', 'pair.market.currency')->first();

        if (!$order) {
            return $this->response("Order not found");
        }

        if ($request->update_filed == "amount") {
            return $this->updateAmount($request, $order, $user);
        } else {
            return $this->updateRate($request, $order, $user);
        }
    }

    private function updateAmount($request, $order, $user) {
        $pair = $order->pair;

        if ($request->amount == $order->amount) {
            return $this->response("Please change the order amount");
        }

        if ($request->amount <= $order->filled_amount) {
            return $this->response("Already filled amount" . showAmount($order->filled_amount, currencyFormat: false));
        }

        if ($order->order_side == Status::BUY_SIDE_ORDER) {
            $chargePercentage = $pair->percent_charge_for_buy;
            $currency         = $pair->market->currency;
            $minAmount        = $pair->minimum_buy_amount;
            $maxAmount        = $pair->maximum_buy_amount;
            $wallet           = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->spot()->first();
            $type             = "buy";
            $oldCharge        = $order->charge;
        } else {
            $chargePercentage = $pair->percent_charge_for_sell;
            $currency         = $pair->coin;
            $minAmount        = $pair->minimum_sell_amount;
            $maxAmount        = $pair->maximum_sell_amount;
            $wallet           = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->spot()->first();
            $type             = "sell";
        }

        if ($request->amount > $order->amount) {
            $updatedAmount = $request->amount - $order->amount;
            $orderAmount   = $order->amount + $updatedAmount;
            $charge        = (($updatedAmount * $order->rate) / 100) * $chargePercentage;
            $order->charge += $charge;
        } else {
            $updatedAmount = $order->amount - $request->amount;
            $orderAmount   = $order->amount - $updatedAmount;
            $charge        = (($updatedAmount * $order->rate) / 100) * $chargePercentage;
            $order->charge -= $charge;
        }

        $oldOrderAmount = $order->amount;

        if ($request->amount < $minAmount) {
            return $this->response("Minimum $type amount " . showAmount($minAmount, currencyFormat: false) . ' ' . $currency->symbol);
        }

        if ($request->amount > $maxAmount && $maxAmount != -1) {
            return $this->response("Maximum $type amount " . showAmount($maxAmount, currencyFormat: false) . ' ' . $currency->symbol);
        }

        if ($request->amount > $order->amount) {
            $requiredAmount = $order->order_side == Status::BUY_SIDE_ORDER ? ($charge + ($updatedAmount * $order->rate)) : $updatedAmount;
            if ($requiredAmount > $wallet->balance) {
                return $this->response('You don\'t have sufficient ' . $currency->symbol . ' wallet balance for ' . $type . ' coin.');
            }
        }

        $totalAmount   = $orderAmount * $order->rate;
        $order->amount = $orderAmount;
        $order->total  = $totalAmount;
        $order->save();

        if ($order->is_draft != Status::YES) {
            if ($order->order_side == Status::BUY_SIDE_ORDER) {
                $details = "Update buy order on  " . $pair->symbol . " pair. updated amount is  " . showAMount($updatedAmount, currencyFormat: false) . ' ' . @$order->pair->coin->symbol;
                if ($request->amount > $oldOrderAmount) {
                    $this->createTrx($wallet, 'order_buy', ($updatedAmount * $order->rate), $charge, $details, $user);
                } else {
                    $chargePercent    = ($updatedAmount / $oldOrderAmount) * 100;
                    $chargeBackAmount = ($oldCharge / 100) * $chargePercent;
                    $this->createTrx($wallet, 'order_buy', ($updatedAmount * $order->rate), $chargeBackAmount, $details, $user, '+');
                }
            } else {
                $details = "Update sell order on  " . $pair->symbol . " pair. updated amount is  " . showAmount($updatedAmount, currencyFormat: false) . @$order->pair->coin->symbol;
                $this->createTrx($wallet, 'order_sell', $updatedAmount, 0, $details, $user, $request->amount > $oldOrderAmount ? '-' : '+');
            }
        }

        return $this->response("Your order update successfully", true, [
            'order_amount' => $order->amount,
        ]);
    }

    private function updateRate($request, $order, $user) {
        $pair = $order->pair;

        if ($request->rate == $order->rate) {
            return $this->response("Please change the rate");
        }

        $oldTotal = $order->total;
        $newTotal = $request->rate * $order->amount;

        if ($order->order_side == Status::SELL_SIDE_ORDER) {
            $charge        = ($newTotal / 100) * $pair->percent_charge_for_sell;
            $order->rate   = $request->rate;
            $order->total  = $newTotal;
            $order->charge = $charge;
            $order->save();

            return $this->response("Rate update successfully", true, [
                'order_rate' => $order->rate,
            ]);
        }

        $marketCurrency           = $pair->market->currency;
        $userMarketCurrencyWallet = Wallet::where('user_id', $user->id)->where('currency_id', $marketCurrency->id)->spot()->first();

        if (!$userMarketCurrencyWallet) {
            return $this->response('Your market currency wallet not found');
        }

        $charge  = $order->charge;
        $details = 'update order rate on ' . $pair->symbol . ' pair. Rate ' . showAmount($order->rate, currencyFormat: false) . ' to  ' . showAmount($request->rate, currencyFormat: false) . '';

        if ($newTotal > $oldTotal) {
            $newAmount = $newTotal - $oldTotal;
            $newCharge = ($newAmount / 100) * $pair->percent_charge_for_buy;
            $charge    = $charge + $newCharge;
            $trxType   = "-";
            if (($newAmount + $newCharge) > $userMarketCurrencyWallet->balance) {
                return $this->response('You don\'t have sufficient ' . $marketCurrency->symbol . ' wallet balance for buy coin.');
            }
        } else {
            $newAmount = $oldTotal - $newTotal;
            $newCharge = ($newAmount / 100) * $pair->percent_charge_for_buy;
            $charge    = $charge - $newCharge;
            $trxType   = "+";
        }

        $order->rate   = $request->rate;
        $order->total  = $newTotal;
        $order->charge = $charge;
        $order->save();

        if ($order->is_draft != Status::YES) {
            $this->createTrx($userMarketCurrencyWallet, 'order_buy', $newAmount, $newCharge, $details, $user, $trxType);
        }
        return $this->response("Rate update successfully", true, [
            'order_rate' => $order->rate,
        ]);
    }
}
