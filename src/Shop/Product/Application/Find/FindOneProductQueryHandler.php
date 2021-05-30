<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryHandler;
use Shared\Domain\Bus\Query\Response;
use Shop\Product\Domain\ProductId;

final class FindOneProductQueryHandler implements QueryHandler
{
    public function __construct(
        private FindOneProduct $find_one_product
    ) {}

    public function __invoke(FindOneProductQuery $query): Response
    {
        return $this->execute($query);
    }

    public function execute(Query $query): Response
    {
        $id = new ProductId($query->id());
        $product = $this->find_one_product->__invoke($id);

        return ProductResponse::fromProduct($product);
    }
}
