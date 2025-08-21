<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use ApiQuery;

    public function name(): Attribute
    {
        return new Attribute(
            get: fn() => @$this->currency->symbol . " Wallet",
        );
    }

    public function totalBalance(): Attribute
    {
        return new Attribute(
            get: fn() => @$this->in_order + $this->balance
        );
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSpot($query)
    {
        return $query->where('wallet_type', Status::WALLET_TYPE_SPOT);
    }

    public function scopeFunding($query)
    {
        return $query->where('wallet_type', Status::WALLET_TYPE_FUNDING);
    }

    public function scopeFuture($query)
    {
        return $query->where('wallet_type', Status::WALLET_TYPE_FUTURE);
    }


    public function typeBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->wallet_type == Status::WALLET_TYPE_SPOT) {
                return '<span class="badge badge--base">' . trans('Spot') . '</span>';
            } elseif ($this->wallet_type == Status::WALLET_TYPE_FUNDING) {
                return '<span class="badge badge--primary">' . trans('Funding') . '</span>';
            } elseif ($this->wallet_type == Status::WALLET_TYPE_FUTURE) {
                return '<span class="badge badge--info">' . trans('Future') . '</span>';
            } else {
                return '<span class="badge badge--dark">' . trans('N/A') . '</span>';
            }
        });
    }
    public function typeText(): Attribute
    {
        return new Attribute(function () {
            if ($this->wallet_type == Status::WALLET_TYPE_SPOT) {
                return "spot";
            }

            if ($this->wallet_type == Status::WALLET_TYPE_FUNDING) {
                return "funding";
            }

            if ($this->wallet_type == Status::WALLET_TYPE_FUTURE) {
                return "future";
            }
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($wallet) {
            if ($wallet->wallet_type == Status::WALLET_TYPE_FUTURE) {
                $user             = $wallet->user;
                $runningPositions = FutureOrder::positioned()->where('margin_mode', Status::MARGIN_MODE_CROSS)->where('user_id', $user->id)->get();
                if ($runningPositions->count()) {
                    $position         = $runningPositions->first();
                    $liquidationPrice = getLiquidationRate($position);
                    if ($runningPositions->count() == 1) {
                        $position->liquidation_rate = $liquidationPrice;
                        $position->save();
                    }
                }
            }
        });

    }

}
