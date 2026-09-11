<?php

namespace App\Modules\KPI_CM\CVM\Repository;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class CM_CVM extends Model
{
    use CrudTrait;

    protected $table = 'prg_cm_cvm';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'region',
        'encargados',
        'ubigeo',
        'departamento',
        'ccpp',
        'indicador',
        'tecnologia',
        'dl_3g',
        'dl_4g',
        'ul_3g',
        'ul_4g',
        'cm_enviado',
        'fecha',
        'fecha_limite',
        'periodo',
        'multa',
        'grupo_multa',
        //editables
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
        return 'prg_cm_cvm';
    }
}
