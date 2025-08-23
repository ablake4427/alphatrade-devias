<?php

namespace App\Http\Controllers\User\P2P;

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
use Exception;
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
            return jsonResponse($validator->errors()->all());
        }

        try {
            $id = decrypt($id);
        } catch (Exception $ex) {
            return jsonResponse("Something went to wrong");
        }

        $type  = $request->type;
        $scope = $type == 'buy' ? 'sell' : 'buy';
        $ad    = Ad::$scope()->active()->where('id', $id)
            ->withCount(['trades as total_trade', 'trades' => function ($q) {
                $q->where('status', Status::P2P_TRADE_COMPLETED);
            }])
            ->with("paymentWindow", 'asset', 'fiat')
            ->first();
        $user       = auth()->user();
        $coinWallet = Wallet::where('user_id', $ad->user_id)->where('currency_id', $ad->asset_id)->funding()->first();

        if (!$ad || !$coinWallet) {
            return jsonResponse("Something went to wrong");
        }

        $pageTitle         = "Trade";
        $coinWalletBalance = $coinWallet->balance;
        $feedback          = userFeedback($ad->user_id);
        $html              = view("Template::p2p.trade.request.$type", compact('pageTitle', 'ad', 'coinWalletBalance', 'type', 'feedback'))->render();

        return jsonResponse(null, true, [
            'ad'   => $ad,
            'html' => $html
        ]);

        return view('Template::p2p.trade', compact('pageTitle', 'ad'));
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
            return jsonResponse($validator->errors()->all());
        }

        try {
            $id = decrypt($id);
        } catch (Exception $ex) {
            return jsonResponse("Something went to wrong");
        }

        $type  = $request->type;
        $scope = $type == 'buy' ? 'sell' : 'buy';
        $ad    = Ad::$scope()->active()->where('id', $id)->with("paymentWindow", 'asset', 'fiat')->first();
        $user  = auth()->user();

        if ($ad->user_id == $user->id) {
            return jsonResponse("Trading with self Ad is not permitted.");
        }

        $coinWallet   = Wallet::where('user_id', $user->id)->where('currency_id', $ad->asset_id)->funding()->first();
        $paymentMthod = AdPaymentMethod::where('id', $request->payment_method)->where('ad_id', $ad->id)->first();

        if (!$ad || !$coinWallet || !$paymentMthod) {
            return jsonResponse("Something went to wrong");
        }

        if ($request->fiat_amount < $ad->minimum_amount) {
            return jsonResponse("The minimum amount is " . showAmount($ad->minimum_amount,currencyFormat:false) . " " . $ad->fiat->symbol);
        }

        if ($request->fiat_amount > $ad->maximum_amount) {
            return jsonResponse("The maximum amount is " . showAmount($ad->maximum_amount,currencyFormat:false) . " " . $ad->fiat->symbol);
        }

        if ($type == 'sell') {
            $userPaymentMethodExists = UserPaymentMethod::where('user_id', $user->id)->where('payment_method_id', $paymentMthod->payment_method_id)->first();
            if (!$userPaymentMethodExists) {
                $formData          = $paymentMthod->paymentMethod->userData->form_data;
                $paymentMethod     = $paymentMthod->paymentMethod;
                $paymentMethodHtml = view("Template::p2p.trade.add_payment_method", compact('paymentMethod'))->render();
                return jsonResponse("Please add your payment method information.", false, ['ad_payment_method' => true, 'html' => $paymentMethodHtml, 'title' => $paymentMethod->name . " Information"]);
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
            return jsonResponse("Seller don't have sufficient wallet balance for trade.");
        }

        $trx     = getTrx();
        $details = "P2P Sell Order: " . showAmount($request->asset_amount,currencyFormat:false) . " " . $ad->asset->symbol;
        $this->createTrx("-", $sellerWallet, $request->asset_amount, "p2p_sell_order", $details, $trx);

        $chargeAmount = ($request->asset_amount / 100) * gs('p2p_trade_charge');

        if ($chargeAmount > 0) {
            $details = "P2P Sell Order Charge: " . showAmount($chargeAmount,currencyFormat:false) . " " . $ad->asset->symbol;
            $this->createTrx("-", $sellerWallet, $chargeAmount, "p2p_sell_order", $details, $trx);
        }

        $trade                    = new Trade();
        $trade->type              = $type == 'buy' ? 1 : 2;
        $trade->uid               = $trx;
        $trade->ad_id             = $ad->id;
        $trade->buyer_id          = $buyerId;
        $trade->seller_id         = $sellerId;
        $trade->payment_method_id = $paymentMthod->payment_method_id;
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
            'asset_amount' => showAmount($trade->asset_amount,currencyFormat:false),
            'fiat_amount'  => showAmount($trade->fiat_amount,currencyFormat:false),
            'asset'        => @$ad->asset->symbol,
            'fiat'         => @$ad->fiat->symbol,
            'date'         => showDateTime($ad->created_at)
        ]);

        $data['url'] = route('user.p2p.trade.details', $trade->id);
        return jsonResponse($ad->fiat->symbol . " $type successfully", true, $data);
    }

    public function details($id)
    {

        $user      = auth()->user();
        $trade     = Trade::myTrade()->where('id', $id)->with("paymentMethod")->firstOrFail();
        $pageTitle = "Trade-" . $trade->uid;

        $buyer    = UserPaymentMethod::where('user_id', $trade->seller_id)->where('payment_method_id', $trade->payment_method_id)->first();
        $messages = TradeMessage::where('trade_id', $trade->id)->get();

        if ($trade->buyer_id == $user->id) {
            $trader = $trade->seller;
        } else {
            $trader = $trade->buyer;
        }

        $sellerPaymentMethod       = UserPaymentMethod::where('user_id', $trade->seller_id)->where('payment_method_id', $trade->payment_method_id)->first();
        $feedback                   = userFeedback($trader->id);
        $tradeFeedback              = TradeFeedBack::where('trade_id', $trade->id)->first();
        $feedBackAbility           = $this->checkFeedbackAbility($trade, $user->id);
        $paymentTimeRemind         = @$trade->paymentWindow->minute - $trade->created_at->diffInMinutes();
        $paymentTimeRemindInSecond = $trade->created_at->diffInSeconds() % 60;

        return view('Template::user.p2p.trade.details', compact('trade', 'pageTitle', 'user', 'sellerPaymentMethod', 'trader', 'messages', 'feedback', 'tradeFeedback', 'feedBackAbility', 'paymentTimeRemind', 'paymentTimeRemindInSecond'));
    }

    public function list($scope)
    {
        $scopes = ['running', 'completed'];
        abort_if(!in_array($scope, $scopes), 404);
        $user      = auth()->user();
        $trades    = Trade::$scope()->with('ad.fiat', 'ad.asset', 'buyer', 'seller', 'paymentMethod')->myTrade($user->id)->latest('id')->paginate(getPaginate());
        $pageTitle = ucfirst($scope) . " Trade";
        return view('Template::user.p2p.trade.index', compact('trades', 'pageTitle', 'user'));
    }

    public function cancel($id)
    {
        $trade = Trade::myTrade()->where('id', $id)->pending()->firstOrFail();
        $user  = auth()->user();

        if ($trade->seller_id == $user->id) {
            $paymentTimeRemind = @$trade->paymentWindow->minute - $trade->created_at->diffInMinutes();
            if ($paymentTimeRemind > 0) {
                return returnBack("You can cancel this trade after $paymentTimeRemind minute");
            }
        }

        $trade->status = Status::P2P_TRADE_CANCELED;
        $trade->save();

        $seller  = $trade->seller;
        $details = "Cancel p2p sell order: " . showAmount($trade->asset_amount,currencyFormat:false) . " " . @$trade->ad->asset->symbol;

        $wallet = Wallet::funding()->where('user_id', $seller->id)->where('currency_id', $trade->ad->asset_id)->first();
        $this->createTrx("+", $wallet, $trade->asset_amount, "p2p_sell_order", $details, $trade->uid);

        if ($trade->charge > 0) {
            $details = "Returned P2P sell order charge " . showAmount($trade->charge,currencyFormat:false) . " " . @$trade->ad->asset->symbol;
            $this->createTrx("+", $wallet, $trade->charge, "p2p_sell_order", $details, $trade->uid);
        }

        notify($seller, 'P2P_TRADE_CANCELED', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->asset_amount,currencyFormat:false),
            'fiat_amount'  => showAmount($trade->fiat_amount,currencyFormat:false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);

        return returnBack("Trade canceled successfully", 'success');
    }

    public function paid($id)
    {
        $trade         = Trade::myTrade()->where('id', $id)->pending()->firstOrFail();
        $trade->status = Status::P2P_TRADE_PAID;
        $trade->save();

        notify(@$trade->seller, 'P2P_TRADE_PAID', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->asset_amount,currencyFormat:false),
            'fiat_amount'  => showAmount($trade->fiat_amount,currencyFormat:false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);

        return returnBack("Trade paid successfully", 'success');
    }
    public function dispute($id)
    {
        $trade         = Trade::myTrade()->where('id', $id)->paid()->firstOrFail();
        $trade->status = Status::P2P_TRADE_REPORTED;
        $trade->save();

        notify(@$trade->ad->user, 'P2P_TRADE_REPORT', [
            'order_id'     => $trade->order_id,
            'asset_amount' => showAmount($trade->ad->asset_amount,currencyFormat:false),
            'fiat_amount'  => showAmount($trade->fiat_amount,currencyFormat:false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
            'report_date'  => showDateTime(now())
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->id();
        $adminNotification->title     = 'P2P Trade  Report';
        $adminNotification->click_url = route('admin.p2p.trade.index', 'reported');
        $adminNotification->save();

        return returnBack("Trade reported successfully", 'success');
    }

    public function release($id)
    {
        $trade         = Trade::myTrade()->where('id', $id)->paid()->firstOrFail();
        $trade->status = Status::P2P_AD_COMPLETED;
        $trade->save();

        $buyer   = $trade->buyer;
        $details = "Realse p2p buy order: " . showAmount($trade->asset_amount,currencyFormat:false) . " " . @$trade->ad->fiat->symbol;

        $wallet = Wallet::funding()->where('user_id', $buyer->id)->where('currency_id', $trade->ad->asset_id)->first();
        $this->createTrx("+", $wallet, $trade->asset_amount, "p2p_buy_order", $details);

        notify(@$buyer, 'P2P_TRADE_RELEASE', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->ad->asset_amount,currencyFormat:false),
            'fiat_amount'  => showAmount($trade->fiat_amount,currencyFormat:false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);
        return returnBack("Trade Release successfully", 'success');
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
        $request->validate([
            'type'    => 'required|in:' . Status::P2P_TRADE_FEEDBACK_NEGATIVE . ',' . Status::P2P_TRADE_FEEDBACK_POSITIVE . '',
            'comment' => 'required|string',
        ]);

        $feedbackId = $request->feedback_id ?? 0;
        $trade     = Trade::myTrade()->where('id', $id)->firstOrFail();
        $user      = auth()->user();
        
        if ($feedbackId) {
            $feedback = TradeFeedBack::where('trade_id', $trade->id)->where('id', $feedbackId)->where('provide_by', $user->id)->firstOrFail();
        } else {
            $feedback = TradeFeedBack::where('trade_id', $trade->id)->first();
            abort_if($feedback || !$this->checkFeedbackAbility($trade, $user->id), 404);
            $feedback             = new TradeFeedBack();
            $feedback->trade_id   = $trade->id;
            $feedback->user_id    = $trade->ad->user_id;
            $feedback->provide_by = $user->id;
        }
        $feedback->comment = $request->comment;
        $feedback->type    = $request->type;
        $feedback->save();

        return returnBack("Feedback added successfully", 'success');
    }
    public function feedbackDelete($id)
    {
        $feedback = TradeFeedBack::where('id', $id)->where('provide_by', auth()->id())->firstOrFail();
        $feedback->delete();

        return returnBack("Feedback deleted successfully", 'success');
    }

    private function checkFeedbackAbility($trade, $userId)
    {
        $condition =  Status::P2P_TRADE_COMPLETED == $trade->status && ($trade->buyer_id == $userId && $trade->type == Status::P2P_TRADE_SIDE_BUY || $trade->seller_id == $userId && $trade->type == Status::P2P_TRADE_SIDE_SELL);
        return $condition;
    }
}
