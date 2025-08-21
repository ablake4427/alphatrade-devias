<?php

namespace App\Http\Controllers\Api\P2P;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\P2P\Ad;
use App\Models\P2P\AdPaymentMethod;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeFeedBack;
use App\Models\P2P\TradeMessage;
use App\Models\P2P\UserPaymentMethod;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TradeController extends Controller
{
    public function request(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:buy,sell',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $type  = $request->type;
        $scope = $type == 'buy' ? 'sell' : 'buy';
        $ad    = Ad::$scope()->active()->where('id', $id)
            ->withCount(['trades as total_trade', 'trades' => function ($q) {
                $q->where('status', Status::P2P_TRADE_COMPLETED);
            }])
            ->with("paymentWindow", 'asset', 'fiat', 'user', 'paymentMethods.paymentMethod.userPaymentMethod')
            ->first();

        if (!$ad) {
            $notify[] = 'Ad not found';
            return responseError('not_found', $notify);
        }

        $coinWallet = Wallet::where('user_id', $ad->user_id)->where('currency_id', $ad->asset_id)->funding()->first();

        if (!$coinWallet) {
            $notify[] = 'Coin wallet not found';
            return responseError('not_found', $notify);
        }

        $sellerWallet = Wallet::funding()->where('user_id', auth()->id())->where('currency_id', $ad->asset_id)->first();

        if (!$sellerWallet) {
            $notify[] = 'Seller wallet not found';
            return responseError('not_found', $notify);
        }

        $coinWalletBalance = $coinWallet->balance;
        $feedback          = userFeedback($ad->user_id);

        $notify[] = 'Trade';
        return responseSuccess('trade', $notify, [
            'ad'                    => $ad,
            'coin_wallet_balance'   => $coinWalletBalance,
            'seller_wallet_balance' => $sellerWallet->balance,
            'type'                  => $type,
            'feedback'              => $feedback,
        ]);
    }

    public function requestSave(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fiat_amount'    => 'required|numeric|gt:0',
            'asset_amount'   => 'required|numeric|gt:0',
            'payment_method' => 'required|integer',
            'type'           => 'required|in:buy,sell',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $type  = $request->type;
        $scope = $type == 'buy' ? 'sell' : 'buy';
        $ad    = Ad::$scope()->active()->where('id', $id)->with("paymentWindow", 'asset', 'fiat')->first();

        if (!$ad) {
            $notify[] = 'Ad not found';
            return responseError('not_found', $notify);
        }

        $user = auth()->user();

        if ($ad->user_id == $user->id) {
            $notify[] = 'Trading with self Ad is not permitted';
            return responseError('not_permitted', $notify);
        }

        $coinWallet    = Wallet::where('user_id', $user->id)->where('currency_id', $ad->asset_id)->funding()->first();
        $paymentMethod = AdPaymentMethod::where('id', $request->payment_method)->where('ad_id', $ad->id)->first();

        if (!$ad || !$coinWallet || !$paymentMethod) {
            $notify[] = 'Something went to the wrong';
            return responseError('something_wrong', $notify);
        }

        if ($request->fiat_amount < $ad->minimum_amount) {
            $notify[] = "The minimum amount is " . showAmount($ad->minimum_amount, currencyFormat: false) . " " . $ad->fiat->symbol;
            return responseError('invalid_amount', $notify);
        }

        if ($request->fiat_amount > $ad->maximum_amount) {
            $notify[] = "The maximum amount is " . showAmount($ad->maximum_amount, currencyFormat: false) . " " . $ad->fiat->symbol;
            return responseError('invalid_amount', $notify);
        }

        if ($type == 'sell') {
            $userPaymentMethodExists = UserPaymentMethod::where('user_id', $user->id)->where('payment_method_id', $paymentMethod->payment_method_id)->first();
            if (!$userPaymentMethodExists) {
                $notify[] = 'Payment method not exists';
                return responseError('not_exists', $notify);
            }
            $sellerId = $user->id;
            $buyerId  = $ad->user_id;
        } else {
            $buyerId  = $user->id;
            $sellerId = $ad->user_id;
        }

        $sellerWallet = Wallet::funding()->where('user_id', $sellerId)->where('currency_id', $ad->asset_id)->first();
        $chargeAmount = ($request->asset_amount / 100) * gs('p2p_trade_charge');

        if (($sellerWallet->balance < $request->asset_amount + $chargeAmount)) {
            $notify[] = 'Seller don\'t have sufficient wallet balance for trade.';
            return responseError('insufficient_balance', $notify);
        }

        $trx     = getTrx();
        $details = "P2P Sell Order: " . showAmount($request->asset_amount, currencyFormat: false) . " " . $ad->asset->symbol;
        $this->createTrx("-", $sellerWallet, $request->asset_amount, "p2p_sell_order", $details, $trx);

        $chargeAmount = ($request->asset_amount / 100) * gs('p2p_trade_charge');

        if ($chargeAmount > 0) {
            $details = "P2P Sell Order Charge: " . showAmount($chargeAmount, currencyFormat: false) . " " . $ad->asset->symbol;
            $this->createTrx("-", $sellerWallet, $chargeAmount, "p2p_sell_order", $details, $trx);
        }

        $trade                    = new Trade();
        $trade->type              = $type == 'buy' ? 1 : 2;
        $trade->uid               = $trx;
        $trade->ad_id             = $ad->id;
        $trade->buyer_id          = $buyerId;
        $trade->seller_id         = $sellerId;
        $trade->payment_method_id = $paymentMethod->payment_method_id;
        $trade->asset_amount      = $request->asset_amount;
        $trade->fiat_amount       = $request->fiat_amount;
        $trade->price             = $ad->price;
        $trade->payment_window_id = $ad->payment_window_id;
        $trade->charge            = $chargeAmount;
        $trade->save();

        if (@$trade->ad->auto_replay_text) {
            $message              = new TradeMessage();
            $message->message     = $trade->ad->auto_replay_text;
            $message->trade_id    = $trade->id;
            $message->sender_id   = $trade->ad->user_id;
            $message->receiver_id = $trade->ad->user_id == $sellerId ? $buyerId : $sellerId;
            $message->save();
        }

        notify($ad->user, 'P2P_TRADE', [
            'order_id'     => $trx,
            'asset_amount' => showAmount($trade->asset_amount, currencyFormat: false),
            'fiat_amount'  => showAmount($trade->fiat_amount, currencyFormat: false),
            'asset'        => @$ad->asset->symbol,
            'fiat'         => @$ad->fiat->symbol,
            'date'         => showDateTime($ad->created_at),
        ]);

        $notify[] = 'Trade created successfully';
        return responseSuccess('created_created', $notify, [
            'trade' => $trade,
            'ad'    => $ad,
        ]);
    }

    public function details($id)
    {
        $user  = auth()->user();
        $trade = Trade::myTrade()->where('id', $id)->with('paymentMethod', 'ad.fiat', 'ad.asset')->first();
        if (!$trade) {
            $notify[] = 'Trade not found';
            return responseError('not_found', $notify);
        }

        $buyer    = UserPaymentMethod::where('user_id', $trade->seller_id)->where('payment_method_id', $trade->payment_method_id)->first();
        $messages = TradeMessage::where('trade_id', $trade->id)->orderBy('id', 'desc')->get();

        if ($trade->buyer_id == $user->id) {
            $trader = $trade->seller;
        } else {
            $trader = $trade->buyer;
        }

        $sellerPaymentMethod       = UserPaymentMethod::where('user_id', $trade->seller_id)->where('payment_method_id', $trade->payment_method_id)->first();
        $feedback                  = userFeedback($trader->id);
        $tradeFeedback             = TradeFeedBack::where('trade_id', $trade->id)->first();
        $feedBackAbility           = $this->checkFeedbackAbility($trade, $user->id);
        $paymentTimeRemind         = @$trade->paymentWindow->minute - $trade->created_at->diffInMinutes();
        $paymentTimeRemindInSecond = $trade->created_at->diffInSeconds() % 60;

        $notify[] = "Trade-" . $trade->uid;

        return responseSuccess('trade_details', $notify, [
            'trade'                         => $trade,
            'user'                          => $user,
            'seller_payment_method'         => $sellerPaymentMethod,
            'trader'                        => $trader,
            'messages'                      => $messages,
            'feedback'                      => $feedback,
            'trade_feedback'                => $tradeFeedback,
            'feedback_ability'              => $feedBackAbility,
            'payment_time_remind'           => $paymentTimeRemind,
            'payment_time_remind_in_second' => $paymentTimeRemindInSecond,
        ]);

    }

    public function list($scope)
    {
        $scopes = ['running', 'completed'];

        if (!in_array($scope, $scopes)) {
            $notify[] = 'Something went to the wrong';
            return responseError('something_wrong', $notify);
        }

        $user   = auth()->user();
        $trades = Trade::$scope()->with('ad.fiat', 'ad.asset', 'buyer', 'seller', 'paymentMethod')->myTrade($user->id)->latest('id')->apiQuery();

        $notify[] = ucfirst($scope) . " Trade";
        return responseSuccess('trade_list', $notify, [
            'trades' => $trades,
        ]);
    }

    public function cancel($id)
    {
        $trade = Trade::myTrade()->where('id', $id)->pending()->first();

        if (!$trade) {
            $notify[] = 'Trade not found';
            return responseError('not_found', $notify);
        }

        $user = auth()->user();

        if ($trade->seller_id == $user->id) {
            $paymentTimeRemind = @$trade->paymentWindow->minute - $trade->created_at->diffInMinutes();
            if ($paymentTimeRemind > 0) {
                $notify[] = "You can cancel this trade after $paymentTimeRemind minute";
                return responseError('not_found', $notify);
            }
        }

        $trade->status = Status::P2P_TRADE_CANCELED;
        $trade->save();

        $seller  = $trade->seller;
        $details = "Cancel p2p sell order: " . showAmount($trade->asset_amount, currencyFormat: false) . " " . @$trade->ad->asset->symbol;

        $wallet = Wallet::funding()->where('user_id', $seller->id)->where('currency_id', $trade->ad->asset_id)->first();
        $this->createTrx("+", $wallet, $trade->asset_amount, "p2p_sell_order", $details, $trade->uid);

        if ($trade->charge > 0) {
            $details = "Returned P2P sell order charge " . showAmount($trade->charge, currencyFormat: false) . " " . @$trade->ad->asset->symbol;
            $this->createTrx("+", $wallet, $trade->charge, "p2p_sell_order", $details, $trade->uid);
        }

        notify($seller, 'P2P_TRADE_CANCELED', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->asset_amount, currencyFormat: false),
            'fiat_amount'  => showAmount($trade->fiat_amount, currencyFormat: false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);

        $notify[] = 'Trade canceled successfully';
        return responseSuccess('trade_canceled', $notify);
    }

    public function paid($id)
    {
        $trade = Trade::myTrade()->where('id', $id)->pending()->first();

        if (!$trade) {
            $notify[] = 'Trade not found';
            return responseError('not_found', $notify);
        }

        $trade->status = Status::P2P_TRADE_PAID;
        $trade->save();

        notify(@$trade->seller, 'P2P_TRADE_PAID', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->asset_amount, currencyFormat: false),
            'fiat_amount'  => showAmount($trade->fiat_amount, currencyFormat: false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);

        $notify[] = 'Trade paid successfully';
        return responseSuccess('trade_paid', $notify);
    }
    public function dispute($id)
    {
        $trade = Trade::myTrade()->where('id', $id)->paid()->first();

        if (!$trade) {
            $notify[] = 'Trade not found';
            return responseError('not_found', $notify);
        }

        $trade->status = Status::P2P_TRADE_REPORTED;
        $trade->save();

        notify(@$trade->ad->user, 'P2P_TRADE_REPORT', [
            'order_id'     => $trade->order_id,
            'asset_amount' => showAmount($trade->ad->asset_amount, currencyFormat: false),
            'fiat_amount'  => showAmount($trade->fiat_amount, currencyFormat: false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
            'report_date'  => showDateTime(now()),
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->id();
        $adminNotification->title     = 'P2P Trade  Report';
        $adminNotification->click_url = route('admin.p2p.trade.index', 'reported');
        $adminNotification->save();

        $notify[] = 'Trade reported successfully';
        return responseSuccess('trade_reported', $notify);
    }

    public function release($id)
    {
        $trade = Trade::myTrade()->where('id', $id)->paid()->first();

        if (!$trade) {
            $notify[] = 'Trade not found';
            return responseError('not_found', $notify);
        }

        $trade->status = Status::P2P_AD_COMPLETED;
        $trade->save();

        $buyer   = $trade->buyer;
        $details = "Release p2p buy order: " . showAmount($trade->asset_amount, currencyFormat: false) . " " . @$trade->ad->fiat->symbol;

        $wallet = Wallet::funding()->where('user_id', $buyer->id)->where('currency_id', $trade->ad->asset_id)->first();
        $this->createTrx("+", $wallet, $trade->asset_amount, "p2p_buy_order", $details);

        notify(@$buyer, 'P2P_TRADE_RELEASE', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->ad->asset_amount, currencyFormat: false),
            'fiat_amount'  => showAmount($trade->fiat_amount, currencyFormat: false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);

        $notify[] = 'Trade released successfully';
        return responseSuccess('trade_released', $notify);
    }

    private function createTrx($trxType, $wallet, $amount, $remark, $details, $trx = null)
    {
        if ($trxType == '+') {
            $wallet->balance += $amount;
        } else {
            $wallet->balance -= $amount;
        }
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $wallet->user_id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = $trxType;
        $transaction->details      = $details;
        $transaction->trx          = $trx ?? getTrx();
        $transaction->remark       = $remark;
        $transaction->save();
    }

    public function feedback(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type'    => 'required|in:' . Status::P2P_TRADE_FEEDBACK_NEGATIVE . ',' . Status::P2P_TRADE_FEEDBACK_POSITIVE . '',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $feedbackId = $request->feedback_id ?? 0;
        $trade      = Trade::myTrade()->where('id', $id)->first();

        if (!$trade) {
            $notify[] = 'Trade not found';
            return responseError('not_found', $notify);
        }

        $user = auth()->user();

        if ($feedbackId) {
            $feedback = TradeFeedBack::where('trade_id', $trade->id)->where('id', $feedbackId)->where('provide_by', $user->id)->first();
            if (!$feedback) {
                $notify[] = 'Feedback not found';
                return responseError('not_found', $notify);
            }
            $notify[] = 'Feedback updated successfully';
        } else {
            $feedback = TradeFeedBack::where('trade_id', $trade->id)->first();

            if ($feedback || !$this->checkFeedbackAbility($trade, $user->id)) {
                $notify[] = 'Something went to the wrong';
                return responseError('something_wrong', $notify);
            }

            $feedback             = new TradeFeedBack();
            $feedback->trade_id   = $trade->id;
            $feedback->user_id    = $trade->ad->user_id;
            $feedback->provide_by = $user->id;
            $notify[]             = 'Feedback added successfully';
        }
        $feedback->comment = $request->comment;
        $feedback->type    = $request->type;
        $feedback->save();

        return responseSuccess('feedback_added', $notify);
    }
    public function feedbackDelete($id)
    {
        $feedback = TradeFeedBack::where('id', $id)->where('provide_by', auth()->id())->first();

        if (!$feedback) {
            $notify[] = 'Feedback not found';
            return responseError('not_found', $notify);
        }

        $feedback->delete();

        $notify[] = 'Feedback deleted successfully';
        return responseSuccess('feedback_deleted', $notify);
    }

    private function checkFeedbackAbility($trade, $userId)
    {
        $condition = Status::P2P_TRADE_COMPLETED == $trade->status && ($trade->buyer_id == $userId && $trade->type == Status::P2P_TRADE_SIDE_BUY || $trade->seller_id == $userId && $trade->type == Status::P2P_TRADE_SIDE_SELL);
        return $condition;
    }
}
