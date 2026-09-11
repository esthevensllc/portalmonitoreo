<?php

namespace App\Exports;

use App\Models\SITIOS_OBSERVADOS_TINE;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Illuminate\Support\Facades\DB;

class SitiosTineExport implements FromQuery, WithHeadings, WithStyles,WithColumnWidths, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID','CÓDIGO','NOMBRE ESTACIÓN','DEPARTAMENTO','OBSERVACIÓN','TECNOLOGÍA','GRUPO MOTIVO','DETALLE MOTIVO', 'ACCIÓN SOLUCIÓN', 'FECHA SOLUCIÓN'
        ];
    }

    public function query()
    {
        return SITIOS_OBSERVADOS_TINE::select('id','site_name','bcf_address','departamento','tipo_obs_fitel','tecnologia','grupo_motivo','detalle_motivo','accion_solucion','solution_at')->where('estado',1);
    }

    public function map($query): array
    {
        if($query->solution_at){
            $date = strtotime($query->solution_at);  
            $date = date('d/m/Y', $date);
        }else{
            $date = "";
        }
        return [
            $query->id,
            $query->site_name,            
            $query->bcf_address,
            $query->departamento,
            $query->tipo_obs_fitel,
            $query->tecnologia,
            $query->grupo_motivo,
            $query->detalle_motivo,
            $query->accion_solucion,
            $date            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        
        $motivos = DB::select("SELECT id,nombre FROM PRG_SITIOS_OBSERVADOS_MOTIVO WHERE estado=1");

        $i= 2;

        $sheet->setCellValue('L1', 'ID');
        $sheet->setCellValue('M1', 'GRUPO MOTIVO');

        foreach ($motivos as $motivo) {

            $sheet->setCellValue('L'.$i, $motivo->id);
            $sheet->setCellValue('M'.$i, $motivo->nombre);

            $i++;
        }

        $sheet->getStyle('L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            'G1:J1' => [
                'font' => ['color' => ['argb' => '00FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => '00FF0000',
                    ],
                    'endColor' => [
                        'argb' => '00FF0000',
                    ],
                ]
            ],

            'L1:M1' => [
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => '00FFFF00',
                    ],
                    'endColor' => [
                        'argb' => '00FFFF00',
                    ],
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'M' => 55            
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

}
