<?php

namespace AMovil\Shared\Domain\Criteria;

final class Order
{
	private string $orderBy;
	private OrderType $orderType;

	public function __construct(string $orderBy, OrderType $orderType) {
		$this->orderBy = $orderBy;
		$this->orderType = $orderType;
	}

	public static function createDesc(string $orderBy): self
	{
		return new self($orderBy, OrderType::from(OrderType::DESC));
	}

	public static function createAsc(string $orderBy): self
	{
		return new self($orderBy, OrderType::from(OrderType::ASC));
	}

	public static function fromValues(?string $orderBy, ?string $order): self
	{
		return ($orderBy === null || $order === null) ? self::none() : new self(
			$orderBy,
			OrderType::from($order)
		);
	}

	public static function none(): self
	{
		return new self('', OrderType::from(OrderType::NONE));
	}

	public function orderBy(): string
	{
		return $this->orderBy;
	}

	public function orderType(): OrderType
	{
		return $this->orderType;
	}

	public function isNone(): bool
	{
		return $this->orderType()->isNone();
	}
}
