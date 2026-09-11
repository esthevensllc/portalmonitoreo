<?php

namespace App\Modules\KPI_CM\CCS_CV_TEMT\Exports;

use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMP;
use App\Modules\Shared\Exports\BaseExport;
use DateTime;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CCS_VS_TEMTExport extends BaseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithColumnFormatting
{
    use Exportable;
    private $collection;
    private $applyFormat;

    public function __construct($collection, $applyFormat = true)
    {
        $this->collection = $collection;
        $this->applyFormat = $applyFormat;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            'ID',
            'ENCARGADOS',
            'REGION',
            'UBIGEO',
            'DEPARTAMENTO',
            'PROV',
            'DIST',
            'CCPP',
            'TECNOLOGIA',
            'INDICADOR',
            'GSM',
            'UMTS',
            'COMENTARIO PARA OSIPTEL',
            'FECHA',
            'FECHA LIMITE',
            'PERIODO',
            'MULTA',
            'GRUPO_MULTA',
            'ACTIVIDAD',
            'FECHA ACTIVIDAD',
            'ESTADO',
            'ACTA LEVANTADA (SI / NO)',
            'COMENTARIOS RED',
            'ENVIADO OSIPTEL (SI/NO)',
            'FECHA DE ENVIO OSIPTEL',
            'COMENTARIOS RG',
        ];
    }
    
    public function map($item): array
    {
        $gsm = $item->gsm;
        $umts = $item->umts;
        $fecha = $item->fecha;
        $fecha_limite = $item->fecha_limite;
        $fecha_actividad = $item->fecha_actividad;
        $fecha_env_osiptel = $item->fecha_env_osiptel;
        if($this->applyFormat){
            $gsm = $this->formatCustomPorc($item->indicador, $item->gsm);
            $umts = $this->formatCustomPorc($item->indicador, $item->umts);
            $fecha = $this->formatDate($item->fecha);
            $fecha_limite = $this->formatDate($item->fecha_limite);
            $fecha_actividad = $this->formatDate($item->fecha_actividad);
            $fecha_env_osiptel = $this->formatDate($item->fecha_env_osiptel);
        }
        return [
            $item->id,
            $item->encargados,
            $item->region,
            $item->ubigeo,
            $item->departamento,
            $item->provincia,
            $item->distrito,
            $item->ccpp,
            $item->tecnologia,
            $item->indicador,
            $gsm,
            $umts,
            $item->comentario_osiptel,
            $fecha,
            $fecha_limite,
            $item->periodo,
            $item->multa,
            $item->grupo_multa,
            $item->actividad,
            $fecha_actividad,
            $item->estado,
            $item->acta_levantada,
            $item->comentarios_red,
            $item->enviado_osiptel,
            $fecha_env_osiptel,
            $item->comentarios_rg,
        ];
    }

    private function formatCustomPorc($indicador, $value){
        $indicador = strtolower($indicador);
        if(str_contains($indicador, 'ccs') || str_contains($indicador, 'css') ){
            if(is_numeric($value)){
                return is_null($value) ? null : ($value / 100);
            }
        }
        return $value;
    }

    private function formatDate2(?string $date, $fromFormat = 'Y-m-d H:i:s'){
        $datetime = DateTime::createFromFormat($fromFormat, $date);
        if($datetime){
            return Date::dateTimeToExcel($datetime);
        }
        return '';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:R1' => [
                'font' => [
                    'color' => array('rgb' => 'FFFFFF'),
                ],
                'fill' => [
                    
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FF0000',
                    ],
                    'endColor' => [
                        'argb' => 'FF0000',
                    ],
                ]
            ],
            'S1:W1' => [
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => '92D050',
                    ],
                    'endColor' => [
                        'argb' => '92D050',
                    ],
                ]
            ],
            'X1:Z1' => [
                'font' => [
                    'color' => array('rgb' => 'FFFFFF'),
                ],
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FF0000',
                    ],
                    'endColor' => [
                        'argb' => 'FF0000',
                    ],
                ]
            ]
        ];
    }

    public function columnFormats(): array
    {
        if($this->applyFormat){
            return [
                'D' => NumberFormat::FORMAT_TEXT,
                // 'J' => NumberFormat::FORMAT_PERCENTAGE_00,
                // 'K' => NumberFormat::FORMAT_PERCENTAGE_00,
                'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                'T' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                'Y' => NumberFormat::FORMAT_DATE_DDMMYYYY
            ];
        }
        return [];
    }
}
