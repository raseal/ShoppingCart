<?php

declare(strict_types=1);

namespace Shop\Product\Application\Create;

use Shared\Domain\Bus\Command\Command;

final class CreateProductCommand implements Command
{
    public function __construct(
        private string $id,
        private string $name,
        private float $price,
        private ?float $offer_price
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function offerPrice(): ?float
    {
        return $this->offer_price;
    }
}
