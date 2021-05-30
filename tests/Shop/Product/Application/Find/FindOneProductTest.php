<?php

declare(strict_types=1);

namespace Test\Shop\Product\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Product\Application\Find\FindOneProduct;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductDoesNotExist;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use Shop\Product\Domain\ProductRepository;

class FindOneProductTest extends TestCase
{
    private ProductRepository $product_repository;
    private ProductId $product_id;

    public function setUp(): void
    {
        $this->product_repository = $this->createMock(ProductRepository::class);
        $this->product_id = new ProductId(Uuid::uuid4()->toString());
    }

    public function test_should_find_one_product(): void
    {
        $product = new Product(
            $this->product_id,
            new ProductName('any_name'),
            new Price(12),
            new OfferPrice(10)
        );

        $this->product_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn($product);

        $service = new FindOneProduct($this->product_repository);
        $result = $service->__invoke($this->product_id);

        self::assertInstanceOf(Product::class, $result);
    }

    public function test_throw_ProductDoesNotExist_exception_on_nonexistent_product(): void
    {
        $this->expectException(ProductDoesNotExist::class);

        $this->product_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn(null);

        $service = new FindOneProduct($this->product_repository);
        $service->__invoke($this->product_id);
    }
}
