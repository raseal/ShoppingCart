<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryHandler;
use Shared\Domain\Bus\Query\Response;
use Shop\Product\Domain\ProductDoesNotExist;
use Shop\Product\Domain\ProductId;
use Shop\Product\Domain\ProductRepository;

class FindOneProductQueryHandler implements QueryHandler
{
    public function __construct(
        private ProductRepository $product_repository
    ) {}

    public function __invoke(FindOneProductQuery $query): Response
    {
        return $this->execute($query);
    }

    public function execute(Query $query): Response
    {
        $id = new ProductId($query->id());
        $product = $this->product_repository->findById($id);

        if (null === $product) {
            throw new ProductDoesNotExist($id);
        }

        return ProductResponse::fromProduct($product);
    }
}
