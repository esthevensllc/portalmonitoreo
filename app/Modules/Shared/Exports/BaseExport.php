<?php

namespace App\Modules\Shared\Exports;

use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date;

abstract class BaseExport
{
    public function formatPorcentaje($value){
        if(is_numeric($value)){
            return is_null($value) ? null : ($value / 100);
        }
        return $value;
    }

    public function formatDate(?string $date, $fromFormat = 'Y-m-d H:i:s'){
        $datetime = DateTime::createFromFormat($fromFormat, $date);
        if($datetime){
            return Date::dateTimeToExcel($datetime);
        }
        return '';
    }
}
