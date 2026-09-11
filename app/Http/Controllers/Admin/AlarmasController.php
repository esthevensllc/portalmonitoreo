<?php

namespace App\Http\Controllers\Admin;

use AMovil\Shared\Exports\Domain\ExportService;
use AMovil\Shared\Exports\Domain\WriterType;
use App\Models\Alarmas\AlarmaSharedModel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class AlarmasController extends BaseListController
{
    use \App\Traits\Filters\CriteriaFilterTrait;
    /*
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    private $id_tracing;
    private $title;
    private $fields = [];
    */

    protected function beforeSetup()
    {
        $this->base_module_id = 15;
        parent::beforeSetup();
        $this->route = config('backpack.base.route_prefix')."/alarmas/{$this->id_tracing}";
        
        $str_date = (new DateTime())->format("YmdHis");
        $this->exportOptions = [
            "filename" => "{$this->trac_name}_{$this->module_name}_{$str_date}.csv",
            "type" => WriterType::CSV,
        ];
    }

    protected function loadFilters()
    {
        $this->crud->addFilter([   // date_range
            'type' => 'date_range', // db columns for start_date & end_date
            'name' => 'occurrencetime', // db columns for start_date & end_date
            'label' => 'Intervalo fechas'
        ], false, function($value){
            $dates = json_decode($value);
            $this->crud->addClause('where', 'occurrencetime', '>=', $dates->from);
            $this->crud->addClause('where', 'occurrencetime', '<=', $dates->to);
        });

        foreach($this->fields as $field => $config){
            $label = strtoupper($field);
            if(array_key_exists("label", $config)){
                $label = $config["label"];
            }
            $type = "default";
            if($field === "occurrencetime"){
                $type = "date";
            }else{
                $this->loadCriteriaFilter($field, $label, $type);
            }
        }
    }

    /*
    public function setup()
    {
	    $this->beforeSetup();
        CRUD::setModel(AlarmaSharedModel::class);

        //$model = new AlarmaSharedModel();

        //dd($model->newQuery());
        
        CRUD::setRoute(config('backpack.base.route_prefix')."/alarmas/{$this->id_tracing}");
        //CRUD::setEntityNameStrings('Desempeño', 'TRANSPORTE | Desempeño');
	    CRUD::setEntityNameStrings("{$this->title}", "{$this->title} | Alarmas");
        $this->loadFilters();
    }

    private function beforeSetup()
    {
        $request = $this->crud->getRequest();
        $this->id_tracing = $request->route('id_tracing');

        $main_type = DB::table('pso_type')
        ->where('type_name', $this->id_tracing)
        ->where('type_father', 232)
        ->where('status', 1)
        ->first();

        $mod_main_type = DB::table('pso_type')
        ->where('type_father', $main_type !== null ? $main_type->id_type : -1)
        ->where('type_name', 15)
        ->where('status', 1)
        ->first();

        $mod_main_type = DB::table('pso_type')
        ->where('type_father', $mod_main_type !== null ? $mod_main_type->id_type : -1)
        ->where('status', 1)
        ->first();

        $busqueda_father = DB::table('pso_type')
        ->where('type_father', $mod_main_type !== null ? $mod_main_type->id_type : -1)
        ->where('status', 1)
        ->first();

        if($busqueda_father === null){
            throw new Exception("Error en configuración del portal");
        }

        // fields config
        $father_fields = DB::table('pso_type')
        ->where('type_name', $this->id_tracing)
        ->where('type_father', 2371)
        ->where('status', 1)
        ->first();

        $main = DB::table('pso_type')
        ->where('type_father', $father_fields !== null ? $father_fields->id_type : -1)
        ->where('type_name', "15")
        ->where('status', 1)
        ->first();
        
        $fields = DB::table('pso_type')
        ->where('type_father', $main !== null ? $main->id_type : -1)
        ->where('status', 1)
        ->get();

        $this->title = $main_type->type_description;

        if(!defined("PSO_BASE_TABLE_QUERY")){
            define("PSO_BASE_TABLE_QUERY", $this->get_query_parts($busqueda_father->type_description));
        }

        if(count($fields) > 0){
            foreach($fields as $row){
                $field_name = strtolower($row->type_name);
                if(strpos($field_name, " ")){
                    $field_name_parts = explode(" ", $field_name);
                    $field_name = $field_name_parts[count($field_name_parts)-1];
                }
                $this->fields[$field_name] = ["label" => $row->type_description];
            }
        }else{
            $response = DB::select(DB::raw("SELECT * FROM ({$busqueda_father->type_description}) FETCH FIRST '1' ROWS ONLY"));
            if(count($response) > 0){
                $fields = json_decode(json_encode($response[0]), true);
                foreach($fields as $field => $value){
                    $this->fields[$field] = [];
                }
            }
        }
        if(!defined("PSO_BASE_TABLE_ID")){
            $fields = array_keys($this->fields);
            $table_id = nuLL;
            if(count($fields) > 1){
                $table_id = $fields[0];
            }
            define("PSO_BASE_TABLE_ID", $table_id);
        }
        if(!defined("PSO_BASE_TABLE_FIELDS")){
            define("PSO_BASE_TABLE_FIELDS", array_keys($this->fields));
        }
        //dd($this->fields);
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
            //'selcect_pso' => strpos($query, "SELECT"),
            //'substr' => substr($query, $from_index),
            //'count' => $count,
            //'from_sql' => $from_sql,
            //'end_index_from' => $end_index_from,
            'select' => $select_sql,
            'from' => trim($from_query),
            'where' => $where_sql,
        ];
    }

    private function loadFilters()
    {
        $this->crud->addFilter([   // date_range
            'type' => 'date_range', // db columns for start_date & end_date
            'name' => 'occurrencetime', // db columns for start_date & end_date
            'label' => 'Intervalo fechas'
        ], false, function($value){
            $dates = json_decode($value);
            $this->crud->addClause('where', 'occurrencetime', '>=', $dates->from);
            $this->crud->addClause('where', 'occurrencetime', '<=', $dates->to);
        });

        foreach($this->fields as $field => $config){
            $label = strtoupper($field);
            if(array_key_exists("label", $config)){
                $label = $config["label"];
            }
            $type = "default";
            if($field === "occurrencetime"){
                $type = "date";
            }else{
                $this->loadCriteriaFilter($field, $label, $type);
            }
        }
    }

    protected function setupListOperation()
    {
        // CRUD::setFromDb();

        foreach($this->fields as $field => $config){
            $col_options = ['name' => $field, "type" => "text"];
            if(array_key_exists("label", $config)){
                $col_options["label"] = $config["label"];
            }
            $this->crud->addColumn($col_options);
        }
        //$this->crud->enableExportButtons();
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
        $filename = "{$this->title}_Alarmas.csv";
        return response($content, 200, [
            //'Content-Encoding' => 'UTF-8',
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;filename="'.$filename.'"'
        ]);
    }
    */

    public function index()
    {
        if($this->crud->getRequest()->get("occurrencetime") === null){
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", (new Datetime())->format("Y-m-d")." 00:00:00");
            $str_to = $dt->format("Y-m-d")." 23:59:59";
            $dt->modify("-3 month");
            $str_from = $dt->format("Y-m-d H:i:s");
            $alarmas_filter = urlencode(json_encode(["from" => $str_from, "to" => $str_to]));
            return redirect("alarmas/{$this->id_tracing}?occurrencetime=".$alarmas_filter);
        }

        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }
}
