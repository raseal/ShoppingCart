<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Create;

use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandHandler;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartAlreadyExists;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

final class CreateCartCommandHandler implements CommandHandler
{
    public function __construct(
        private CartRepository $cart_repository
    ) {}

    public function __invoke(CreateCartCommand $command): void
    {
        $this->execute($command);
    }

    public function execute(Command $command): void
    {
        $id = new CartId($command->id());

        if (null !==$this->cart_repository->findById($id)) {
            throw new CartAlreadyExists($id);
        }

        $cart = new Cart(
            $id,
            CreationDate::now(),
            new CartLines([]),
            new CartTotalAmount(0)
        );

        $this->cart_repository->save($cart);
    }
}
