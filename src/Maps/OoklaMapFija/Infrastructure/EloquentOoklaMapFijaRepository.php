<?php

namespace AMovil\Maps\OoklaMapFija\Infrastructure;

use AMovil\Maps\OoklaMapFija\Domain\OoklaMapFijaRepository;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class EloquentOoklaMapFijaRepository implements OoklaMapFijaRepository
{
    public function getByMonthAndOperatorAndKpiname(DateTime $month, string $operator, string $kpiName) {
        $allowOperator = [
            'Claro' => 1,
            'Bitel' => 1,
            'Entel' => 1,
            'Movistar' => 1,
            'Win' => 1,
            'Claro Fibra' => 1,
            'WOW' => 1,
            'Movistar Fibra' => 1,
            'Mi Fibra' => 1,
        ];
        if (!array_key_exists($operator, $allowOperator)) {
            throw new Exception("El operador {$operator} no es válido");
        }
        $strMonth = $month->format("Y-m-d");
        $query = "SELECT
        --mes,
        attr_sim_operator_common_name operator,
        attr_location_latitude latitude,
        attr_location_longitude longitude,
        round(score, 4) score,
        val_download_kbps,
        val_upload_kbps,
        val_download_latency_iqm_ms,
        val_upload_latency_iqm_ms,
        num_devices
        from vw_mobile_speed_test_speedscore_fija
        where mes = to_date('{$strMonth}', 'yyyy-mm-dd')
        and attr_sim_operator_common_name = '{$operator}'
        and {$kpiName} is not null";
        
        return DB::select($query);
    }
    
    public function getKpiMonths() {
        $query = "SELECT to_char(mes, 'yyyy-mm-dd') mes from vw_mobile_speed_test_speedscore_fija
        group by to_char(mes, 'yyyy-mm-dd')
        order by mes desc";
        return DB::select($query);
    }

    public function getKpiOperators() {
        $query = "SELECT attr_sim_operator_common_name operator from vw_mobile_speed_test_speedscore_fija
        group by attr_sim_operator_common_name
        order by (case when attr_sim_operator_common_name = 'Claro' then 1 else null end)";
        return DB::select($query);
    }
    
    public function getLastMonths(int $months) {
        $query = "SELECT
        to_char(mes, 'yyyy-mm-dd') mes,
        attr_sim_operator_common_name operator,
        round(median(score), 2)*100 score,
        round(median(val_download_kbps), 2) val_download_kbps,
        round(median(val_upload_kbps), 2) val_upload_kbps,
        round(median(val_download_latency_iqm_ms), 2) val_download_latency_iqm_ms,
        round(median(val_upload_latency_iqm_ms), 2) val_upload_latency_iqm_ms,
        round(sum(num_devices), 2) num_devices
        from vw_mobile_speed_test_speedscore_fija
        group by to_char(mes, 'yyyy-mm-dd'), attr_sim_operator_common_name
        order by mes";
        return DB::select($query);
    }
}
