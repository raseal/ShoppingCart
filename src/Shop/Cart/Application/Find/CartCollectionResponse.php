<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use JsonSerializable;
use Shared\Domain\Bus\Query\ResponseCollection;
use Shop\Cart\Domain\CartCollection;

class CartCollectionResponse extends ResponseCollection implements JsonSerializable
{
    protected function type(): string
    {
        return CartResponse::class;
    }

    public static function fromCartCollection(CartCollection $carts): self
    {
        $items = [];

        foreach($carts as $cart) {
            $items[] = CartResponse::fromCart($cart);
        }

        return new self($items);
    }

    public function jsonSerialize(): array
    {
        return $this->items();
    }
}
