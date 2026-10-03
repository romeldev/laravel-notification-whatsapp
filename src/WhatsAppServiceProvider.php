<?php

namespace Romeldev\WhatsApp;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Romeldev\WhatsApp\Channels\WhatsAppChannel;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/whatsapp.php', 'whatsapp');

        $this->app->singleton('whatsapp', fn ($app) => new WhatsAppManager($app));
        $this->app->alias('whatsapp', WhatsAppManager::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/whatsapp.php' => config_path('whatsapp.php'),
            ], 'whatsapp-config');
        }

        Notification::extend('whatsapp', function ($app): WhatsAppChannel {
            return $app->make(WhatsAppChannel::class);
        });
    }
}
