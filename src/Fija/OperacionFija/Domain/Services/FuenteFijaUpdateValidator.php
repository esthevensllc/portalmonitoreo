<?php

namespace AMovil\Fija\OperacionFija\Domain\Services;

use Illuminate\Support\Facades\Validator;

class FuenteFijaUpdateValidator
{
    public function validate(array $data)
    {
        return Validator::make($data, [
            "id" => ["required", "exists:operaciones_fija_fuente,id"],
            "plano" => "present",
            "tipo_nodo" => "present",
            "tipo_fuente" => "present",
            "ubicado_en" => "present",
            "tiene_baterias" => "present",
            "anio_fab" => "present|nullable|numeric",
            "tipo_respaldo" => "present",
            "amp" => "present",
            "candado" => "present",
            "barra" => "present",
            "seguro_h" => "present",
            "seguro_baterias" => "present",
            "fecha_manto" => "present|nullable|date",
            "anio_manto" => "present|nullable|numeric",
            "mes_manto" => "present",
            "referido" => "present",
            "region" => "present",
            "distrito" => "present",
            "segmento_urbano" => "present",
            "gestion_campo" => "present",
            "nivel_de_seguridad" => "present",
            "comentario" => "present",
            "latitud" => "required|numeric|between:-90,90",
            "longitud" => "required|numeric|between:-180,180",
        ]);
    }
}
