<?php

declare(strict_types=1);

namespace Shop\Product\Domain;

interface ProductRepository
{
    public function save(Product $product): void;
}
