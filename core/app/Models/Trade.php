<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use ApiQuery;

    protected $appends = [
        'formatted_date'
    ];

    public function getFormattedDateAttribute()
    {
        return showDateTime($this->created_at, 'y.m.d h:i');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function tradeSideBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->trade_side == Status::BUY_SIDE_TRADE) {
                $html = '<span class="text--success">' . trans('Buy') . '</span>';
            } else {
                $html = '<span class="text--danger">' . trans('Sell') . '</span>';
            }
            return $html;
        });
    }
}
