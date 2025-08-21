<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BinaryTrade extends Model {
    use GlobalStatus, ApiQuery;
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function coinPair() {
        return $this->belongsTo(CoinPair::class, 'coin_pair_id');
    }
    public function scopePending($query) {
        return $query->where('win_status', Status::BINARY_TRADE_PENDING);
    }
    public function scopeWin($query) {
        return $query->where('win_status', Status::BINARY_TRADE_WIN);
    }
    public function scopeLose($query) {
        return $query->where('win_status', Status::BINARY_TRADE_LOSE);
    }
    public function scopeRefund($query) {
        return $query->where('win_status', Status::BINARY_TRADE_REFUND);
    }
    public function scopeProcessing($query) {
        return $query->where('win_status', Status::BINARY_TRADE_PROCESSING);
    }

    public function winStatusBadge(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->win_status == Status::BINARY_TRADE_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } else if ($this->win_status == Status::BINARY_TRADE_WIN) {
                $html = '<span class="badge badge--success">' . trans('Win') . '</span>';
            } else if ($this->win_status == Status::BINARY_TRADE_REFUND) {
                $html = '<span class="badge badge--info">' . trans('Refund') . '</span>';
            } else {
                $html = '<span class="badge badge--danger">' . trans('Lose') . '</span>';
            }
            return $html;
        });
    }
    public function tradeStatusBadge(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::ENABLE) {
                $html = '<span class="badge badge--danger">' . trans('Closed') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans('Running') . '</span>';
            }
            return $html;
        });
    }
}
