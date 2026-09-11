<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MENU_TIFRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MENU_TIFCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MENU_TIFCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MENU_TIF::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/menu_tif');
        CRUD::setEntityNameStrings('menu_tif', 'Menú');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('PERIODO', 'PERIODO', 'number');
        $this->loadCriteriaFilter('SERVICIO', 'SERVICIO');
        $this->loadCriteriaFilter('TOTAL_LINEAS', 'TOTAL LINEAS', 'number');
        $this->loadCriteriaFilter('TOTAL_AVERIAS', 'TOTAL AVERIAS', 'number');
        $this->loadCriteriaFilter('AVERIAS_24H', 'AVERIAS 24H', 'number');
        $this->loadCriteriaFilter('TIF', 'TIF', 'number');
        $this->loadCriteriaFilter('TIF_24H', 'TIF_24H', 'number');
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

        CRUD::column('tif')->type('number');
        CRUD::column('tif')->decimals(4);

        CRUD::column('tif_24h')->type('number');
        CRUD::column('tif_24h')->decimals(4);

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
        CRUD::setValidation(MENU_TIFRequest::class);

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
