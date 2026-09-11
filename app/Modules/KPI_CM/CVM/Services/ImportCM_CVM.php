<?php

namespace App\Modules\KPI_CM\CVM\Services;

use App\Modules\KPI_CM\Actividades\Repository\CM_Actividad;
use App\Modules\KPI_CM\CVM\Entity\SaveCollectionValidator;
use App\Modules\KPI_CM\CVM\Repository\CM_CVM_Repository;
use App\Modules\KPI_CM\Estados\Repository\CM_Estado;
use App\Modules\Shared\Import\BaseImport;
use App\Modules\Shared\Services\Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;

class ImportCM_CVM extends BaseImport implements ToCollection
{
    private $validator;
    private $repo;
    private $response;

    public function __construct()
    {
        $this->repo = new CM_CVM_Repository();
        $this->validator = new SaveCollectionValidator($this->repo);
    }

    public function uniqueBy()
    {
        return 'id';
    }

    public function collection(Collection $rows)
    {
        if(count($rows)>=2){
            $mappedData = $this->mapAllDataToKeyValue($rows);
            $this->validator->validate($mappedData);

            // dd($mappedData);
            if($this->validator->passes()){
                foreach($mappedData as $row){
                    
                    if($this->repo->find($row['id']) === null){
                        $this->repo->create($row);
                    }else{
                        $this->repo->update($row);
                    }
                    DB::commit();
                }
                $this->response = new Response([]);
            }
            $this->response = new Response($this->validator->collectionErrorsImploded(), $mappedData);
        }else{
            $this->response = new Response([]);
        }
    }

    public function response(): Response {
        return $this->response;
    }

    private function mapAllDataToKeyValue($collection){
        $data = [];
        for ($i=1; $i < count($collection); $i++) {
            $row = $collection[$i];
            $toAdd = [
                'id' => $row[0],
                'region' => $row[1],
                'encargados' => $row[2],
                'ubigeo' => $row[3],
                'departamento' => $row[4],
                'ccpp' => $row[5],
                'indicador' => $row[6],
                'tecnologia' => $row[7],
                'dl_3g' => $this->mapPorcentaje($row[8]),
                'dl_4g' => $this->mapPorcentaje($row[9]),
                'ul_3g' => $this->mapPorcentaje($row[10]),
                'ul_4g' => $this->mapPorcentaje($row[11]),
                'cm_enviado' => $row[12],
                'fecha' => $this->formatToDateTime($row[13]),
                'fecha_limite' => $this->formatToDateTime($row[14]),
                'periodo' => $row[15],
                'multa' => $this->mapBooleanState($row[16]),
                'grupo_multa' => $row[17],

                'actividad' => $this->mapAccion($row[18]),
                'fecha_actividad' => $this->formatToDateTime($row[19]),
                'estado' => $this->mapEstado($row[20]),
                'acta_levantada' => $this->mapBooleanState($row[21]),
                'comentarios_red' => $row[22],

                'enviado_osiptel' => $this->mapBooleanState($row[23]),
                'fecha_env_osiptel' => $this->formatToDateTime($row[24]),
                'comentarios_rg' => $row[25],
            ];
            $data[] = $toAdd;
        }
        return $data;
    }

    public function mapAccion($value){
        return is_null($value) ? CM_Actividad::defaultValue() : $value;
    }

    public function mapEstado($value){
        return is_null($value) ? CM_Estado::defaultValue() : $value;
    }
}
