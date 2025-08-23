<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Constants\Status;
use App\Traits\GlobalStatus;

class FutureTradeConfig extends Model
{
    use GlobalStatus;

    public function coinPair()
    {
        return $this->belongsTo(CoinPair::class, 'pair_id');
    }

    public function futureOrders()
    {
        return $this->hasMany(FutureOrder::class);
    }

    public function isDefaultStatus(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->is_default == Status::YES) {
                $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
            } else {
                $html = '<span class="badge badge--dark">' . trans('No') . '</span>';
            }
            return $html;
        });
    }

}
