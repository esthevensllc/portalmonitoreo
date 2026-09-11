<?php

namespace App\Traits\Filters;

use App\Entities\CriteriaOperatorEntity;
use DB;

trait CriteriaFilterTrait
{
    private function loadCriteriaFilter($name, $label, $values = 'default', $mapValue = 'default'){
        if($values === 'default'){
            $values = CriteriaOperatorEntity::basicOperators();
        }else if($values === 'number'){
            $values = CriteriaOperatorEntity::numberOperators();
        }else if($values === 'date'){
            $values = CriteriaOperatorEntity::dateOperators();
        }

        $this->crud->addFilter([
            'type' => 'custom_criteria',
            'name' => preg_replace('([^A-Za-z0-9_])', '', $name),
            'label' => $label
        ],
        $values,
        function($value) use($name, $mapValue) {
            $criteria = json_decode($value);
            // dd();
            if(isset($criteria->operator) && isset($criteria->value)){
                // $criteriaValue = $criteria->value;
                $criteria = $mapValue === 'default' ? $criteria : $mapValue(clone $criteria);
                // dd($criteria);
                switch ($criteria->operator) {
                    case 'like':
                        $this->crud->query->where(DB::Raw("LOWER($name)"), 'LIKE', "%".strtolower($criteria->value)."%");
                        break;
                    case 'equals':
                        $this->crud->query->where(DB::Raw($name), '=', $criteria->value);
                        break;
                    case 'not_equals':
                        $this->crud->query->where(DB::Raw($name), '!=', $criteria->value);
                        break;
                    case 'is_null':
                        $this->crud->query->whereNull(DB::Raw($name));
                        break;
                    case 'less_greater_than':
                        $operationValues = explode(',', $criteria->value);
                        $this->crud->query->where(DB::Raw($name), '>=', $operationValues[0])->where(DB::Raw($name), '<=', $operationValues[1]);
                        break;
                    case 'in':
                        $arrayValues = explode(',', $criteria->value);
                        $operationValues = array_map('trim', $arrayValues);
                        $this->crud->query->whereIn(DB::Raw($name), $operationValues);
                        break;
                    case 'not_in':
                        $operationValues = explode(',', str_replace(' ', '', $criteria->value));
                        $this->crud->query->whereNotIn(DB::Raw($name), $operationValues);
                        break;
                    case 'date_equals':
                        $this->crud->query->where(function($query) use($name, $criteria){
                            $query->whereRaw("$name = TO_DATE(?, 'YYYY-MM-DD')", [$criteria->value]);
                        });
                        break;
                    case 'date_between':
                        $operationValues = explode(',', $criteria->value);
                        $this->crud->query->where(function($query) use($name, $operationValues){
                            $query->whereRaw("$name >= TO_DATE(?, 'YYYY-MM-DD') AND $name < TO_DATE(?, 'YYYY-MM-DD')", [$operationValues[0], $operationValues[1]]);
                        });
                        break;
                    default:
                        $this->crud->query->where(DB::Raw("LOWER($name)"), 'LIKE', "%".strtolower($criteria->value)."%");
                        break;
                }
            }
            //$this->crud->query->where(DB::Raw("LOWER($name)"), 'LIKE', "%".strtolower($value)."%");
        });
}
}
