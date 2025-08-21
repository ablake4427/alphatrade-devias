<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use GlobalStatus;

    protected $casts = [
        'mail_config'           => 'object',
        'sms_config'            => 'object',
        'global_shortcodes'     => 'object',
        'socialite_credentials' => 'object',
        'firebase_config'       => 'object',
        'wallet_types'          => 'object',
        'pusher_config'         => 'object',
        'config_progress'       => 'object',
    ];

    protected $hidden = ['email_template', 'mail_config', 'sms_config', 'system_info', 'trading_view_widget'];

    public function scopeSiteName($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('GeneralSetting');
        });
    }
}
