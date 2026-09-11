<?php

namespace App\Models\Alarmas;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class AlarmaSharedModel extends Model
{
    use CrudTrait;

    protected $table = "pso_type";
    public $primaryKey = PSO_BASE_TABLE_ID;
    protected $fillable = PSO_BASE_TABLE_FIELDS;
    public $timestamps = false;
    public $incrementing = false;
    
    
    public function newQuery()
    {
        /*\Yajra\Oci8\Query\OracleBuilder::class;
        $connection = DB::connection();
        new Builder($connection);
        return DB::table("pseg_type");*/
        //dd(gettype(DB::table("dual")->first()));
        //$result = DB::table(DB::raw("(".PSO_BASE_TABLE_QUERY.")"))->limit(10)->get();
        //dd($result);
        $query = PSO_BASE_TABLE_QUERY;
        //$builder = DB::table(DB::raw("(".PSO_BASE_TABLE_QUERY.")"));
        $builder = DB::table(DB::raw($query['from']))
        ->selectRaw($query['select']);
        if($query['where'] !== ""){
            $builder->where(function($subquery) use ($query) {
                $subquery->whereRaw($query['where']);
            });
        }
        return new CustomBuilder($builder);
    }

}
