<?php

namespace App\Exports;

use App\Models\COBERTURA_Cobertura_Movil_Detalle;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

ini_set('memory_limit', -1);

class CoberturaMovilDetalleExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return ["ubigeo", "departamento", "provincia", "distrito", "ccpp", "operadora", "gsm", "umts", "lte", "trimestre", "clasif_inei", "cdma", "iden", "wimax", "lte_4_5g", "tec_5g", "habitantes"];
    }

    public function query()
    {
        return COBERTURA_Cobertura_Movil_Detalle::select(["ubigeo", "departamento", "provincia", "distrito", "ccpp", "operadora", "gsm", "umts", "lte", "trimestre", "clasif_inei", "cdma", "iden", "wimax", "lte_4_5g", "tec_5g", "habitantes"]);
    }
}
