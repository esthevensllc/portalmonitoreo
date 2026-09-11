<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_4G_Movil_ProvisionRequest;
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
class ACCESO_4G_Movil_ProvisionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ACCESO_4G_Movil_Provision::class);
        CRUD::setRoute(config("backpack.base.route_prefix") . "/acceso_4g_movil_provision/{tracingID}/{menuID}");
        CRUD::setEntityNameStrings("acceso_4g_movil_provision", "ACCESO | 4g Movil | Provision");
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("enodob_name","enodob_name");
        $this->loadCriteriaFilter("enodob_address","enodob_address");
        $this->loadCriteriaFilter("sector_name","sector_name");
        $this->loadCriteriaFilter("cell_id","cell_id");
        $this->loadCriteriaFilter("enodob_id","enodob_id");
        $this->loadCriteriaFilter("cellid","cellid");
        $this->loadCriteriaFilter("departamento","departamento");
        $this->loadCriteriaFilter("provincia","provincia");
        $this->loadCriteriaFilter("distrito","distrito");
        $this->loadCriteriaFilter("sub_region","sub_region");
        $this->loadCriteriaFilter("tac","tac");
        $this->loadCriteriaFilter("localcellid","localcellid");
        $this->loadCriteriaFilter("objid","objid");
        $this->loadCriteriaFilter("additionalspectrumemission","additionalspectrumemission");
        $this->loadCriteriaFilter("aircellflag","aircellflag");
        $this->loadCriteriaFilter("cellactivestate","cellactivestate");
        $this->loadCriteriaFilter("celladminstate","celladminstate");
        $this->loadCriteriaFilter("cellname","cellname");
        $this->loadCriteriaFilter("cellradius","cellradius");
        $this->loadCriteriaFilter("cellspecificoffset","cellspecificoffset");
        $this->loadCriteriaFilter("cpricompression","cpricompression");
        $this->loadCriteriaFilter("crsportnum","crsportnum");
        $this->loadCriteriaFilter("csgind","csgind");
        $this->loadCriteriaFilter("customizedbandwidthcfgind","customizedbandwidthcfgind");
        $this->loadCriteriaFilter("dlbandwidth","dlbandwidth");
        $this->loadCriteriaFilter("dlcyclicprefix","dlcyclicprefix");
        $this->loadCriteriaFilter("dlearfcn","dlearfcn");
        $this->loadCriteriaFilter("emergencyareaidcfgind","emergencyareaidcfgind");
        $this->loadCriteriaFilter("enodebfunctionname","enodebfunctionname");
        $this->loadCriteriaFilter("fddtddind","fddtddind");
        $this->loadCriteriaFilter("freqband","freqband");
        $this->loadCriteriaFilter("highspeedflag","highspeedflag");
        $this->loadCriteriaFilter("multirrucellflag","multirrucellflag");
        $this->loadCriteriaFilter("phycellid","phycellid");
        $this->loadCriteriaFilter("preamblefmt","preamblefmt");
        $this->loadCriteriaFilter("qoffsetfreq","qoffsetfreq");
        $this->loadCriteriaFilter("rootsequenceidx","rootsequenceidx");
        $this->loadCriteriaFilter("txrxmode","txrxmode");
        $this->loadCriteriaFilter("uepowermaxcfgind","uepowermaxcfgind");
        $this->loadCriteriaFilter("ulbandwidth","ulbandwidth");
        $this->loadCriteriaFilter("ulcyclicprefix","ulcyclicprefix");
        $this->loadCriteriaFilter("ulearfcncfgind","ulearfcncfgind");
        $this->loadCriteriaFilter("workmode","workmode");
        $this->loadCriteriaFilter("userlabel","userlabel");
        $this->loadCriteriaFilter("eucellstandbymode","eucellstandbymode");
        $this->loadCriteriaFilter("specialsubframepatterns","specialsubframepatterns");
        $this->loadCriteriaFilter("subframeassignment","subframeassignment");
        $this->loadCriteriaFilter("cnopsharinggroupid","cnopsharinggroupid");
        $this->loadCriteriaFilter("csirsperiod","csirsperiod");
        $this->loadCriteriaFilter("freqpriorityforanr","freqpriorityforanr");
        $this->loadCriteriaFilter("intrafreqanrind","intrafreqanrind");
        $this->loadCriteriaFilter("crsportmap","crsportmap");
        $this->loadCriteriaFilter("mbts_name","mbts_name");
        $this->loadCriteriaFilter("cluster_name","cluster_name");
        $this->loadCriteriaFilter("latitud","latitud");
        $this->loadCriteriaFilter("longitud","longitud");
        $this->loadCriteriaFilter("modelo_rru","modelo_rru");
        $this->loadCriteriaFilter("manufacturerdata","manufacturerdata");
        $this->loadCriteriaFilter("direccion","direccion");
        $this->loadCriteriaFilter("mme_usn_primario","mme_usn_primario");
        $this->loadCriteriaFilter("mme_usn_secundario","mme_usn_secundario");
        $this->loadCriteriaFilter("referencesignalpwr","referencesignalpwr");
        $this->loadCriteriaFilter("ltecellindex","ltecellindex");

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
        CRUD::setValidation(ACCESO_4G_Movil_ProvisionRequest::class);

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

        $fileName = "4G_movil_provision_".$now->format("Ymd").".csv";

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
