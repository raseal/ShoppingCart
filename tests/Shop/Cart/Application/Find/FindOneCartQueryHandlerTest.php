<?php

declare(strict_types=1);

namespace Test\Shop\Cart\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Cart\Application\Find\CartResponse;
use Shop\Cart\Application\Find\FindOneCart;
use Shop\Cart\Application\Find\FindOneCartQuery;
use Shop\Cart\Application\Find\FindOneCartQueryHandler;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartDoesNotExist;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

class FindOneCartQueryHandlerTest extends TestCase
{
    private FindOneCart $find_one_cart;
    private FindOneCartQuery $query;

    public function setUp(): void
    {
        $this->find_one_cart = $this->createMock(FindOneCart::class);
        $this->query = new FindOneCartQuery(Uuid::uuid4()->toString());
    }

    public function test_should_find_one_cart(): void
    {
        $cart = new Cart(
            new CartId(Uuid::uuid4()->toString()),
            CreationDate::now(),
            new CartLines([]),
            new CartTotalAmount(0)
        );

        $this->find_one_cart
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($cart);

        $handler = new FindOneCartQueryHandler($this->find_one_cart);
        $response = $handler->execute($this->query);

        self::assertInstanceOf(CartResponse::class, $response);
    }

    public function test_throw_CartDoesNotExist_on_nonexistent_cart(): void
    {
        $this->expectException(CartDoesNotExist::class);

        $cart_id = new CartId(Uuid::uuid4()->toString());

        $this->find_one_cart
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new CartDoesNotExist($cart_id));

        $handler = new FindOneCartQueryHandler($this->find_one_cart);
        $handler->execute($this->query);
    }
}
