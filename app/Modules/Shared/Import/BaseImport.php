<?php

namespace App\Modules\Shared\Import;

use App\Modules\Shared\BooleanState\Repository\BooleanState;
use PhpOffice\PhpSpreadsheet\Shared\Date;

abstract class BaseImport
{
    protected function mapPorcentaje($value){
        if(!is_null($value)){
            return is_numeric($value) ? ($value * 100) : $value;
        }
        return $value;
    }

    protected function formatToDateTime($intDateTime){
        if(is_numeric($intDateTime)){
            return Date::excelToDateTimeObject($intDateTime);
        }
        return null;
    }

    protected function mapBooleanState($value){
        if(is_null($value)){
            return BooleanState::default();
        }
        return $value;
    }
}
