<?php

namespace Romeldev\WhatsApp\Channels;

use Illuminate\Notifications\Notification;
use Romeldev\WhatsApp\Exceptions\InvalidRecipientNumber;
use Romeldev\WhatsApp\Exceptions\WhatsAppException;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;
use Romeldev\WhatsApp\WhatsAppManager;

class WhatsAppChannel
{
    public function __construct(private readonly WhatsAppManager $manager) {}

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            throw new WhatsAppException(
                sprintf('La notificación [%s] no define el método toWhatsApp().', $notification::class)
            );
        }

        /** @var WhatsAppMessage $message */
        $message = $notification->toWhatsApp($notifiable);

        if (! $message->hasRecipient()) {
            $message->to($this->resolveRecipient($notifiable, $notification));
        }

        $this->manager->driver($message->driverName())->send($message);
    }

    private function resolveRecipient(object $notifiable, Notification $notification): string
    {
        $route = null;

        if (method_exists($notifiable, 'routeNotificationFor')) {
            $route = $notifiable->routeNotificationFor('whatsapp', $notification);
        }

        if ($route === null || $route === '') {
            throw InvalidRecipientNumber::for('(vacío)', 'no se pudo resolver el destinatario de la notificación.');
        }

        return (string) $route;
    }
}
