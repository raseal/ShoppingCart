<?php

declare(strict_types=1);

namespace Shop\Cart\Infrastructure;

use Doctrine\DBAL\Driver\Connection;
use Shop\Cart\Domain\Cart;
use Shop\Cart\Domain\CartCollection;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartLine;
use Shop\Cart\Domain\CartLineId;
use Shop\Cart\Domain\CartLines;
use Shop\Cart\Domain\CartLineTotalAmount;
use Shop\Cart\Domain\CartRepository;
use Shop\Cart\Domain\CartTotalAmount;
use Shop\Cart\Domain\CreationDate;
use Shop\Cart\Domain\Units;
use Shop\Product\Domain\ProductId;

class DBALCartRepository implements CartRepository
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(Cart $cart): void
    {
        $this->connection->beginTransaction();
        $this->saveCart($cart);
        $this->saveCartLines($cart->cartLines());
        $this->connection->commit();
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
    LEFT JOIN
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
     LEFT JOIN
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

            if (null === $line['cart_line_id']) {
                continue;
            }

            $result[] = new CartLine(
                new CartLineId($line['cart_line_id']),
                new CartId($line['cart_id']),
                new ProductId($line['product_id']),
                new Units((int)$line['quantity']),
                null,
                new CartLineTotalAmount((float)$line['total_line'])
            );
        }

        return new CartLines($result);
    }

    private function saveCart(Cart $cart): void
    {
        $query = <<<SQL
REPLACE INTO cart(id, created, total) VALUES
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

    private function saveCartLines(CartLines $cart_lines): void
    {
        $query = <<<SQL
REPLACE INTO cart_lines(id, id_cart, id_product, quantity, total) VALUES
    (
        UUID_TO_BIN(?),
        UUID_TO_BIN(?),
        UUID_TO_BIN(?),
        ?,
        ?
    )
SQL;
        $statement = $this->connection->prepare($query);

        foreach($cart_lines->items() as $item) {
            $statement->execute([
                $item->id()->value(),
                $item->cartId()->value(),
                $item->productId()->value(),
                $item->quantity()->value(),
                $item->totalAmount()->value(),
            ]);
        }
    }
}
