<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DRIVE_TESTRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Entities\CriteriaOperatorEntity;

/**
 * Class DRIVE_TESTCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DRIVE_TESTCrudController extends CrudController
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
        CRUD::setModel(\App\Models\DRIVE_TEST::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/drive_test');
        CRUD::setEntityNameStrings('drive_test', 'Drive Test');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->crud->addFilter(['type' => 'date_range', 'name' => 'DIA', 'label' => 'DIA'],
            false,
            function($value) {
                $dates = json_decode($value);
                //$this->crud->query->where('DIA', $value);
                $this->crud->query->where('DIA', '>=', $dates->from);
                $this->crud->query->where('DIA', '<=', $dates->to);
            });
        $this->loadCriteriaFilter('IDCLIENT', 'IDCLIENT');
        $this->loadCriteriaFilter('IDSESSION', 'IDSESSION', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('IMSI', 'IMSI', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('JOB_NAME', 'JOB_NAME');
        $this->loadCriteriaFilter('TECH_DL', 'TECH_DL');
        $this->loadCriteriaFilter('THROUGHPUT_DL_KBPS', 'THROUGHPUT_DL_KBPS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('UMBRAL_DL_KBPS', 'UMBRAL_DL_KBPS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('THROUGHPUT_UL_KBPS', 'THROUGHPUT_UL_KBPS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('UMBRAL_UL_KBPS', 'UMBRAL_UL_KBPS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('DELAY', 'DELAY', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('JITTER', 'JITTER', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('AVG_DELAY', 'AVG_DELAY', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('LATITUDE', 'LATITUDE', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('LONGITUDE', 'LONGITUDE', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('IDLAC', 'IDLAC', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('IDCELL_DL', 'IDCELL_DL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CELLNAME_DL', 'CELLNAME_DL');
        $this->loadCriteriaFilter('IDCELL_UL', 'IDCELL_UL', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('CELLNAME_UL', 'CELLNAME_UL');
        $this->loadCriteriaFilter('UBIGEO', 'UBIGEO');
        $this->loadCriteriaFilter('N_TESTS', 'N_TESTS', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('N_TESTS_DOWN', 'N_TESTS_DOWN', CriteriaOperatorEntity::numberOperators());
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
        CRUD::setValidation(DRIVE_TESTRequest::class);

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
