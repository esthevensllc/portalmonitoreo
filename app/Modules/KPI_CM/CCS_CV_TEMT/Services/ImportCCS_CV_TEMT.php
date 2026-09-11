<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Services;

use App\Modules\KPI_CM\Actividades\Repository\CM_Actividad;
use App\Modules\KPI_CM\CCS_CV_TEMT\Entity\SaveCollectionValidator;
use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMT_Repository;
use App\Modules\KPI_CM\Estados\Repository\CM_Estado;
use App\Modules\Shared\Import\BaseImport;
use App\Modules\Shared\Services\Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DB;

class ImportCCS_CV_TEMT extends BaseImport implements ToCollection
{
    private $validator;
    private $repo;
    private $response;

    public function __construct()
    {
        $this->repo = new CCS_CV_TEMT_Repository();
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

            // dd([$mappedData]);
            
            if($this->validator->passes()){
                foreach($mappedData as $row){
                    // if($this->repo->find($row['id']) === null){
                    if($row['id'] === null){
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
            $alarmaToAdd = [
                'id' => $row[0],
                'encargados' => $row[1],
                'region' => $row[2],
                'ubigeo' => $row[3],
                'departamento' => $row[4],
                'provincia' => $row[5],
                'distrito' => $row[6],
                'ccpp' => $row[7],
                'tecnologia' => $row[8],
                'indicador' => $row[9],
                'gsm' => $this->mapGSM_UMTS($row[9], $row[10]),
                'umts' => $this->mapGSM_UMTS($row[9], $row[11]),
                'comentario_osiptel' => $row[12],
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
            $data[] = $alarmaToAdd;
        }
        return $data;
    }

    public function mapGSM_UMTS($indicador, $value){
        $indicador = strtolower($indicador);
        if(str_contains($indicador, 'ccs') || str_contains($indicador, 'css') ){
            if(is_numeric($value)){
                return round($this->mapPorcentaje($value), 4);
            }
        }
        return $value;
    }

    public function mapAccion($value){
        return is_null($value) ? CM_Actividad::defaultValue() : $value;
    }

    public function mapEstado($value){
        return is_null($value) ? CM_Estado::defaultValue() : $value;
    }
}
