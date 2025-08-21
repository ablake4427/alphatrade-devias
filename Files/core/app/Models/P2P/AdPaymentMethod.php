<?php

namespace App\Models\P2P;

use Illuminate\Database\Eloquent\Model;

class AdPaymentMethod extends Model
{
    protected $table = "p2p_ad_payment_methods";

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
