<?php

namespace Romeldev\WhatsApp\Facades;

use Illuminate\Support\Facades\Facade;
use Romeldev\WhatsApp\Testing\WhatsAppFake;
use Romeldev\WhatsApp\WhatsAppManager;

/**
 * @method static \Romeldev\WhatsApp\Contracts\WhatsAppTransport driver(?string $driver = null)
 * @method static void send(\Romeldev\WhatsApp\Messages\WhatsAppMessage $message)
 * @method static \Romeldev\WhatsApp\WhatsAppManager extend(string $driver, \Closure $callback)
 *
 * @see WhatsAppManager
 */
class WhatsApp extends Facade
{
    /**
     * Replace the bound manager with a fake for testing.
     */
    public static function fake(): WhatsAppFake
    {
        $app = static::getFacadeApplication();

        $fake = new WhatsAppFake($app);

        $app->instance('whatsapp', $fake);

        static::swap($fake);

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'whatsapp';
    }
}
