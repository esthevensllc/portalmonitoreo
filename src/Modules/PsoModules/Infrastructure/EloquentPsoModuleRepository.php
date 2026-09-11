<?php

namespace AMovil\Modules\PsoModules\Infrastructure;

use AMovil\Modules\PsoModules\Domain\PsoModuleRepository;
use Illuminate\Support\Facades\DB;

class EloquentPsoModuleRepository implements PsoModuleRepository
{
    public function findTitleByIdTracingAndModuleType(int $id_tracing, int $type_id)
    {
        $trac_title = DB::table("pso_type t")
        ->select("tt.type_name as main_module", "trac.tracing_description as module", "t2.type_name as module_type")
        ->join("pso_seguimientone trac", function($join){
            $join->on("trac.id_tracing", "=", "t.type_name")
            ->where("trac.status", "=", DB::raw('1'));
        })
        ->join("pso_type tt", function($join) {
            $join->on("tt.id_type", "=", "trac.id_type")
            ->where("tt.status", "=", DB::raw('1'));
        })
        ->join("pso_type t1", function($join) use ($type_id) {
            $join->on("t1.type_father", "=", "t.id_type")
            ->where("t1.type_name", "=", DB::raw("to_char({$type_id})"))
            //->where("t1.type_name", "=", DB::raw('to_char(16)'))
            ->where("t1.status", "=", DB::raw('1'));
        })
        ->join("pso_type t2", function($join){
            $join->on("t2.id_type", "=", "t1.type_name")
            ->where("t2.status", "=", DB::raw('1'));
        })
        ->where("t.type_name", $id_tracing)
        //->where("t.type_name", "23")
        ->where("t.status", 1)
        ->first();
        return $trac_title;
    }
}
