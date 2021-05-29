<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use DateTimeImmutable;
use Shared\Domain\ValueObject\DateValueObject;

final class CreationDate extends DateValueObject
{
    public static function now(string $format = 'Y-m-d H:i:s'): self
    {
        $now = new DateTimeImmutable();

        return new self($now->format($format), $format);
    }
}
