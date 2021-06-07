<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\Aggregate\Collection;
use Shop\Product\Domain\ProductId;

final class CartLines extends Collection
{
    protected function type(): string
    {
        return CartLine::class;
    }

    public function findLineByProductId(ProductId $product_id): ?CartLine
    {
        foreach ($this->items() as $item) {
            if ($item->productId()->equals($product_id)) {
                return $item;
            }
        }

        return null;
    }
}
