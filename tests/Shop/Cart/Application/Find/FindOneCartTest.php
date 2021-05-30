<?php

declare(strict_types=1);

namespace Test\Shop\Cart\Application\Find;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Cart\Application\Find\FindOneCart;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartDoesNotExist;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

class FindOneCartTest extends TestCase
{
    private CartRepository $cart_repository;
    private CartId $cart_id;

    public function setUp(): void
    {
        $this->cart_repository = $this->createMock(CartRepository::class);
        $this->cart_id = new CartId(Uuid::uuid4()->toString());
    }

    public function test_should_find_one_cart(): void
    {
        $cart = new Cart(
            new CartId(Uuid::uuid4()->toString()),
            CreationDate::now(),
            new CartLines([]),
            new CartTotalAmount(0)
        );

        $this->cart_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn($cart);

        $service = new FindOneCart($this->cart_repository);
        $result = $service->__invoke($this->cart_id);

        self::assertInstanceOf(Cart::class, $result);
    }

    public function test_throw_CartDoesNotExist_on_nonexistent_cart(): void
    {
        $this->expectException(CartDoesNotExist::class);

        $this->cart_repository
            ->expects(self::once())
            ->method('findById')
            ->willReturn(null);

        $service = new FindOneCart($this->cart_repository);
        $service->__invoke($this->cart_id);
    }
}
