<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shop\Product\Domain\ProductId;

final class CartLine
{
    public function __construct(
        private CartLineId $id,
        private CartId $cart_id,
        private ProductId $product_id,
        private CartLineQuantity $quantity,
        private CartLineTotalAmount $total_amount
    ) {}

    public function id(): CartLineId
    {
        return $this->id;
    }

    public function cartId(): CartId
    {
        return $this->cart_id;
    }

    public function productId(): ProductId
    {
        return $this->product_id;
    }

    public function quantity(): CartLineQuantity
    {
        return $this->quantity;
    }

    public function totalAmount(): CartLineTotalAmount
    {
        return $this->total_amount;
    }
}
