<?php

declare(strict_types=1);

namespace Shop\Product\Infrastructure;

use Doctrine\DBAL\Driver\Connection;
use Shop\Product\Domain\OfferPrice;
use Shop\Product\Domain\Price;
use Shop\Product\Domain\Product;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductName;
use Shop\Product\Domain\ProductRepository;

final class DBALProductRepository implements ProductRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(Product $product): void
    {
        $query = <<<SQL
INSERT INTO product(id, name, price, offer_price) VALUES
    (
     UUID_TO_BIN(:id), 
     :name, 
     :price, 
     :offer_price
     )
SQL;

        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $product->id()->value());
        $statement->bindValue('name', $product->name()->value());
        $statement->bindValue('price', $product->price()->value());
        $statement->bindValue('offer_price', $product->offerPrice()->value());

        $statement->execute();
    }

    public function findById(ProductId $product_id): ?Product
    {
        $query = <<<SQL
SELECT
       BIN_TO_UUID(id) AS id,
       name,
       price,
       offer_price
FROM 
     product
WHERE 
      id = UUID_TO_BIN(:id)
SQL;
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $product_id->value());
        $statement->execute();

        $product_data = $statement->fetchAssociative();

        if (false === $product_data) {
            return null;
        }

        return new Product(
            new ProductId($product_data['id']),
            new ProductName($product_data['name']),
            new Price((float)$product_data['price']),
            new OfferPrice((float)$product_data['offer_price'])
        );
    }
}
