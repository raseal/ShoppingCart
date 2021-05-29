<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\Collection;

class CartCollection extends Collection
{
    protected function type(): string
    {
        return Cart::class;
    }
}
