<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritePair extends Model
{
    public function futureTradeConfig()
    {
        return $this->belongsTo(FutureTradeConfig::class);
    }
}
