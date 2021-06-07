<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\DomainError;
use function sprintf;

class CartIsFull extends DomainError
{
    private CartId $cart_id;

    public function __construct(CartId $cart_id)
    {
        $this->cart_id = $cart_id;
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return sprintf(
            'The cart <%s> does not accept more items',
            $this->cart_id->value()
        );
    }
}
