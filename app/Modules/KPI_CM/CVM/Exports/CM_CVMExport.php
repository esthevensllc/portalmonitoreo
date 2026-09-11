<?php

namespace App\Modules\KPI_CM\CVM\Exports;

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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CM_CVMExport  extends BaseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithColumnFormatting
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
            'REGION',
            'ENCARGADOS',
            'UBIGEO',
            'DEPARTAMENTO',
            'CCPP',
            'INDICADOR',
            'TECNOLOGIA',
            'DL 3G',
            'DL 4G',
            'UL 3G',
            'UL 4G',
            'CM ENVIADO',
            'FECHA',
            'FECHA LIMITE',
            'PERIODO',
            'MULTA (SI/NO)',
            'GRUPO MULTA',

            'ACTIVIDAD',
            'FECHA ACTIVIDAD',
            'ESTADO',
            'ACTA LEVANTADA (SI/NO)',
            'COMENTARIOS RED',

            'ENVIADO OSIPTEL',
            'FECHA ENV OSIPTEL',
            'COMENTARIOS RG',
        ];
    }
    
    public function map($item): array
    {
        $dl_3g = $item->dl_3g;
        $dl_4g = $item->dl_4g;
        $ul_3g = $item->ul_3g;
        $ul_4g = $item->ul_4g;
        $fecha = $item->fecha;
        $fecha_limite = $item->fecha_limite;
        $fecha_actividad = $item->fecha_actividad;
        $fecha_env_osiptel = $item->fecha_env_osiptel;
        if($this->applyFormat){
            $dl_3g = $this->formatPorcentaje($item->dl_3g);
            $dl_4g = $this->formatPorcentaje($item->dl_4g);
            $ul_3g = $this->formatPorcentaje($item->ul_3g);
            $ul_4g = $this->formatPorcentaje($item->ul_4g);
            $fecha = $this->formatDate($item->fecha);
            $fecha_limite = $this->formatDate($item->fecha_limite);
            $fecha_actividad = $this->formatDate($item->fecha_actividad);
            $fecha_env_osiptel = $this->formatDate($item->fecha_env_osiptel);
        }
        return [
            $item->id,
            $item->region,
            $item->encargados,
            $item->ubigeo,
            $item->departamento,
            $item->ccpp,
            $item->indicador,
            $item->tecnologia,
            $dl_3g,
            $dl_4g,
            $ul_3g,
            $ul_4g,
            $item->cm_enviado,
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
            ],
        ];
    }

    public function columnFormats(): array
    {
        if($this->applyFormat){
            return [
                'D' => NumberFormat::FORMAT_TEXT,
                // 'H' => NumberFormat::FORMAT_PERCENTAGE_00,
                'I' => NumberFormat::FORMAT_PERCENTAGE_00,
                'J' => NumberFormat::FORMAT_PERCENTAGE_00,
                'K' => NumberFormat::FORMAT_PERCENTAGE_00,
                'L' => NumberFormat::FORMAT_PERCENTAGE_00,
                'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                'T' => NumberFormat::FORMAT_DATE_DDMMYYYY,
                'Y' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            ];
        }
        return [];
    }
}
