<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\ExportService;
use AMovil\Shared\Exports\Domain\WriterType;
use App\Models\Provision\ProvisionSharedModel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;
use Illuminate\Support\Facades\DB;

class BaseListController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    protected $id_tracing;
    protected $trac_name;
    protected $trac_description;
    protected $module_name;
    protected $fields = [];
    protected $route = "";
    protected $base_module_id = 13;
    protected $exportOptions = [
        "filename" => "",
        "type" => WriterType::CSV,
    ];
    protected $tracOptions = [];

    public function setup()
    {
	    $this->beforeSetup();
        CRUD::setModel(ProvisionSharedModel::class);
        
        CRUD::setRoute($this->route);
	    CRUD::setEntityNameStrings(
            "{$this->main_module_title} | {$this->trac_description} | {$this->module_name}",
            "{$this->main_module_title} | {$this->trac_description} | {$this->module_name}"
        );

        $this->crud->query = ProvisionSharedModel::query();
        $this->loadFilters();
    }

    protected function beforeSetup()
    {
        $request = $this->crud->getRequest();
        $this->id_tracing = $request->route('id_tracing');

        $trac_config = DB::table('pso_type t')
        ->select("t.type_description as trac_title", "t3.type_description as trac_sql")
        ->join("pso_type t1", function($join){
            $join->on("t1.type_father", "=", "t.id_type")
            ->where("t1.type_name", "=", DB::raw('to_char('.$this->base_module_id.')'))
            ->where("t1.status", "=", DB::raw('1'));
        })
        ->join("pso_type t2", function($join){
            $join->on("t2.type_father", "=", "t1.id_type")
            ->where("t2.type_name", "=", DB::raw('to_char(8)'))
            ->where("t2.status", "=", DB::raw('1'));
        })
        ->join("pso_type t3", function($join){
            $join->on("t3.type_father", "=", "t2.id_type")
            ->where("t3.status", "=", DB::raw('1'));
        })
        ->where('t.type_name', $this->id_tracing)
        ->where('t.type_father', 232)
        ->where('t.status', 1)
        ->first();

        $base_module_title = DB::table("pso_type")
        ->where('id_type', $this->base_module_id)
        ->where('status', 1)
        ->first();

        $main_module_title = DB::table("pso_seguimientone pso")
        ->select(
            "pso.tracing_name as tracing_name",
            "pso.tracing_description as tracing_description",
            "type.type_name as main_title",
            "type.type_description as main_icon"
        )
        ->join("pso_type type", "type.id_type", "=", "pso.id_type")
        ->where('pso.id_tracing', $this->id_tracing)
        ->where('pso.status', 1)
        ->first();

        if($trac_config === null || $base_module_title == null){
            throw new Exception("Error en configuración del portal");
        }

        //$this->title = $trac_config->trac_title;
        $this->module_name = $base_module_title->type_name;
        $this->main_module_title = $main_module_title->main_title;
        $this->trac_name = $main_module_title->tracing_name;
        $this->trac_description = $main_module_title->tracing_description;
        
        /*if(!defined("PSO_BASE_TABLE_QUERY")){
            define("PSO_BASE_TABLE_QUERY", $this->get_query_parts($trac_config->trac_sql));
        }*/

        $fields_config = DB::table('pso_type t')
        ->select("t2.id_type", "t2.type_name as name", "t2.type_description as label")
        ->join("pso_type t1", function($join){
            $join->on("t1.type_father", "=", "t.id_type")
            ->where("t1.type_name", "=", DB::raw("to_char({$this->base_module_id})"))
            ->where("t1.status", "=", DB::raw('1'));
        })
        ->join("pso_type t2", function($join){
            $join->on("t2.type_father", "=", "t1.id_type")
            ->where("t2.status", "=", DB::raw('1'));
        })
        ->where('t.type_name', $this->id_tracing)
        ->where('t.type_father', 2371)
        ->where('t.status', 1)
        ->get();

        if(count($fields_config) > 0){
            foreach($fields_config as $row){
                $field_name = strtolower($row->name);
                if(strpos($field_name, " ")){
                    $field_name_parts = explode(" ", $field_name);
                    $field_name = $field_name_parts[count($field_name_parts)-1];
                }
                if(!in_array($field_name, ["fila", "f1"])){
                    $this->fields[$field_name] = ["label" => $row->label];
                }
            }
        }else{
            $response = DB::select(DB::raw("SELECT * FROM ({$trac_config->trac_sql}) FETCH FIRST '1' ROWS ONLY"));
            if(count($response) > 0){
                $fields = json_decode(json_encode($response[0]), true);
                foreach($fields as $field => $value){
                    if(!in_array($field, ["fila", "f1"])){
                        $this->fields[$field] = ["label" => strtoupper($field)];
                    }
                }
            }
        }

        // add mapper to fields
        $fieldValuesToMap = $this->getFiltersToMap($this->getKeyConfig());
        foreach($fieldValuesToMap as $field => $template){
            $this->fields[$field]["mapValueBy"] = $template;
        }

        if(!defined("PSO_BASE_TABLE_QUERY")){
            $query_fields = [];
            foreach($fields_config as $row){
                $query_fields[] = strtolower($row->name);
            }
            $query_parts = $this->get_query_parts($trac_config->trac_sql);
            if(count($query_fields) > 0){
                $query_parts["select"] = implode(" , ", $query_fields);
            }
            define("PSO_BASE_TABLE_QUERY", $query_parts);
        }

        if(!defined("PSO_BASE_TABLE_FIELDS")){
            define("PSO_BASE_TABLE_FIELDS", array_keys($this->fields));
        }
        if(!defined("PSO_BASE_TABLE_ID")){
            $fields = array_keys($this->fields);
            $table_id = nuLL;
            if(count($fields) > 1){
                $table_id = $fields[0];
            }
            define("PSO_BASE_TABLE_ID", $table_id);
        }
    }

    private function replaceFirst( $origen, $destino, $txt, $limit = -1)
    {
        $origen = '/'.preg_quote( ''.$origen, '/' ).'/i';
        return ''.preg_replace( $origen, ''.$destino, ''.$txt, $limit);
    }

    private function get_query_parts($query)
    {
        $query = strtoupper($query);
        $from_index = strpos($query, "FROM");
        $from_sql = substr($query, $from_index);
        $cacarter_index = strpos($from_sql,  "(");
    
        $from_query = substr($from_sql, 4, $cacarter_index ? $cacarter_index - 4 : null);
    
        $end_index_from = false;
        $subquery = false;
    
        if(str_replace([" ", "\n", "\r", "\t"], ["", "", "", ""], $from_query) === ""){
            // subquery
            $subquery = true;
            $count = substr_count($from_sql, "(");
            //$end_index_from = strpos($from_sql,  ")", $count-1);
            $from_sql = $this->replaceFirst(")", "@", $from_sql, $count-1);
            //$end_index_from = strpos($from_sql,  "WHERE");
            $end_index_from = strpos($from_sql,  ")");
            if($end_index_from === false){
            }
            $from_query = substr($from_sql, 4, $end_index_from-3);
            $from_query = str_replace("@", ")", $from_query);
        }else{
            $end_index_from = strpos($from_sql,  "WHERE");
            $from_query = substr($from_sql, 4, $end_index_from ? $end_index_from -4 : null);
        }
    
        $where_sql = "";
        if($end_index_from){
            $where_sql = substr($from_sql, $end_index_from+5);
        }
        $select_index = strpos($query, "SELECT");
        $select_sql = substr($query, $select_index+6, $from_index - $select_index - 6);
    
        return [
            'subquery' => $subquery,
            'query' => $query,
            'select' => strtolower(trim($select_sql)),
            'from' => strtolower(trim($from_query)),
            'where' => strtolower($where_sql),
        ];
    }

    protected function getKeyConfig()
    {
        $id_tracing = $this->crud->getRequest()->route("id_tracing");
        return $id_tracing;
    }

    protected function getFiltersToMap($key)
    {
        if(array_key_exists($key, $this->tracOptions)){
			return $this->tracOptions[$key]["fieldValuesToMap"] ?? [];
		}
		return [];
    }

    protected function loadFilters()
    {
        foreach($this->fields as $field => $config){
            $label = strtoupper($field);
            if(array_key_exists("label", $config)){
                $label = $config["label"];
            }
            if(array_key_exists("mapValueBy", $config)){
                $this->loadCriteriaFilter($field, $label, "default", function($criteria) use ($config) {
                    if($criteria->value !== null){
                        $resp = DB::select(DB::raw("select ".$config["mapValueBy"] . " as value from dual"), ["value" => $criteria->value]);
                        $criteria->value = $resp[0]->value;
                    }
                    return $criteria;
                });
            }else{
                $this->loadCriteriaFilter($field, $label);
            }
        }
    }

    protected function setupListOperation()
    {
        foreach($this->fields as $field => $config){
            $col_options = ['name' => $field, "type" => "text"];
            if(array_key_exists("label", $config)){
                $col_options["label"] = $config["label"];
            }
            if(array_key_exists("type", $config)){
                $col_options["type"] = $config["type"];
            }
            if(array_key_exists("function", $config)){
                $col_options["function"] = $config["function"];
            }
            if($col_options["label"] == "TIEMPO_TRANSCURRIDO"){
                $col_options["label"] = "TIEMPO TRANSCURRIDO";
                $col_options["type"] = "number";
                $col_options["decimals"] = 2;
                $col_options["dec_point"] = ".";
                $this->crud->enableExportButtons();
            }
            $this->crud->addColumn($col_options);
        }
        $this->crud->removeButton('update');
        $this->crud->custom_prop = 1;
        $this->crud->addButtonFromView('top', 'import', 'buttonOptions', 'end');
    }

    public function export()
    {
        $export = app(ExportService::class);
        //$this->crud->query = ProvisionSharedModel::query();
        $collection = $this->crud->getEntries();

        //dd($this->crud->query->toSql());
        //dd(ProvisionSharedModel::query()->toSql());

        $headers = $this->fields;
        foreach($headers as $field => $config){
            if(!array_key_exists("label", $config)){
                $headers[$field] = ["label" => strtoupper($field)];
            }
        }

        $export->loadData($headers, $collection);
        $content = $export->getWriter($this->exportOptions["type"])->getOutput();
        $filename = $this->exportOptions["filename"];

        $headers_type = [
            'csv' => [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment;filename="'.$filename.'"'
            ],
            'xlsx' => [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="'.$filename.'"'
            ],
        ];

        return response($content, 200, $headers_type[strtolower($this->exportOptions["type"])]);
    }
}
