<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryHandler;
use Shared\Domain\Bus\Query\Response;
use Shop\Cart\Domain\CartRepository;

class FindAllCartsQueryHandler implements QueryHandler
{
    public function __construct(
        private CartRepository $cart_repository
    ) {}

    public function __invoke(FindAllCartsQuery $query): Response
    {
        return $this->execute($query);
    }

    public function execute(Query $query): Response
    {
        $carts = $this->cart_repository->findAll();

        return CartCollectionResponse::fromCartCollection($carts);
    }
}
