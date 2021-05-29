<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop\Cart;


use Ramsey\Uuid\Uuid;
use Shared\Infrastructure\Symfony\Controller\Controller;
use Shop\Cart\Application\Create\CreateCartCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateCartController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $command = new CreateCartCommand(
               Uuid::uuid4()->toString()
            );
            $this->dispatch($command);

            return $this->createApiResponse([], Response::HTTP_CREATED);

        } catch (Throwable $exception) {
            return $this->createApiResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
