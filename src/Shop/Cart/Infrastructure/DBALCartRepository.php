<?php

declare(strict_types=1);

namespace Shop\Cart\Infrastructure;

use Doctrine\DBAL\Driver\Connection;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;

class DBALCartRepository implements CartRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(Cart $cart): void
    {
        $query = <<<SQL
INSERT INTO cart(id, created, total) VALUES
    (
     UUID_TO_BIN(:id), 
     :created, 
     :total 
     )
SQL;

        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $cart->id()->value());
        $statement->bindValue('created', $cart->creationDate()->value());
        $statement->bindValue('total', $cart->totalAmount()->value());

        $statement->execute();
    }

    public function findById(CartId $cart_id): ?Cart
    {
        $query = <<<SQL
SELECT
       BIN_TO_UUID(id) AS id,
       created,
       total
FROM 
     cart
WHERE 
      id = UUID_TO_BIN(:id)
SQL;
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $cart_id->value());
        $statement->execute();

        $cart_data = $statement->fetchAssociative();

        if (false === $cart_data) {
            return null;
        }

        return new Cart(
            new CartId($cart_data['id']),
            new CreationDate($cart_data['created'], 'Y-m-d H:i:s'),
            new CartLines([]), // TODO: retrieve all cart lines
            new CartTotalAmount((float)$cart_data['total'])
        );
    }
}
