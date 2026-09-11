<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CV_3G extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'PRG_CV_MULTAS_3G';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    //protected $fillable = ['ubigeo','periodo_2020_01','periodo_2020_02','periodo_2021_01','multa_umts','periodo_multa','estado'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getRegion()
    {
        if($this->cc!=null){
            $region = DB::table("PRG_CV_MULTAS_GENERAL")->select("region")->where("cc",$this->cc)->first();
            return $region->region;
        }else{
            return "-";
        }
    }

    public function getDepartamento()
    {
        if($this->cc!=null){
            $departamento = DB::table("PRG_CV_MULTAS_GENERAL")->select("departamento")->where("cc",$this->cc)->first();
            return $departamento->departamento;
        }else{
            return "-";
        }
    }

    public function getDistrito()
    {
        if($this->cc!=null){
            $distrito = DB::table("PRG_CV_MULTAS_GENERAL")->select("distrito")->where("cc",$this->cc)->first();
            return $distrito->distrito;
        }else{
            return "-";
        }
    }

    public function getCCPP()
    {
        if($this->cc!=null){
            $ccpp = DB::table("PRG_CV_MULTAS_GENERAL")->select("ccpp")->where("cc",$this->cc)->first();
            return $ccpp->ccpp;
        }else{
            return "-";
        }
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
