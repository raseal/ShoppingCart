<?php

declare(strict_types=1);

namespace Shop\Cart\Infrastructure;

use Doctrine\DBAL\Driver\Connection;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartCollection;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLine;
use Shop\Cart\Domain\CartLineId;
use Shop\Cart\Domain\CartLineQuantity;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartLineTotalAmount;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;
use Shop\Product\Domain\ProductId;

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
        // TODO: save cart_lines
    }

    public function findById(CartId $cart_id): ?Cart
    {
        $query = <<<SQL
SELECT
       BIN_TO_UUID(c.id) AS cart_id,
       c.created AS cart_created,
       c.total AS cart_total,
       BIN_TO_UUID(cl.id) AS cart_line_id,
       BIN_TO_UUID(cl.id_product) AS product_id,
       cl.quantity,
       cl.total AS total_line
FROM 
     cart AS c
    JOIN
         cart_lines AS cl ON cl.id_cart = c.id
WHERE 
      c.id = UUID_TO_BIN(:id)
SQL;
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $cart_id->value());
        $statement->execute();

        $cart_data = $statement->fetchAllAssociative();

        if (0 === count($cart_data)) {
            return null;
        }

        return $this->hydrateItem($cart_data);
    }

    public function findAll(): CartCollection
    {
        $query = <<<SQL
SELECT
       BIN_TO_UUID(c.id) AS cart_id,
       c.created AS cart_created,
       c.total AS cart_total,
       BIN_TO_UUID(cl.id) AS cart_line_id,
       BIN_TO_UUID(cl.id_product) AS product_id,
       cl.quantity,
       cl.total AS total_line
FROM
     cart AS c
     JOIN
         cart_lines AS cl ON cl.id_cart = c.id
SQL;
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $carts = $statement->fetchAllAssociative();

         return $this->hydrateItems($carts);
    }

    private function hydrateItems(array $items): CartCollection
    {
        $cart_info = [];
        $data = [];

        foreach ($items as $item) {
            $cart_info[$item['cart_id']][] = $item;
        }

        foreach ($cart_info as $info) {
            $data[] = $this->hydrateItem($info);
        }

        return new CartCollection($data);
    }

    private function hydrateItem(array $item): Cart
    {
        return new Cart(
            new CartId($item[0]['cart_id']),
            new CreationDate($item[0]['cart_created'], 'Y-m-d H:i:s'),
            $this->getCartLines($item),
            new CartTotalAmount((float)$item[0]['cart_total'])
        );
    }

    private function getCartLines(array $lines): CartLines
    {
        $result = [];

        foreach($lines as $line) {
            $result[] = new CartLine(
                new CartLineId($line['cart_line_id']),
                new CartId($line['cart_id']),
                new ProductId($line['product_id']),
                new CartLineQuantity((int)$line['quantity']),
                new CartLineTotalAmount((float)$line['total_line'])
            );
        }

        return new CartLines($result);
    }
}
