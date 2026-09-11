<?php

namespace App\Modules\KPI_CM\CVM\Services;

use App\Modules\KPI_CM\CVM\Exports\CM_CVMExport;

class ExportErrorsCM_CVM
{
    public function __invoke($errors)
    {
        $mappedData = $this->mapErrors($errors);
        $collection = collect($mappedData);
        return (new CM_CVMExport($collection, false))->download('CM_CVM_ERRORS'.'.xlsx');
    }

    public function mapErrors($collectionErrors){
        $defaultFields = [
            'id' => '',
            'region' => '',
            'encargados' => '',
            'ubigeo' => '',
            'departamento' => '',
            'ccpp' => '',
            'indicador' => '',
            'tecnologia' => '',
            'dl_3g' => '',
            'dl_4g' => '',
            'ul_3g' => '',
            'ul_4g' => '',
            'cm_enviado' => '',
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
