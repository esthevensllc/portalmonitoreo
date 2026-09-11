<?php

namespace App\Exports;

use App\Models\MAESTRO_BTS;
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



class MaestroBtsExport implements FromQuery, WithHeadings, WithStyles, WithColumnWidths,ShouldAutoSize
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
            'BCF_NAME','BCF_ADDRESS','BSC_NAME','MSC_NAME','STATUS','TECNOLOGIA'
        ];
    }

    public function query()
    {
        return MAESTRO_BTS::select('bcf_name','bcf_address','bsc_name','msc_name','clasif_name','tecnologia','mes','anio')->where('mes',$this->p_mes)->where('anio',$this->p_anio);
    }

    public function styles(Worksheet $sheet)
    {
        
        $motivos = DB::select("SELECT bcf_name,bcf_address,bsc_name,msc_name,clasif_name,tecnologia,mes,anio FROM INDCAL_TINE_MAESTRO_BTS WHERE mes = $this->p_mes AND anio = $this->p_anio");

        $i= 2;

        $sheet->setCellValue('A1', 'BCF_NAME');
        $sheet->setCellValue('B1', 'BCF_ADDRESS');
        $sheet->setCellValue('C1', 'BSC_NAME');
        $sheet->setCellValue('D1', 'MSC_NAME');
        $sheet->setCellValue('E1', 'CLASIF_NAME');
        $sheet->setCellValue('F1', 'TECNOLOGIA');
        $sheet->setCellValue('G1', 'MES');
        $sheet->setCellValue('H1', 'ANIO');

        foreach ($motivos as $motivo) {

            $sheet->setCellValue('A'.$i, $motivo->bcf_name);
            $sheet->setCellValue('B'.$i, $motivo->bcf_address);
            $sheet->setCellValue('C'.$i, $motivo->bsc_name);
            $sheet->setCellValue('D'.$i, $motivo->msc_name);
            $sheet->setCellValue('E'.$i, $motivo->clasif_name);
            $sheet->setCellValue('F'.$i, $motivo->tecnologia);
            $sheet->setCellValue('G'.$i, $motivo->mes);
            $sheet->setCellValue('H'.$i, $motivo->anio);

            $i++;
        }

        $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => false]],

            'A1:H1' => [
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
            'H' => 10          
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    
}
