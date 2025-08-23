<?php

namespace App\Models\P2P;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TradeMessage extends Model
{
    protected $table = "p2p_trade_messages";

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
