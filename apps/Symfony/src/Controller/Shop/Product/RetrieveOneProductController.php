<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Product;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Product\Application\Find\FindOneProductQuery;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class RetrieveOneProductController extends Controller
{
    public function __invoke(string $id): Response
    {
        try {
            $query = new FindOneProductQuery($id);
            $response = $this->ask($query);

            return $this->createApiResponse($response);

        } catch (Throwable $exception) {
            return $this->createApiResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
