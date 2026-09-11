<?php

namespace App\Modules\MapaPoligonos\Repository;

use App\Modules\Shared\Repository\BaseRepository;

use DB;

class MapaPoligonoRepository extends BaseRepository
{
    public function create($data){
        $model = new MapaPoligono();
        $model->nombre = $this->mapField($data, 'nombre');
        $model->num_cli_potenciales = $this->mapField($data, 'num_cli_potenciales');
        $model->num_emp_potenciales = $this->mapField($data, 'num_emp_potenciales');
        $model->num_dep_edificios = $this->mapField($data, 'num_dep_edificios');
        $model->num_condominios = $this->mapField($data, 'num_condominios');
        $model->tipo_incidencia = $this->mapField($data, 'tipo_incidencia');
        $model->num_cli_afectados = $this->mapField($data, 'num_cli_afectados');
        $model->num_emp_afectados = $this->mapField($data, 'num_emp_afectados');
        $model->observacion = $this->mapField($data, 'observacion');
        $model->poligono = $this->mapPoligonoToSave($data);
        $model->save();
    }

    public function mapPoligonoToSave($data){
        $poligono = $this->mapField($data, 'poligono');
        $poligono = str_replace('(', '[', $poligono);
        $poligono = str_replace(')', ']', $poligono);
        $poligonoArray = $this->mapCoordinates('['.$poligono.']');
        if(count($poligonoArray) > 0){
            $poligonoArray[] = $poligonoArray[0];
            for ($i=0; $i < count($poligonoArray); $i++) { 
                $temp = $poligonoArray[$i][0];
                $poligonoArray[$i][0] = $poligonoArray[$i][1];
                $poligonoArray[$i][1] = $temp;
            }
        }
        return json_encode($poligonoArray);
    }

    public function find($id){
        return DB::table(MapaPoligono::table())->where('id', $id)->first();
    }

    public function get(){
        return DB::table(MapaPoligono::table())
            ->select(
                'id',
                'nombre',
                'num_cli_potenciales',
                'num_emp_potenciales',
                'num_dep_edificios',
                'num_condominios',
                'tipo_incidencia',
                'num_cli_afectados',
                'num_emp_afectados',
                'observacion',
                'poligono',
                'created_at'
            )
            ->get();
    }

    public function getAsPoligonFormat(){
        $poligonos = $this->get();
        $featuresMap = [];
        foreach($poligonos as $row){
            $featuresMap[] = $this->poligonoPresenter($row);
        }
        return [
            'type' => 'FeatureCollection',
            'features' => $featuresMap
        ];
    }

    private function poligonoPresenter($row){
        return [
            'type' => 'Feature',
            'properties' => [
                'id' => $row->id,
                'nombre' => $row->nombre,
                'num_cli_potenciales' => $row->num_cli_potenciales,
                'num_emp_potenciales' => $row->num_emp_potenciales,
                'num_dep_edificios' => $row->num_dep_edificios,
                'num_condominios' => $row->num_condominios,
                'tipo_incidencia' => $row->tipo_incidencia,
                'num_cli_afectados' => $row->num_cli_afectados,
                'num_emp_afectados' => $row->num_emp_afectados,
                'observacion' => $row->observacion,
                'created_at' => $row->created_at,
                'fillColor' => '#dc3545',
                'strokeColor' => '#dc3545'
            ],
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [
                    $this->mapCoordinates($row->poligono)
                ]
            ]
        ];
    }

    private function mapCoordinates($stringCoordPoligono): array {
        // $stringCoordPoligono = str_replace('(', '[', $stringCoordPoligono);
        // $stringCoordPoligono = str_replace(')', ']', $stringCoordPoligono);
        $array = json_decode($stringCoordPoligono);
        if($array === null){
            return [];
        }
        return $array;
    }
}
