<?php

namespace App\Exports;

use App\Models\REPORTE_CAPACIDAD;

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

class ReporteCapacidadExport implements FromQuery,WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'FECHA','ENODOB_NAME','ENODOB_ADDRESS','USO_PRB','QUINCENAL','MES'
        ];
    }

    public function query()
    {
        return REPORTE_CAPACIDAD::select('fecha','enodob_name','enodob_adderss','uso_prb','quincenal','mes');
    }
}
