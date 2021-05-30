<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use JsonSerializable;
use Shared\Domain\Bus\Query\Response;
use Shop\Cart\Domain\Cart;

final class CartResponse implements Response, JsonSerializable
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

    public function cartId(): string
    {
        return $this->cart_id;
    }

    public function lines(): array
    {
        return $this->lines;
    }

    public function created(): string
    {
        return $this->created;
    }

    public function totalAmount(): float
    {
        return $this->total_amount;
    }

    public function jsonSerialize(): array
    {
        $cart_lines = [];

        foreach($this->lines() as $line) {
            $cart_lines[] = [
                'id' => $line->id()->value(),
                'cartId' => $line->cartId()->value(),
                'productId' => $line->productId()->value(),
                'quantity' => $line->quantity()->value(),
                'totalAmount' => $line->totalAmount()->value(),
            ];
        }

        return [
            'cartId' => $this->cartId(),
            'lines' => $cart_lines,
            'created' => $this->created(),
            'totalAmount' => $this->totalAmount(),
        ];
    }
}
