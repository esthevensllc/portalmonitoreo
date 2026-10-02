<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ListaDiariaTwampExport implements FromQuery, WithHeadings
{
    /**
     * Exporta la lista diaria del día anterior completo usando la fecha de Oracle.
     * Solo considera FO, MW y FO+MW cuyo AVG_DELAY_MAX_FB supera el umbral
     * correspondiente al medio de transmisión.
     */
    public function query()
    {
        return DB::table('RSS_TX_TWAMP_DIA')
            ->select([
                'RESULT_TIME',
                'MBTS_NAME',
                'NOMBRE_TERCERO',
                'REGION',
                'DEPARTAMENTO',
                'PROVINCIA',
                'DISTRITO',
                'MEDIO_TX',
                DB::raw('ESTADO_DELAY18X3 AS ESTADO_DELAY'),
                'UMBRAL_FO',
                'UMBRAL_MW',
                DB::raw('ROUND(AVG_DELAY_MAX_FB, 1) AS AVG_DELAY_MS'),
                DB::raw("CASE
                    WHEN MEDIO_TX = 'FO' AND AVG_DELAY_MAX_FB > UMBRAL_FO THEN 1
                    WHEN MEDIO_TX IN ('MW','FO+MW') AND AVG_DELAY_MAX_FB > UMBRAL_MW THEN 1
                END AS UP_UMBRAL"),
            ])
            ->whereRaw("RESULT_TIME >= TRUNC(SYSDATE, 'DD') - 1")
            ->whereRaw("RESULT_TIME < TRUNC(SYSDATE, 'DD')")
            ->whereIn('MEDIO_TX', ['FO', 'MW', 'FO+MW'])
            ->whereRaw("CASE
                WHEN MEDIO_TX = 'FO' AND AVG_DELAY_MAX_FB > UMBRAL_FO THEN 1
                WHEN MEDIO_TX IN ('MW','FO+MW') AND AVG_DELAY_MAX_FB > UMBRAL_MW THEN 1
            END = 1")
            ->orderBy('RESULT_TIME')
            ->orderBy('MBTS_NAME');
    }

    public function headings(): array
    {
        return [
            'RESULT_TIME',
            'MBTS_NAME',
            'NOMBRE_TERCERO',
            'REGION',
            'DEPARTAMENTO',
            'PROVINCIA',
            'DISTRITO',
            'MEDIO_TX',
            'ESTADO_DELAY',
            'UMBRAL_FO',
            'UMBRAL_MW',
            'AVG_DELAY_MS',
            'UP_UMBRAL',
        ];
    }
}
