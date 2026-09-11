<?php

namespace AMovil\Shared\Domain\Criteria;

use InvalidArgumentException;

class FilterOperator
{
    const EQUAL = 'eq';
    const NOT_EQUAL = 'ne';
    const GT = 'gt';
    const GTE = 'gte';
    const LT = 'lt';
    const CONTAINS = 'cn';
    const NOT_CONTAINS = 'nc';
    const IS_NULL = 'isnull';
    const IS_NOTNULL = 'isnotnull';

    public string $value;

    private function __construct($value)
    {
        if(!in_array($value, [self::EQUAL, self::NOT_EQUAL, self::GT, self::GTE, self::LT, self::CONTAINS, self::NOT_CONTAINS, self::IS_NULL, self::IS_NOTNULL])){
            throw new InvalidArgumentException("El operador no es valido");
        }
        $this->value = $value;
    }

    public function isContaining(): bool
	{
		return in_array($this->value, [self::CONTAINS, self::NOT_CONTAINS], true);
	}

    public static function from($value) :FilterOperator {
        return new self($value);
    }
}
