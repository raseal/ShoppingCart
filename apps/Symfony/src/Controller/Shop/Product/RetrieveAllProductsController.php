<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Product;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Product\Application\Find\FindAllProductsQuery;
use Symfony\Component\HttpFoundation\Response;

final class RetrieveAllProductsController extends Controller
{
    public function __invoke(): Response
    {
        $query = new FindAllProductsQuery();
        $response = $this->ask($query);

        return $this->createApiResponse($response);
    }
}
