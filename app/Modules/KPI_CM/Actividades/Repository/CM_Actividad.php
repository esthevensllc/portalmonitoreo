<?php

namespace App\Modules\KPI_CM\Actividades\Repository;

use Illuminate\Database\Eloquent\Model;

class CM_Actividad extends Model
{
    protected $table = 'prg_cm_actividad';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    public static function table(){
        return 'prg_cm_actividad';
    }

    public static function defaultValue(){
        return 'NINGUNO';
    }
}
