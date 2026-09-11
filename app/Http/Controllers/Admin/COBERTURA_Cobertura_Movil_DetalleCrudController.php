<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\COBERTURA_Cobertura_Movil_DetalleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Exports\CoberturaMovilDetalleExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
/**
 * Class COBERTURA_Cobertura_Movil_DetalleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class COBERTURA_Cobertura_Movil_DetalleCrudController extends CrudController
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
        CRUD::setModel(\App\Models\COBERTURA_Cobertura_Movil_Detalle::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cobertura_cobertura_movil_detalle/{tracingID}/{menuID}');
        CRUD::setEntityNameStrings('cobertura_cobertura_movil_detalle', 'COBERTURA | Cobertura Movil | Detalle');
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter("ubigeo", "ubigeo");
        $this->loadCriteriaFilter("departamento", "departamento");
        $this->loadCriteriaFilter("provincia", "provincia");
        $this->loadCriteriaFilter("distrito", "distrito"); 
        $this->loadCriteriaFilter("ccpp","ccpp");
        $this->loadCriteriaFilter("operadora","operadora");
        $this->loadCriteriaFilter("gsm","gsm");
        $this->loadCriteriaFilter("umts","umts");
        $this->loadCriteriaFilter("lte","lte");
        $this->loadCriteriaFilter("trimestre","trimestre");
        $this->loadCriteriaFilter("clasif_inei","clasif_inei");
        $this->loadCriteriaFilter("cdma","cdma");
        $this->loadCriteriaFilter("iden","iden");
        $this->loadCriteriaFilter("wimax","wimax");
        $this->loadCriteriaFilter("lte_4_5g","lte_4_5g");
        $this->loadCriteriaFilter("tec_5g","tec_5g");
        $this->loadCriteriaFilter("habitantes","habitantes");
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
            'name' => "ubigeo", // The db column name
            'label' => "Ubigeo", // Table column heading
            'function_name' => 'getId',
            'type' => 'model_function',
            'limit' => 200
        ]);

        CRUD::setFromDb(); // columns

        $this->crud->removeButton("update");

        $this->crud->addButtonFromView("top", "exportCoberturaDetalle", "exportCoberturaDetalle", "end");

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(COBERTURA_Cobertura_Movil_DetalleRequest::class);

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

    public function export() 
    {
        $now = Carbon::now();

        $fileName = "Cobertura_Movil_detalle_".$now->format("YmdHis").".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return Excel::download(new CoberturaMovilDetalleExport,$fileName,\Maatwebsite\Excel\Excel::CSV,$headers);
    }
}
