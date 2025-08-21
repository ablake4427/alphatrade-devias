<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FutureTrade extends Model
{
    public function futureTradeConfig()
    {
        return $this->belongsTo(FutureTradeConfig::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
