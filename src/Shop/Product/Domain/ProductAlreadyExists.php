<?php

declare(strict_types=1);

namespace Shop\Product\Domain;

use Shared\Domain\DomainError;
use function sprintf;

final class ProductAlreadyExists extends DomainError
{
    private ProductId $product_id;

    public function __construct(ProductId $product_id)
    {
        $this->product_id = $product_id;
        parent::__construct();
    }

    public function errorMessage(): string
    {
        return sprintf(
            'The product already exists: <%s>',
            $this->product_id->value()
        );
    }
}
