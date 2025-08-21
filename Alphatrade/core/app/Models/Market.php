<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use GlobalStatus;

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    public function pairs()
    {
        return $this->hasMany(CoinPair::class, 'market_id');
    }
}
