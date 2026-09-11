<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LOG_OSIPTELRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDO;
/**
 * Class LOG_OSIPTELCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LOG_OSIPTELCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\LOG_OSIPTEL::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/log_osiptel');
        CRUD::setEntityNameStrings('log_osiptel', 'Log Osiptel');
        $this->loadFilters();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        CRUD::setFromDb(); // columns

        $this->crud->addFilter([
            'name'  => 'tecnologia',
            'type'  => 'dropdown',
            'label' => 'TECNOLOGIA'
        ], [
            '3G' => '3G',
            '4G' => '4G'
        ]
        ,
         function($value) { // if the filter is active
             $this->crud->addClause('where', 'TECNOLOGIA', $value);
        });
        $this->crud->addFilter([
            'name'  => 'formato',
            'type'  => 'dropdown',
            'label' => 'FORMATO'
        ], [
            'FORMATO 1' => 'FORMATO 1',
            'FORMATO 2' => 'FORMATO 2'
        ]
        ,
         function($value) { // if the filter is active
             $this->crud->addClause('where', 'FORMATO', $value);
        });
        $this->crud->addFilter([
            'name'  => 'trimestre',
            'type'  => 'dropdown',
            'label' => 'TRIMESTRE'
        ], [
            'PRIMERO' => 'PRIMERO',
            'SEGUNDO' => 'SEGUNDO',
            'TERCERO' => 'TERCERO',
            'CUARTO' => 'CUARTO'
        ]
        ,
         function($value) { // if the filter is active
             $this->crud->addClause('where', 'TRIMESTRE', $value);
        });

        $this->crud->addButtonFromView('line', 'verRegistrosOsiptel', 'verRegistrosOsiptel', 'beginning');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(LOG_OSIPTELRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('RESULT_TIME', 'FECHA');
        // $this->loadCriteriaFilter('GRUPO_MOTIVO', 'GRUPO_MOTIVO');
        // dd($this->crud->query->toSql());

        
        
    }

    public function verRegistros($id)
    {
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        $nodob_name = $entry->nodob_name;
        $nodob_address = $entry->nodob_address;
        $cellname = $entry->cellname;
        $fecha = $entry->result_time;
        $tecnologia = $entry->tecnologia;
        $formato = $entry->formato;
        // whatever you decide to do
        if($tecnologia == '3G' && $formato == 'FORMATO 1'){

            $query = DB::select("select  result_time, 
            PROVEEDOR, TECNOLOGIA, nodob_address,NODOB_NAME ,CELLNAME, 'Formato 1' formato
            from PRG_OSIPTEL_TH_3G  
            WHERE NODOB_NAME = '".$nodob_name. "' AND  NODOB_ADDRESS  = '".$nodob_address. "' AND CELLNAME = '".$cellname. "' AND to_char(result_time,'yyyy-mm-dd') like '%".$fecha. "' ORDER BY result_time");
           
            $data = $query;
              
            return response()->json(compact('data'));
        }else if($tecnologia == '3G' && $formato == 'FORMATO 2'){
            $query = DB::select("select  result_time, 
            PROVEEDOR, TECNOLOGIA, nodob_address,NODOB_NAME , 'Formato 2' formato
            from PRG_OSIPTEL_USO_3G 
            WHERE NODOB_NAME = '".$nodob_name. "' AND  NODOB_ADDRESS  = '".$nodob_address. "' AND to_char(result_time,'yyyy-mm-dd') like '%".$fecha. "' ORDER BY result_time");
           
            $data = $query;
              
            return response()->json(compact('data'));
        }else if($tecnologia == '4G' && $formato == 'FORMATO 1'){
            $query = DB::select("select  result_time, 
            PROVEEDOR, TECNOLOGIA, enodob_address,ENODOB_NAME , 'Formato 1' formato
            from PRG_OSIPTEL_TH_4G 
            WHERE ENODOB_NAME = '".$nodob_name. "' AND  ENODOB_ADDRESS  = '".$nodob_address. "' AND CELLNAME = '".$cellname. "' AND to_char(result_time,'yyyy-mm-dd') like '%".$fecha. "' ORDER BY result_time");
           
            $data = $query;
              
            return response()->json(compact('data'));
        }else if($tecnologia == '4G' && $formato == 'FORMATO 2'){
            $query = DB::select("select  result_time, 
            PROVEEDOR, TECNOLOGIA, enodob_address,ENODOB_NAME , 'Formato 2' formato
            from PRG_OSIPTEL_USO_4G 
            WHERE ENODOB_NAME = '".$nodob_name. "' AND  ENODOB_ADDRESS  = '".$nodob_address. "' AND to_char(result_time,'yyyy-mm-dd') like '%".$fecha. "' ORDER BY result_time");
           
            $data = $query;
              
            return response()->json(compact('data'));
        }
        
    }

    

}
