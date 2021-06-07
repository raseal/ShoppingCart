<?php

declare(strict_types=1);

namespace Test\Shop\Cart\Application\AddProduct;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Cart\Application\AddProduct\AddProductToCartCommand;
use Shop\Cart\Application\AddProduct\AddProductToCartCommandHandler;
use Shop\Cart\Application\Find\FindOneCart;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartIsFull;
use Shop\Cart\Domain\CartLineIsFull;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;
use Shop\Cart\Domain\Units;
use Shop\Product\Application\Find\FindOneProduct;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use function random_int;

class AddProductToCartCommandHandlerTest extends TestCase
{
    private Product $product;
    private Cart $cart;
    private FindOneProduct $find_one_product;
    private FindOneCart $find_one_cart;
    private AddProductToCartCommand $command;
    private Units $units;
    private CartRepository $repository;

    public function setUp(): void
    {
        $this->find_one_product = $this->createMock(FindOneProduct::class);
        $this->find_one_cart = $this->createMock(FindOneCart::class);
        $this->repository = $this->createMock(CartRepository::class);

        $this->units = new Units(random_int(1, 2));

        $this->product = new Product(
            new ProductId(Uuid::uuid4()->toString()),
            new ProductName('Name'),
            new Price(25.0),
            new OfferPrice(20.0)
        );

        $this->cart = new Cart(
            new CartId(Uuid::uuid4()->toString()),
            CreationDate::now(),
            new CartLines([]),
            new CartTotalAmount(0)
        );

        $this->command = new AddProductToCartCommand(
            $this->cart->id()->value(),
            $this->product->id()->value(),
            $this->units->value()
        );
    }

    public function test_should_add_new_product(): void
    {
        $this->serviceFindsACart();
        $this->serviceFindsAProduct();

        $handler = new AddProductToCartCommandHandler($this->find_one_product, $this->find_one_cart, $this->repository);
        self::assertEquals(0, $this->cart->cartLines()->count());
        $handler->execute($this->command);
        self::assertEquals(1, $this->cart->cartLines()->count());
    }

    public function test_should_update_existent_line(): void
    {
        $this->serviceFindsACart(2);
        $this->serviceFindsAProduct(2);

        $handler = new AddProductToCartCommandHandler($this->find_one_product, $this->find_one_cart, $this->repository);
        $handler->execute($this->command);
        self::assertEquals(1, $this->cart->cartLines()->count());
        $first_amount = $this->cart->totalAmount()->value();

        $handler->execute($this->command);
        self::assertEquals(1, $this->cart->cartLines()->count());
        self::assertGreaterThan($first_amount, $this->cart->totalAmount()->value());
    }

    public function test_should_throw_exception_on_creating_more_than_10_lines(): void
    {
        $this->expectException(CartIsFull::class);

        $this->fillCartWithTenLines();
        $this->serviceFindsACart();
        $this->serviceFindsAProduct();

        $handler = new AddProductToCartCommandHandler($this->find_one_product, $this->find_one_cart, $this->repository);
        $handler->execute($this->command);
    }

    public function test_should_throw_exception_on_adding_more_than_50_products(): void
    {
        $this->expectException(CartLineIsFull::class);

        $this->cart->addProduct($this->product, new Units(50));
        $this->cart->addProduct($this->product, new Units(1));
    }

    private function serviceFindsAProduct(int $times_called = 1): void
    {
        $this->find_one_product
            ->expects(self::exactly($times_called))
            ->method('__invoke')
            ->willReturn($this->product);
    }

    private function serviceFindsACart(int $times_called = 1): void
    {
        $this->find_one_cart
            ->expects(self::exactly($times_called))
            ->method('__invoke')
            ->willReturn($this->cart);
    }

    private function fillCartWithTenLines(): void
    {
        for($i = 0; $i < 10; $i++) {
            $this->cart->addProduct(
                new Product(
                    new ProductId(Uuid::uuid4()->toString()),
                    new ProductName('test-product' . $i),
                    new Price(1),
                    new OfferPrice(1)
                ),
                new Units(2)
            );
        }
    }
}
