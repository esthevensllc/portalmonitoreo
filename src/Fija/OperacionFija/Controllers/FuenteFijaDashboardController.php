<?php

namespace AMovil\Fija\OperacionFija\Controllers;

use AMovil\Fija\OperacionFija\Services\FuenteFijaFinder;

class FuenteFijaDashboardController
{
    private $finder;

    public function __construct(
        FuenteFijaFinder $finder
    ){
        $this->finder = $finder;
    }

    public function view($tracingID)
    {
        $title = "ACCESSO | Fija | Dashboard Operación Fija";
        $username = backpack_user()->username;
        return view("fija.operacion_fija_dashboard", compact("tracingID", "title", "username"));
    }

    public function getNumTipoRespaldoByRegion(){
        $data = $this->finder->getNumTipoRespaldoByRegion();
        return response()->json($data);
    }

    public function getNumTipoRespaldoByMonth(){
        $data = $this->finder->getNumTipoRespaldoByMonth();
        return response()->json($data);
    }
}
