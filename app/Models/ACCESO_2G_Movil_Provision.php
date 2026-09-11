<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ACCESO_2G_Movil_Provision extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'LECTURA.VW_REPORTE_2GHW_LAC';
    protected $primaryKey = 'trxname';
    // public $timestamps = false;
    //protected $guarded = ['trxname'];
    protected $fillable = ['bsc_name',
    'mbts_name',
    'btsname',
    'site_name',
    'site_address',
    'segment_name',
    'cellid',
    'cellname',
    'ci',
    'lac',
    'rac',
    'trxid',
    'trxname',
    'freq',
    'trxno',
    'idtype',
    'ismainbcch',
    'istmptrx',
    'gtrxgroupid',
    'tch_total',
    'pdtch_total',
    'mcc',
    'mnc',
    'ncc',
    'bcc',
    'exttp',
    'iuotp',
    'flexmaio',
    'csvsp',
    'csdsp',
    'pshpsp',
    'pslpsvp',
    'bspbcchblks',
    'bspagblksres',
    'bsprachblks',
    'type',
    'opname',
    'vipcell',
    'mocncmcell',
    'hybhifreqbandsupport',
    'glocellid',
    'nsei',
    'bvci',
    'sysdesc',
    'syscontact',
    'syslocation',
    'sysservices',
    'admstat',
    'distrito',
    'provincia',
    'departamento',
    'sub_region',
    'cluster_name',
    'actstatus',
    'direccion',
    'modelo_rru',
    'manufacturerdata'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

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
