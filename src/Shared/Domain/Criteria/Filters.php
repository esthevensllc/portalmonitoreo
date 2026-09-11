<?php

namespace AMovil\Shared\Domain\Criteria;

class Filters
{
    private $filters;

    private function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public static function fromValues(array $values): self
    {
        $filters = [];
        foreach($values as $filter){
            $filters[] = Filter::fromValues($filter);
        }
        return new self($filters);
    }

    public static function fromStringValues(array $values): self
    {
        $filters = [];
        foreach($values as $filter){
            $filters[] = Filter::fromStringValues($filter);
        }
        return new self($filters);
    }

    public function add(Filter $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function items(): array
    {
        return $this->filters;
    }

    public function count(): int {
        return count($this->filters);
    }
}
