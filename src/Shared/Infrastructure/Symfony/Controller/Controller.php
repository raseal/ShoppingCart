<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandBus;
use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryBus;
use Shared\Domain\Bus\Query\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;

abstract class Controller extends AbstractController
{
    public function __construct(
        protected CommandBus $command_bus,
        protected QueryBus $query_bus
    ) {}

    protected function dispatch(Command $command): void
    {
        $this->command_bus->dispatch($command);
    }

    protected function ask(Query $query): Response
    {
        return $this->query_bus->ask($query);
    }

    protected function getPayloadAsArray(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    protected function createRandomUuidAsString(): string
    {
        return Uuid::uuid4()->toString();
    }
}
