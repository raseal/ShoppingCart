<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shared\Domain\Bus\Query\Query;

class FindOneCartQuery implements Query
{
    public function __construct(
        private string $cart_id
    ) { }

    public function cartId(): string
    {
        return $this->cart_id;
    }
}
