<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductId;

final class CartLine
{
    private const MAX_ITEMS_PER_LINE = 50;
    private CartLineId $id;
    private CartId $cart_id;
    private ProductId $product_id;
    private Units $quantity;
    private CartLineTotalAmount $total_amount;

    public function __construct(
        CartLineId $id,
        CartId $cart_id,
        ProductId $product_id,
        Units $quantity,
        ?Price $unit_price = null,
        ?CartLineTotalAmount $total_amount = null
    ) {
        $this->ensureLineIsNotFull($quantity);
        $this->id = $id;
        $this->cart_id = $cart_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->total_amount = $total_amount ?? $this->calculateTotalLine($unit_price);
    }

    public static function create(Product $product, Units $units, CartId $cart_id): self
    {
        return new self(
            CartLineId::random(),
            $cart_id,
            $product->id(),
            $units,
            $product->price()
        );
    }

    public function incrementQuantity(Units $units, Price $unit_price): void
    {
        $new_units = $this->quantity->add($units);
        $this->ensureLineIsNotFull($new_units);
        $this->quantity = $new_units;

        $this->total_amount = $this->calculateTotalLine($unit_price);
    }

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

    public function quantity(): Units
    {
        return $this->quantity;
    }

    public function totalAmount(): CartLineTotalAmount
    {
        return $this->total_amount;
    }

    private function ensureLineIsNotFull(Units $units): void
    {
        if ($units->value() > self::MAX_ITEMS_PER_LINE) {
            throw new CartLineIsFull();
        }
    }

    private function calculateTotalLine(Price $unit_price): CartLineTotalAmount
    {
        return new CartLineTotalAmount($unit_price->value() * $this->quantity()->value());
    }
}
