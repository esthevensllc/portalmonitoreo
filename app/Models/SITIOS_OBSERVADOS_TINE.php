<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SITIOS_OBSERVADOS_TINE extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'PRG_SITIOS_OBSERVADOS_TINE';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    //protected $guarded = ['site_name'];
    protected $fillable = ['site_name'.'bcf_address','departamento','tipo_obs_fitel','tecnologia','grupo_motivo','detalle_motivo','accion_solucion','responsable_ingrad','personal_ingrad','created_at','updated_at','estado','solution_at','mes','anio'];
    // protected $hidden = [];
    // protected $dates = [];
    public $incrementing = true; 

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getMotivo()
    {
        if($this->grupo_motivo!=null && $this->grupo_motivo>0 && $this->grupo_motivo<9 && is_numeric($this->grupo_motivo)){
            $motivo = DB::table("PRG_SITIOS_OBSERVADOS_MOTIVO")->select("nombre")->where("estado",1)->where("id",$this->grupo_motivo)->first();
            return $motivo->nombre;
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
