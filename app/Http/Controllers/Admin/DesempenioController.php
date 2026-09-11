<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_4G_Movil_DesempeñoRequest;
use App\Models\Desempenio\SharedModel;
use App\Models\PSO_Type;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

class DesempenioController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;

    private $request;
    private $model;

    private $idtype_main;
    private $section_id;
    private $fields = [];
    private $ids = [];
    private $extraFilters;

    private $extra_filters = [
		"1" => [
			"rol_281" => [
				"menus_id" => ["369","29"],
				"filter_369" => [["CORPORATIVO", "=", "1"]],
				"filter_29" => [["CORPORATIVO", "=", "1"]],
			],
		],
		"2" => [
			"rol_281" => [
				"menus_id" => ["369","27","29"],
				"filter_369" => [["CORPORATIVO", "=", "1"]],
				"filter_27" => [["CORPORATIVO", "=", "1"]],
				"filter_29" => [["CORPORATIVO", "=", "1"]],
			],
		],
		"3" => [
			"rol_281" => [
				"menus_id" => ["369","27","29"],
				"filter_369" => [["CORPORATIVO", "=", "1"]],
				"filter_27" => [["CORPORATIVO", "=", "1"]],
				"filter_29" => [["CORPORATIVO", "=", "1"]],
			],
		],
	];

    public function beforeSetup(){
        $request = $this->crud->getRequest();
        $path_info = $request->getPathInfo();
        $uri_parts = explode("/", substr($path_info, 1));
        //dd($uri_parts);
        $id_tracing = $uri_parts[1];
        $menu_id = $uri_parts[2];
        $sect_id = $uri_parts[3] ?? $request->get('filterSect');

        $user_rols = backpack_user()->roles;
        $this->extraFilters = $this->getExtraFilterBy($id_tracing, count($user_rols)>0 ? $user_rols[0]->perfil : null);

        $main_type = PSO_Type::builderByTypeName_TypeFather($id_tracing, 232)->first();
        $main_sections = $this->getMainTypeSections($main_type->id_type);

        $this->title = $main_type->type_description;
        $this->id_tracing = $id_tracing;

        $main_module = DB::table("pso_seguimientone pso")
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

        $base_module_title = DB::table("pso_type")
        ->where('id_type', 16)
        ->where('status', 1)
        ->first();

        $this->title = $main_module->tracing_description;
        $this->main_module_title = $main_module->main_title;
        $this->module_name = $base_module_title->type_name;
        //$this->sect_id = $sect_id;
        
        $sections = PSO_Type::getByTypeFather($main_sections->id_type);
        $builder = DB::table("pso_type t1")
        ->select("t1.id_type", "t1.type_name", "t1.type_description", "t1.type_father",
        DB::raw("t2.id_type t2_id_type"), DB::raw("t2.type_name t2_type_name"), DB::raw("t2.type_description t2_type_description"), DB::raw("t2.type_father t2_type_father"))
        ->join("pso_type t2", DB::raw("to_char(t2.id_type)"), "=", DB::raw("t1.type_name"))
        ->where('t1.type_father', $main_sections->id_type)
        ->where('t1.status', 1);

        if($this->extraFilters !== null){
            $builder->whereIn("t2.id_type", $this->extraFilters["menus_id"]);
        }
        $sections = $builder->orderBy(DB::raw("to_number(t2.type_description, '999999999D9999999999999999999',' NLS_NUMERIC_CHARACTERS = ''.,''')"), "desc")
        ->get();

        $id_section = null;
        //Load table
        $counter = 0;
        /*dd([
            'PSO_BASE_TABLE' => defined("PSO_BASE_TABLE"),
            'PSO_BASE_TABLE_ID' => defined("PSO_BASE_TABLE_ID"),
            'PSO_BASE_TABLE_IDS' => defined("PSO_BASE_TABLE_IDS")
        ]);*/
        /*dd([
            "sect_id" => $sect_id,
            "id_section" => $id_section,
            "sections" => $sections->toArray()
        ]);*/

        foreach($sections as $index => $row){
            if($sect_id === $row->id_type || ($index+1 === count($sections) && $id_section === null)){
                $counter++;
                $id_section = $row->id_type;
                $this->id_section = $row->id_type;
                $section_config = PSO_Type::getByTypeName($row->id_type)->groupBy('type_father')->toArray();
                //dd($section_config);
                if(count($section_config) === 0){
                    continue;
                }
                /*dd([
                    "row" => $row,
                    "section_config" => $section_config
                ]);*/
                if(array_key_exists('331', $section_config)){
                    $table = strtolower($section_config['331'][0]['type_description']);
                    //SUB_REGION,REGION|SUB_REGION
                    $parts = explode("|", strtolower($row->type_description));
                    
                    $this->fields = explode(",", $parts[0]);
                    $this->ids = explode(",", $parts[1]);
                    $this->section_id = $row->id_type;
                    $this->idtype_main = $row->type_father;
                    if(!defined("PSO_BASE_TABLE")){
                        define("PSO_BASE_TABLE", $table);
                    }
                    if(!defined("PSO_BASE_TABLE_ID")){
                        define("PSO_BASE_TABLE_ID", $this->ids[0]);
                    }
                    if(!defined("PSO_BASE_TABLE_IDS")){
                        define("PSO_BASE_TABLE_IDS", $this->ids);
                    }
                }
            }
        }
    }

    private function getMainTypeSections($id_main_type){
        $main_config_desempenio = PSO_Type::builderByTypeName_TypeFather('16', $id_main_type)->first();
        $row = PSO_Type::builderByTypeName_TypeFather('10', $main_config_desempenio->id_type)->first();
        $busqueda_type = PSO_Type::getByTypeFather($row->id_type);
        foreach($busqueda_type as $row){
            return $row;
        }
        return null;
    }
    

    public function setup()
    {
	$this->beforeSetup();
        CRUD::setModel(SharedModel::class);
	//dd($this);
	//$this->crud->model->setIds($this->ids);
        
        CRUD::setRoute(config('backpack.base.route_prefix')."/desemp/{$this->id_tracing}/16/{$this->id_section}");
        //CRUD::setEntityNameStrings('Desempeño', 'TRANSPORTE | Desempeño');
	CRUD::setEntityNameStrings("{$this->main_module_title} | {$this->title}", "{$this->main_module_title} | {$this->title} | {$this->module_name}");
        $this->loadFilters();
    }

    public function loadFilters(){
        foreach($this->fields as $row){
            $this->loadCriteriaFilter($row, strtoupper($row));
        }
    }

    protected function setupListOperation()
    {
        foreach($this->fields as $row){
            CRUD::column($row)->type('text');
        }
	\Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.switch",
            'label' => 'Agrupación:',
            'text1' => 'Individual',
            'text2' => 'Agrupado'
        ])->to('before_content');

        //sections
        $celdas = $this->crud->model->getCeldas($this->idtype_main);
        $celdasFiltered = [];
        $defaultFilters = [];
        if($this->extraFilters !== null){
            foreach ($celdas as $row) {
                if(in_array($row["TYPESEC"], $this->extraFilters["menus_id"])){
                    $celdasFiltered[] = $row;
                    if($this->section_id === $row["SECID"]){
                        $defaultFilters = $this->extraFilters["filter_{$row['TYPESEC']}"];
                    }
                }
            }
        }else{
            $celdasFiltered = $celdas;
        }
	    $secids = array_column($celdasFiltered, 'SECID');
        $sectnames = array_column($celdasFiltered, 'SECTNAME');
        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.select",
            'options' => $secids,
            'values' => $sectnames,
            'selected' => $this->section_id
        ])->to('before_content');
        // dd(["secids" => $this->crud->model->getCeldas($this->idtype_main), "sectnames" => $sectnames]);

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.tabs"
        ])->to('before_content');

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.highchart"
        ])->to('after_content');

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.imput_group"
        ])->to('after_content');
	$this->crud->addButtonFromView('line', 'verGrafica', 'verGrafica', 'beginning');
	$this->crud->addButtonFromView('line', 'agregarGrafica', 'agregarGrafica', 'end');

        \Backpack\CRUD\app\Library\Widget::add([
            'type'     => 'view',
            'view'     => 'widgets.shared.config',
            'config' => [
                'id_tracing' => $this->id_tracing
            ],
        ])->to('after_content');

        foreach($defaultFilters as $row){
            $this->crud->addClause("where", $row[0], $row[1], $row[2]);
        }
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ACCESO_4G_Movil_DesempeñoRequest::class);
        CRUD::setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    private function getExtraFilterBy($id_tracing, $rol_id)
    {
        if(array_key_exists($id_tracing, $this->extra_filters)){
            if(array_key_exists("rol_".$rol_id, $this->extra_filters[$id_tracing])){
                return $this->extra_filters[$id_tracing]["rol_".$rol_id];
            }
        }
        return null;
    }
}
