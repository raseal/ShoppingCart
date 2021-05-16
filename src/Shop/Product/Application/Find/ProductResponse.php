<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use Shared\Domain\Bus\Query\Response;
use Shop\Product\Domain\Product;

class ProductResponse implements Response
{
    private function __construct(
        private string $id,
        private string $name,
        private float $price,
        private float $offer_price
    ) {}

    public static function fromProduct(Product $product): self
    {
        return new self(
            $product->id()->value(),
            $product->name()->value(),
            $product->price()->value(),
            $product->offerPrice()->value()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getOfferPrice(): float
    {
        return $this->offer_price;
    }
}
