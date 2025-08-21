<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FutureOrder extends Model
{
    public function scopePositioned($query)
    {
        return $query->where('status', Status::FUTURE_ORDER_POSITIONED);
    }

    public function scopeLiquidated($query)
    {
        return $query->where('status', Status::FUTURE_ORDER_LIQUIDATED);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', Status::FUTURE_ORDER_CLOSED);
    }

    public function scopeSellSideOrder($query)
    {
        return $query->where('order_side', Status::SELL_SIDE_ORDER);
    }
    public function scopeBuySideOrder($query)
    {
        return $query->where('order_side', Status::BUY_SIDE_ORDER);
    }

    public function futureTradeConfig()
    {
        return $this->belongsTo(FutureTradeConfig::class);
    }

    public function futureQueueOrders()
    {
        return $this->hasMany(FutureQueueOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::FUTURE_ORDER_POSITIONED) {
                $html = '<span class="badge badge--primary">' . trans('Running') . '</span>';
            } elseif ($this->status == Status::FUTURE_ORDER_LIQUIDATED) {
                $html = '<span class="badge badge--danger">' . trans('Liquidated') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans('Completed') . '</span>';
            }
            return $html;
        });
    }
}
