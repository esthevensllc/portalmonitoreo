<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ACCESO_4G_Tdd_Provision extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lectura.vw_reporte_4ghw_tac_tdd';
    protected $primaryKey = 'cellname';
    // public $timestamps = false;
    //protected $guarded = ['trxname'];
    protected $fillable = ['enodob_name',
    'enodob_address',
    'sector_name',
    'cell_id',
    'enodob_id',
    'cellid',
    'departamento',
    'provincia',
    'distrito',
    'sub_region',
    'tac',
    'localcellid',
    'objid',
    'additionalspectrumemission',
    'aircellflag',
    'cellactivestate',
    'celladminstate',
    'cellname',
    'cellradius',
    'cellspecificoffset',
    'cpricompression',
    'crsportnum',
    'csgind',
    'customizedbandwidthcfgind',
    'dlbandwidth',
    'dlcyclicprefix',
    'dlearfcn',
    'emergencyareaidcfgind',
    'enodebfunctionname',
    'fddtddind',
    'freqband',
    'highspeedflag',
    'multirrucellflag',
    'phycellid',
    'preamblefmt',
    'qoffsetfreq',
    'rootsequenceidx',
    'txrxmode',
    'uepowermaxcfgind',
    'ulbandwidth',
    'ulcyclicprefix',
    'ulearfcncfgind',
    'workmode',
    'userlabel',
    'eucellstandbymode',
    'specialsubframepatterns',
    'subframeassignment',
    'cnopsharinggroupid',
    'csirsperiod',
    'freqpriorityforanr',
    'intrafreqanrind',
    'crsportmap',
    'mbts_name',
    'cluster_name',
    'latitud',
    'longitud',
    'modelo_rru',
    'manufacturerdata',
    'direccion',
    'mme_usn_primario',
    'mme_usn_secundario',
    'referencesignalpwr',
    'ltecellindex'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getId(){
        return $this->cellname;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
