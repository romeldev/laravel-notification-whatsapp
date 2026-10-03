<?php

namespace Romeldev\WhatsApp\Testing;

use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\Assert;
use Romeldev\WhatsApp\Contracts\WhatsAppTransport;
use Romeldev\WhatsApp\Messages\WhatsAppMessage;
use Romeldev\WhatsApp\WhatsAppManager;

class WhatsAppFake extends WhatsAppManager implements WhatsAppTransport
{
    /**
     * @var list<WhatsAppMessage>
     */
    protected array $sent = [];

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->sent = [];
    }

    public function driver($driver = null): WhatsAppTransport
    {
        return $this;
    }

    public function send(WhatsAppMessage $message): void
    {
        $this->sent[] = $message;
    }

    /**
     * Assert that at least one message matching the given callback was sent.
     *
     * @param  callable(WhatsAppMessage): bool  $callback
     */
    public function assertSent(callable $callback): void
    {
        Assert::assertTrue(
            $this->matching($callback) !== [],
            'No se envió ningún mensaje de WhatsApp que coincida con el criterio.'
        );
    }

    /**
     * Assert that no message matching the given callback was sent.
     *
     * @param  callable(WhatsAppMessage): bool  $callback
     */
    public function assertNotSent(callable $callback): void
    {
        Assert::assertSame(
            [],
            $this->matching($callback),
            'Se envió un mensaje de WhatsApp que debía coincidir como no enviado.'
        );
    }

    public function assertNothingSent(): void
    {
        Assert::assertSame([], $this->sent, 'Se enviaron mensajes de WhatsApp inesperados.');
    }

    /**
     * @return list<WhatsAppMessage>
     */
    public function sent(): array
    {
        return $this->sent;
    }

    /**
     * @param  callable(WhatsAppMessage): bool  $callback
     * @return list<WhatsAppMessage>
     */
    private function matching(callable $callback): array
    {
        return array_values(array_filter($this->sent, $callback));
    }
}
