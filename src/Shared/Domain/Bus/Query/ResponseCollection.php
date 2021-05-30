<?php

declare(strict_types=1);

namespace Shared\Domain\Bus\Query;

use Shared\Domain\Aggregate\Collection;

abstract class ResponseCollection extends Collection implements Response
{
}
