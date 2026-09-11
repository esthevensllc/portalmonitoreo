<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ACCESO_2G_Movil_ProvisionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ACCESO_2G_Movil_ProvisionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ACCESO_2G_Movil_ProvisionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ACCESO_2G_Movil_Provision::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/acceso_2g_movil_provision/{tracingID}/{menuID}');
        CRUD::setEntityNameStrings('acceso_2g_movil_provision', 'ACCESO | 2G Movil | Provision');
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("BSC_NAME","BSC_NAME");
        $this->loadCriteriaFilter("MBTS_NAME", "MBTS_NAME");
        $this->loadCriteriaFilter("BTSNAME", "BTSNAME");
        $this->loadCriteriaFilter("SITE_NAME", "SITE_NAME");
        $this->loadCriteriaFilter("SITE_ADDRESS", "SITE_ADDRESS");
        $this->loadCriteriaFilter("SEGMENT_NAME", "SEGMENT_NAME");
        $this->loadCriteriaFilter("CELLID", "CELLID");
        $this->loadCriteriaFilter("CELLNAME", "CELLNAME");
        $this->loadCriteriaFilter("CI", "CI");
        $this->loadCriteriaFilter("LAC", "LAC");
        $this->loadCriteriaFilter("RAC", "RAC");
        $this->loadCriteriaFilter("TRXID", "TRXID");
        $this->loadCriteriaFilter("TRXNAME", "TRXNAME");
        $this->loadCriteriaFilter("FREQ", "FREQ");
        $this->loadCriteriaFilter("TRXNO", "TRXNO");
        $this->loadCriteriaFilter("IDTYPE", "IDTYPE");
        $this->loadCriteriaFilter("ISMAINBCCH", "ISMAINBCCH");
        $this->loadCriteriaFilter("ISTMPTRX", "ISTMPTRX");
        $this->loadCriteriaFilter("GTRXGROUPID", "GTRXGROUPID");
        $this->loadCriteriaFilter("TCH_TOTAL", "TCH_TOTAL");
        $this->loadCriteriaFilter("PDTCH_TOTAL", "PDTCH_TOTAL");
        $this->loadCriteriaFilter("MCC", "MCC");
        $this->loadCriteriaFilter("MNC", "MNC");
        $this->loadCriteriaFilter("NCC", "NCC");
        $this->loadCriteriaFilter("BCC", "BCC");
        $this->loadCriteriaFilter("EXTTP", "EXTTP");
        $this->loadCriteriaFilter("IUOTP", "IUOTP");
        $this->loadCriteriaFilter("FLEXMAIO", "FLEXMAIO");
        $this->loadCriteriaFilter("CSVSP", "CSVSP");
        $this->loadCriteriaFilter("CSDSP", "CSDSP");
        $this->loadCriteriaFilter("PSHPSP", "PSHPSP");
        $this->loadCriteriaFilter("PSLPSVP", "PSLPSVP");
        $this->loadCriteriaFilter("BSPBCCHBLKS", "BSPBCCHBLKS");
        $this->loadCriteriaFilter("BSPAGBLKSRES", "BSPAGBLKSRES");
        $this->loadCriteriaFilter("BSPRACHBLKS", "BSPRACHBLKS");
        $this->loadCriteriaFilter("TYPE", "TYPE");
        $this->loadCriteriaFilter("OPNAME", "OPNAME");
        $this->loadCriteriaFilter("VIPCELL", "VIPCELL");
        $this->loadCriteriaFilter("MOCNCMCELL", "MOCNCMCELL");
        $this->loadCriteriaFilter("HYBHIFREQBANDSUPPORT", "HYBHIFREQBANDSUPPORT");
        $this->loadCriteriaFilter("GLOCELLID", "GLOCELLID");
        $this->loadCriteriaFilter("NSEI", "NSEI");
        $this->loadCriteriaFilter("BVCI", "BVCI");
        $this->loadCriteriaFilter("SYSDESC", "SYSDESC");
        $this->loadCriteriaFilter("SYSCONTACT", "SYSCONTACT");
        $this->loadCriteriaFilter("SYSLOCATION", "SYSLOCATION");
        $this->loadCriteriaFilter("SYSSERVICES", "SYSSERVICES");
        $this->loadCriteriaFilter("ADMSTAT", "ADMSTAT");
        $this->loadCriteriaFilter("DISTRITO", "DISTRITO");
        $this->loadCriteriaFilter("PROVINCIA", "PROVINCIA");
        $this->loadCriteriaFilter("DEPARTAMENTO", "DEPARTAMENTO");
        $this->loadCriteriaFilter("SUB_REGION", "SUB_REGION");
        $this->loadCriteriaFilter("CLUSTER_NAME", "CLUSTER_NAME");
        $this->loadCriteriaFilter("ACTSTATUS", "ACTSTATUS");
        $this->loadCriteriaFilter("DIRECCION", "DIRECCION");
        $this->loadCriteriaFilter("MODELO_RRU", "MODELO_RRU");
        $this->loadCriteriaFilter("MANUFACTURERDATA", "MANUFACTURERDATA");
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
        CRUD::setValidation(ACCESO_2G_Movil_ProvisionRequest::class);

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
