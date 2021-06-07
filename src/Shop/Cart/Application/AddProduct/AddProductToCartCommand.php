<?php

declare(strict_types=1);

namespace Shop\Cart\Application\AddProduct;

use Shared\Domain\Bus\Command\Command;

final class AddProductToCartCommand implements Command
{
    public function __construct(
        private string $cart_id,
        private string $product_id,
        private int $quantity
    ) {}

    public function cartId(): string
    {
        return $this->cart_id;
    }

    public function productId(): string
    {
        return $this->product_id;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
