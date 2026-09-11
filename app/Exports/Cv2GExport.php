<?php

namespace App\Exports;

use App\Models\CV_2G;

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

class Cv2GExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function headings(): array
    {
        $head1 = ['ID','CC','REGION','DEPARTAMENTO','PROVINCIA','DISTRITO','CCPP'];
        $head2 = ['MULTA GSM','PERIODO DE MULTA','FECHA DE MEJORA','COMENTARIO MEJORA','FECHA DE DIAGNOSTICO'	,'COMENTARIO DIAGNOSTICO'	,'FECHA DE SOLUCION',	'SUSTENTO SOLUCIÓN',	'Fecha de DT (Responsable OyM)',	'ValorDT'	,'Ruta de Mediciones'	,'Fecha Insercion',	'CCPP Observacion' ,	'Responsable Principal'	,'Analista Responsable'	,'Diagnostico'	,'Fecha Diagnostico',	'Accion Realizada'	,'Forecast Principal'	,'Solucion Principal',	'Estado',	'comentarios'];
        $periodos = DB::table('prg_cv_periodos')->where('estado',1)->orderBy('id','asc')->pluck('periodo')->toArray();    
        $headf = array_merge($head1,$periodos,$head2);
        return $headf;
    }

    public function query()
    {
        $head1 = ['id','cc','region','departamento','provincia','distrito','ccpp'];
        $head2 = ['multa_gsm','periodo_multa','fecha_comentario_mejora','comentario_mejora','fecha_comentario_diagnostico','comentario_diagnostico','fecha_solucion','sustento_solucion','fecha_dt','valor_dt','ruta_mediciones','fecha_insercion','ccpp_observacion_2g','responsable_principal','analista_responsable','diagnostico','fecha_diagnostico','accion_realizada','forecast_principal','solucion_principal','estado','comentarios'];
        $periodos = DB::table('prg_cv_periodos')->where('estado',1)->orderBy('id','asc')->pluck('periodo')->toArray();    
        $headf = array_merge($head1,$periodos,$head2);
        return CV_2G::select($headf);
    }

    public function styles(Worksheet $sheet)
    {                 
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
            ],

            'A1:M1' => [
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

            'N1:AG1' => [
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => '00FFA500',
                    ],
                    'endColor' => [
                        'argb' => '00FFA500',
                    ],
                ]
            ]
        ];
    }
}
