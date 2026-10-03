<?php

use Romeldev\WhatsApp\WhatsAppServiceProvider;

beforeEach(function () {
    if (is_file(config_path('whatsapp.php'))) {
        unlink(config_path('whatsapp.php'));
    }
});

afterEach(function () {
    if (is_file(config_path('whatsapp.php'))) {
        unlink(config_path('whatsapp.php'));
    }
});

it('resolves the config defaults without publishing', function () {
    expect(config('whatsapp.default'))->toBe('evolution')
        ->and(config('whatsapp.country'))->toBe('PE')
        ->and(config('whatsapp.validate_recipient'))->toBeTrue()
        ->and(config('whatsapp.drivers.evolution'))->toHaveKey('base_url');
});

it('publishes the config file with the whatsapp-config tag', function () {
    $target = config_path('whatsapp.php');

    expect(is_file($target))->toBeFalse();

    $this->artisan('vendor:publish', [
        '--tag' => 'whatsapp-config',
        '--force' => true,
    ])->assertSuccessful();

    expect(is_file($target))->toBeTrue();

    $config = require $target;

    expect($config)->toHaveKeys(['default', 'country', 'validate_recipient', 'drivers'])
        ->and($config['drivers'])->toHaveKey('evolution');
});

it('publishes the config through the service provider', function () {
    $this->artisan('vendor:publish', [
        '--provider' => WhatsAppServiceProvider::class,
        '--force' => true,
    ])->assertSuccessful();

    expect(is_file(config_path('whatsapp.php')))->toBeTrue();
});
