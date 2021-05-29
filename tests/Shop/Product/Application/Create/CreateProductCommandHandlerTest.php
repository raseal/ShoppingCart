<?php

declare(strict_types=1);

namespace Test\Shop\Product\Application\Create;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Product\Application\Create\CreateProductCommand;
use Shop\Product\Application\Create\CreateProductCommandHandler;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductAlreadyExists;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use Shop\Product\Domain\ProductRepository;

class CreateProductCommandHandlerTest extends TestCase
{
    private ProductRepository $product_repository;
    private CreateProductCommand $command;

    public function setUp(): void
    {
        $this->product_repository = $this->createMock(ProductRepository::class);
        $this->command = new CreateProductCommand(Uuid::uuid4()->toString(), 'name', 12, 10);
    }

    public function test_should_create_product(): void
    {
        $this->product_repository
            ->expects(self::once())
            ->method('save');

        $handler = new CreateProductCommandHandler($this->product_repository);
        $handler->execute($this->command);
    }

    public function test_throw_ProductAlreadyExists_exception_on_duplicate_insertion(): void
    {
        $this->expectException(ProductAlreadyExists::class);

        $product = new Product(
            new ProductId(Uuid::uuid4()->toString()),
            new ProductName('name'),
            new Price(12),
            new OfferPrice(10)
        );

        $this->product_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn($product);

        $handler = new CreateProductCommandHandler($this->product_repository);
        $handler->execute($this->command);
    }
}
