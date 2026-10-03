<?php

namespace Romeldev\WhatsApp\Support;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Romeldev\WhatsApp\Exceptions\InvalidRecipientNumber;

final class RecipientNumber
{
    private function __construct(
        private readonly string $e164,
        private readonly string $digits,
    ) {}

    /**
     * Parse and normalize a phone number, validating it when enabled.
     */
    public static function parse(string $number, ?string $country = null): self
    {
        $number = trim($number);
        $country = $country ?? (string) config('whatsapp.country', 'PE');

        try {
            $util = PhoneNumberUtil::getInstance();
            $phoneNumber = $util->parse($number, $country === '' ? null : $country);
        } catch (NumberParseException $exception) {
            throw InvalidRecipientNumber::for($number, $exception->getMessage());
        }

        $shouldValidate = (bool) config('whatsapp.validate_recipient', true);

        if ($shouldValidate && ! $util->isValidNumber($phoneNumber)) {
            throw InvalidRecipientNumber::for($number, 'el número no pertenece a un rango válido.');
        }

        $e164 = $util->format($phoneNumber, PhoneNumberFormat::E164);

        return new self($e164, ltrim($e164, '+'));
    }

    /**
     * The number in E.164 format, including the leading plus sign.
     */
    public function e164(): string
    {
        return $this->e164;
    }

    /**
     * The number in E.164 format without the leading plus sign, which is the
     * shape most WhatsApp providers expect.
     */
    public function digits(): string
    {
        return $this->digits;
    }

    public function __toString(): string
    {
        return $this->digits;
    }
}
