<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class DRIVE_TEST extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'PRG_MTV_DTEST_WEB';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['dia', 'idclient', 'idsession', 'imsi', 'job_name', 'tech_dl', 'throughput_dl_kbps', 'umbral_dl_kbps', 'throughput_ul_kbps', 'umbral_ul_kbps', 'delay', 'jitter', 'avg_delay', 'latitude', 'longitude', 'idlac', 'idcell_dl', 'cellname_dl', 'idcell_ul', 'cellname_ul', 'ubigeo', 'n_tests', 'n_tests_down', 'mstras_validas'];
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
