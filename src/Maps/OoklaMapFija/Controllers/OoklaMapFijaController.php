<?php

namespace AMovil\Maps\OoklaMapFija\Controllers;

use AMovil\Maps\OoklaMapFija\Services\OoklaMapFijaFinder;
use DateTime;

class OoklaMapFijaController
{
    private $finder;

    public function __construct(OoklaMapFijaFinder $finder)
    {
        $this->finder = $finder;
    }

    public function view($tracingID)
    {
        $title = "OOKLA | OOKLA | Mapa Ookla Fija";
        $username = backpack_user()->username;
        $months = $this->finder->getKpiMonths();
        $kpiList = $this->finder->getKpiList();
        $operators = $this->finder->getKpiOperators();
        return view("ookla.ookla_map_fija", compact("tracingID", "title", "username", "months", "kpiList", "operators"));
    }

    public function getByMonthAndOperatorAndKpiname($month, $operator, $kpiName){
        $dtMonth = DateTime::createFromFormat("Y-m-d", $month);
        if ($dtMonth === false) {
            return response()->json(["message" => "El mes no es válido"], 400);
        }
        $reponse = $this->finder->getByMonthAndOperatorAndKpiname($dtMonth, $operator, $kpiName);
        if ($reponse->fails()) {
            return response()->json($reponse->errors(), 400);
        }
        return response()->json(["data" => $reponse->data()]);
    }

    public function getKpiMonths(){
        $data = $this->finder->getKpiMonths();
        return response()->json(["data" => $data]);
    }

    public function getKpiOperators(){
        $data = $this->finder->getKpiOperators();
        return response()->json(["data" => $data]);
    }

    public function getKpiLastMonths(){
        $data = $this->finder->getKpiLastMonths();
        return response()->json($data);
    }
}
