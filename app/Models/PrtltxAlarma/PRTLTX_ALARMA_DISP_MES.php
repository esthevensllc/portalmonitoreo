<?php

namespace App\Models\PrtltxAlarma;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use DB;

class PRTLTX_ALARMA_DISP_MES extends Model
{
    use CrudTrait;

    protected $table = 'PRTLTX_ALARMA_DISP_MES';
    protected $primaryKey = 'ne_name';
    public $incrementing = false;
    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'ne_name',
        'tipo_nodo',
        'anio_mes',
        'disponibilidad',
        'disp_seg',
        'indisp_seg'
    ];
    // protected $hidden = ['id'];
    // protected $dates = [];
    public static function queryDispGroupedByMes(){
        return DB::table('PRTLTX_ALARMA_DISP_MES')
        ->select('anio_mes', DB::Raw('ROUND( (1-(SUM(INDISP_SEG)/SUM(DISP_SEG)))*100, 4) as disponibilidad'))
        ->groupBy('anio_mes');
    }

    public static function queryDispGroupedByMesAndTipoNodo(){
        return DB::table('PRTLTX_ALARMA_DISP_MES')
        ->select('anio_mes', 'tipo_nodo', DB::Raw('ROUND( (1-(SUM(INDISP_SEG)/SUM(DISP_SEG)))*100, 4) as disponibilidad'))
        ->groupBy('anio_mes', 'tipo_nodo');
    }

    public static function queryDispGroupedByMesAndTipoNodoAndNeName(){
        return DB::table('PRTLTX_ALARMA_DISP_MES')
        ->select('anio_mes', 'tipo_nodo', 'ne_name', DB::Raw('ROUND( (1-(SUM(INDISP_SEG)/SUM(DISP_SEG)))*100, 4) as disponibilidad'))
        ->groupBy('anio_mes', 'tipo_nodo', 'ne_name');
    }

    public function whereMesBetween($mesIni, $mesFin){
        $this->query()
        ->where(DB::Raw("concat(anio_mes, 'YYYY-MM')"), '>=', $mesIni)
        ->where(DB::Raw("concat(anio_mes, 'YYYY-MM')"), '<=', $mesFin);
        return $this->query();
    }
}
