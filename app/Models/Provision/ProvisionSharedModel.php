<?php

namespace App\Models\Provision;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProvisionSharedModel extends Model
{
    use CrudTrait;

    protected $table = "pso_type";
    public $primaryKey = PSO_BASE_TABLE_ID;
    protected $fillable = PSO_BASE_TABLE_FIELDS;
    public $timestamps = false;
    public $incrementing = false;

    public function newQuery()
    {
        $query = PSO_BASE_TABLE_QUERY;
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
