<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VW_MTV_LIN_RCRSRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Entities\CriteriaOperatorEntity;

/**
 * Class LINEAS-RECURRENTESCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VW_MTV_LIN_RCRSCrudController extends CrudController
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
        CRUD::setModel(\App\Models\VW_MTV_LIN_RCRS::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lineasrecurrentes');
        CRUD::setEntityNameStrings('lineas-recurrentes', 'Lineas Recurrentes');
        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('IMSI', 'IMSI');
        $this->loadCriteriaFilter('IMSI_X', 'IMSI X', CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('ANO', "AÑO", CriteriaOperatorEntity::numberOperators());
        $this->loadCriteriaFilter('SEMANAS', 'SEMANAS');
        $this->loadCriteriaFilter('JOB_NAME', 'JOB NAME');
        $this->loadCriteriaFilter('N_MED', 'N MED');
        $this->loadCriteriaFilter('MED_TOTAL', 'MED TOTAL', CriteriaOperatorEntity::numberOperators());
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

        $this->crud->addColumn([
            'name' => 'imsi', // The db column name
            'label' => "Imsi", // Table column heading
            'type' => 'text',
            'limit'  => 50
        ]);

        $this->crud->addColumn([
            'name' => 'imsi_x', // The db column name
            'label' => "Imsi_x", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'ano', // The db column name
            'label' => "Año", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'semanas', // The db column name
            'label' => "Semanas", // Table column heading
            'type' => 'text',
            'limit'  => 4000
        ]);

        $this->crud->addColumn([
            'name' => 'job_name', // The db column name
            'label' => "Job name", // Table column heading
            'type' => 'text',
            'limit'  => 4000
        ]);

        $this->crud->addColumn([
            'name' => 'n_med', // The db column name
            'label' => "N med", // Table column heading
            'type' => 'text',
            'limit'  => 4000
        ]);

        $this->crud->addColumn([
            'name' => 'med_total', // The db column name
            'label' => "Med Total", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->orderBy('med_total','desc');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */

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
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(VW_MTV_LIN_RCRSRequest::class);

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
