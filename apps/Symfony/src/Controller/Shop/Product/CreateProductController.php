<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Product;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Product\Application\Create\CreateProductCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class CreateProductController extends Controller
{
    public function __invoke(Request $request): Response
    {
        try {
            $parameters = $this->getPayloadAsArray($request);
            $command = new CreateProductCommand(
                $this->createRandomUuidAsString(),
                $parameters['name'],
                (float)$parameters['price'],
                (float)$parameters['offer_price']
            );

            $this->dispatch($command);

            return $this->createApiResponse([], Response::HTTP_CREATED);

        } catch (Throwable $exception) {
            return $this->createApiResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
