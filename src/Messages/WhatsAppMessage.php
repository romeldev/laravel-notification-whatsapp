<?php

namespace Romeldev\WhatsApp\Messages;

use Romeldev\WhatsApp\Exceptions\InvalidRecipientNumber;
use Romeldev\WhatsApp\Support\RecipientNumber;

final class WhatsAppMessage
{
    private ?RecipientNumber $recipient = null;

    private string $body = '';

    private ?string $driver = null;

    private function __construct() {}

    public static function create(): self
    {
        return new self;
    }

    /**
     * Set the destination number. When the number has no international
     * prefix, the given country (or the configured default) is used.
     */
    public function to(string $number, ?string $country = null): self
    {
        $this->recipient = RecipientNumber::parse($number, $country);

        return $this;
    }

    public function body(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Override the driver used for this specific message.
     */
    public function driver(?string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function hasRecipient(): bool
    {
        return $this->recipient !== null;
    }

    public function recipient(): RecipientNumber
    {
        if ($this->recipient === null) {
            throw InvalidRecipientNumber::for('(vacío)', 'no se definió un destinatario.');
        }

        return $this->recipient;
    }

    public function bodyText(): string
    {
        return $this->body;
    }

    public function driverName(): ?string
    {
        return $this->driver;
    }
}
