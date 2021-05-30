<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryHandler;
use Shared\Domain\Bus\Query\Response;
use Shop\Cart\Domain\CartId;

final class FindOneCartQueryHandler implements QueryHandler
{
    public function __construct(
        private FindOneCart $find_one_cart
    ) {}

    public function __invoke(FindOneCartQuery $query): Response
    {
        return $this->execute($query);
    }

    public function execute(Query $query): Response
    {
        $id = new CartId($query->cartId());
        $cart = $this->find_one_cart->__invoke($id);

        return CartResponse::fromCart($cart);
    }
}
