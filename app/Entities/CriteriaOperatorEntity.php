<?php

namespace App\Entities;

class CriteriaOperatorEntity
{
    public static function basicOperators(): array {
        return [
            'like' => 'Contiene',
            'equals' => 'Igual',
            'not_equals' => 'Diferente',
            'is_null' => 'Is null',            
            'not_in' => 'Multiple Dif'
        ];
    }

    public static function numberOperators(): array {
        return array_merge(self::basicOperators(), [
            'less_greater_than' => 'RANGO (N1, N2)'
        ]);
    }

    public static function dateOperators(): array {
        return [
            'date_equals' => 'Igual',
            'is_null' => 'Is null',
            'date_between' => 'Fecha entre (F1, F2)'
        ];
    }
}
