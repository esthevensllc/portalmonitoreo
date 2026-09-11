<?php

namespace App\Models\PrtltxAlarma;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PRTLTX_ALARMA_DISP_DET extends Model
{
    use CrudTrait;

    protected $table = 'prtltx_alarma_disp_det';
    protected $primaryKey = 'estado_valid_alarm';
    //public $incrementing = false;
    //public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'indisp_seg',
        'estado_valid_alarm',
        'mes',
        'log_serial_number',
        'nombre_red',
        'codigo_alarma',
        'nombre_alarma',
        'tipo_alarma',
        'estado_actual',
        'id_estado',
        'fecha_inicalarma',
        'fecha_finalarma',
        'fec_ocurred',
        'fec_cleared',
        'fecha_cierre_periodo_val',
        'fecha_cierre_periodo_utld'
    ];
    // protected $hidden = ['id'];
    // protected $dates = [];
}
