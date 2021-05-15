<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus\Query;

use BadMethodCallException;
use ReflectionClass;
use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryBus;
use Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonySyncQueryBus implements QueryBus
{
    private MessageBus $bus;

    public function __construct($query_handlers)
    {
        $this->bus = new MessageBus(
            [
                new HandleMessageMiddleware(new HandlersLocator($this->handlers($query_handlers))),
            ]
        );
    }

    public function ask(Query $query): ?Response
    {
        try {
            $stamp = $this->bus->dispatch($query)->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (NoHandlerForMessageException) {
            throw new BadMethodCallException('Unregistered query');
        }
    }

    private function handlers(iterable $query_handlers): array
    {
        $handlers = [];

        foreach ($query_handlers as $query_handler) {
            $reflection = new ReflectionClass($query_handler);

            if (!$reflection->hasMethod('__invoke')) {
                continue;
            }

            $method = $reflection->getMethod('__invoke');

            if ($method->getNumberOfParameters() > 1) {
                continue;
            }

            $query = $method->getParameters()[0]->getType()->getName();
            $handlers[$query] = [$query_handler];
        }

        return $handlers;
    }
}
