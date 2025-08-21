<?php

namespace App\Models\P2P;

use App\Constants\Status;
use App\Models\User;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use ApiQuery;

    protected $table = "p2p_trades";

    public function ad()
    {
        return $this->belongsTo(Ad::class, 'ad_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    public function paymentWindow()
    {
        return $this->belongsTo(PaymentWindow::class, 'payment_window_id');
    }

    public function scopePending($query)
    {
        $query->where('status', Status::P2P_TRADE_PENDING);
    }

    public function scopePaid($query)
    {
        $query->where('status', Status::P2P_TRADE_PAID);
    }

    public function scopeRunning($query)
    {
        $query->whereIn('status', [Status::P2P_TRADE_PENDING, Status::P2P_TRADE_PAID]);
    }

    public function scopeCompleted($query)
    {
        $query->whereIn('status', [Status::P2P_TRADE_CANCELED, Status::P2P_TRADE_COMPLETED, Status::P2P_TRADE_REPORTED]);
    }
    public function scopeReported($query)
    {
        $query->where('status', Status::P2P_TRADE_REPORTED);
    }
    public function scopeMyTrade($query, $userId = null)
    {
        $userId = $id ?? auth()->id();

        $query->where(function ($q) use ($userId) {
            $q->where('seller_id', $userId)->orWhere('buyer_id', $userId);
        });
    }

    public function typeBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->type == Status::P2P_TRADE_SIDE_BUY) {
                return '<span class="badge badge--success">' . trans('Buy') . '</span>';
            } else {
                return '<span class="badge badge--danger">' . trans('Sell') . '</span>';
            }
        });
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->status == Status::P2P_TRADE_PENDING) {
                return '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } elseif ($this->status == Status::P2P_TRADE_PAID) {
                return '<span class="badge badge--info badge-info">' . trans('Paid') . '</span>';
            } elseif ($this->status == Status::P2P_TRADE_REPORTED) {
                return '<span class="badge badge--warning">' . trans('Reported') . '</span>';
            } elseif ($this->status == Status::P2P_TRADE_COMPLETED) {
                return '<span class="badge badge--success">' . trans('Completed') . '</span>';
            } else {
                return '<span class="badge badge--danger">' . trans('Canceled') . '</span>';
            }
        });
    }
}
