<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use JsonSerializable;
use Shared\Domain\Bus\Query\Response;
use Shop\Product\Domain\Product;

final class ProductResponse implements Response, JsonSerializable
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

    public function offerPrice(): float
    {
        return $this->offer_price;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'price' => $this->price(),
            'offerPrice' => $this->offerPrice(),
        ];
    }
}
