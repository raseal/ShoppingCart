<?php

declare(strict_types=1);

namespace Shared\Domain;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;
use function sprintf;

abstract class Collection implements Countable, IteratorAggregate
{
    protected array $items;

    public function __construct(array $items)
    {
        $this->ensureAllItemsHaveSameType($items);
        $this->items = $items;
    }

    abstract protected function type(): string;

    public function add(mixed $item): void
    {
        $this->ensureItemHasSameType($item);
        $this->items[] = $item;
    }

    public function item(int $index): mixed
    {
        return $this->items[$index] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function items(): array
    {
        return $this->items;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    private function ensureAllItemsHaveSameType(array $items): void
    {
        foreach ($items as $item) {
            $this->ensureItemHasSameType($item);
        }
    }

    private function ensureItemHasSameType(mixed $item): void
    {
        $expected_class = $this->type();

        if (! $item instanceof $expected_class) {
            throw new InvalidArgumentException(
                sprintf(
                    'The item should be an instance of %s',
                    $expected_class
                )
            );
        }
    }
}
