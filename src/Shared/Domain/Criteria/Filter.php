<?php

namespace AMovil\Shared\Domain\Criteria;

use Exception;

class Filter
{
    private $field;
    private $operator;
    private $value;

    private function __construct(string $field, FilterOperator $operator, string $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    public static function fromValues(array $values): self
    {
        return new self(
            $values["field"],
            FilterOperator::from($values["operator"]),
            $values["value"]
        );
    }

    public static function fromStringValues(string $values): self
    {
        $parts = explode(".", $values);
        if(count($parts) < 3){
            throw new Exception("El filtro {$values} no es válido");
        }
        $value = [];
        for ($i=2; $i < count($parts); $i++) {
            $value[] = $parts[$i];
        }
        
        return new self(
            $parts[0],
            FilterOperator::from($parts[1]),
            implode(".", $value)
        );
    }

    public function field(): string {
        return $this->field;
    }

    public function operator(): FilterOperator {
        return $this->operator;
    }

    public function value(): string {
        return $this->value;
    }
}
