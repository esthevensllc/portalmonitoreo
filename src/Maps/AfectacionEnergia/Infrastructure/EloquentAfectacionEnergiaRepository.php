<?php

namespace AMovil\Maps\AfectacionEnergia\Infrastructure;

use AMovil\Maps\AfectacionEnergia\Domain\AfectacionEnergiaRepository;
use AMovil\Shared\Domain\Criteria\Criteria;
use AMovil\Shared\Infrastructure\Eloquent\EloquentCriteriaConverter;
use AMovil\Shared\Infrastructure\Eloquent\EloquentCriteriaConverter2;
use Illuminate\Support\Facades\DB;

class EloquentAfectacionEnergiaRepository implements AfectacionEnergiaRepository
{
    public function getCantSitiosCaidos(){
        return DB::table('energ_mapa_dist_gen')->get();
    }

    public function findDetalleEnergiaByUbigeo($ubigeo)
    {
        $data = DB::table('energ_mapa_dist_data_gen')->where("ubigeo", $ubigeo)->first();
        if($data !== null){
            $data->grupo_horas = DB::table('energ_mapa_data_det_sitio_grupo')->where("ubigeo", $ubigeo)->get();
            $data->sitios = DB::table('energ_mapa_data_det_sitio')->where("ubigeo", $ubigeo)->get();
        }
        return $data;
    }

    public function getRegionFromSites(){
        return DB::table("ENERG_MAPA_DET_SITIO_COORD")
        ->selectRaw("region as label")
        ->groupBy("region")
        ->get();
    }

    public function getProvinciaFromSites(){
        return DB::table("ENERG_MAPA_DET_SITIO_COORD")
        ->selectRaw("provincia id, provincia label, region")
        ->groupBy("provincia", "region")
        ->orderBy('provincia')
        ->get();
    }

    public function getDistritoFromSites(){
        return DB::table("ENERG_MAPA_DET_SITIO_COORD")
        ->selectRaw("distrito id, distrito label, provincia, region")
        ->groupBy("distrito", "provincia", "region")
        ->orderBy('distrito')
        ->orderBy('distrito')
        ->get();
    }

    public function getGruposAfectacionFromSites(){
        return DB::table("m_grupo_porc_afectacion")
        ->selectRaw("GRUPO_PORC_AFECTACION label")
        ->orderBy('GRUPO_PORC_AFECTACION')
        ->get();
    }

    public function getGruposSitioFromSites(){
        return DB::table("rss_grupo_sitios_oym")
        ->selectRaw("grupo_sitios label")
        ->orderBy('grupo_sitios')
        ->get();
    }
    
    public function getGruposAutonomiaFromSites(){
        return DB::table("m_grupo_autonomia_max")
        ->selectRaw("grupo_autonomia label")
        ->orderBy('grupo_autonomia')
        ->get();
    }

    public function getCaidosUltMesFromSites(){
        return DB::table('m_caido_ult_mes')
        ->selectRaw("caido_ult_mes label")
        ->orderBy('caido_ult_mes', 'desc')
        ->get();
    }
    
    public function getCaidosUlt4MesFromSites(){
        return DB::table('m_caido_ult_4_meses')
        ->selectRaw("caido_ult_4meses label")
        ->orderBy('caido_ult_4meses', 'desc')
        ->get();
    }

    public function getSites(Criteria $criteria)
    {
        $converter = new EloquentCriteriaConverter2(DB::table('ENERG_MAPA_DET_SITIO_COORD'), $criteria, [
            "region" => null,
            "provincia" => null,
            "distrito" => null,
            "grupo_afectacion" => 'GRUPO_PORC_AFECTACION',
            "grupo_sitios" => null,
            "grupo_autonomia" => null,
            "sem_recurrentes" => 'CANT_SEMANAS_CC_ULT_4M',
            "caido_ult_mes" => null,
            "caido_ult_4meses" => null,
        ]);
        return $converter->convert()->get();
    }

}
