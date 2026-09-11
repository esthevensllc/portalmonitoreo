<?php

namespace AMovil\Shared\Infrastructure\Eloquent;

use AMovil\Shared\Domain\Criteria\Criteria;
use AMovil\Shared\Domain\Criteria\Filter;
use AMovil\Shared\Domain\Criteria\FilterOperator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EloquentCriteriaConverter2
{
    private Builder $builder;
    private Criteria $criteria;
    private array $fields;

    public function __construct(Builder $builder, Criteria $criteria, array $fields)
    {
        $this->builder = $builder;
        $this->criteria = $criteria;
        $this->fields = $fields;
    }

    public function convert()
    {
        foreach($this->criteria->filters()->items() as $filter){
            $this->convertFilter($filter);
        }
        $order = $this->criteria->order();
        $limit = $this->criteria->limit();
        $offset = $this->criteria->offset() ?? 0;
        if($limit !== null){
            $this->builder->skip($offset)->limit($limit);
        }

        if(!$order->isNone()){
            $this->builder->orderBy($order->orderBy(), $order->orderType()->value());
        }
        return $this->builder;
    }

    private function convertFilter(Filter $filter)
    {
        $field = $filter->field();
        $operator = $filter->operator();
        $value = $filter->value();
        if(!array_key_exists($field, $this->fields)){
            throw new InvalidArgumentException("El campo {$field} no es valido");
        }else{
            if($this->fields[$field] !== null){
                $field = $this->fields[$field];
            }
        }
        if($operator->isContaining()) {
            if($operator->value === FilterOperator::CONTAINS){
                $this->builder->where(function($query) use ($field, $value){
                    $queryField = "lower({$field})";
                    if(is_numeric(str_replace("_", "", $field))){
                        $queryField = "lower(\"{$field}\")";
                    }
                    $query->whereRaw("{$queryField} like '%'|| lower(?) ||'%'", $value);
                });
            }else if($operator->value === FilterOperator::NOT_CONTAINS){
                $this->builder->where(function($query) use ($field, $value){
                    $queryField = "lower({$field})";
                    if(is_numeric(str_replace("_", "", $field))){
                        $queryField = "lower(\"{$field}\")";
                    }
                    $query->whereRaw("{$queryField} not like '%'|| lower(?) ||'%'", $value);
                });
            }
        } else {
            if(!is_numeric(str_replace("_", "", $field))){
                $field = DB::raw($field);
            }
            switch ($operator->value) {
                case FilterOperator::EQUAL:
                    $this->builder->where($field, "=", $value);
                    break;
                case FilterOperator::NOT_EQUAL:
                    $this->builder->where($field, "!=", $value);
                    break;
                case FilterOperator::GT:
                    $this->builder->where($field, ">", $value);
                    break;
                case FilterOperator::GTE:
                    $this->builder->where($field, ">=", $value);
                    break;
                case FilterOperator::LT:
                    $this->builder->where($field, "<", $value);
                    break;
                case FilterOperator::IS_NULL:
                    $this->builder->whereNull($field);
                    break;
                case FilterOperator::IS_NOTNULL:
                    $this->builder->whereNotNull($field);
                    break;
                default:
                    break;
            }
        }
    }
}
