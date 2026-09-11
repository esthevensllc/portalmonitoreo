<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\REPORTE_DEPARTAMENTO_MENSUAL_3GRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class REPORTE_DEPARTAMENTO_MENSUAL_3GCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class REPORTE_DEPARTAMENTO_MENSUAL_3GCrudController extends CrudController
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
        CRUD::setModel(\App\Models\REPORTE_DEPARTAMENTO_MENSUAL_3G::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reporte_departamento_mensual_3g');
        CRUD::setEntityNameStrings('reporte_departamento_mensual_3g', 'Reporte Departamento Mensual 3G');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('departamento', 'DEPARTAMENTO');
        $this->loadCriteriaFilter('tine', 'TINE %', 'number');
        $this->loadCriteriaFilter('tlli', 'TLLI %', 'number');
        $this->loadCriteriaFilter('trafico', 'TRAFICO', 'number');
        $this->loadCriteriaFilter('mes', 'MES');
        $this->loadCriteriaFilter('anio', 'AÑO');
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
            'name' => 'departamento', // The db column name
            'label' => "DEPARTAMENTO", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'tine', // The db column name
            'label' => "TINE %", // Table column heading
            'type' => 'number',
            'decimals' => 4,
            'dec_point' => '.',
        ]);

        $this->crud->addColumn([
            'name' => 'tlli', // The db column name
            'label' => "TLLI %", // Table column heading
            'type' => 'number',
            'decimals' => 4,
            'dec_point' => '.',
        ]);
        
        $this->crud->addColumn([
            'name' => 'trafico', // The db column name
            'label' => "TRAFICO", // Table column heading
            'type' => 'number',
            'decimals' => 4,
            'dec_point' => '.',
            'thousands_sep' => ''
        ]);
        
        $this->crud->addColumn([
            'name' => 'mes', // The db column name
            'label' => "MES", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'anio', // The db column name
            'label' => "AÑO", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addClause('where','estado','=',1);

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
        CRUD::setValidation(REPORTE_DEPARTAMENTO_MENSUAL_3GRequest::class);

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
}
