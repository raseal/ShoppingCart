<?php

declare(strict_types=1);

namespace Test\Shop\Product\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Product\Application\Find\FindAllProductsQuery;
use Shop\Product\Application\Find\FindAllProductsQueryHandler;
use Shop\Product\Application\Find\ProductCollectionResponse;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductCollection;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use Shop\Product\Domain\ProductRepository;

class FindAllProductsQueryHandlerTest extends TestCase
{
    private ProductRepository $product_repository;

    public function setUp(): void
    {
        $this->product_repository = $this->createMock(ProductRepository::class);
    }

    public function test_should_find_all_products(): void
    {
        $query = new FindAllProductsQuery();
        $valid_id = Uuid::uuid4()->toString();
        $product = new Product(
            new ProductId($valid_id),
            new ProductName('any_name'),
            new Price(12),
            new OfferPrice(10)
        );
        $product_collection = new ProductCollection([$product]);

        $this->product_repository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn($product_collection);

        $handler = new FindAllProductsQueryHandler($this->product_repository);
        $response = $handler->execute($query);

        self::assertInstanceOf(ProductCollectionResponse::class, $response);
    }
}
