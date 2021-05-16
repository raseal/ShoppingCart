<?php

declare(strict_types=1);

namespace Shop\Product\Application\Create;

use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandHandler;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductAlreadyExists;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use Shop\Product\Domain\ProductRepository;

final class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private ProductRepository $product_repository
    ) {}
    public function __invoke(CreateProductCommand $command): void
    {
        $this->execute($command);
    }

    public function execute(Command $command): void
    {
        $id = new ProductId($command->id());
        $name = new ProductName($command->name());
        $price = new Price($command->price());
        $offer_price = (null === $command->offerPrice()) ? null : new OfferPrice($command->offerPrice());

        if (null !== $this->product_repository->findById($id)) {
            throw new ProductAlreadyExists($id);
        }

        $product = new Product($id, $name, $price, $offer_price);

        $this->product_repository->save($product);
    }
}
