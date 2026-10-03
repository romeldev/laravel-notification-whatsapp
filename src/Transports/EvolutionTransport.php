<?php

namespace Romeldev\WhatsApp\Transports;

use Illuminate\Support\Facades\Http;
use Romeldev\WhatsApp\Contracts\WhatsAppTransport;
use Romeldev\WhatsApp\Exceptions\DriverNotConfigured;
use Romeldev\WhatsApp\Exceptions\WhatsAppException;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;

final class EvolutionTransport implements WhatsAppTransport
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey,
        private readonly string $instance,
    ) {}

    /**
     * @param  array<string, mixed>  $config
     */
    public static function fromConfig(array $config): self
    {
        $baseUrl = rtrim((string) ($config['base_url'] ?? ''), '/');
        $apiKey = (string) ($config['api_key'] ?? '');
        $instance = (string) ($config['instance'] ?? '');

        if ($baseUrl === '' || $apiKey === '' || $instance === '') {
            throw DriverNotConfigured::named('evolution');
        }

        return new self($baseUrl, $apiKey, $instance);
    }

    public function send(WhatsAppMessage $message): void
    {
        $response = Http::baseUrl($this->baseUrl)
            ->withHeaders(['apikey' => $this->apiKey])
            ->acceptJson()
            ->post("/message/sendText/{$this->instance}", [
                'number' => $message->recipient()->digits(),
                'text' => $message->bodyText(),
            ]);

        if ($response->failed()) {
            throw WhatsAppException::fromResponse('Evolution API', $response->status(), $response->body());
        }
    }
}
