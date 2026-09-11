<?php

namespace App\Models\PrtltxAlarma;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PRTLTX_ALARMAS_MYSQL extends Model
{
    use CrudTrait;

    protected $table = 'PRTLTX_ALARMAS_MYSQL';
    protected $primaryKey = 'idlog_serial';
    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        //'id',
        'idlog_serial',
        'nombre_red',
        'codigo_red',
        'codigo_alarma',
        'nombre_alarma',
        'tipo_alarma',
        'estado_actual',
        'severidad_alarma',
        'fecha_inicalarma',
        'fecha_finalarma',
        'detalle_alar',
        'causas'
    ];
    //protected $hidden = ['id'];
    // protected $dates = [];
}
