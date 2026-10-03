<?php

use Romeldev\WhatsApp\Exceptions\InvalidRecipientNumber;
use Romeldev\WhatsApp\Facades\WhatsApp;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;
use Romeldev\WhatsApp\Tests\Fixtures\TestNotifiable;
use Romeldev\WhatsApp\Tests\Fixtures\TestNotifiableWithoutRoute;
use Romeldev\WhatsApp\Tests\Fixtures\TestNotification;
use Romeldev\WhatsApp\Tests\Fixtures\TestNotificationWithRecipient;

it('sends through the notification channel using the notifiable route', function () {
    $fake = WhatsApp::fake();

    (new TestNotifiable)->notify(new TestNotification);

    $fake->assertSent(fn (WhatsAppMessage $message) => $message->bodyText() === 'Hola desde la notificación'
        && $message->recipient()->digits() === '51987654321');
});

it('prefers the recipient set on the message', function () {
    $fake = WhatsApp::fake();

    (new TestNotifiable)->notify(new TestNotificationWithRecipient);

    $fake->assertSent(fn (WhatsAppMessage $message) => $message->recipient()->digits() === '51999888777');
});

it('throws when the recipient cannot be resolved', function () {
    WhatsApp::fake();

    (new TestNotifiableWithoutRoute)->notify(new TestNotification);
})->throws(InvalidRecipientNumber::class);
