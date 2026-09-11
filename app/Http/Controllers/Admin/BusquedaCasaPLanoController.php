<?php

namespace App\Http\Controllers\Admin;

use App\Traits\DB\ProcedureTrait;
use PDO;

class BusquedaCasaPLanoController
{
    use ProcedureTrait;

    public function view($tracingID)
    {
        $title = "FACTIBILIDAD | Ventas | Buscar Dirección";
        $username = backpack_user()->username;
        return view("factibilidad_fija.busqueda_casa_plano", compact("tracingID", "title", "username"));
    }

    public function gePlanos(?string $plano)
    {
        if($plano === null){
            return response()->json([]);
        }
        if(strlen($plano) < 4){
            return response()->json([]);
        }
        $username = backpack_user()->username;
        $procedure = "begin pkg_inv_factibilidad_venta.sp_muestraplanoedificio(:plano, :cuentaUser, :application, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "cuentaUser" => ["value" => $username, "type" => PDO::PARAM_STR],
            "application" => ["value" => "PMONITOREO", "type" => PDO::PARAM_STR],
            "plano" => ["value" => $plano, "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]["JSON_EDIFICIOS"] = json_decode($data[$i]["JSON_EDIFICIOS"]);
        }
        return $data;
    }
}
