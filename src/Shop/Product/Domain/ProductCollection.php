<?php

declare(strict_types=1);

namespace Shop\Product\Domain;

use Shared\Domain\Collection;

class ProductCollection extends Collection
{
    protected function type(): string
    {
        return Product::class;
    }
}
