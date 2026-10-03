<?php

use Romeldev\WhatsApp\Exceptions\InvalidRecipientNumber;
use Romeldev\WhatsApp\Support\RecipientNumber;

it('normalizes a local peruvian number', function () {
    $recipient = RecipientNumber::parse('987654321');

    expect($recipient->e164())->toBe('+51987654321')
        ->and($recipient->digits())->toBe('51987654321');
});

it('normalizes an international number', function () {
    $recipient = RecipientNumber::parse('+51 987 654 321');

    expect($recipient->digits())->toBe('51987654321');
});

it('rejects an invalid number', function () {
    RecipientNumber::parse('123');
})->throws(InvalidRecipientNumber::class);

it('skips validation when disabled', function () {
    config()->set('whatsapp.validate_recipient', false);

    $recipient = RecipientNumber::parse('+51987654321');

    expect($recipient->digits())->toBe('51987654321');
});
