<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Cart;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Cart\Application\Find\FindAllCartsQuery;
use Symfony\Component\HttpFoundation\Response;

class RetrieveAllCartsController extends Controller
{
    public function __invoke(): Response
    {
        $query = new FindAllCartsQuery();
        $response = $this->ask($query);

        return $this->createApiResponse($response);
    }
}
