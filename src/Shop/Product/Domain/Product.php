<?php

declare(strict_types=1);

namespace Shop\Product\Domain;

final class Product
{
    public function __construct(
        private ProductId $id,
        private ProductName $name,
        private Price $price,
        private OfferPrice $offer_price
    ) {}

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function offerPrice(): OfferPrice
    {
        return $this->offer_price;
    }
}
