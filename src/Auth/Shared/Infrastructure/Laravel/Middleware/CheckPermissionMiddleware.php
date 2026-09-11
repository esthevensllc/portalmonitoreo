<?php

namespace AMovil\Auth\Shared\Infrastructure\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class CheckPermissionMiddleware
{
    use \App\Traits\DB\ProcedureTrait;

    public function handle(Request $request, Closure $next, ...$guards){
        $tracingId = $request->route("id_tracing");
        $tracingId = $tracingId ?? $request->route("tracingID");
        $routeDetails = $request->route()->getAction();
        $tracingId = $tracingId ?? (array_key_exists("tracingId", $routeDetails) ? $routeDetails['tracingId'] : null);
        $tracingId = $tracingId ?? $request->get("tracingID");
        $menuId = $request->route()->getAction()['menuId'] ?? null;

        if(!$this->hasAccess($tracingId, $menuId)){
            if ($request->acceptsJson()) {
                return response()
                ->json([
                    "message" => "El usuario no tiene permisos para acceder al módulo"
                ], 403);
            } else {
                return abort(403, 'El usuario no tiene permisos para acceder al módulo');
            }
        }

        return $next($request);
    }

    protected function hasAccess($tracingId, $menuId): bool {
        if($tracingId === null || $menuId === null){
            return false;
        }
        $user = backpack_auth()->user();
        $roles = $user->roles;

        foreach($roles as $row){
            if ($this->roleHasAccess($row->perfil, $tracingId, $menuId)) {
                return true;
            }
        }
        return false;
    }

    private function roleHasAccess($rolId, $tracingId, $menuId): bool {
        $proflieAccessFather = $this->getADMTypeByFatherIdTypeName(3, $rolId);
		$menuListFather = array_filter($proflieAccessFather, function ($itm) use ($tracingId) {
			return $itm["TYPE_DESCRIPTION"] == strval($tracingId);
		});
        if (count($menuListFather) === 0) {
            return false;
        }
		$menuListFather = end($menuListFather);
		$menuADMList = $this->getADMTypeByFatherId($menuListFather["ID_TYPE"]);
		// if (!in_array(strval($menuId), array_column($menuADMList, "TYPE_NAME"))) {
		// 	$menuID = 0;
		// }
        $menuList = $this->getTracingMenu($tracingId);
        $menuList = array_filter($menuList, function ($menuItmSlc) use ($menuADMList) {
			return in_array(strval($menuItmSlc["ID_TYPE"]), array_column($menuADMList, "TYPE_NAME"));
		});

        foreach($menuADMList as $row){
            if (strval($menuId) === $row['TYPE_NAME']) {
                return true;
            }
        }
        return false;
    }

    private function getADMTypeByFatherIdTypeName($fatherId, $typeName){
        return $this->executeProcedure("begin PK_PADM_PROCESO.SP_GET_TYPEBYFATHERIDTYPENAME(:fatherId, :typeName, :resultado); end;", [
            "fatherId" => ["value" => $fatherId, "type" => PDO::PARAM_INT],
            "typeName" => ["value" => $typeName, "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
    }

    private function getADMTypeByFatherId($fatherId){
        return $this->executeProcedure("begin PK_PADM_PROCESO.SP_GET_TYPEBYFATHERID(:fatherId, :resultado); end;", [
            "fatherId" => ["value" => $fatherId, "type" => PDO::PARAM_INT],
            "resultado" => [],
        ]);
    }

    private function getTracingMenu($tracingId){
        return $this->executeProcedure("begin PSO_OSSPORTAL.SP_GET_TRACINGMENU(:tracingId, :resultado); end;", [
            "tracingId" => ["value" => $tracingId, "type" => PDO::PARAM_INT],
            "resultado" => [],
        ]);
    }
}
