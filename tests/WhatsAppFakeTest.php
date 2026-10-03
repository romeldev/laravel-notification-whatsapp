<?php

use PHPUnit\Framework\AssertionFailedError;
use Romeldev\WhatsApp\Facades\WhatsApp;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

it('records messages sent through the facade', function () {
    $fake = WhatsApp::fake();

    WhatsApp::send(WhatsAppMessage::create()->to('+51987654321')->body('Hola'));

    $fake->assertSent(fn (WhatsAppMessage $message) => $message->bodyText() === 'Hola');
    expect($fake->sent())->toHaveCount(1);
});

it('asserts that nothing was sent', function () {
    WhatsApp::fake()->assertNothingSent();
});

it('fails when asserting nothing but a message was sent', function () {
    $fake = WhatsApp::fake();

    WhatsApp::send(WhatsAppMessage::create()->to('+51987654321')->body('Hola'));

    $fake->assertNothingSent();
})->throws(AssertionFailedError::class);

it('supports assertNotSent with a callback', function () {
    $fake = WhatsApp::fake();

    WhatsApp::send(WhatsAppMessage::create()->to('+51987654321')->body('Hola'));

    $fake->assertNotSent(fn (WhatsAppMessage $message) => $message->bodyText() === 'Adiós');
});
