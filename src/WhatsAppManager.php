<?php

namespace Romeldev\WhatsApp;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Manager;
use Romeldev\WhatsApp\Contracts\WhatsAppTransport;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;
use Romeldev\WhatsApp\Transports\EvolutionTransport;

class WhatsAppManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return (string) $this->config()->get('whatsapp.default', 'evolution');
    }

    /**
     * Send the message through its driver, or the default one.
     */
    public function send(WhatsAppMessage $message): void
    {
        $this->driver($message->driverName())->send($message);
    }

    public function driver($driver = null): WhatsAppTransport
    {
        /** @var WhatsAppTransport $transport */
        $transport = parent::driver($driver);

        return $transport;
    }

    protected function createEvolutionDriver(): WhatsAppTransport
    {
        /** @var array<string, mixed> $config */
        $config = $this->config()->get('whatsapp.drivers.evolution', []);

        return EvolutionTransport::fromConfig($config);
    }

    private function config(): ConfigRepository
    {
        /** @var ConfigRepository $config */
        $config = $this->container->make('config');

        return $config;
    }
}
