<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\INCIDENCIAS_TIFRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

use App\Models\INCIDENCIAS_TIF;

use App\Imports\IncidenciasTifImport;

/**
 * Class INCIDENCIAS_TIFCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class INCIDENCIAS_TIFCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
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
        CRUD::setModel(\App\Models\INCIDENCIAS_TIF::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/incidencias_tif');
        CRUD::setEntityNameStrings('incidencias_tif', 'Incidencias');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('PERIODO', 'PERIODO');
        $this->loadCriteriaFilter('TICKET', 'TICKET', 'number');
        $this->loadCriteriaFilter('MASIVA', 'MASIVA', 'number');
        $this->loadCriteriaFilter('ACTIVO', 'ACTIVO', 'number');
        $this->loadCriteriaFilter('TIEMPO_REPARACION', 'TIEMPO REPARACIÓN', 'number');
        $this->loadCriteriaFilter('METRICA', 'METRICA');
        $this->loadCriteriaFilter('CANALES', 'CANALES', 'number');
        $this->loadCriteriaFilter('CODIGO_CLIENTE', 'CODIGO CLIENTE');
        $this->loadCriteriaFilter('NOMBRE_CLIENTE', 'NOMBRE CLIENTE');
        $this->loadCriteriaFilter('CAUSA', 'CAUSA');
        $this->loadCriteriaFilter('RESPONSABLE', 'RESPONSABLE');
        $this->loadCriteriaFilter('ESTADO_DE_ATENCION', 'ESTADO DE ATENCIÓN');
        $this->loadCriteriaFilter('FECHA_INICIO', 'FECHA INICIO');
        $this->loadCriteriaFilter('FECHA_FIN', 'FECHA FIN');
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

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        CRUD::column('nombre_cliente')->limit(1000);
        CRUD::column('causa')->limit(1000);
        CRUD::column('tiempo_reparacion')->type('number');
        CRUD::column('tiempo_reparacion')->decimals(4);

        $lastFecha = INCIDENCIAS_TIF::select('periodo')->orderBy('periodo','desc')->first();

        $this->crud->addClause('where','periodo','=',$lastFecha->periodo);
        
        $this->crud->enableExportButtons();

        $this->crud->addButtonFromView('top', 'import', 'importTif', 'end');

        $this->crud->removeButton('update');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(INCIDENCIAS_TIFRequest::class);

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

    public function import(Request $request) 
    {
        $file = $request->file('fileImport');

        Excel::import(new IncidenciasTifImport, $file);

        $periodo = INCIDENCIAS_TIF::OrderBy('created_at','desc')->first();

        DB::statement("CALL PK_PRG.SP_TIF_RESUMEN_VOZ('".$periodo->periodo."')");

        return back()->with('message', 'Importación de incidencias completada');
    }
}
