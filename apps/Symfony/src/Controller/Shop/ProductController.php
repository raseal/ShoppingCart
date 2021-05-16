<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Product\Application\Create\CreateProductCommand;
use Shop\Product\Application\Find\FindOneProductQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ProductController extends Controller
{
    public function retrieveOne(string $id): Response
    {
        try {
            $query = new FindOneProductQuery($id);
            $response = $this->ask($query);

            return $this->createApiResponse($response);

        } catch (Throwable $exception) {
            return $this->createApiResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(Request $request): Response
    {
        $parameters = $this->getPayloadAsArray($request);
        $command = new CreateProductCommand(
            $this->createRandomUuidAsString(),
            $parameters['name'],
            (float)$parameters['price'],
            (float)$parameters['offer_price']
        );

        $this->dispatch($command);

        return new Response(null, Response::HTTP_CREATED);
    }
}
