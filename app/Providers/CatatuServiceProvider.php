<?php

namespace App\Providers;

use App\Services\Mpesa\MpesaClient;
use App\Services\Mpesa\MpesaConfig;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CatatuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MpesaConfig::class, function (Application $app) {
            $cfg = $app['config']->get('catatu.mpesa', []);

            return new MpesaConfig(
                consumerKey: $cfg['consumer_key'] ?? null,
                consumerSecret: $cfg['consumer_secret'] ?? null,
                shortcode: $cfg['shortcode'] ?? null,
                passkey: $cfg['passkey'] ?? null,
                environment: $cfg['environment'] ?? 'sandbox',
                callbackUrl: $cfg['callback_url'] ?? '',
                stub: (bool) ($cfg['stub'] ?? true),
            );
        });

        $this->app->singleton(MpesaClient::class);
    }
}
