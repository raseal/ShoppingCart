<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Cart;

use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Cart\Application\AddProduct\AddProductToCartCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AddProductToCartController extends Controller
{
    public function __invoke(string $cartId, Request $request): Response
    {
        try {
            $parameters = $this->getPayloadAsArray($request);
            $command = new AddProductToCartCommand(
                $cartId,
                $parameters['product_id'],
                $parameters['quantity']
            );

            $this->dispatch($command);

            return $this->createApiResponse('', Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            return $this->createApiResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
