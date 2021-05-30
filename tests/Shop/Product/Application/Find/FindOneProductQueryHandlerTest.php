<?php

declare(strict_types=1);

namespace Test\Shop\Product\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Product\Application\Find\FindOneProduct;
use Shop\Product\Application\Find\FindOneProductQuery;
use Shop\Product\Application\Find\FindOneProductQueryHandler;
use Shop\Product\Application\Find\ProductResponse;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductDoesNotExist;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;

class FindOneProductQueryHandlerTest extends TestCase
{
    private FindOneProductQuery $query;
    private FindOneProduct $find_one_product;

    public function setUp(): void
    {
        $this->find_one_product = $this->createMock(FindOneProduct::class);
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

        $this->find_one_product
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($product);

        $handler = new FindOneProductQueryHandler($this->find_one_product);
        $response = $handler->execute($this->query);

        self::assertInstanceOf(ProductResponse::class, $response);
    }

    public function test_throw_ProductDoesNotExist_exception_on_nonexistent_product(): void
    {
        $this->expectException(ProductDoesNotExist::class);

        $product_id = new ProductId(Uuid::uuid4()->toString());

        $this->find_one_product
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ProductDoesNotExist($product_id));

        $handler = new FindOneProductQueryHandler($this->find_one_product);
        $handler->execute($this->query);
    }
}
