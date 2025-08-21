<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CurrencyDataProvider extends Model
{
    use GlobalStatus;

    protected $guard = ['id'];

    protected $casts = [
        'configuration' => 'object',
    ];

    public function defaultStatusBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->is_default == Status::YES) {
                return '<span class="badge badge--success">' . trans('Yes') . '</span>';
            }else{
                return '<span class="badge badge--dark">' . trans('No') . '</span>';
            }
        });
    }
}
