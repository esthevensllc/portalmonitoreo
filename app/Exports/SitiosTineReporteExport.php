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

class SitiosTineReporteExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths,ShouldAutoSize
{
    use Exportable;
    public function __construct(string $p_mes, string $p_anio)
    {
        $this->p_mes = $p_mes;
        $this->p_anio = $p_anio;

    }
    public function headings(): array
    {
        return [
            'CODIGO','NOMBRE ESTACIÓN','DEPARTAMENTO','OBSERVACIÓN','TECNOLOGÍA','MES','ANIO'
        ];
    }

    public function query()
    {
        return SITIOS_OBSERVADOS_TINE::select('site_name','bcf_address','departamento','tipo_obs_fitel','tecnologia','mes','anio')->where('estado',1)->where('mes',$this->p_mes)->where('anio',$this->p_anio);
    }

    public function styles(Worksheet $sheet)
    {
        
        $motivos = DB::select("SELECT site_name,bcf_address,departamento,tipo_obs_fitel,tecnologia,mes,anio FROM PRG_SITIOS_OBSERVADOS_TINE WHERE mes = $this->p_mes AND anio = $this->p_anio");

        $i= 2;

        $sheet->setCellValue('A1', 'CODIGO');
        $sheet->setCellValue('B1', 'NOMBRE ESTACIÓN');
        $sheet->setCellValue('C1', 'DEPARTAMENTO');
        $sheet->setCellValue('D1', 'OBSERVACIÓN');
        $sheet->setCellValue('E1', 'TECNOLOGÍA');
        $sheet->setCellValue('F1', 'MES');
        $sheet->setCellValue('G1', 'ANIO');

        foreach ($motivos as $motivo) {

            $sheet->setCellValue('A'.$i, $motivo->site_name);
            $sheet->setCellValue('B'.$i, $motivo->bcf_address);
            $sheet->setCellValue('C'.$i, $motivo->departamento);
            $sheet->setCellValue('D'.$i, $motivo->tipo_obs_fitel);
            $sheet->setCellValue('E'.$i, $motivo->tecnologia);
            $sheet->setCellValue('F'.$i, $motivo->mes);
            $sheet->setCellValue('G'.$i, $motivo->anio);

            $i++;
        }

        $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => false]],

            'A1:G1' => [
                'font' => ['color' => ['argb' => '000000']],
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'A6FAFF',
                    ],
                    'endColor' => [
                        'argb' => 'A6FAFF',
                    ],
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,            
            'D' => 10,            
            'F' => 10,            
            'G' => 10,                     
        ];
    }

    

}
