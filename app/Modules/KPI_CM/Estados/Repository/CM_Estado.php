<?php

namespace App\Modules\KPI_CM\Estados\Repository;

use Illuminate\Database\Eloquent\Model;

class CM_Estado extends Model
{
    protected $table = 'prg_cm_estado';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    public static function table(){
        return 'prg_cm_estado';
    }

    public static function defaultValue(){
        return 'NINGUNO';
    }
}
