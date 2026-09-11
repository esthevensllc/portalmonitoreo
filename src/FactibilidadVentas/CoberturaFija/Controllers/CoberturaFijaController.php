<?php

namespace AMovil\FactibilidadVentas\CoberturaFija\Controllers;

use AMovil\FactibilidadVentas\CoberturaFija\Services\CoberturaFijaFinder;
use AMovil\Shared\Infrastructure\Laravel\Controllers\CriteriaRequest;
use Illuminate\Http\Request;

class CoberturaFijaController
{
    private $finder;

    public function __construct(CoberturaFijaFinder $finder)
    {
        $this->finder = $finder;
    }

    public function view($tracingID)
    {
        $title = "FACTIBILIDAD | VENTAS | COBERTURA FIJA";
        return view("factibilidad_fija.cobertura_fija", compact("tracingID", "title"));
    }

    public function getCoberturaFtth($idTracing, Request $request){
        // $criteriaRequest = new CriteriaRequest($request);
        $polygons = $this->finder->getPolygonsFtthByTipo($request->get("tipo"));
        // return base64_encode(json_encode($polygons));
        return response()->json($polygons);
    }

    public function findPolygonFtth($idTracing, $id){
        $response = $this->finder->findPolygonFtth($id);
        return response()->json($response->toArray(), $response->passes() ? 200 : 404);
    }

    public function getCoberturaHfc($idTracing){
        // $criteriaRequest = new CriteriaRequest($request);
        $polygons = $this->finder->getPolygonsHfc();
        return response()->json($polygons);
    }

    public function findPolygonHfc($idTracing, $id){
        $response = $this->finder->findPolygonHfc($id);
        return response()->json($response->toArray(), $response->passes() ? 200 : 404);
    }
}
