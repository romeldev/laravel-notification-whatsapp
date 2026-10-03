<?php

use Illuminate\Support\Facades\Http;
use Romeldev\WhatsApp\Exceptions\DriverNotConfigured;
use Romeldev\WhatsApp\Exceptions\WhatsAppException;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;
use Romeldev\WhatsApp\Transports\EvolutionTransport;

it('sends the message to the evolution endpoint', function () {
    Http::fake(['evolution.test/*' => Http::response(['ok' => true], 201)]);

    $transport = EvolutionTransport::fromConfig(config('whatsapp.drivers.evolution'));

    $transport->send(
        WhatsAppMessage::create()->to('+51 987 654 321')->body('Hola')
    );

    Http::assertSent(function ($request) {
        return $request->url() === 'https://evolution.test/message/sendText/clinic'
            && $request->hasHeader('apikey', 'secret')
            && $request['number'] === '51987654321'
            && $request['text'] === 'Hola';
    });
});

it('throws a whatsapp exception when the provider fails', function () {
    Http::fake(['evolution.test/*' => Http::response(['error' => 'boom'], 500)]);

    $transport = EvolutionTransport::fromConfig(config('whatsapp.drivers.evolution'));

    $transport->send(WhatsAppMessage::create()->to('+51987654321')->body('Hola'));
})->throws(WhatsAppException::class);

it('throws when required configuration is missing', function () {
    EvolutionTransport::fromConfig(['base_url' => '', 'api_key' => '', 'instance' => '']);
})->throws(DriverNotConfigured::class);
