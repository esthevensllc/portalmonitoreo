<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_3G_Movil_ProvisionRequest;
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
class ACCESO_3G_Movil_ProvisionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ACCESO_3G_Movil_Provision::class);
        CRUD::setRoute(config("backpack.base.route_prefix") . "/acceso_3g_movil_provision/{tracingID}/{menuID}");
        CRUD::setEntityNameStrings("acceso_3g_movil_provision", "ACCESO | 3g Movil | Provision");
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("rncid","rncid");
        $this->loadCriteriaFilter("rnc_name","rnc_name");
        $this->loadCriteriaFilter("mbts_name","mbts_name");
        $this->loadCriteriaFilter("nodob_name","nodob_name");
        $this->loadCriteriaFilter("nodob_address","nodob_address");
        $this->loadCriteriaFilter("sector_name","sector_name");
        $this->loadCriteriaFilter("cellname","cellname");
        $this->loadCriteriaFilter("locell","locell");
        $this->loadCriteriaFilter("bandind","bandind");
        $this->loadCriteriaFilter("uarfcnuplinkind","uarfcnuplinkind");
        $this->loadCriteriaFilter("uarfcnuplink","uarfcnuplink");
        $this->loadCriteriaFilter("uarfcndownlink","uarfcndownlink");
        $this->loadCriteriaFilter("cfgracind","cfgracind");
        $this->loadCriteriaFilter("cnopgrpindex","cnopgrpindex");
        $this->loadCriteriaFilter("maxtxpower","maxtxpower");
        $this->loadCriteriaFilter("tcell","tcell");
        $this->loadCriteriaFilter("ninsyncind","ninsyncind");
        $this->loadCriteriaFilter("noutsyncind","noutsyncind");
        $this->loadCriteriaFilter("trlfailure","trlfailure");
        $this->loadCriteriaFilter("dltpcpattern01count","dltpcpattern01count");
        $this->loadCriteriaFilter("pscrambcode","pscrambcode");
        $this->loadCriteriaFilter("txdiversityind","txdiversityind");
        $this->loadCriteriaFilter("spgid","spgid");
        $this->loadCriteriaFilter("lac","lac");
        $this->loadCriteriaFilter("rac","rac");
        $this->loadCriteriaFilter("sac","sac");
        $this->loadCriteriaFilter("cio","cio");
        $this->loadCriteriaFilter("nodebname","nodebname");
        $this->loadCriteriaFilter("srn","srn");
        $this->loadCriteriaFilter("sn","sn");
        $this->loadCriteriaFilter("ssn","ssn");
        $this->loadCriteriaFilter("vplimitind","vplimitind");
        $this->loadCriteriaFilter("dssflag","dssflag");
        $this->loadCriteriaFilter("dsssmallcovmaxtxpower","dsssmallcovmaxtxpower");
        $this->loadCriteriaFilter("cchcnopindex","cchcnopindex");
        $this->loadCriteriaFilter("dpgid","dpgid");
        $this->loadCriteriaFilter("sysdesc","sysdesc");
        $this->loadCriteriaFilter("syscontact","syscontact");
        $this->loadCriteriaFilter("syslocation","syslocation");
        $this->loadCriteriaFilter("sysservices","sysservices");
        $this->loadCriteriaFilter("supportrncinpool","supportrncinpool");
        $this->loadCriteriaFilter("loadsharingtype","loadsharingtype");
        $this->loadCriteriaFilter("redundancytype","redundancytype");
        $this->loadCriteriaFilter("cellid","cellid");
        $this->loadCriteriaFilter("actstatus","actstatus");
        $this->loadCriteriaFilter("hspdschcodenum","hspdschcodenum");
        $this->loadCriteriaFilter("departamento","departamento");
        $this->loadCriteriaFilter("provincia","provincia");
        $this->loadCriteriaFilter("distrito","distrito");
        $this->loadCriteriaFilter("sub_region","sub_region");
        $this->loadCriteriaFilter("cluster_name","cluster_name");
        $this->loadCriteriaFilter("modelo_rru","modelo_rru");
        $this->loadCriteriaFilter("manufacturerdata","manufacturerdata");
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
            'name' => "cellname", // The db column name
            'label' => "Cellname", // Table column heading
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
        CRUD::setValidation(ACCESO_3G_Movil_ProvisionRequest::class);

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

        $fileName = "3G_movil_provision_".$now->format("Ymd").".csv";

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
