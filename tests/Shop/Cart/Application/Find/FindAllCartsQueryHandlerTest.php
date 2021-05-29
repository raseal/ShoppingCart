<?php

declare(strict_types=1);

namespace Test\Shop\Cart\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Cart\Application\Find\CartCollectionResponse;
use Shop\Cart\Application\Find\FindAllCartsQuery;
use Shop\Cart\Application\Find\FindAllCartsQueryHandler;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartCollection;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

class FindAllCartsQueryHandlerTest extends TestCase
{
    private CartRepository $cart_repository;

    public function setUp(): void
    {
        $this->cart_repository = $this->createMock(CartRepository::class);
    }

    public function test_it_should_find_all_carts(): void
    {
        $query = new FindAllCartsQuery();
        $cart = new Cart(
            new CartId(Uuid::uuid4()->toString()),
            CreationDate::now(),
            new CartLines([]),
            new CartTotalAmount(0)
        );
        $cart_collection = new CartCollection([$cart]);

        $this->cart_repository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn($cart_collection);

        $handler = new FindAllCartsQueryHandler($this->cart_repository);
        $response = $handler->execute($query);

        self::assertInstanceOf(CartCollectionResponse::class, $response);
    }
}
