<?php

namespace App\Exports;

use App\Models\CV_GENERAL;

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

class CvGeneralExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Procedimiento De Multa 2G','Procedimiento De Multa 3G','Ubigeo','Región','Departamento','Provincia','Distrito','CCPP','CC','cobertura 2020',' cobertura 2021'
            
        ];
    }

    public function query()
    {
        return CV_GENERAL::select('procedimiento_2g','procedimiento_3g','ubigeo','region','departamento','provincia','distrito','ccpp','cc','cobertura_2020','cobertura_2021');
    }

    public function styles(Worksheet $sheet)
    {                 
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
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
        ];
    }
}
