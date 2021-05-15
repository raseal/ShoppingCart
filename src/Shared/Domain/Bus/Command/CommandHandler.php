<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Command;

interface CommandHandler
{
    public function execute(Command $command): void;
}
