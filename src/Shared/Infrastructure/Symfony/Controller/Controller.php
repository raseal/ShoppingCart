<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Controller;

use Ramsey\Uuid\Uuid;
use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;

abstract class Controller extends AbstractController
{
    public function __construct(
        protected CommandBus $command_bus
    ) {}

    protected function dispatch(Command $command): void
    {
        $this->command_bus->dispatch($command);
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
