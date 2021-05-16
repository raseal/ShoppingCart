<?php

declare(strict_types=1);

namespace Shop\Product\Application\Find;

use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryHandler;
use Shared\Domain\Bus\Query\Response;
use Shop\Product\Domain\ProductRepository;

final class FindAllProductsQueryHandler implements QueryHandler
{
    public function __construct(
        private ProductRepository $product_repository
    ) {}

    public function __invoke(FindAllProductsQuery $query): Response
    {
        return $this->execute($query);
    }

    public function execute(Query $query): Response
    {
        $products = $this->product_repository->findAll();

        return ProductCollectionResponse::fromProductCollection($products);
    }
}
