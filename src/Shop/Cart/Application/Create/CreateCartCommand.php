<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Create;

use Shared\Domain\Bus\Command\Command;

final class CreateCartCommand implements Command
{
    public function __construct(
        private string $id
    ) {}

    public function id(): string
    {
        return $this->id;
    }
}
