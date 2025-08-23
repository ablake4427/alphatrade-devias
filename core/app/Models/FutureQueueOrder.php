<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FutureQueueOrder extends Model
{
    public function scopeOpen($query)
    {
        return $query->where('status', Status::FUTURE_QUEUE_OPEN);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', Status::FUTURE_QUEUE_COMPLETED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', Status::FUTURE_QUEUE_CANCELED);
    }

    public function scopeBuySide($query)
    {
        return $query->where('order_side', Status::BUY_SIDE_ORDER);
    }

    public function scopeSellSide($query)
    {
        return $query->where('order_side', Status::SELL_SIDE_ORDER);
    }

    public function scopeQueueMarketOrder($query)
    {
        return $query->where('type', Status::QUEUE_MARKET_ORDER);
    }

    public function scopeQueueLimitOrder($query)
    {
        return $query->where('type', Status::QUEUE_LIMIT_ORDER);
    }

    public function scopeQueueStopLimitOrder($query)
    {
        return $query->where('type', Status::QUEUE_STOP_LIMIT);
    }

    public function scopeQueueTakeProfitLossOrder($query)
    {
        return $query->where('type', Status::QUEUE_TAKE_PROFIT_LOSS);
    }

    public function scopeNotQueueStopLimitOrder($query)
    {
        return $query->where('type', '!=', Status::QUEUE_STOP_LIMIT);
    }

    public function scopeSellSideOrder($query)
    {
        return $query->where('order_side', Status::SELL_SIDE_ORDER);
    }
    public function scopeBuySideOrder($query)
    {
        return $query->where('order_side', Status::BUY_SIDE_ORDER);
    }

    public function futureOrder()
    {
        return $this->belongsTo(FutureOrder::class);
    }

    public function futureTradeConfig()
    {
        return $this->belongsTo(FutureTradeConfig::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pair()
    {
        return $this->belongsTo(CoinPair::class, 'pair_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::FUTURE_QUEUE_OPEN) {
                $html = '<span class="badge badge--primary">' . trans('Open') . '</span>';
            } elseif ($this->status == Status::FUTURE_QUEUE_CANCELED) {
                $html = '<span class="badge badge--danger">' . trans('Canceled') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans('Completed') . '</span>';
            }
            return $html;
        });
    }

}
