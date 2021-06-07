<?php

declare(strict_types=1);

namespace Shop\Cart\Application\AddProduct;

use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandHandler;
use Shop\Cart\Application\Find\FindOneCart;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\Units;
use Shop\Product\Application\Find\FindOneProduct;
use Shop\Product\Domain\ProductId;

final class AddProductToCartCommandHandler implements CommandHandler
{
    public function __construct(
        private FindOneProduct $find_one_product,
        private FindOneCart $find_one_cart,
        private CartRepository $cart_repository
    ) {}

    public function __invoke(AddProductToCartCommand $command): void
    {
        $this->execute($command);
    }

    public function execute(Command $command): void
    {
        $product_id = new ProductId($command->productId());
        $cart_id = new CartId($command->cartId());
        $quantity = new Units($command->quantity());

        $product = $this->find_one_product->__invoke($product_id);
        $cart = $this->find_one_cart->__invoke($cart_id);

        $cart->addProduct($product, $quantity);

        $this->cart_repository->save($cart);
    }
}
