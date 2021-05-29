<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\DomainError;
use function sprintf;

final class CartAlreadyExists extends DomainError
{
    private CartId $cart_id;

    public function __construct(CartId $cart_id)
    {
        $this->cart_id = $cart_id;
        parent::__construct();
    }

    function errorMessage(): string
    {
        return sprintf(
            'The cart already exists: <%s>',
            $this->cart_id->value()
        );
    }
}
