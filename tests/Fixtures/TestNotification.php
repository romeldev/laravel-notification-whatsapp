<?php

namespace Romeldev\WhatsApp\Tests\Fixtures;

use Illuminate\Notifications\Notification;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

class TestNotification extends Notification
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
        return WhatsAppMessage::create()->body('Hola desde la notificación');
    }
}
