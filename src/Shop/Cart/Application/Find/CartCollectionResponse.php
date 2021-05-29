<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shared\Domain\Bus\Query\ResponseCollection;
use Shop\Cart\Domain\CartCollection;

class CartCollectionResponse extends ResponseCollection
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
}
