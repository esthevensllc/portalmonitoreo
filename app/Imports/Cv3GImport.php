<?php

namespace App\Imports;

use App\Models\CV_3G;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class Cv3GImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return CV_3G|null
     */
    public function model(array $row)
    {
        $now = Carbon::now();

        $reg = new CV_3G();

        $periodos = DB::table('prg_cv_periodos')->where('estado',1)->orderBy('id','asc')->pluck('periodo')->toArray();  
       
        if(is_numeric($row['id'])){
            $reg = $reg->find($row['id']);
            if($reg){
                foreach($periodos as $periodo){
                    $reg->update([   
                        strtolower($periodo) => $row[strtolower($periodo)]
                    ]);   
                }

                $fecha_comentario_mejora = $this->validDate($row['fecha_de_mejora']);
                $fecha_comentario_diagnostico = $this->validDate($row['fecha_de_diagnostico']);
                $fecha_solucion = $this->validDate($row['fecha_de_solucion']);
                $fecha_dt = $this->validDate($row['fecha_de_dt_responsable_oym']);
                $fecha_insercion = $this->validDate($row['fecha_insercion']);
                $fecha_diagnostico = $this->validDate($row['fecha_diagnostico']);

                $reg->update([        
                    'multa_umts'=> $row['multa_umts'],
                    'periodo_multa'=> $row['periodo_de_multa'],
                    'fecha_comentario_mejora'=> $fecha_comentario_mejora,
                    'comentario_mejora'=> $row['comentario_mejora'],
                    'fecha_comentario_diagnostico'=> $fecha_comentario_diagnostico,
                    'comentario_diagnostico'=> $row['comentario_diagnostico'],
                    'fecha_solucion'=> $fecha_solucion,
                    'sustento_solucion'=> $row['sustento_solucion'],
                    'fecha_dt'=> $fecha_dt,
                    'valor_dt'=> $row['valordt'],
                    'ruta_mediciones'=> $row['ruta_de_mediciones'],
                    'fecha_insercion'=> $fecha_insercion,
                    'ccpp_observacion_2g'=> $row['ccpp_observacion'],
                    'responsable_principal'=> $row['responsable_principal'],
                    'analista_responsable'=> $row['analista_responsable'],
                    'diagnostico'=> $row['diagnostico'],
                    'fecha_diagnostico'=> $fecha_diagnostico,
                    'accion_realizada'=> $row['accion_realizada'],
                    'forecast_principal'=> $row['forecast_principal'],
                    'solucion_principal'=> $row['solucion_principal'],
                    'estado'=> $row['estado'],
                    'comentarios'=> $row['comentarios'],
                ]);
                    
            }
            return $reg;
        }
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
                $arrfecha = explode('-', $field);
                $arrfechatime = explode(' ', $arrfecha[2]);
                $fecha = $arrfechatime[0].'-'.$arrfecha[1].'-'.$arrfecha[0].' '.$arrfechatime[1];
                if (checkdate(intval($arrfecha[1]),intval($arrfecha[0]),intval($arrfechatime[0]))) {
                    // valid date                       
                    return $fecha;
                }
            }    
        }

        return null;
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