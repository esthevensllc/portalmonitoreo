<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\WriterType;
use DateTime;

class ConfiguracionController extends BaseListController
{
    protected function beforeSetup()
    {
        $this->base_module_id = 14;// 10142
        parent::beforeSetup();
        // dd(PSO_BASE_TABLE_QUERY);
        $this->route = config('backpack.base.route_prefix')."/configuracion/{$this->id_tracing}";
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.csv",
            "type" => WriterType::CSV,
        ];
    }
}
