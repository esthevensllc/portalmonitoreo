<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\ExportService;
use AMovil\Shared\Exports\Domain\WriterType;
use App\Models\Provision\ProvisionSharedModel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class ProvisionController extends BaseListController
{
    protected function beforeSetup()
    {
        $this->base_module_id = 13;
        parent::beforeSetup();
        $this->route = config('backpack.base.route_prefix')."/provision/{$this->id_tracing}";
        
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.csv",
            "type" => WriterType::CSV,
        ];
    }

    /*
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    private $id_tracing;
    private $title;
    private $fields = [];

    public function setup()
    {
	    $this->beforeSetup();
        CRUD::setModel(ProvisionSharedModel::class);
        
        CRUD::setRoute(config('backpack.base.route_prefix')."/provision/{$this->id_tracing}");
        //CRUD::setEntityNameStrings('Desempeño', 'TRANSPORTE | Desempeño');
	    CRUD::setEntityNameStrings("{$this->title}", "{$this->title} | Provisión");
        $this->loadFilters();
    }

    public function beforeSetup()
    {
        $request = $this->crud->getRequest();
        $this->id_tracing = $request->route('id_tracing');

        $trac_config = DB::table('pso_type t')
        ->select("t.type_description as trac_title", "t3.type_description as trac_sql")
        ->join("pso_type t1", function($join){
            $join->on("t1.type_father", "=", "t.id_type")
            ->where("t1.type_name", "=", DB::raw('to_char(13)'))
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

        if($trac_config === null){
            throw new Exception("Error en configuración del portal");
        }

        $this->title = $trac_config->trac_title;
        
        if(!defined("PSO_BASE_TABLE_QUERY")){
            define("PSO_BASE_TABLE_QUERY", $this->get_query_parts($trac_config->trac_sql));
        }

        $fields_config = DB::table('pso_type t')
        ->select("t2.id_type", "t2.type_name as name", "t2.type_description as label")
        ->join("pso_type t1", function($join){
            $join->on("t1.type_father", "=", "t.id_type")
            ->where("t1.type_name", "=", DB::raw("to_char(13)"))
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
            'select' => $select_sql,
            'from' => trim($from_query),
            'where' => $where_sql,
        ];
    }

    private function loadFilters()
    {
        foreach($this->fields as $field => $config){
            $label = strtoupper($field);
            if(array_key_exists("label", $config)){
                $label = $config["label"];
            }
            $this->loadCriteriaFilter($field, $label);
        }
    }

    protected function setupListOperation()
    {
        foreach($this->fields as $field => $config){
            $col_options = ['name' => $field, "type" => "text"];
            if(array_key_exists("label", $config)){
                $col_options["label"] = $config["label"];
            }
            $this->crud->addColumn($col_options);
        }
        $this->crud->removeButton('update');
        $this->crud->addButtonFromView('top', 'import', 'exportButton', 'end');
    }

    public function export()
    {
        $export = app(ExportService::class);
        $collection = $this->crud->getEntries();

        $headers = $this->fields;
        foreach($headers as $field => $config){
            if(!array_key_exists("label", $config)){
                $headers[$field] = ["label" => strtoupper($field)];
            }
        }

        $export->loadData($headers, $collection);
        $content = $export->getWriter(WriterType::CSV)->getOutput();
        $filename = "{$this->title}_Provision.csv";
        return response($content, 200, [
            //'Content-Encoding' => 'UTF-8',
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;filename="'.$filename.'"'
        ]);
    }
    */


}
