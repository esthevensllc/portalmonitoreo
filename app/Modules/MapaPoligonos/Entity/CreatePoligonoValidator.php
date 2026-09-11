<?php

namespace App\Modules\MapaPoligonos\Entity;

use App\Modules\MapaPoligonos\Repository\MapaPoligono;
use App\Modules\Shared\Entity\Validator as EntityValidator;
use Validator;

class CreatePoligonoValidator extends EntityValidator
{
    public function validate($data){
        $validator = Validator::make($data, [
            'nombre' => ['required', 'max:200', 'unique:'.MapaPoligono::table()],
            'num_cli_potenciales' => ['nullable', 'numeric', 'integer'],
            'num_emp_potenciales' => ['nullable', 'numeric', 'integer'],
            'num_dep_edificios' => ['nullable', 'numeric', 'integer'],
            'num_condominios' => ['nullable', 'numeric', 'integer'],
            'tipo_incidencia' => ['nullable', 'max:200'],
            'num_cli_afectados' => ['nullable', 'numeric', 'integer'],
            'num_emp_afectados' => ['nullable', 'numeric', 'integer'],
            'observacion' => ['nullable', 'max:500'],
            'poligono' => ['required'],
        ]);
        if($validator->fails()){
            $this->setErrors($validator->errors()->messages());
        }
    }
}
