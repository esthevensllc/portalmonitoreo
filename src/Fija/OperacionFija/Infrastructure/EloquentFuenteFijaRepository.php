<?php

namespace AMovil\Fija\OperacionFija\Infrastructure;

use AMovil\Fija\OperacionFija\Domain\FuenteFija;
use AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository;
use Illuminate\Support\Facades\DB;

class EloquentFuenteFijaRepository implements FuenteFijaRepository
{
    public function findById($id): ?FuenteFija
    {
        $model = FuenteFijaModel::find($id);
        if($model === null){
            return null;
        }
        return $model->toDomain();
    }

    public function exists($id): bool
    {
        $model = FuenteFijaModel::find($id);
        return $model !== null;
    }

    public function create(FuenteFija $fuente): FuenteFija
    {
        $model = FuenteFijaModel::fromDomain($fuente);
        $model->save();
        return $model->toDomain();
    }

    public function update(FuenteFija $fuente): void
    {
        $model = FuenteFijaModel::fromDomain($fuente);
        $model->save();
    }

    public function updateImages(FuenteFija $fuente): void
    {
        $model = FuenteFijaModel::fromDomain($fuente);
        $model->updateImages($fuente->getImages());
    }

    public function delete($id): void
    {
        $model = FuenteFijaModel::find($id);
        $model->delete();
    }

    public function get(){
        return FuenteFijaModel::get();
    }

    public function getNumTipoRespaldoByRegion(){
        $query = "SELECT nvl(region, 'NO DEFINIDO') region,
        CASE WHEN TIPO_RESPALDO LIKE '%MULTISWITCH%' THEN 'MULTISWITCH' ELSE NVL(TIPO_RESPALDO, 'NO DEFINIDO') END TIPO_RESPALDO,
        count(*) counter
        FROM operaciones_fija_fuente
        GROUP BY nvl(region, 'NO DEFINIDO'),
        CASE WHEN TIPO_RESPALDO LIKE '%MULTISWITCH%' THEN 'MULTISWITCH' ELSE NVL(TIPO_RESPALDO, 'NO DEFINIDO') END";
        $result = DB::select($query);

        $tiposRespaldoDefault = ["region" => null];
        foreach($result as $row){
            $key = $row->tipo_respaldo;
            if(!array_key_exists($key, $tiposRespaldoDefault)){
                $tiposRespaldoDefault[$key] = 0;
            }
        }

        $resultByRegion = [];
        foreach($result as $row){
            $key = $row->region;
            if(!array_key_exists($key, $resultByRegion)){
                $resultByRegion[$key] = $tiposRespaldoDefault;
                $resultByRegion[$key]["region"] = $key;
            }
            $resultByRegion[$key][$row->tipo_respaldo] = $row->counter;
        }
        $result = [];
        foreach($resultByRegion as $row){
            $result[] = $row;
        }
        return $result;
    }

    public function getNumTipoRespaldoByMonth()
    {
        $query = "SELECT trunc(fecha_manto, 'mm') fecha,
        CASE WHEN TIPO_RESPALDO LIKE '%MULTISWITCH%' THEN 'MULTISWITCH' ELSE NVL(TIPO_RESPALDO, 'NO DEFINIDO') END TIPO_RESPALDO,
        count(*) counter
        FROM operaciones_fija_fuente
        WHERE fecha_manto is not null
        GROUP BY trunc(fecha_manto, 'mm'),
        CASE WHEN TIPO_RESPALDO LIKE '%MULTISWITCH%' THEN 'MULTISWITCH' ELSE NVL(TIPO_RESPALDO, 'NO DEFINIDO') END
        ORDER BY trunc(fecha_manto, 'mm')";
        $result = DB::select($query);
        return $result;
    }
}
