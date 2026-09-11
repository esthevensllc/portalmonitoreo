<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\REPORTE_CAPACIDAD_LOGRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
/**
 * Class REPORTE_CAPACIDAD_LOGCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class REPORTE_CAPACIDAD_LOGCrudController extends CrudController
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
        CRUD::setModel(\App\Models\REPORTE_CAPACIDAD_LOG::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reporte_capacidad_log');
        CRUD::setEntityNameStrings('reporte_capacidad_log', 'REPORTE_CAPACIDAD_LOG');
        $this->loadFilters();
    }

    private function loadFilters(){

       


        
        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->addFilter([
            'name'  => 'mes',
            'type'  => 'dropdown',
            'label' => 'MES'
        ], [
            'ENERO' => 'ENERO',
            'FEBRERO' => 'FEBRERO',
            'MARZO' => 'MARZO',
            'ABRIL' => 'ABRIL',
            'MAYO' => 'MAYO',
            'JUNIO' => 'JUNIO',
            'JULIO' => 'JULIO',
            'AGOSTO' => 'AGOSTO',
            'SETIEMBRE' => 'SETIEMBRE',
            'OCTUBRE' => 'OCTUBRE',
            'NOVIEMBRE' => 'ENERO',
            'DICIEMBRE' => 'DICIEMBRE',
            
        ]
        ,
         function($value) { // if the filter is active
             $this->crud->addClause('where', 'MES', $value);
        });


        $this->crud->addFilter([
            'name'  => 'tecnologia',
            'type'  => 'dropdown',
            'label' => 'TECNOLOGIA'
        ], [
            '2G' => '2G',
            '3G' => '3G',
            '4G' => '4G',
            '5G' => '5G'
        ]
        ,
         function($value) { // if the filter is active
             $this->crud->addClause('where', 'TECNOLOGIA', $value);
        });


        $this->crud->addButtonFromView('line', 'verRegistrosCapacidad', 'verRegistrosCapacidad', 'beginning');
        CRUD::setFromDb(); // columns

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
        CRUD::setValidation(REPORTE_CAPACIDAD_LOGRequest::class);

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

    public function verRegistros($id)
    {
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        $mes = $entry->mes;
        $tecnologia = $entry->tecnologia;
        // whatever you decide to do
        if($mes == 'ENERO'){
            $fecha = '01';
        }else if($mes == 'FEBRERO'){
            $fecha = '02';
        }else if($mes == 'MARZO'){
            $fecha = '03';
        }else if($mes == 'ABRIL'){
            $fecha = '04';
        }else if($mes == 'MAYO'){
            $fecha = '05';
        }else if($mes == 'JUNIO'){
            $fecha = '06';
        }else if($mes == 'JULIO'){
            $fecha = '07';
        }else if($mes == 'AGOSTO'){
            $fecha = '08';
        }else if($mes == 'SETIMBRE'){
            $fecha = '09';
        }else if($mes == 'OCTUBRE'){
            $fecha = '10';
        }else if($mes == 'NOVIEMBRE'){
            $fecha = '11';
        }else if($mes == 'DICIEMBRE'){
            $fecha = '12';
        }

            $query = DB::select("SELECT DIA, TECNOLOGIA FROM LOG_REPORTE_CAPACIDAD where to_char(to_date(DIA,'DD/MM/YYYY'),'MM') = ".$fecha." 
            and tecnologia = '".$tecnologia."' order by to_char(to_date(DIA,'DD/MM/YYYY'),'DD')");
           
            $data = $query;
              
            return response()->json(compact('data'));
        
        
    }
}
