<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Controller;

use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class Controller extends AbstractController
{
    public function __construct(
        protected CommandBus $command_bus
    ) {}

    protected function dispatch(Command $command): void
    {
        $this->command_bus->dispatch($command);
    }
}
