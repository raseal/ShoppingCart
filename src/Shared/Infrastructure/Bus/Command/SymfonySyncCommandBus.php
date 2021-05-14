<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Command;

use BadMethodCallException;
use ReflectionClass;
use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class SymfonySyncCommandBus implements CommandBus
{
    private MessageBus $bus;

    public function __construct(iterable $command_handlers)
    {
        $this->bus = new MessageBus(
            [
                new HandleMessageMiddleware(new HandlersLocator($this->handlers($command_handlers)))
            ]
        );
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (NoHandlerForMessageException) {
            throw new BadMethodCallException('Unregistered command');
        }
    }

    private function handlers(iterable $command_handlers): array
    {
        $handlers = [];

        foreach ($command_handlers as $command_handler) {
            $reflection = new ReflectionClass($command_handler);

            if (!$reflection->hasMethod('__invoke')) {
                continue;
            }

            $method = $reflection->getMethod('__invoke');

            if ($method->getNumberOfParameters() > 1) {
                continue;
            }

            $command = $method->getParameters()[0]->getType()->getName();
            $handlers[$command] = [$command_handler];
        }

        return $handlers;
    }
}
