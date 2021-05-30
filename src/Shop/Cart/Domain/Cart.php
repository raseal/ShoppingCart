<?php

declare(strict_types=1);

namespace Shop\Cart\Domain;

use Shared\Domain\Aggregate\AggregateRoot;

final class Cart extends AggregateRoot
{
    public function __construct(
        private CartId $id,
        private CreationDate $creation_date,
        private CartLines $cart_lines,
        private CartTotalAmount $total_amount
    ) {}

    public function id(): CartId
    {
        return $this->id;
    }

    public function creationDate(): CreationDate
    {
        return $this->creation_date;
    }

    public function cartLines(): CartLines
    {
        return $this->cart_lines;
    }

    public function totalAmount(): CartTotalAmount
    {
        return $this->total_amount;
    }
}
