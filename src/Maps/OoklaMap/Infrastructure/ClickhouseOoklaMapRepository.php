<?php

namespace AMovil\Maps\OoklaMap\Infrastructure;

use AMovil\Maps\OoklaMap\Domain\OoklaMapRepository;
use Illuminate\Support\Facades\DB;
use ClickHouseDB\Client;
use DateTime;
use Exception;

class ClickhouseOoklaMapRepository implements OoklaMapRepository
{
    public function getByMonthAndOperatorAndKpiname(DateTime $month, string $operator, string $kpiName) {
        $allowOperator = [
            'Claro' => 1,
            'Bitel' => 1,
            'Entel' => 1,
            'Movistar' => 1,
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
        from default.vw_mobile_speed_test_speedscore
        where mes = toDate('{$strMonth}')
        and attr_sim_operator_common_name = '{$operator}'
        and {$kpiName} is not null";
        
        return DB::connection("clickhouse")->select($query);
    }
    
    public function getKpiMonths() {
        $query = "SELECT mes from default.vw_mobile_speed_test_speedscore group by mes order by mes desc";
        return DB::connection("clickhouse")->select($query);
    }

    public function getKpiOperators() {
        $query = "SELECT attr_sim_operator_common_name operator from default.vw_mobile_speed_test_speedscore
        group by attr_sim_operator_common_name
        order by (case when attr_sim_operator_common_name = 'Claro' then 1 else null end)";
        return DB::connection("clickhouse")->select($query);
    }
    
    public function getLastMonths(int $months) {
        $query = "SELECT
        mes,
        attr_sim_operator_common_name operator,
        round(median(score), 2)*100 score,
        round(median(val_download_kbps), 2) val_download_kbps,
        round(median(val_upload_kbps), 2) val_upload_kbps,
        round(median(val_download_latency_iqm_ms), 2) val_download_latency_iqm_ms,
        round(median(val_upload_latency_iqm_ms), 2) val_upload_latency_iqm_ms,
        round(sum(num_devices), 2) num_devices
        from default.vw_mobile_speed_test_speedscore
        group by mes, attr_sim_operator_common_name
        order by mes";
        return DB::connection("clickhouse")->select($query);
    }
}
