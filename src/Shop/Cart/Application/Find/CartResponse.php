<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shared\Domain\Bus\Query\Response;
use Shop\Cart\Domain\Cart;

final class CartResponse implements Response
{
    private function __construct(
        private string $cart_id,
        private array $lines,
        private string $created,
        private float $total_amount
    ){}

    public static function fromCart(Cart $cart): self
    {
        return new self(
            $cart->id()->value(),
            $cart->cartLines()->items(),
            $cart->creationDate()->value(),
            $cart->totalAmount()->value()
        );
    }

    public function getCartId(): string
    {
        return $this->cart_id;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getTotalAmount(): float
    {
        return $this->total_amount;
    }
}
