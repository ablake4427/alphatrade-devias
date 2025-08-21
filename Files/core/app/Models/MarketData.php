<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketData extends Model
{
    protected $casts = [
        'html_classes' => 'object'
    ];
}
