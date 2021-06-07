<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\Aggregate\AggregateRoot;
use Shop\Product\Domain\Product;

final class Cart extends AggregateRoot
{
    private const MAX_ALLOWED_LINES = 10;

    public function __construct(
        private CartId $id,
        private CreationDate $creation_date,
        private CartLines $cart_lines,
        private CartTotalAmount $total_amount
    ) {}

    public function addProduct(Product $product, Units $units): void
    {
        $this->ensureCartIsNotFull();
        $line = $this->cartLines()->findLineByProductId($product->id());

        if (null === $line) {
            $line = CartLine::create($product, $units, $this->id());
            $this->cart_lines->add($line);
        } else {
            $line->incrementQuantity($units, $product->price());
        }

        $this->calculateTotalCart();
    }

    public function id(): CartId
    {
        return $this->id;
    }

    public function creationDate(): CreationDate
    {
        return $this->creation_date;
    }

    public function cartLines(): CartLines
    {
        return $this->cart_lines;
    }

    public function totalAmount(): CartTotalAmount
    {
        return $this->total_amount;
    }

    private function ensureCartIsNotFull(): void
    {
        if ($this->cartLines()->count() >= self::MAX_ALLOWED_LINES) {
            throw new CartIsFull($this->id());
        }
    }

    private function calculateTotalCart(): void
    {
        $amount = new CartTotalAmount(0);

        foreach ($this->cartLines() as $line) {
            $amount = $amount->add($line->totalAmount());
        }

        $this->total_amount = $amount;
    }
}
