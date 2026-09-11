<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\WriterType;
use DateTime;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReportListController extends BaseListController
{
    private $defaultFilters = [
        '18-5610' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY')), 'YYYY-MM') AS value FROM PSO_0DATA_18_5610)"]
        ],
        '18-5611' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY')), 'YYYY-MM') AS value FROM PSO_0DATA_18_5611)"]
        ],
        '18-5612' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY')), 'YYYY-MM') AS value FROM PSO_0DATA_18_5612)"]
        ],
        '18-5613' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => '(SELECT MAX(FECHA_REPORTE) AS value FROM PSO_0DATA_18_5613)']
        ],
        '18-5614' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => '2022-02']
        ],
        '18-5615' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5615)']
        ],
        '18-5616' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY')), 'YYYY-MM') AS value FROM PSO_0DATA_18_5616)"]
        ],
        '18-5617' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5617)']
        ],
        '18-5618' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5618)']
        ],
        '18-5619' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(MES, 'DD/MM/YYYY')), 'YYYY-MM') AS VALUE FROM PSO_0DATA_18_5619_MES)"]
        ],
        '18-5620' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5620)']
        ],
        '18-5621' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY')), 'YYYY-MM') AS value FROM PSO_0DATA_18_5621)"]
        ],
        '18-5622' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT TO_CHAR(MAX(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY')), 'YYYY-MM') AS value FROM PSO_0DATA_18_5622)"]
        ],
        '18-5623' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5623)']
        ],
        '18-5625' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5625)']
        ],
        '18-5624' => [
            'fecha_reporte' => ['operator' => 'equals', 'value' => "(SELECT MAX(TO_CHAR(TO_DATE(FECHA_REPORTE, 'DD/MM/YYYY'),'YYYY-MM')) AS VALUE FROM PSO_0DATA_18_5624)"]
        ],
        '18-5626' => [
            'mes' => ['operator' => 'equals', 'value' => '(SELECT MAX(MES) AS value FROM PSO_0DATA_18_5626)']
        ],
    ];

    protected $tracOptions = [
        '18-5610' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5611' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5612' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        // '18-5613' => ["countTotalRows" => false],
        '18-5614' => [
            "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5615' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5616' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5617' => [
            "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5618' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5619' => [
            "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5620' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5621' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5622' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5623' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        '18-5624' => [
            "countTotalRows" => false,
            "fieldValuesToMap" => ["fecha_reporte" => "TO_CHAR(TO_DATE(:value, 'YYYY-MM'), 'DD/MM/YYYY')"]
        ],
        // '18-5625' => ["countTotalRows" => false],
        '18-5626' => [
            // "countTotalRows" => false,
            "fieldValuesToMap" => ["hora_pico_mes" => "TO_DATE(:value, 'DD/MM/YYYY HH24')"]
        ],
    ];

    protected function beforeSetup()
    {
        $this->base_module_id = $this->crud->getRequest()->route("menu_id");// 10142
        parent::beforeSetup();
        // dd(PSO_BASE_TABLE_QUERY);
        $this->route = config('backpack.base.route_prefix')."/replist/{$this->id_tracing}/{$this->base_module_id}";
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.csv",
            "type" => WriterType::CSV,
        ];
    }

    public function index()
    {
        $id_tracing = $this->crud->getRequest()->route("id_tracing");
        $menu_id = $this->crud->getRequest()->route("menu_id");
        // dd("{$id_tracing}-{$menu_id}");

        if(array_key_exists("{$id_tracing}-{$menu_id}", $this->defaultFilters)){
            $filters = $this->defaultFilters["{$id_tracing}-{$menu_id}"];

            $filtersIsNotApplied = true;
            foreach($filters as $i => $_){
                $filtersIsNotApplied = $this->crud->getRequest()->get($i) === null;
                if(!$filtersIsNotApplied){
                    break;
                }
            }

            if($filtersIsNotApplied){
                $str_filters = [];
                foreach($filters as $i => $row){
                    if(str_contains(strtoupper($row["value"]), "SELECT")){
                        $resp = DB::select($row["value"]);
                        if(count($resp) === 0){
                            throw new InvalidArgumentException("El filtro predeterminado para $i no es válido");
                        }
                        if(!isset($resp[0]->value)){
                            throw new InvalidArgumentException("El filtro predeterminado para $i no es válido");
                        }
                        $row["value"] = $resp[0]->value;
                    }
                    $str_filters[] = $i."=".urlencode(json_encode($row));
                }
                $str_filters = implode("&", $str_filters);
                //dd($str_filters);
                return redirect("replist/{$this->id_tracing}/{$menu_id}?".$str_filters);
            }
        }

        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        
        return view($this->crud->getListView(), $this->data);
    }

    protected function getKeyConfig()
    {
        $id_tracing = $this->crud->getRequest()->route("id_tracing");
        $menu_id = $this->crud->getRequest()->route("menu_id");
        return "{$id_tracing}-{$menu_id}";
    }
}
