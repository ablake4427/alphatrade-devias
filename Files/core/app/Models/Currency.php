<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Currency extends Model
{
    use GlobalStatus;

    protected $casts = [
        'html_classes' => 'object',
    ];
    protected $appends = [
        'image_url'
    ];

    public function nameAndSymbol(): Attribute
    {
        return new Attribute(
            get: fn () => $this->name . '-' . $this->symbol,
        );
    }

    public function imageUrl(): Attribute
    {
        return new Attribute(
            get: fn () => getImage(getFilePath('currency') . '/' . $this->image, getFileSize('currency')),
        );
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'currency_id');
    }

    public function pairs()
    {
        return $this->hasMany(CoinPair::class, 'coin_id');
    }

    public function depositMethods()
    {
        return $this->hasMany(GatewayCurrency::class, 'currency', 'symbol');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'currency_id')->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function withdrawMethods()
    {
        return $this->hasMany(WithdrawMethod::class, 'currency', 'symbol');
    }

    public function marketData()
    {
        return $this->hasOne(MarketData::class, 'currency_id');
    }

    public function scopeCrypto($query)
    {
        $query->where('type', Status::CRYPTO_CURRENCY);
    }

    public function scopeFiat($query)
    {
        $query->where('type', Status::FIAT_CURRENCY);
    }

    public function scopeRankOrdering($query)
    {
        return $query->orderByRaw('ranking = 0 ASC, ranking ASC');
    }

    public function scopeP2POrdering($query)
    {
        $query->orderByRaw('p2p_sn = 0, p2p_sn ASC');
    }

    public function typeBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->type == Status::CRYPTO_CURRENCY) {
                $html = '<span class="badge badge--primary">' . trans('Crypto') . '</span>';
            } else {
                $html = '<span class="badge badge--success">' . trans('Fiat') . '</span>';
            }
            return $html;
        });
    }
}
