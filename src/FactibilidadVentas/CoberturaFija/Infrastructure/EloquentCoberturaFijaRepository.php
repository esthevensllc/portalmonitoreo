<?php

namespace AMovil\FactibilidadVentas\CoberturaFija\Infrastructure;

use AMovil\FactibilidadVentas\CoberturaFija\Domain\CoberturaFijaRepository;
use AMovil\Shared\Domain\Criteria\Criteria;
use AMovil\Shared\Infrastructure\Eloquent\EloquentCriteriaConverter2;
use Illuminate\Support\Facades\DB;

class EloquentCoberturaFijaRepository implements CoberturaFijaRepository
{
    public function getPolygonsFtthByTipo(string $tipo) {
        return DB::table("GIS_WS_COBERTURA_H_FTTH")
        ->selectRaw("id, sdo_util.to_geojson(geom) polygon")
        ->whereRaw("(case when idplano like '%S' then 'OVERLAP'
            when idplano like '%-R' then 'OVERLAP-EDIFICIO'
            when idplano like '%-O' or idplano like '%-F' or idplano like '%-X' then 'RESIDENCIAS-OFICINA'
            end) = ?", [$tipo])
        ->get();
    }
    
    public function getPolygonsHfc() {
        return DB::table("GIS_WS_COBERTURA_H_HFC")
        ->selectRaw("id, sdo_util.to_geojson(geom) polygon")
        ->get();
    }

    public function findPolygonFtthById($id)
    {
        return DB::table("GIS_WS_COBERTURA_H_FTTH")
        ->selectRaw("ID, NOMBRE, NODO_OPTICO, IDENTIFICA_RED, HHP, ESTADO,
        CARACTERISTICA_CLARO, FECHA_CARGA, IDPAP, IDPLANO, CLASE_PROYECTO,
        TECNOLOGIA, CMTS_OLT, F_LIBERADO, TIPO_MODIFI, FLAG, SGA_DISTRITO,
        SGA_PROVINCIA")
        ->where("id", $id)
        ->first();
    }

    public function findPolygonHfcById($id)
    {
        return DB::table("GIS_WS_COBERTURA_H_HFC")
        ->selectRaw("ID, NOMBRE, NODO_OPTICO, IDENTIFICA_RED, HHP, ESTADO,
        CARACTERISTICA_CLARO, FECHA_CARGA, IDPAP, IDPLANO, CLASE_PROYECTO,
        TECNOLOGIA, CMTS_OLT, F_LIBERADO, TIPO_MODIFI, FLAG, SGA_DISTRITO,
        SGA_PROVINCIA")
        ->where("id", $id)
        ->first();
    }
}
