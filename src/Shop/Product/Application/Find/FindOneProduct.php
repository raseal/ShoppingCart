<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductDoesNotExist;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductRepository;

class FindOneProduct
{
    public function __construct(
        private ProductRepository $product_repository
    ) {}

    public function __invoke(ProductId $product_id): Product
    {
        $product = $this->product_repository->findById($product_id);

        if (null === $product) {
            throw new ProductDoesNotExist($product_id);
        }

        return $product;
    }
}
