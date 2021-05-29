<?php

declare(strict_types=1);

namespace Test\Shop\Cart\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

class CartTest extends TestCase
{
    public function test_should_create_a_cart()
    {
        $cart_id = Uuid::uuid4()->toString();
        $creation_date = CreationDate::now();

        $cart = new Cart(
            new CartId($cart_id),
            $creation_date,
            new CartLines([]),
            new CartTotalAmount(0)
        );

        self::assertEquals($cart_id, $cart->id()->value());
        self::assertEquals($creation_date, $cart->creationDate()->value());
        self::assertCount(0, $cart->cartLines()->items());
        self::assertEquals(0, $cart->totalAmount()->value());
    }

    public function test_throw_exception_on_invalid_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cart(
            new CartId('invalid-id'),
            CreationDate::now(),
            new CartLines([]),
            new CartTotalAmount(0)
        );
    }
}
