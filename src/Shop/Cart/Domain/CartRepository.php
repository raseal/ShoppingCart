<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

interface CartRepository
{
    public function save(Cart $cart): void;

    public function findById(CartId $cart_id): ?Cart;

    public function findAll(): CartCollection;
}
