<?php

namespace Romeldev\WhatsApp\Exceptions;

use RuntimeException;

class WhatsAppException extends RuntimeException
{
    public static function fromResponse(string $provider, int $status, string $body): self
    {
        return new self(
            sprintf('El proveedor %s respondió con el estado %d: %s', $provider, $status, $body)
        );
    }
}
