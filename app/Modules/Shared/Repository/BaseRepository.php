<?php

namespace App\Modules\Shared\Repository;

abstract class BaseRepository
{
    protected function mapField($value, $key){
        return isset($value[$key]) ? $value[$key] : null;
    }
}
