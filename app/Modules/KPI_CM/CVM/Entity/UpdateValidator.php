<?php

namespace App\Modules\KPI_CM\CVM\Entity;

use App\Modules\KPI_CM\Actividades\Repository\CM_Actividad;
use App\Modules\KPI_CM\CVM\Repository\CM_CVM;
use App\Modules\KPI_CM\Estados\Repository\CM_Estado;
use App\Modules\Shared\Entity\Validator as EntityValidator;
use Validator;

class UpdateValidator extends EntityValidator
{
    public function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => ['required', 'exists:'.CM_CVM::table().',id'],
            'region' => ['nullable', 'max:200'],
            'encargados' => ['required', 'max:200'],
            'ubigeo' => ['required', 'numeric'],
            'departamento' => ['nullable', 'max:200'],
            'ccpp' => ['nullable', 'max:200'],
            'indicador' => ['required', 'max:100'],
            'tecnologia' => ['required', 'max:100'],
            'dl_3g' => ['nullable', 'numeric', 'min:0', 'max:900'],
            'dl_4g' => ['nullable', 'numeric', 'min:0', 'max:900'],
            'ul_3g' => ['nullable', 'numeric', 'min:0', 'max:900'],
            'ul_4g' => ['nullable', 'numeric', 'min:0', 'max:900'],
            'cm_enviado' => ['nullable', 'max:1000'],
            'fecha' => ['required', 'date'],
            'fecha_limite' => ['nullable', 'date'],
            'periodo' => ['required', 'max:7'],
            'multa' => ['required', 'max:2'],
            'grupo_multa' => ['nullable', 'max:200'],
            'actividad' => ['required', 'exists:'.CM_Actividad::table().',id'],
            'fecha_actividad' => ['required', 'date'],
            'estado' => ['required', 'exists:'.CM_Estado::table().',id'],
            'acta_levantada' => ['required', 'max:2'],
            'comentarios_red' => ['nullable', 'max:1000'],
            'enviado_osiptel' => ['required', 'max:2'],
            'fecha_env_osiptel' => ['nullable', 'date'],
            'comentarios_rg' => ['nullable', 'max:1000'],
            // 'acta_archivo' => ['nullable', 'max:500'],
        ]);
        if($validator->fails()){
            $this->setErrors($validator->errors()->messages());
        }else{
            $this->setErrors([]);
        }
    }
}
