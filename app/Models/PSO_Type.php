<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PSO_Type extends Model
{
    protected $table = 'pso_type';
    public $primaryKey = 'id_type';
    public $timestamps = false;
    public $incrementing = false;

    public static function getByTypeFather($type_father){
        return self::where('type_father', $type_father)->where('status', 1)->get();
    }
    
    public static function getByTypeName($type_name){
        return self::where('type_name', $type_name)->where('status', 1)->get();
    }

    public static function builderByTypeName_TypeFather($type_name, $type_father){
        return self::where('type_father', $type_father)->where('type_name', $type_name)->where('status', 1);
    }

}
