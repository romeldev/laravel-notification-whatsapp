<?php

namespace Romeldev\WhatsApp\Contracts;

use Romeldev\WhatsApp\Messages\WhatsAppMessage;

interface WhatsAppTransport
{
    /**
     * Send the given WhatsApp message.
     */
    public function send(WhatsAppMessage $message): void;
}
