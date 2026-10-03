<?php

namespace Romeldev\WhatsApp\Tests\Fixtures;

use Illuminate\Notifications\Notification;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

class TestNotificationWithRecipient extends Notification
{
    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsApp(object $notifiable): WhatsAppMessage
    {
        return WhatsAppMessage::create()
            ->to('+51999888777')
            ->body('Con destinatario explícito');
    }
}
