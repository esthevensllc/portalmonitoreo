<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class OperacionFijaController
{
    public function view($tracingID)
    {
        $title = "ACCESSO | Fija | Operación Fija";
        $username = backpack_user()->username;
        return view("fija.operacion_fija", compact("tracingID", "title", "username"));
    }

    public function getFuentesHfc(){
        return DB::select("select id, plano, latitud, longitud, tipo_respaldo from operaciones_fija_fuente");
    }
}
