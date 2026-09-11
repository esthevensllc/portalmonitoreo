<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_4G_Tdd_ProvisionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;

use App\Exports\ProvisionExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ACCESO_2G_Movil_ProvisionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ACCESO_4G_Tdd_ProvisionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
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
        CRUD::setModel(\App\Models\ACCESO_4G_Tdd_Provision::class);
        CRUD::setRoute(config("backpack.base.route_prefix") . "/acceso_4g_fijo_provision/{tracingID}/{menuID}");
        CRUD::setEntityNameStrings("acceso_4g_fijo_provision", "ACCESO | 4g Tdd | Provision");
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("distintivo","distintivo");
        $this->loadCriteriaFilter("enodob_id","enodob_id");
        $this->loadCriteriaFilter("local_cell_id","local_cell_id");
        $this->loadCriteriaFilter("cellname","cellname");
        $this->loadCriteriaFilter("sector_name","sector_name");
        $this->loadCriteriaFilter("enodob_name","enodob_name");
        $this->loadCriteriaFilter("distrito","distrito");
        $this->loadCriteriaFilter("provincia","provincia");
        $this->loadCriteriaFilter("sub_region","sub_region");
        $this->loadCriteriaFilter("region","region");
        $this->loadCriteriaFilter("latitud","latitud");
        $this->loadCriteriaFilter("longitud","longitud");
        $this->loadCriteriaFilter("fecha_insercion","fecha_insercion");
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => "cellname", // The db column name
            'label' => "Cellname", // Table column heading
            'function_name' => 'getId',
            'type' => 'model_function',
            'limit' => 200
        ]);

        CRUD::setFromDb(); // columns

        $this->crud->removeButton("update");

        $this->crud->addButtonFromView("top", "export", "export", "end");

        //$this->crud->addClause('where','servicio','=',1);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column("price")->type("number");
         * - CRUD::addColumn(["name" => "price", "type" => "number"]); 
         */

        //$this->crud->enableExportButtons();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ACCESO_4G_Tdd_ProvisionRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field("price")->type("number");
         * - CRUD::addField(["name" => "price", "type" => "number"])); 
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

        $fileName = "4G_tdd_provision_".$now->format("Ymd").".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return Excel::download(new ProvisionExport,$fileName,\Maatwebsite\Excel\Excel::CSV,$headers);
    }
}
