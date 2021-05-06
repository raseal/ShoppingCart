<?php

declare(strict_types=1);

namespace SymfonyApp\Controller\Shop;

use Symfony\Component\HttpFoundation\Response;

class ProductController
{
    public function retrieve(?string $id): Response
    {
        return new Response("GET $id");
    }

    public function create(): Response
    {
        return new Response("CREATE product!");
    }
}
