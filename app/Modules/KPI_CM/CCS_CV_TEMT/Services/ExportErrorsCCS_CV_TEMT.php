<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Services;

class ExportErrorsCCS_CV_TEMT
{
    public function __invoke($errors)
    {
        $mappedData = $this->mapErrors($errors);
        $collection = collect($mappedData);
        return (new \App\Modules\KPI_CM\CCS_CV_TEMT\Exports\CCS_VS_TEMTExport($collection, false))
                ->download('CM_CCS_VS_TEMT_ERRORS'.'.xlsx', null);
            
    }

    public function mapErrors($collectionErrors){
        $defaultFields = [
            'id' => '',
            'encargados' => '',
            'region' => '',
            'ubigeo' => '',
            'departamento' => '',
            'provincia' => '',
            'distrito' => '',
            'ccpp' => '',
            'tecnologia' => '',
            'indicador' => '',
            'gsm' => '',
            'umts' => '',
            'comentario_osiptel' => '',
            'fecha' => '',
            'fecha_limite' => '',
            'periodo' => '',
            'multa' => '',
            'grupo_multa' => '',
            'actividad' => '',
            'fecha_actividad' => '',
            'estado' => '',
            'acta_levantada' => '',
            'comentarios_red' => '',
            'enviado_osiptel' => '',
            'fecha_env_osiptel' => '',
            'comentarios_rg' => '',
        ];
        $newArray = [];
        foreach($collectionErrors as $key => $errors){
            $toAdd = array_merge($defaultFields, $errors);
            // $key = 'row.1';
            $excelIndex = (explode(".", $key)[1]) + 2;
            $toAdd['id'] = 'row.'.$excelIndex.', '.$toAdd['id'];
            $newArray[$key] = json_decode(json_encode($toAdd));

        }
        return $newArray;
    }
}
