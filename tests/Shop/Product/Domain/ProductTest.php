<?php

declare(strict_types=1);

namespace Test\Shop\Product\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;

class ProductTest extends TestCase
{
    public function test_should_create_a_product(): void
    {
        $id = Uuid::uuid4()->toString();
        $product = new Product(
            new ProductId($id),
            new ProductName('name'),
            new Price(12),
            new OfferPrice(10)
        );

        self::assertEquals($id, $product->id()->value());
        self::assertEquals('name', $product->name()->value());
        self::assertEquals(12, $product->price()->value());
        self::assertEquals(10, $product->offerPrice()->value());
    }

    public function test_throw_exception_on_invalid_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Product(
            new ProductId('invalid-id'),
            new ProductName('name'),
            new Price(12),
            new OfferPrice(10)
        );
    }
}
