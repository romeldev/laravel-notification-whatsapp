<?php

use Romeldev\WhatsApp\Transports\EvolutionTransport;
use Romeldev\WhatsApp\WhatsAppManager;

it('resolves the default driver from config', function () {
    expect(app(WhatsAppManager::class)->driver())
        ->toBeInstanceOf(EvolutionTransport::class);
});

it('resolves a named driver', function () {
    expect(app(WhatsAppManager::class)->driver('evolution'))
        ->toBeInstanceOf(EvolutionTransport::class);
});

it('throws for an unknown driver', function () {
    app(WhatsAppManager::class)->driver('unknown');
})->throws(InvalidArgumentException::class);
