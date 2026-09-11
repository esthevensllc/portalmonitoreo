<?php

namespace App\Http\Controllers\Admin;

use App\Traits\DB\ProcedureTrait;
use Illuminate\Http\Request;
use PDO;

class BusquedaEdificioController
{
    use ProcedureTrait;
    
    public function view($tracingID)
    {
        $title = "FACTIBILIDAD | Ventas | Busqueda Edificio";
        $username = backpack_user()->username;
        $ubigeos = $this->getUbigeos();
        return view("factibilidad_fija.busqueda_edificio", compact("tracingID", "title", "username", "ubigeos"));
    }

    public function getUbigeos()
    {
        $username = backpack_user()->username;
        $procedure = "begin pkg_inv_factibilidad_venta.sp_catalogo_ubigeo_edificios(:cuentaUser, :application, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "cuentaUser" => ["value" => $username, "type" => PDO::PARAM_STR],
            "application" => ["value" => "PMONITOREO", "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
        return $data;
    }

    public function getEdificiosByUbigeo($ubigeo)
    {
        $username = backpack_user()->username;
        $procedure = "begin pkg_inv_factibilidad_venta.sp_edificios_ubigeo(:ubigeo, :cuentaUser, :application, :resultado); end;";
        $data = $this->executeProcedure($procedure, [
            "cuentaUser" => ["value" => $username, "type" => PDO::PARAM_STR],
            "application" => ["value" => "PMONITOREO", "type" => PDO::PARAM_STR],
            "ubigeo" => ["value" => $ubigeo, "type" => PDO::PARAM_STR],
            "resultado" => [],
        ]);
        return $data;
    }

}
