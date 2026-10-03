<?php

namespace Romeldev\WhatsApp\Exceptions;

class InvalidRecipientNumber extends WhatsAppException
{
    public static function for(string $number, string $reason): self
    {
        return new self(
            sprintf('El número de destino [%s] no es válido: %s', $number, $reason)
        );
    }
}
