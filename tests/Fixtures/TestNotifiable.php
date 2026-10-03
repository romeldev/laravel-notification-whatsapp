<?php

namespace Romeldev\WhatsApp\Tests\Fixtures;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForWhatsapp(): string
    {
        return '+51987654321';
    }
}
