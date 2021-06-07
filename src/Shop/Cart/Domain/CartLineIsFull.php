<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\DomainError;

class CartLineIsFull extends DomainError
{
    public function __construct()
    {
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return 'You can not add more units for this product';
    }
}
