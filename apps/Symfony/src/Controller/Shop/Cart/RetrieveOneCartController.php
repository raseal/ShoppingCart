<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Cart;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Cart\Application\Find\FindOneCartQuery;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RetrieveOneCartController extends Controller
{
    public function __invoke(string $id): Response
    {
        try {
            $query = new FindOneCartQuery($id);
            $response = $this->ask($query);

            return $this->createApiResponse($response);

        } catch (Throwable $exception) {
            return $this->createApiResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
