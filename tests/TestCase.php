<?php

namespace Romeldev\WhatsApp\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Romeldev\WhatsApp\WhatsAppServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WhatsAppServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('whatsapp.default', 'evolution');
        $app['config']->set('whatsapp.country', 'PE');
        $app['config']->set('whatsapp.validate_recipient', true);
        $app['config']->set('whatsapp.drivers.evolution', [
            'base_url' => 'https://evolution.test',
            'api_key' => 'secret',
            'instance' => 'clinic',
        ]);
    }
}
