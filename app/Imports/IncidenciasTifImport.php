<?php

namespace App\Imports;

use App\Models\INCIDENCIAS_TIF;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

use App\Models\LINEAS_TIF;

class IncidenciasTifImport implements ToModel
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
     * @return INCIDENCIAS_TIF|null
     */
    public function model(array $row)
    {

        $lastFecha = Carbon::now();

        $lastFecha = $lastFecha->subMinutes(5);
        
        $fecha_inicio = $this->validDate($row[13]);

        $fecha_fin = $this->validDate($row[14]);

        if( $fecha_inicio && $fecha_fin && is_numeric($row[2]) && is_numeric($row[3]) && is_numeric($row[4]) && is_numeric($row[5]) && is_numeric($row[7]) && strlen($row[0]) <= 10 && strlen($row[1]) <= 100 && strlen($row[6]) <= 10 && strlen($row[8]) <= 50 && strlen($row[9]) <= 255 && strlen($row[10]) <= 1000 && strlen($row[11]) <= 100 && strlen($row[12]) <= 100 ){

            INCIDENCIAS_TIF::where('periodo',$row[0])->where('created_at','<',$lastFecha->toDateTimeString())->delete();

            $periodo = LINEAS_TIF::where('periodo',$row[0])->first();           

            if(!$periodo){

                $data = [
                    ['periodo' => $row[0], 'servicio' => 'VOZ'],
                    ['periodo' => $row[0], 'servicio' => 'INTERNET'],
                    ['periodo' => $row[0], 'servicio' => 'CABLE']
                ];

                LINEAS_TIF::insert($data);
            }

            return new INCIDENCIAS_TIF([ 
                'periodo'=> $row[0],
                'grupo_servicio'=> $row[1],
                'ticket'=> $row[2],
                'masiva'=> $row[3],
                'activo'=> $row[4],
                'tiempo_reparacion'=> $row[5],
                'metrica'=> $row[6],
                'canales'=> $row[7],
                'codigo_cliente'=> $row[8],
                'nombre_cliente'=> $row[9],
                'causa'=> $row[10],
                'responsable'=> $row[11],
                'estado_de_atencion'=> $row[12],
                'fecha_inicio'=> $fecha_inicio,
                'fecha_fin'   => $fecha_fin,
            ]);
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

    public function validDate($field){
        
        if(is_numeric($field)){
            $fecha = Date::excelToDateTimeObject($field);
            $strfecha = $fecha->format('d/m/Y');
            $arrfecha = explode('/', $strfecha);
            if (checkdate(intval($arrfecha[1]),intval($arrfecha[0]),intval($arrfecha[2]))) {
                // valid date          
                return $fecha;
            }
        }else{
            if(strlen($field)==10){
                $arrfecha = explode('/', $field);
                $fecha = $arrfecha[2].'-'.$arrfecha[1].'-'.$arrfecha[0];
                if (checkdate(intval($arrfecha[1]),intval($arrfecha[0]),intval($arrfecha[2]))) {
                    // valid date                       
                    return $fecha;
                }
            }
            if(strlen($field)==19){
                $arrfecha = explode('/', $field);
                $arrfechatime = explode(' ', $arrfecha[2]);
                $fecha = $arrfechatime[0].'-'.$arrfecha[1].'-'.$arrfecha[0].' '.$arrfechatime[1];
                if (checkdate(intval($arrfecha[1]),intval($arrfecha[0]),intval($arrfechatime[0]))) {
                    // valid date                       
                    return $fecha;
                }
            }    
        }

        return 0;
    }
}