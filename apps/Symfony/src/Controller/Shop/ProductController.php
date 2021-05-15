<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Product\Application\Create\CreateProductCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProductController extends Controller
{
    public function retrieve(?string $id): Response
    {
        return new Response("GET $id");
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
