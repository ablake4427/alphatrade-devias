<?php

namespace App\Http\Controllers\User\P2P;

use App\Constants\Status;
use App\Events\P2PMessage;
use App\Http\Controllers\Controller;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    public function save(Request $request, $tradeId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return jsonResponse($validator->errors()->all());
        }

        $trade = Trade::myTrade()->where('id', $tradeId)->first();

        if (!$trade) {
            return jsonResponse("Trade not found");
        }
        if ($trade->completed == Status::P2P_TRADE_COMPLETED || $trade->status == Status::P2P_TRADE_CANCELED) {
            return jsonResponse("Trade is completed");
        }

        $userId             = auth()->id();
        $message            = new TradeMessage();
        $message->trade_id  = $trade->id;
        $message->sender_id = $userId;

        if ($trade->seller_id == $userId) {
            $receiverId = $trade->buyer_id;
        } else {
            $receiverId = $trade->seller_id;
        }

        $message->receiver_id = $receiverId;
        $message->message     = $request->message;
        $message->save();

        $senderHtml   = view('Template::user.p2p.trade.single_message', ['message' => $message, 'direction' => 'sender'])->render();
        $receiverHtml = view('Template::user.p2p.trade.single_message', ['message' => $message, 'direction' => 'receiver'])->render();
        $adminHtml    = view("admin.p2p.trade.single_message", ['message' => $message])->render();

        event(new P2PMessage($trade->id, $message->receiver_id, $receiverHtml, $adminHtml, $request->message, auth()->id()));
        return jsonResponse(null, true, ['html' => $senderHtml]);
    }
}
