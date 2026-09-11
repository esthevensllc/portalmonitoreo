<?php

namespace AMovil\Auth\Shared\Infrastructure\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckPermissionGraphMiddleware extends CheckPermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$guards){
        $row = $this->getTracingIdAndMenuId($request->get('secSepID'));
        if ($row === null) {
            return $this->return403($request);
        }
        $tracingId = $row->tracing_id;
        $menuId = $row->menu_id;
        if(!$this->hasAccess($tracingId, $menuId)){
            return $this->return403($request);
        }

        return $next($request);
    }

    private function getTracingIdAndMenuId($secSepID){
        $query = "SELECT f.type_name tracing_id, e.type_name menu_id FROM pso_type a
        INNER JOIN pso_type b ON b.id_type = a.type_father AND b.STATUS = 1
        INNER JOIN pso_type c ON c.id_type = b.type_father AND c.STATUS = 1
        INNER JOIN pso_type d ON d.id_type = c.type_father AND d.STATUS = 1
        INNER JOIN pso_type e ON e.id_type = d.type_father AND e.STATUS = 1
        INNER JOIN pso_type f ON f.id_type = e.type_father AND f.STATUS = 1
        WHERE a.id_type = :id_type";

        $result = DB::select($query, ["id_type" => $secSepID]);
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    private function return403(Request $request){
        if ($request->acceptsJson()) {
            return response()
            ->json([
                "message" => "El usuario no tiene permisos para acceder al módulo"
            ], 403);
        } else {
            return abort(403, 'El usuario no tiene permisos para acceder al módulo');
        }
    }
}
