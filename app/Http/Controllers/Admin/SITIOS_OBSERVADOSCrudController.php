<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SITIOS_OBSERVADOSRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SITIOS_OBSERVADOSCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SITIOS_OBSERVADOSCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\SITIOS_OBSERVADOS::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sitios_observados');
        CRUD::setEntityNameStrings('sitios_observados', 'Sitios observados');
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
            'name' => 'site_name', // The db column name
            'label' => "CÓDIGO", // Table column heading
            'type' => 'text',
            'key' => 'site_name',
            'tableColumn' => true
        ]);

        $this->crud->addColumn([
            'name' => 'bcf_address', // The db column name
            'label' => "NOMBRE ESTACION", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'departamento', // The db column name
            'label' => "DEPARTAMENTO", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'tipo_obs_fitel', // The db column name
            'label' => "OBSERVACIÓN", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'tecnologia', // The db column name
            'label' => 'TECNOLOGÍA', // Table column heading
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
        CRUD::setValidation(SITIOS_OBSERVADOSRequest::class);

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
