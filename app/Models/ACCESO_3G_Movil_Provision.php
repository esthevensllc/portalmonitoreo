<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ACCESO_3G_Movil_Provision extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'LECTURA.VW_REPORTE_3GHW_LAC';
    protected $primaryKey = 'cellname';
    // public $timestamps = false;
    //protected $guarded = ['trxname'];
    public $incrementing = false;
    protected $fillable = ["rncid",
    "rnc_name",
    "mbts_name",
    "nodob_name",
    "nodob_address",
    "sector_name",
    "cellname",
    "locell",
    "bandind",
    "uarfcnuplinkind",
    "uarfcnuplink",
    "uarfcndownlink",
    "cfgracind",
    "cnopgrpindex",
    "maxtxpower",
    "tcell",
    "ninsyncind",
    "noutsyncind",
    "trlfailure",
    "dltpcpattern01count",
    "pscrambcode",
    "txdiversityind",
    "spgid",
    "lac",
    "rac",
    "sac",
    "cio",
    "nodebname",
    "srn",
    "sn",
    "ssn",
    "vplimitind",
    "dssflag",
    "dsssmallcovmaxtxpower",
    "cchcnopindex",
    "dpgid",
    "sysdesc",
    "syscontact",
    "syslocation",
    "sysservices",
    "supportrncinpool",
    "loadsharingtype",
    "redundancytype",
    "cellid",
    "actstatus",
    "hspdschcodenum",
    "departamento",
    "provincia",
    "distrito",
    "sub_region",
    "cluster_name",
    "modelo_rru",
    "manufacturerdata",
    "direccion"];
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
