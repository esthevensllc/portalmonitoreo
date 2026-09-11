<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\REPORTE_CAPACIDADRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;



use App\Exports\ReporteCapacidadExport;

/**
 * Class REPORTE_CAPACIDADCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class REPORTE_CAPACIDADCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\REPORTE_CAPACIDAD::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reporte_capacidad');
        CRUD::setEntityNameStrings('reporte_capacidad', 'Reporte Capacidad');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('FECHA', 'FECHA');
        $this->loadCriteriaFilter('ENODOB_NAME', 'ENODOB_NAME');
        $this->loadCriteriaFilter('ENODOB_ADDRESS', 'ENODOB_ADDRESS');
        $this->loadCriteriaFilter('USO_PRB', 'USO_PRB');
        //$this->loadCriteriaFilter('QUINCENA', 'QUINCENA');
        $this->loadCriteriaFilter('MES', 'MES');
        $this->loadCriteriaFilter('ANIO', 'AÑO');
        // $this->loadCriteriaFilter('GRUPO_MOTIVO', 'GRUPO_MOTIVO');
        // dd($this->crud->query->toSql());

        
        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb();
        

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        // dropdown filter
        $this->crud->addFilter([
            'name'  => 'quincena',
            'type'  => 'dropdown',
            'label' => 'QUINCENA'
        ], [
            'Primera quincena' => 'Primera quincena',
            'Segunda quincena' => 'Segunda quincena'
        ]
        ,
         function($value) { // if the filter is active
             $this->crud->addClause('where', 'QUINCENA', $value);
        });

         $this->crud->enableExportButtons();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(REPORTE_CAPACIDADRequest::class);

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

    public function export() 
    {
        $now = Carbon::now();

        return (new ReporteCapacidadExport)->download('REPORTE_CAPACIDAD'.$now->format('Ymd').'.xlsx');
    }
}
