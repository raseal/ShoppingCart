<?php

declare(strict_types=1);

namespace Test\Shop\Cart\Application\Create;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Shop\Cart\Application\Create\CreateCartCommand;
use Shop\Cart\Application\Create\CreateCartCommandHandler;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartAlreadyExists;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

class CreateCartCommandHandlerTest extends TestCase
{
    private CartRepository $cart_repository;
    private CreateCartCommand $command;

    public function setUp(): void
    {
        $this->cart_repository = $this->createMock(CartRepository::class);
        $this->command = new CreateCartCommand(Uuid::uuid4()->toString());
    }

    public function test_should_create_cart(): void
    {
        $this->cart_repository
            ->expects(self::once())
            ->method('save');

        $handler = new CreateCartCommandHandler($this->cart_repository);
        $handler->execute($this->command);
    }

    public function test_should_throw_CartAlreadyExists_on_duplicate_insertion(): void
    {
        $this->expectException(CartAlreadyExists::class);

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

        $handler = new CreateCartCommandHandler($this->cart_repository);
        $handler->execute($this->command);
    }
}
