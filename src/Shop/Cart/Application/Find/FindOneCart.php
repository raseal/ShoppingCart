<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartDoesNotExist;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartRepository;

class FindOneCart
{
    public function __construct(
        private CartRepository $cart_repository
    ) {}

    public function __invoke(CartId $cart_id): Cart
    {
        $cart = $this->cart_repository->findById($cart_id);

        if (null === $cart) {
            throw new CartDoesNotExist($cart_id);
        }

        return $cart;
    }
}
