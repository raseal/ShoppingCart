<?php

declare(strict_types=1);

namespace Shop\Cart\Application\Find;

use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryHandler;
use Shared\Domain\Bus\Query\Response;
use Shop\Cart\Domain\CartDoesNotExist;
use Shop\Cart\Domain\CartId;
use Shop\Cart\Domain\CartRepository;

final class FindOneCartQueryHandler implements QueryHandler
{
    public function __construct(
        private CartRepository $cart_repository
    ) {}

    public function __invoke(FindOneCartQuery $query): Response
    {
        return $this->execute($query);
    }

    public function execute(Query $query): Response
    {
        $id = new CartId($query->cartId());
        $cart = $this->cart_repository->findById($id);

        if (null === $cart) {
            throw new CartDoesNotExist($id);
        }

        return CartResponse::fromCart($cart);
    }
}
