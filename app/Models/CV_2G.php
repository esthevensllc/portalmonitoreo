<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CV_2G extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'PRG_CV_MULTAS_2G';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    //protected $fillable = [];
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
            if($region == null){
                return "-";
            }else{
                return $region->region;
            }
        }else{
            return "-";
        }
    }

    public function getDepartamento()
    {
        if($this->cc!=null){
            $departamento = DB::table("PRG_CV_MULTAS_GENERAL")->select("departamento")->where("cc",$this->cc)->first();
            if($departamento == null){
                return "-";
            }else{
                return $departamento->departamento;
            }            
        }else{
            return "-";
        }
    }

    public function getDistrito()
    {
        if($this->cc!=null){
            $distrito = DB::table("PRG_CV_MULTAS_GENERAL")->select("distrito")->where("cc",$this->cc)->first();
            if($distrito == null){
                return "-";
            }else{
                return $distrito->distrito;
            }            
        }else{
            return "-";
        }
    }

    public function getCCPP()
    {
        if($this->cc!=null){
            $ccpp = DB::table("PRG_CV_MULTAS_GENERAL")->select("ccpp")->where("cc",$this->cc)->first();
            if($ccpp == null){
                return "-";
            }else{
                return $ccpp->ccpp;
            }
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
