<?php

declare(strict_types=1);

namespace Test\Shop\Product\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Product\Application\Find\FindOneProductQuery;
use Shop\Product\Application\Find\FindOneProductQueryHandler;
use Shop\Product\Application\Find\ProductResponse;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductDoesNotExist;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use Shop\Product\Domain\ProductRepository;

class FindOneProductQueryHandlerTest extends TestCase
{
    private ProductRepository $product_repository;
    private FindOneProductQuery $query;

    public function setUp(): void
    {
        $this->product_repository = $this->createMock(ProductRepository::class);
        $this->query = new FindOneProductQuery(Uuid::uuid4()->toString());
    }

    public function test_should_find_one_product(): void
    {
        $product = new Product(
            new ProductId(Uuid::uuid4()->toString()),
            new ProductName('any_name'),
            new Price(12),
            new OfferPrice(10)
        );

        $this->product_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn($product);

        $handler = new FindOneProductQueryHandler($this->product_repository);
        $response = $handler->execute($this->query);

        self::assertInstanceOf(ProductResponse::class, $response);
    }

    public function test_throw_ProductDoesNotExist_exception_on_nonexistent_product(): void
    {
        $this->expectException(ProductDoesNotExist::class);

        $this->product_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn(null);

        $handler = new FindOneProductQueryHandler($this->product_repository);
        $handler->execute($this->query);
    }
}
