<?php

namespace App\Models\P2P;

use App\Models\Form;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use GlobalStatus;

    protected $casts = [
        'supported_currency' => 'array',
    ];

    protected $table = "p2p_payment_methods";

    public function userData()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function userPaymentMethod()
    {
        return $this->hasOne(UserPaymentMethod::class);
    }


}
