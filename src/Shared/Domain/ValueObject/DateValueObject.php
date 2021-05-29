<?php

declare(strict_types=1);

namespace Shared\Domain\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;
use function sprintf;

abstract class DateValueObject extends StringValueObject
{
    public function __construct(string $date, string $format)
    {
        $this->ensureValidDate($date, $format);
        parent::__construct($date);
    }

    private function ensureValidDate(string $date, string $format): void
    {
        if (false === DateTimeImmutable::createFromFormat($format, $date)) {
           throw new InvalidArgumentException(
               sprintf(
                   'Error creating date `%s` using `%s` format',
                   $date,
                   $format
               )
           );
        }
    }
}
