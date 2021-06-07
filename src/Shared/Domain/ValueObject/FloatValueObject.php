<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    public function __construct(
        private float $value
    ) {}

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $value): bool
    {
        return $value->value() === $this->value();
    }
    public function add(self $value): self
    {
        return new static($value->value() + $this->value());
    }
}
