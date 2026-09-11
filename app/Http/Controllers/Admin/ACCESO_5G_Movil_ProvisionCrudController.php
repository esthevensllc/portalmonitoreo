<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_5G_Movil_ProvisionRequest;
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
class ACCESO_5G_Movil_ProvisionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ACCESO_5G_Movil_Provision::class);
        CRUD::setRoute(config("backpack.base.route_prefix") . "/acceso_5g_movil_provision/{tracingID}/{menuID}");
        CRUD::setEntityNameStrings("acceso_5g_movil_provision", "ACCESO | 5g Movil | Provision");
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("gnodob","gnodob");
        $this->loadCriteriaFilter("gnodob_address","gnodob_address");
        $this->loadCriteriaFilter("distintivo","distintivo");
        $this->loadCriteriaFilter("sector_name","sector_name");
        $this->loadCriteriaFilter("gnbid","gnbid");
        $this->loadCriteriaFilter("mbts","mbts");
        $this->loadCriteriaFilter("nrducellid","nrducellid");
        $this->loadCriteriaFilter("region","region");
        $this->loadCriteriaFilter("sub_region","sub_region");
        $this->loadCriteriaFilter("departamento","departamento");
        $this->loadCriteriaFilter("provincia","provincia");
        $this->loadCriteriaFilter("distrito","distrito");
        $this->loadCriteriaFilter("latitud","latitud");
        $this->loadCriteriaFilter("longitud","longitud");
        $this->loadCriteriaFilter("txrxmode","txrxmode");
        $this->loadCriteriaFilter("cluster_name","cluster_name");
        $this->loadCriteriaFilter("tac","tac");
        $this->loadCriteriaFilter("cellactivestate","cellactivestate");
        $this->loadCriteriaFilter("ulbandwidth","ulbandwidth");
        $this->loadCriteriaFilter("ulnarfcn","ulnarfcn");
        $this->loadCriteriaFilter("celladminstate","celladminstate");
        $this->loadCriteriaFilter("cellid","cellid");
        $this->loadCriteriaFilter("cellradius","cellradius");
        $this->loadCriteriaFilter("cyclicprefixlength","cyclicprefixlength");
        $this->loadCriteriaFilter("dlbandwidth","dlbandwidth");
        $this->loadCriteriaFilter("dlnarfcn","dlnarfcn");
        $this->loadCriteriaFilter("duplexmode","duplexmode");
        $this->loadCriteriaFilter("frequencyband","frequencyband");
        $this->loadCriteriaFilter("gnodebfunctionname","gnodebfunctionname");
        $this->loadCriteriaFilter("lampsitecellflag","lampsitecellflag");
        $this->loadCriteriaFilter("logicalrootsequenceindex","logicalrootsequenceindex");
        $this->loadCriteriaFilter("nrducellactivestate","nrducellactivestate");
        $this->loadCriteriaFilter("nrducellname","nrducellname");
        $this->loadCriteriaFilter("physicalcellid","physicalcellid");
        $this->loadCriteriaFilter("prachfreqstartposition","prachfreqstartposition");
        $this->loadCriteriaFilter("rannotificationareaid","rannotificationareaid");
        $this->loadCriteriaFilter("sib1period","sib1period");
        $this->loadCriteriaFilter("slotassignment","slotassignment");
        $this->loadCriteriaFilter("slotstructure","slotstructure");
        $this->loadCriteriaFilter("ssbdescmethod","ssbdescmethod");
        $this->loadCriteriaFilter("ssbfreqpos","ssbfreqpos");
        $this->loadCriteriaFilter("ssbperiod","ssbperiod");
        $this->loadCriteriaFilter("subcarrierspacing","subcarrierspacing");
        $this->loadCriteriaFilter("taoffset","taoffset");
        $this->loadCriteriaFilter("trackingareaid","trackingareaid");
        $this->loadCriteriaFilter("direccion","direccion");
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
            'name' => "mbts", // The db column name
            'label' => "Mbts", // Table column heading
            'function_name' => 'getId',
            'type' => 'model_function',
            'limit' => 200
        ]);

        CRUD::setFromDb(); // columns

        $this->crud->removeButton("update");

        $this->crud->addButtonFromView("top", "export", "export", "end");

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
        CRUD::setValidation(ACCESO_5G_Movil_ProvisionRequest::class);

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

        $fileName = "5G_movil_provision_".$now->format("Ymd").".csv";

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
