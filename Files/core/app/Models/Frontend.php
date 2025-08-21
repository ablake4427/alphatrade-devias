<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class Frontend extends Model
{
    use ApiQuery;
    
    protected $casts = [
        'data_values' => 'object',
        'seo_content'=>'object'
    ];

    public static function scopeGetContent($data_keys)
    {
        return Frontend::where('data_keys', $data_keys);
    }
}
