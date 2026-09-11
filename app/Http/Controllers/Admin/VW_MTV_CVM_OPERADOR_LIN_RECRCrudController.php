<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VW_MTV_CVM_OPERADOR_LIN_RECRRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Entities\CriteriaOperatorEntity;

/**
 * Class VW_MTV_CVM_OPERADOR_LIN_RECRCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VW_MTV_CVM_OPERADOR_LIN_RECRCrudController extends CrudController
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
        CRUD::setModel(\App\Models\VW_MTV_CVM_OPERADOR_LIN_RECR::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/RESUMEN-CVM-OPERADOR');
        CRUD::setEntityNameStrings('RESUMEN-CVM-OPERADOR', 'Resumen Operador');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('SEMESTRE', 'SEMESTRE');
        $this->loadCriteriaFilter('UBIGEO', 'UBIGEO');
        $this->loadCriteriaFilter('REGION', 'REGION');
        $this->loadCriteriaFilter('DEPARTAMENTO', 'DEPARTAMENTO');
        $this->loadCriteriaFilter('PROVINCIA', 'PROVINCIA');
        $this->loadCriteriaFilter('DISTRITO', 'DISTRITO');
        $this->loadCriteriaFilter('CCPP', 'CCPP');
        $this->loadCriteriaFilter('TECH_DL', 'TECH DL');
        $this->loadCriteriaFilter('NUM_MUESTRAS', 'NUM MUESTRAS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('MSTRAS_HACE_7DIAS', 'MSTRAS_HACE_7DIAS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CVM_LTE_DL', 'CVM LTE DL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CVM_LTE_UL', 'CVM LTE UL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CVM_UMTS_DL', 'CVM UMTS DL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CVM_UMTS_UL', 'CVM UMTS UL', CriteriaOperatorEntity::numberOperators());
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

        //$this->crud->addClause('where','semestre','=','SEMESTRE_ACTUAL');

        // simple filter
/*         $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'description',
            'label' => 'Description'
        ], 
        false, 
        function($value) { // if the filter is active
            // $this->crud->addClause('where', 'description', 'LIKE', "%$value%");
        }); */

        // daterange filter
        /*$this->crud->addFilter([
            'type'  => 'date_range',
            'name'  => 'from_to',
            'label' => 'Date range'
        ],
        false,
        function ($value) { // if the filter is active, apply these constraints
            // $dates = json_decode($value);
            // $this->crud->addClause('where', 'date', '>=', $dates->from);
            // $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
        });
        */

        $this->crud->enableExportButtons();

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
        CRUD::setValidation(VW_MTV_CVM_OPERADOR_LIN_RECRRequest::class);

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
