<?php

namespace App\Models\P2P;

use App\Models\User;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class TradeFeedBack extends Model
{
    use ApiQuery;
    protected $table = "p2p_trade_feed_backs";

    
    public function feedbackProvider()
    {
        return $this->belongsTo(User::class, 'provide_by');
    }

}
