<?php

namespace AMovil\Shared\Domain\Criteria;

use InvalidArgumentException;

class OrderType
{
	const ASC = 'asc';
	const DESC = 'desc';
	const NONE = 'none';

	private $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

	public function isNone(): bool
	{
		return $this->value === self::NONE;
	}

	public static function from($value){
		switch($value){
			case self::ASC: return new OrderType(self::ASC);
			case self::DESC: return new OrderType(self::DESC);
			case self::NONE: return new OrderType(self::NONE);
			default:
				throw new InvalidArgumentException("El tipo de orden no es valido");
		}
	}

	public function value(){
		return $this->value;
	}

}
