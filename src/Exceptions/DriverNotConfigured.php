<?php

namespace Romeldev\WhatsApp\Exceptions;

class DriverNotConfigured extends WhatsAppException
{
    public static function named(string $driver): self
    {
        return new self(
            sprintf('El driver de WhatsApp [%s] no está configurado correctamente.', $driver)
        );
    }
}
