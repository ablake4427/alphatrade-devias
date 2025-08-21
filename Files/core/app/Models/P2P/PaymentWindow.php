<?php

namespace App\Models\P2P;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class PaymentWindow extends Model
{
    use GlobalStatus;

    protected $table = "p2p_payment_windows";
}
