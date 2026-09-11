<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ACCESO_5G_Movil_Provision extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'LECTURA.VW_REPORTE_5GHW_TAC';
    protected $primaryKey = 'mbts';
    // public $timestamps = false;
    //protected $guarded = ['trxname'];
    public $incrementing = false;
    protected $fillable = ["gnodob",
    "gnodob_address",
    "distintivo",
    "sector_name",
    "gnbid",
    "mbts",
    "nrducellid",
    "region",
    "sub_region",
    "departamento",
    "provincia",
    "distrito",
    "latitud",
    "longitud",
    "txrxmode",
    "cluster_name",
    "tac",
    "cellactivestate",
    "ulbandwidth",
    "ulnarfcn",
    "celladminstate",
    "cellid",
    "cellradius",
    "cyclicprefixlength",
    "dlbandwidth",
    "dlnarfcn",
    "duplexmode",
    "frequencyband",
    "gnodebfunctionname",
    "lampsitecellflag",
    "logicalrootsequenceindex",
    "nrducellactivestate",
    "nrducellname",
    "physicalcellid",
    "prachfreqstartposition",
    "rannotificationareaid",
    "sib1period",
    "slotassignment",
    "slotstructure",
    "ssbdescmethod",
    "ssbfreqpos",
    "ssbperiod",
    "subcarrierspacing",
    "taoffset",
    "trackingareaid",
    "direccion"];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    
    public function getId(){
        return $this->mbts;
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
