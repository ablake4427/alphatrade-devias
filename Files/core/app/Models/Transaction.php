<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    use  ApiQuery;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function amountWithoutCharge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->amount - $this->charge
        );
    }

}
