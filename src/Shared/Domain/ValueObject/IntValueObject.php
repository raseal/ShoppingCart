<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class IntValueObject
{
    public function __construct(
        private int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $value): bool
    {
        return $value->value() === $this->value();
    }
}
