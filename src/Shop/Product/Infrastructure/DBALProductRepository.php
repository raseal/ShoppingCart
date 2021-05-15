<?php

declare(strict_types=1);

namespace Shop\Product\Infrastructure;

use Doctrine\DBAL\Driver\Connection;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductRepository;

class DBALProductRepository implements ProductRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(Product $product): void
    {
        $query = 'INSERT INTO product(id, name, price, offer_price) VALUES(UUID_TO_BIN(:id), :name, :price, :offer_price)';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $product->id()->value());
        $statement->bindValue('name', $product->name()->value());
        $statement->bindValue('price', $product->price()->value());
        $statement->bindValue('offer_price', $product->offerPrice()->value());

        $statement->execute();
    }
}
