<?php

namespace App\Lib;

class RequiredConfig
{

    public function getConfig()
    {
        return [
            'general_setting'        => [
                'title' => 'Configure basic setting of your site like Site Name, Currency, Timezone etc',
                'route' => route('admin.setting.general'),
            ],
            'logo_favicon'           => [
                'title' => 'Update the logo and favicon',
                'route' => route('admin.setting.logo.icon'),
            ],
            'notification_template'  => [
                'title' => 'Update the global notification template',
                'route' => route('admin.setting.notification.global.email'),
            ],
            'deposit_method'         => [
                'title' => 'Setup at-least one payment method',
                'route' => route('admin.gateway.automatic.index'),
            ],
            'withdrawal_method'      => [
                'title' => 'Setup at-least one withdrawal method',
                'route' => route('admin.withdraw.method.create'),
            ],
            'seo'                    => [
                'title' => 'Update the seo configuration',
                'route' => route('admin.seo'),
            ],
            'policy_content'         => [
                'title' => 'Update the site policy content',
                'route' => route('admin.frontend.sections', 'policy_pages'),
            ],
            'pusher_config'          => [
                'title' => 'Pusher configuration',
                'route' => route('admin.setting.pusher.configuration'),
            ],
            'chart_setting'          => [
                'title' => 'Configure chart setting',
                'route' => route('admin.setting.chart'),
            ],
            'charge_setting'         => [
                'title' => 'Configure charge setting',
                'route' => route('admin.setting.charge'),
            ],
            'wallet_setting'         => [
                'title' => 'Configure wallet setting',
                'route' => route('admin.wallet.setting'),
            ],
            'currency_data_provider' => [
                'title' => 'Configure currency data provider',
                'route' => route('admin.currency.data.provider.index'),
            ],
            'add_currency'           => [
                'title' => 'Ad at-least one currency',
                'route' => route('admin.currency.crypto'),
            ],
            'add_market'             => [
                'title' => 'Ad at-least one coin market',
                'route' => route('admin.market.list'),
            ],
            'add_coin_pair'          => [
                'title' => 'Ad at-least one coin pair',
                'route' => route('admin.coin.pair.list'),
            ],
        ];
    }

    public function totalConfigs()
    {
        return count($this->getConfig());
    }

    public function completedConfig()
    {
        return gs('config_progress') ?? [];
    }

    public function completedConfigCount()
    {
        return count($this->completedConfig() ?? []);
    }

    public function completedConfigPercent()
    {
        return ($this->completedConfigCount() / $this->totalConfigs()) * 100;
    }

    public static function configured($key)
    {
        $completedConfig = gs('config_progress') ?? [];
        if (!in_array($key, $completedConfig)) {
            $general                  = gs();
            $general->config_progress = array_merge($completedConfig, [$key]);
            $general->save();
        }
    }
}
