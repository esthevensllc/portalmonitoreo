<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LINEAS_TIFRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Illuminate\Support\Facades\DB; 

use App\Models\LINEAS_TIF;

/**
 * Class LINEAS_TIFCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LINEAS_TIFCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LINEAS_TIF::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lineas_tif');
        CRUD::setEntityNameStrings('lineas_tif', 'Líneas');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('ID', 'PERIODO');
        $this->loadCriteriaFilter('PERIODO', 'SERVICIO');
        $this->loadCriteriaFilter('SERVICIO', 'TOTAL');
        $this->loadCriteriaFilter('TOTAL', 'ACCIONES');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        
        $this->crud->addColumn([
            'name' => 'id', // The db column name
            'label' => "Periodo", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'periodo', // The db column name
            'label' => "Servicio", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'servicio', // The db column name
            'label' => "Total", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'total', // The db column name
            'label' => "Acciones", // Table column heading
            'type' => 'text'
        ]);

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
        CRUD::setValidation(LINEAS_TIFRequest::class);

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

    public function editar(Request $request)
    {
        $linea = LINEAS_TIF::find($request->id);

        $linea->total = $request->total;
        $linea->save();

        if($linea->servicio == 'VOZ'){
            DB::statement("CALL PK_PRG.SP_TIF_RESUMEN_VOZ('".$linea->periodo."')");
        }
        
        return back()->with('message', 'Total editado correctamente');
    }
}
