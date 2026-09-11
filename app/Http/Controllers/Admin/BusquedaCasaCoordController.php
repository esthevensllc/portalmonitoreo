<?php

namespace App\Http\Controllers\Admin;

class BusquedaCasaCoordController
{
    public function view($tracingID)
    {
        $title = "FACTIBILIDAD | Factiblidad | Buscar Dirección";
        $username = backpack_user()->username;
        return view("factibilidad_fija.busqueda_casa_coordenadas", compact("tracingID", "title", "username"));
    }
}
