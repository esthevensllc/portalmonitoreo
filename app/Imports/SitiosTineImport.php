<?php

namespace App\Imports;

use App\Models\SITIOS_OBSERVADOS_TINE;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class SitiosTineImport implements ToModel
{
    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'id';
    }

    /**
     * @param array $row
     *
     * @return SITIOS_OBSERVADOS_TINE|null
     */
    public function model(array $row)
    {
        $now = Carbon::now();

        $sitio = new SITIOS_OBSERVADOS_TINE();        

        if(is_numeric($row[0])){
            $sitio = $sitio->find($row[0]);
            if($sitio){
                if(is_numeric($row[9])){        
                    $fecha = Date::excelToDateTimeObject($row[9]);
                    $strfecha = $fecha->format('d/m/Y');
                    $arrfecha = explode('/', $strfecha);
                    if (checkdate(intval($arrfecha[1]),intval($arrfecha[0]),intval($arrfecha[2]))) {
                        // valid date                       
                        $sitio->update([           
                            'grupo_motivo'   => $row[6],
                            'detalle_motivo' => $row[7],
                            'accion_solucion'=> $row[8],
                            'estado'         => 1,
                            'solution_at'    => $fecha,
                        ]);
                    }
                }else{
                    if(strlen($row[9])==10){
                        $arrfecha = explode('/', $row[9]);
                        $fecha = $arrfecha[2].'-'.$arrfecha[1].'-'.$arrfecha[0];
                        if (checkdate(intval($arrfecha[1]),intval($arrfecha[0]),intval($arrfecha[2]))) {
                            // valid date                       
                            $sitio->update([           
                                'grupo_motivo'   => $row[6],
                                'detalle_motivo' => $row[7],
                                'accion_solucion'=> $row[8],
                                'estado'         => 1,
                                'solution_at'    => $fecha,
                            ]);
                        }
                    }else{
                        $sitio->update([           
                            'grupo_motivo'   => $row[6],
                            'detalle_motivo' => $row[7],
                            'accion_solucion'=> $row[8],
                            'estado'         => 1
                        ]);
                    }
                }
                return $sitio;
            }
        }


        /* return new SITIOS_OBSERVADOS_TLLI([
           'id'             => $row[0],
           'site_name'      => $row[1],
           'bcf_address'    => $row[2],
           'departamento'   => $row[3],
           'tipo_obs_fitel' => $row[4],
           'tecnologia'     => $row[5],
           'grupo_motivo'   => $row[6],
           'detalle_motivo' => $row[7],
           'accion_solucion'=> $row[8],j
           'created_at'     => $row[9],
           'updated_at'     => $now,
           'solution_at'    => $now,
           'estado'         => 1,
        ]); */
    }
}