<?php

namespace AMovil\Maps\AfectacionEnergia\Controllers;

use AMovil\Maps\AfectacionEnergia\Services\AfectacionEnergiaFinder;
use AMovil\Shared\Domain\Criteria\Criteria;
use AMovil\Shared\Domain\Criteria\Filter;
use AMovil\Shared\Domain\Criteria\Filters;
use AMovil\Shared\Domain\Criteria\Order;
use Illuminate\Http\Request;

class AfectacionEnergiaController
{
    private $finder;

    public function __construct(AfectacionEnergiaFinder $finder)
    {
        $this->finder = $finder;
    }

    public function view($tracingID){
        $title = "ACCESO | Afectación Energia | Afectación Energia";
        $regiones = $this->finder->getRegionFromSites();
        $provincias = $this->finder->getProvinciaFromSites();
        $distritos = $this->finder->getDistritoFromSites();
        $gruposAfectacion = $this->finder->getGruposAfectacionFromSites();
        $gruposSitios = $this->finder->getGruposSitioFromSites();
        $gruposAutonomia = $this->finder->getGruposAutonomiaFromSites();
        $caidosUltMes = $this->finder->getCaidosUltMesFromSites();
        $caidosUlt4Mes = $this->finder->getCaidosUlt4MesFromSites();
        return view("maps.afectacion_energia", compact(
            "tracingID",
            "title",
            "regiones",
            "provincias",
            "distritos",
            "gruposAfectacion",
            "gruposSitios",
            "gruposAutonomia",
            "caidosUltMes",
            "caidosUlt4Mes",
        ));
    }

    public function getCantSitiosCaidos(){
        return $this->finder->getCantSitiosCaidos();
    }

    public function findDetalleEnergiaByUbigeo($ubigeo){
        // $ubigeo = 123;
        $data = $this->finder->findDetalleEnergiaByUbigeo($ubigeo);
        return response()->json(["data" => $data]);
    }

    public function getSites(Request $request){
        $arrayFilters = [
            "region" => $request->get("region"),
            "provincia" => $request->get("provincia"),
            "distrito" => $request->get("distrito"),
            "grupo_afectacion" => $request->get("grupo_afectacion"),
            "grupo_autonomia" => $request->get("grupo_autonomia"),
            "sem_recurrentes" => $request->get("sem_recurrentes"),
            "caido_ult_mes" => $request->get("caido_ult_mes"),
            "caido_ult_4meses" => $request->get("caido_ult_4meses"),
        ];

        $filters = Filters::fromValues([]);
        foreach($arrayFilters as $keyFilter => $filterValue){
            $operator = "eq";
            if($keyFilter === "sem_recurrentes"){
                $operator = "gte";
            }
            if($filterValue !== null){
                $filters->add(Filter::fromValues(["field" => $keyFilter, "operator" => $operator, "value" => $filterValue]));
            }

        }
        $criteria = new Criteria($filters, Order::none(), null, null);
        return $this->finder->getSites($criteria);
    }
}
