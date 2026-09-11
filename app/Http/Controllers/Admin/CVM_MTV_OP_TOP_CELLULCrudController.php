<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CVM_MTV_OP_TOP_CELLULRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Entities\CriteriaOperatorEntity;

/**
 * Class CVM_MTV_OP_TOP_CELLULCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CVM_MTV_OP_TOP_CELLULCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CVM_MTV_OP_TOP_CELLUL::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cvm_mtv_op_top_cellul');
        CRUD::setEntityNameStrings('cvm_mtv_op_top_cellul', 'Top 5 CELDA_UL Por Ubigeo');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('SEMESTRE', 'SEMESTRE');
        $this->loadCriteriaFilter('UBIGEO', 'UBIGEO');
        $this->loadCriteriaFilter('CCPP', 'CCPP');
        $this->loadCriteriaFilter('TECH_DL', 'TECH DL');
        $this->loadCriteriaFilter('IDCELL_DL', 'IDCELL DL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('IDLAC', 'IDLAC', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CELLNAME', 'CELLNAME');
        $this->loadCriteriaFilter('N_TESTS', 'N TESTS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('N_TESTS_DOWN', 'N TESTS DOWN', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CVM_CELDA', 'CVM CELDA', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('AVG_DELAY', 'AVG DELAY', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('AVG_TH_UL_KBPS', 'AVG TH UL KBPS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('FAIL_COVERAGE', 'FAIL COVERAGE', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CALL_ERROR', 'CALL ERROR', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('PDP_DROP_FAIL', 'PDP DROP FAIL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('SIM_CHANGED', 'SIM CHANGED', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('APN_CHANGED', 'APN CHANGED', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('TIPO_SEMANA', 'TIPO SEMANA');
        $this->loadCriteriaFilter('TOP5_CELLDL', 'TOP5 CELLDL', CriteriaOperatorEntity::numberOperators());
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

        $this->crud->orderBy('ubigeo','asc');
        $this->crud->orderBy('tech_dl','asc');
        $this->crud->orderBy('top5_cellul','asc');

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
        CRUD::setValidation(CVM_MTV_OP_TOP_CELLULRequest::class);

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
