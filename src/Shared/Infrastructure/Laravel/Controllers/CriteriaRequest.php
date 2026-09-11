<?php

namespace AMovil\Shared\Infrastructure\Laravel\Controllers;

use AMovil\Shared\Domain\Criteria\Filters;
use AMovil\Shared\Domain\Criteria\Order;

class CriteriaRequest
{
    private $request;

    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->request = $request;    
    }

    public function getFilters(): array {
        $filters = $this->request->get("filter", []);
        $filters = is_array($filters) ? $filters : [$filters];
        return $filters;
    }

    public function getCriteriaFilters(): Filters {
        $filters = Filters::fromStringValues($this->getFilters());
        return $filters;
    }

    public function getCriteriaOrder(): Order {
        $orderBy = $this->request->get("orderBy");
        $order = $this->request->get("order");
        return Order::fromValues($orderBy, $order);
    }

    public function getLimitOrDefault($default): ?int {
        return $this->request->get("perPage", $default);
    }

    public function getOffset(): ?int {
        $perPage = $this->getLimitOrDefault(null);
        if($perPage === null){
            return null;
        }
        $page = $this->request->get("page", 1);
        return ($page-1)*$perPage;
    }
}
