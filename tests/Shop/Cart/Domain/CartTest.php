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
use Shop\Cart\Domain\Units;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use function random_int;

class CartTest extends TestCase
{
    private Cart $cart;
    private CartId $cart_id;
    private CreationDate $creation_date;

    public function setUp(): void
    {
        $this->cart_id = new CartId(Uuid::uuid4()->toString());
        $this->creation_date = CreationDate::now();
        $this->cart = new Cart(
            $this->cart_id,
            $this->creation_date,
            new CartLines([]),
            new CartTotalAmount(0)
        );
    }

    public function test_should_create_a_cart()
    {
        self::assertEquals($this->cart_id, $this->cart->id()->value());
        self::assertEquals($this->creation_date, $this->cart->creationDate()->value());
        self::assertCount(0, $this->cart->cartLines()->items());
        self::assertEquals(0, $this->cart->totalAmount()->value());
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

    public function test_should_update_total_cart_on_adding_line(): void
    {
        $random_quantity = random_int(0, 50);
        $random_price  = random_int(1, 99);
        $expected_amount = (float)$random_quantity * $random_price;

        $this->cart->addProduct(
            new Product(
                new ProductId(Uuid::uuid4()->toString()),
                new ProductName('Name'),
                new Price($random_price),
                new OfferPrice($random_price)
            ),
            new Units($random_quantity)
        );

        self::assertEquals($expected_amount, $this->cart->totalAmount()->value());
    }
}
