<?php

namespace App\Models\P2P;

use App\Constants\Status;
use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ApiQuery;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use GlobalStatus, ApiQuery;

    protected $table = "p2p_ads";

    public function paymentMethods()
    {
        return $this->hasMany(AdPaymentMethod::class, 'ad_id', 'id');
    }

    public function paymentWindow()
    {
        return $this->belongsTo(PaymentWindow::class, 'payment_window_id');
    }
    public function trades()
    {
        return $this->hasMany(Trade::class, 'ad_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'currency_id', 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fiat()
    {
        return $this->belongsTo(Currency::class, 'fiat_id');
    }
    public function asset()
    {
        return $this->belongsTo(Currency::class, 'asset_id');
    }
    public function scopeBuy($query)
    {
        return $query->where('type', Status::P2P_AD_TYPE_BUY);
    }

    public function scopeSell($query)
    {
        return $query->where('type', Status::P2P_AD_TYPE_SELL);
    }

    public function scopePublishStatus($query)
    {
        $publishStatusSqlStatement = 'CASE WHEN p2p_ads.complete_step = 3 THEN (CASE WHEN p2p_ads.type = 1 THEN 1 ELSE (CASE WHEN wallets.balance >= (p2p_ads.minimum_amount/p2p_ads.price) THEN 1 ELSE 0 END) END ) ELSE 0 END as publish_status';
        $query->leftJoin('wallets', function ($q) {
            $q->on('p2p_ads.asset_id', '=', 'wallets.currency_id')
                ->where('wallets.user_id', \DB::raw('p2p_ads.user_id'))
                ->where('wallet_type', Status::WALLET_TYPE_FUNDING);
        })->selectRaw($publishStatusSqlStatement);
    }

    public function typeBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->type == Status::P2P_AD_TYPE_BUY) {
                return '<span class="badge badge--success">' . trans('Buy') . '</span>';
            } else {
                return '<span class="badge badge--danger">' . trans('Sell') . '</span>';
            }
        });
    }

    public function pricingTypeBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->price_type == Status::P2P_AD_PRICE_TYPE_FIXED) {
                return '<span class="text--warning">' . trans('Fixed') . '</span>';
            } else {
                return '<span class="text--info">' . trans('Margin') . ' <small class="fs-10">(' . getAmount($this->price_margin, 2) . '%)</small> </span>';
            }
        });
    }
}
