<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use Shared\Domain\Bus\Query\ResponseCollection;
use Shop\Product\Domain\ProductCollection;

final class ProductCollectionResponse extends ResponseCollection
{
    protected function type(): string
    {
        return ProductResponse::class;
    }

    public static function fromProductCollection(ProductCollection $products): self
    {
        $items = [];

        foreach ($products as $product) {
            $items[] = ProductResponse::fromProduct($product);
        }

        return new self($items);
    }
}
