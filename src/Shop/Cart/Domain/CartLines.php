<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\Aggregate\Collection;

final class CartLines extends Collection
{
    protected function type(): string
    {
        return CartLine::class;
    }
}
