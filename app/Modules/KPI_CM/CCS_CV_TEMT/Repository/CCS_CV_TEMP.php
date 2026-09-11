<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Repository;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class CCS_CV_TEMP extends Model
{
    use CrudTrait;

    protected $table = 'PRG_CM_CCS_CV_TEMT';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'encargados',
        'region',
        'ubigeo',
        'departamento',
        'provincia',
        'distrito',
        'ccpp',
        'tecnologia',
        'indicador',
        'gsm',
        'umts',
        'comentario_osiptel',
        'fecha',
        'fecha_limite',
        'periodo',
        'multa',
        'grupo_multa',
        'actividad',
        'fecha_actividad',
        'estado',
        'acta_levantada',
        'comentarios_red',
        'enviado_osiptel',
        'fecha_env_osiptel',
        'comentarios_rg',
        'acta_archivo',
        'created_at',
        'updated_at',
    ];

    public static function table(){
        return 'PRG_CM_CCS_CV_TEMT';
    }

}
