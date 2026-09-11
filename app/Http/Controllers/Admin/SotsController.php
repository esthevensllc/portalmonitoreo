<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\WriterType;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SotsController extends BaseListController
{
    protected function beforeSetup()
    {
        $this->base_module_id = 11966;
        parent::beforeSetup();
        // dd($this->crud->filtersEnabled());
        // dd($this->crud->getListView());
        $this->crud->set("custom.fechas", $this->getFechas());
        $this->crud->set("custom.estados", []);
        $this->crud->set("list.view", 'factibilidad_fija.sots_instalaciones');
        // dd($this->crud->get("list.view"));
        $this->route = config('backpack.base.route_prefix')."/sots/{$this->id_tracing}";
        // dd($this->crud->getListView());
        
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.csv",
            "type" => WriterType::CSV,
        ];
    }

    /*public function loadFilters(){

    }*/

    public function setupListOperation(){
        // $this->crud->addButtonFromView('line', 'verBusquedaFats', 'verBusquedaFats', 'beginning');
        $this->crud->addColumn([
            "name" => "verBusquedaFats",
            "label" => "RUTA",
            "type" => "view",
            "view" => "backpack::crud.buttons.verBusquedaFats",
            /*"type" => "closure",
            "function" => function($entry){
                return "TESTING";
            },*/
        ]);
        parent::setupListOperation();
        $this->crud->setDefaultPageLength(50);
        $this->crud->orderBy("fecha_generacion_sot", "desc");
    }

    public function busquedaFatsView(){
        return view("factibilidad_fija.sotsFatsMap");
    }

    public function getFechas(){
        $query = "SELECT
        to_char(FECHA_GENERACION_SOT, 'yyyy-mm-dd') value,
        to_char(FECHA_GENERACION_SOT, 'yyyy-mm-dd') label
        FROM FIJA_SOTS_FACTIBILIDAD
        WHERE FECHA_GENERACION_SOT >= sysdate - 20
        GROUP BY to_char(FECHA_GENERACION_SOT, 'yyyy-mm-dd')
        ORDER BY to_char(FECHA_GENERACION_SOT, 'yyyy-mm-dd') DESC
        FETCH FIRST '7' ROWS only";
        return DB::select($query);
    }

    public function getEstados(Request $request){
        $query = "SELECT
        CASE
            WHEN estado LIKE 'Rechazad%' THEN 'Rechazad'
            WHEN estado LIKE 'Anulad%' THEN 'Anulad'
            WHEN estado is null THEN 'Sin Estado'
        ELSE estado END value,
        nvl(estado, 'Sin Estado') label,
        counter
        FROM (
            SELECT
            initcap(CASE
                WHEN estado LIKE 'Rechazad%' THEN 'Rechazado'
                WHEN estado LIKE 'Anulad%' THEN 'Anulado'
            ELSE estado END) estado,
            count(*) counter
            FROM FIJA_SOTS_FACTIBILIDAD
            [str_filters]
            GROUP BY initcap(CASE
                WHEN estado LIKE 'Rechazad%' THEN 'Rechazado'
                WHEN estado LIKE 'Anulad%' THEN 'Anulado'
            ELSE estado end)
        )";
        $filters = [];
        $values = [];
        if($request->get("estado") !== null){
            $filters[] = "lower(estado) like '%'||lower(:estado)||'%'";
            $json = json_decode($request->get("estado"));
            $values["estado"] = $json->value;
        }
        if($request->get("fecha_generacion_sot") !== null){
            $json = json_decode($request->get("fecha_generacion_sot"));
            if($json->operator === "date_between"){
                $dateValues = explode(',', $json->value);
                $filters[] = "fecha_generacion_sot >= TO_DATE(:fecha_ini, 'YYYY-MM-DD') AND fecha_generacion_sot < TO_DATE(:fecha_fin, 'YYYY-MM-DD')";
                $values["fecha_ini"] = $dateValues[0];
                $values["fecha_fin"] = $dateValues[1];
            }else{
                $filters[] = "trunc(fecha_generacion_sot, 'dd') = to_date(:fecha, 'yyyy-mm-dd')";
                $values["fecha"] = $json->value;
            }
        }
        if(count($filters) > 0){
            $query = str_replace("[str_filters]", " where ".implode(" and ", $filters), $query);
        }else{
            $query = str_replace("[str_filters]", "", $query);
        }
        return DB::select($query, $values);
    }

    public function getSummary(Request $request)
    {
        $query = "SELECT
        count(*) sots,
        count(case when plano_busqueda is not null and fat_busqueda is not null then 1 end) planos_fat,
        count(plano_busqueda) planos,
        count(case when plano_busqueda is null then 1 end) sin_planos
        FROM FIJA_SOTS_FACTIBILIDAD";
        
        $filters = [];
        $values = [];
        if($request->get("estado") !== null){
            $filters[] = "lower(estado) like '%'||lower(:estado)||'%'";
            $json = json_decode($request->get("estado"));
            $values["estado"] = $json->value;
        }
        if($request->get("fecha_generacion_sot") !== null){
            $json = json_decode($request->get("fecha_generacion_sot"));
            if($json->operator === "date_between"){
                $dateValues = explode(',', $json->value);
                $filters[] = "fecha_generacion_sot >= TO_DATE(:fecha_ini, 'YYYY-MM-DD') AND fecha_generacion_sot < TO_DATE(:fecha_fin, 'YYYY-MM-DD')";
                $values["fecha_ini"] = $dateValues[0];
                $values["fecha_fin"] = $dateValues[1];
            }else{
                $filters[] = "trunc(fecha_generacion_sot, 'dd') = to_date(:fecha, 'yyyy-mm-dd')";
                $values["fecha"] = $json->value;
            }
        }
        if(count($filters) > 0){
            $query .= " WHERE ".implode(" AND ", $filters);
        }
        $data = DB::select($query, $values);
        return response()->json($data[0]);
    }
}
