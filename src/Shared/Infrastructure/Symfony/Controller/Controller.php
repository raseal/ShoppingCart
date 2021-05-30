<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Symfony\Controller;

use Shared\Domain\Bus\Command\Command;
use Shared\Domain\Bus\Command\CommandBus;
use Shared\Domain\Bus\Query\Query;
use Shared\Domain\Bus\Query\QueryBus;
use Shared\Domain\Bus\Query\Response as QueryResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function json_decode;
use function json_encode;

abstract class Controller extends AbstractController
{
    public function __construct(
     //   protected SerializerInterface $serializer,
        protected CommandBus $command_bus,
        protected QueryBus $query_bus
    ) {}

    protected function dispatch(Command $command): void
    {
        $this->command_bus->dispatch($command);
    }

    protected function ask(Query $query): QueryResponse
    {
        return $this->query_bus->ask($query);
    }

    protected function getPayloadAsArray(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    protected function createApiResponse(mixed $data, int $status_code = Response::HTTP_OK): Response
    {
        return new Response(
            json_encode($data),
            $status_code,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }
}
