<?php

namespace App\Imports;

use App\Models\CV_GENERAL;
use App\Models\CV_2G;
use App\Models\CV_3G;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class CvGeneralImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return CV_GENERAL|null
     */
    public function model(array $row)
    {
        //$now = Carbon::now();

        $reg = new CV_GENERAL();
        $reg2g = new CV_2G();
        $reg3g = new CV_3G();       

        $reg->updateOrInsert(['cc' => $row['cc']],
        [   'procedimiento_2g'  => $row['procedimiento_de_multa_2g'],
            'procedimiento_3g'  => $row['procedimiento_de_multa_3g'],
            'ubigeo'            => $row['ubigeo'],
            'region'            => $row['region'],
            'departamento'      => $row['departamento'],
            'provincia'         => $row['provincia'],
            'distrito'          => $row['distrito'],
            'ccpp'              => $row['ccpp'],                    
            'cobertura_2020'    => $row['cobertura_2020'],
            'cobertura_2021'    => $row['cobertura_2021']
        ]);

        $reg2g->updateOrInsert(['cc' => $row['cc']],
        [   'ubigeo'            => $row['ubigeo'],
            'region'            => $row['region'],
            'departamento'      => $row['departamento'],
            'provincia'         => $row['provincia'],
            'distrito'          => $row['distrito'],
            'ccpp'              => $row['ccpp'],                    
        ]);

        $reg3g->updateOrInsert(['cc' => $row['cc']],
        [   'ubigeo'            => $row['ubigeo'],
            'region'            => $row['region'],
            'departamento'      => $row['departamento'],
            'provincia'         => $row['provincia'],
            'distrito'          => $row['distrito'],
            'ccpp'              => $row['ccpp'],                    
        ]);
                    
        //return $reg;

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