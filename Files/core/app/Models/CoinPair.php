<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CoinPair extends Model {
    use GlobalStatus, ApiQuery;
    protected $casts = [
        'binary_trade_duration' => 'object',
    ];
    protected $guard = ['id'];

    public function market() {
        return $this->belongsTo(Market::class, 'market_id');
    }
    public function coin() {
        return $this->belongsTo(Currency::class, 'coin_id');
    }
    public function marketData() {
        return $this->hasOne(MarketData::class, 'pair_id');
    }
    public function orders() {
        return $this->hasMany(Order::class, 'pair_id');
    }
    public function trade() {
        return $this->hasMany(Trade::class, 'pair_id');
    }
    public function binaryTrade() {
        return $this->hasMany(BinaryTrade::class, 'coin_pair_id');
    }

    public function futureTradeConfig() {
        return $this->hasOne(FutureTradeConfig::class, 'pair_id');
    }

    public function scopeActiveMarket($query) {
        return $query->whereHas('market', function ($q) {
            $q->active()->whereHas('currency', function ($currency) {
                $currency->active();
            });
        });
    }
    public function scopeActiveCoin($query) {
        return $query->whereHas('coin', function ($q) {
            $q->active();
        });
    }

    public function scopeSpotTrade($query) {
        return $this->where('type', Status::SPOT_TRADE);
    }
    public function scopeBinaryTrade($query) {
        return $this->where('type', Status::BINARY_TRADE);
    }
    public function scopeBothTrade($query) {
        return $this->where('type', Status::BOTH_TRADE);
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

    public function buyPlaceHolder(): Attribute {
        return new Attribute(function () {
            if ($this->maximum_buy_amount <= 0) {
                return trans('Minimum ') . showAmount($this->minimum_buy_amount, currencyFormat: false);
            } else {
                return showAmount($this->minimum_buy_amount, currencyFormat: false) . '-' . showAmount($this->maximum_buy_amount, currencyFormat: false);
            }
        });
    }

    public function sellPlaceHolder(): Attribute {
        return new Attribute(function () {
            if ($this->maximum_sell_amount <= 0) {
                return trans('Minimum ') . showAmount($this->minimum_sell_amount, currencyFormat: false);
            } else {
                return showAmount($this->minimum_sell_amount, currencyFormat: false) . '-' . showAmount($this->maximum_sell_amount, currencyFormat: false);
            }
        });
    }

}
