<?php

namespace App\Http\Controllers\Admin\P2P;

use App\Constants\Status;
use App\Events\P2PMessage;
use App\Http\Controllers\Controller;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeMessage;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TradeController extends Controller
{
    public function index($scope)
    {
        $scopes = ['running', 'completed', 'reported'];
        abort_if(!in_array($scope, $scopes), 404);
        $trades    = Trade::$scope()->with('ad.fiat', 'ad.asset', 'buyer', 'seller', 'paymentMethod')->latest('id')->paginate(getPaginate());
        $pageTitle = ucfirst($scope) . " Trade";
        return view('admin.p2p.trade.index',  compact('trades', 'pageTitle'));
    }

    public function details($id)
    {
        $trade     = Trade::where('id', $id)->with("paymentMethod")->firstOrFail();
        $pageTitle = "P2P Trade-" . $trade->uid;
        $messages  = TradeMessage::where('trade_id', $trade->id)->get();
        return view('admin.p2p.trade.details', compact('trade', 'pageTitle', 'messages'));
    }

    public function complete($id, $action)
    {
        $actions = ['seller', 'buyer'];
        abort_if(!in_array($action, $actions), 404);

        $trade         = Trade::reported()->where('id', $id)->firstOrFail();
        $trade->status = Status::P2P_AD_COMPLETED;
        $trade->save();

        if ($action == 'seller') {
            return $this->sellerFavour($trade);
        } else {
            return $this->buyerFavour($trade);
        }
    }

    private function sellerFavour($trade)
    {
        $seller  = $trade->seller;
        $details = "Cancel(By Admin) after report to the trade";
        $wallet  = Wallet::funding()->where('user_id', $seller->id)->where('currency_id', $trade->ad->asset_id)->first();
        $this->createTrx("+", $wallet, $trade->asset_amount, "p2p_sell_order", $details, $trade->uid);

        if ($trade->charge > 0) {
            $details = "Return P2P sell order charge" . showAmount($trade->charge,currencyFormat:false) . " " . @$trade->ad->fiat->symbol;
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

        return returnBack("Trade completed successfully", 'success');
    }

    private function buyerFavour($trade)
    {
        $buyer   = $trade->buyer;
        $details = "Realse(by admin) p2p buy order: " . showAmount($trade->asset_amount,currencyFormat:false) . " " . @$trade->ad->fiat->symbol;
        $wallet  = Wallet::funding()->where('user_id', $buyer->id)->where('currency_id', $trade->ad->asset_id)->first();
        $this->createTrx("+", $wallet, $trade->asset_amount, "p2p_buy_order", $details, $trade->uid);

        notify(@$buyer, 'P2P_TRADE_RELEASE', [
            'order_id'     => $trade->uid,
            'asset_amount' => showAmount($trade->asset_amount,currencyFormat:false),
            'fiat_amount'  => showAmount($trade->fiat_amount,currencyFormat:false),
            'asset'        => @$trade->ad->asset->symbol,
            'fiat'         => @$trade->ad->fiat->symbol,
            'date'         => showDateTime($trade->ad->created_at),
        ]);
        
        return returnBack("Trade completed successfully", 'success');
    }

    public function messageSave(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return jsonResponse($validator->errors()->all());
        }

        $trade = Trade::where('id', $id)->first();

        if (!$trade) {
            return jsonResponse("Trade not found");
        }

        if ($trade->completed == Status::P2P_TRADE_COMPLETED) {
            return jsonResponse("Trade is completed");
        }

        $message              = new TradeMessage();
        $message->trade_id    = $trade->id;
        $message->sender_id   = 0;
        $message->receiver_id = 0;
        $message->admin_id    = auth()->guard('admin')->user()->id;
        $message->message     = $request->message;
        $message->save();

        $html     = view("admin.p2p.trade.single_message", ['message' => $message,])->render();
        $userHtml = view('Template::user.p2p.trade.single_message', ['message' => $message])->render();

        event(new P2PMessage($trade->id, $trade->seller_id, $userHtml));
        event(new P2PMessage($trade->id, $trade->buyer_id, $userHtml));

        return jsonResponse(null, true, ['html' => $html]);
    }

    private function createTrx($trxType, $wallet, $amount, $remark, $details, $trx = null)
    {
        $wallet->balance += $amount;
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
}
