<?php

namespace App\Models\PrtltxAlarma;

class DispMetaByTipoNodo
{
    public static function get(){
        $dispEsperadaByTipoNodo = [
            //'Agregación sin diversidad de ruta' => ['disponibilidad' => 99.99],
            'Distribucion sin diversidad de ruta' => ['disponibilidad' => 99.99],
            'Distribucion con diversidad de ruta' => ['disponibilidad' => 99.99],
            'Conexion con diversidad de ruta' => ['disponibilidad' => 99.60],
            // 'Conexión sin diversidad de ruta' => ['disponibilidad' => 99.99]
        ];
        return $dispEsperadaByTipoNodo;
    }
}