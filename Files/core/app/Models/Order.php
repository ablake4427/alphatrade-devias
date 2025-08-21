<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use ApiQuery;

    protected $appends = [
        'order_side_badge',
        'formatted_date',
        'status_badge',
    ];

    public function pair()
    {
        return $this->belongsTo(CoinPair::class);
    }
    public function coin()
    {
        return $this->belongsTo(Currency::class, 'coin_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function trades()
    {
        return $this->hasMany(Trade::class, 'order_id');
    }
    public function getFormattedDateAttribute()
    {
        return showDateTime($this->created_at);
    }
    public function orderSideBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->order_side == Status::BUY_SIDE_ORDER) {
                $html = '<span class="text--success">' . trans('Buy') . '</span>';
            } else {
                $html = '<span class="text--danger">' . trans('Sell') . '</span>';
            }
            return $html;
        });
    }

    public function orderTypeBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->order_type == Status::ORDER_TYPE_LIMIT) {
                $html = '<span>' . trans('Limit') . '</span>';
            } elseif ($this->order_type == Status::ORDER_TYPE_MARKET) {
                $html = '<span>' . trans('Market') . '</span>';
            } else {
                $html = '<span>' . trans('Stop Limit') . '</span>';
            }
            return $html;
        });
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::ORDER_OPEN) {
                $html = '<span class="badge badge--primary">' . trans('Open') . '</span>';
            } elseif ($this->status == Status::ORDER_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } elseif ($this->status == Status::ORDER_CANCELED) {
                $html = '<span class="badge badge--danger">' . trans('Canceled') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans('Completed') . '</span>';
            }
            return $html;
        });
    }

    public function scopeOpen($query)
    {
        return $query->where('status', Status::ORDER_OPEN);
    }
    public function scopePending($query)
    {
        return $query->where('status', Status::ORDER_PENDING);
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', Status::ORDER_COMPLETED);
    }
    public function scopeCanceled($query)
    {
        return $query->where('status', Status::ORDER_CANCELED);
    }
    public function scopeSellSideOrder($query)
    {
        return $query->where('order_side', Status::SELL_SIDE_ORDER);
    }
    public function scopeBuySideOrder($query)
    {
        return $query->where('order_side', Status::BUY_SIDE_ORDER);
    }
}
