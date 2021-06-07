<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Ramsey\Uuid\Uuid;
use Shared\Domain\ValueObject\UuidValueObject;

final class CartLineId extends UuidValueObject
{
    public static function random(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
