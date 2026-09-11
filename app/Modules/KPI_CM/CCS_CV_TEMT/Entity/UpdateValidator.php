<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Entity;

use App\Modules\KPI_CM\Actividades\Repository\CM_Actividad;
use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMP;
use App\Modules\KPI_CM\Estados\Repository\CM_Estado;
use App\Modules\Shared\Entity\Validator as EntityValidator;
use Validator;

class UpdateValidator extends EntityValidator
{
    public function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => ['required', 'exists:'.CCS_CV_TEMP::table().',id'],
            'encargados' => ['required', 'max:200'],
            'region' => ['required', 'max:200'],
            'ubigeo' => ['required', 'max:15'],
            'departamento' => ['nullable', 'max:200'],
            'provincia' => ['nullable', 'max:200'],
            'distrito' => ['nullable', 'max:200'],
            'ccpp' => ['nullable', 'max:200'],
            'tecnologia' => ['required', 'max:100'],
            'indicador' => ['required', 'max:100'],
            'gsm' => ['nullable', 'max:8'],
            'umts' => ['nullable', 'max:8'],
            'comentario_osiptel' => ['nullable', 'max:1000'],
            'fecha' => ['nullable', 'date'],
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
