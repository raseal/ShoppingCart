<?php

declare(strict_types=1);

namespace Shop\Product\Domain;

use Shared\Domain\DomainError;
use function sprintf;

final class ProductDoesNotExist extends DomainError
{
    private ProductId $product_id;

    public function __construct(ProductId $product_id)
    {
        $this->product_id = $product_id;
        parent::__construct();
    }

    function errorMessage(): string
    {
        return sprintf(
            'The product <%s> does not exist.',
            $this->product_id->value()
        );
    }
}
