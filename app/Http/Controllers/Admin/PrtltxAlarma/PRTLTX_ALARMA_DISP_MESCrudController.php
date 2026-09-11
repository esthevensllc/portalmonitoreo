<?php

namespace App\Http\Controllers\Admin\PrtltxAlarma;

use App\Exports\PrtltxAlarmasExport;
use App\Http\Requests\MENU_TIFRequest;
use App\Models\PrtltxAlarma\PRTLTX_ALARMA_DISP_MES;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class PRTLTX_ALARMA_DISP_MESCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //se \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(PRTLTX_ALARMA_DISP_MES::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/resumen-prtltx-alarmas');
        CRUD::setEntityNameStrings('prtltx-alarmas', 'Resumen Disponibilidad');
        $this->loadFilters();
        // $this->crud->allowAccess('update');
    }

    private function loadFilters(){
        $this->loadCriteriaFilter('ne_name', 'IDLOG SERIAL', 'number');
        $this->loadCriteriaFilter('tipo_nodo', 'TIPO NODO');
        $this->loadCriteriaFilter('anio_mes', 'AÑO-MES');
        $this->loadCriteriaFilter('disponibilidad', 'DISPONIBILIDAD', 'number');
        $this->loadCriteriaFilter('disp_seg', 'DISP_SEG', 'number');
        $this->loadCriteriaFilter('indisp_seg', 'INDISP_SEG', 'number');
    }

    protected function setupListOperation()
    {
        CRUD::column('ne_name')->type('text');
        CRUD::column('tipo_nodo')->type('text');
        CRUD::column('anio_mes')->label('Año mes')->type('text');
        CRUD::column('disponibilidad')->label('Disponibilidad %')->type('number')->suffix('%')->decimals(2);
        CRUD::column('disp_seg')->type('number');
        CRUD::column('indisp_seg')->type('number');
        // $this->crud->addField([
        //     'name' => 'ne_name',
        //     'type' => 'string',
        //     'label' => 'Name'
        // ]);
        // $this->crud->addField([
        //     'name' => 'tipo_node',
        //     'type' => 'text',
        //     'label' => 'Tipo nodo'
        // ]);
        // $this->crud->addField([
        //     'name' => 'anio_mes',
        //     'type' => 'text',
        //     'label' => 'Año-mes',
        // ]);
        // $this->crud->addField([
        //     'name' => 'disponibilidad',
        //     'type' => 'text',
        //     'label' => 'Disponibilidad'
        // ]);
        // $this->crud->addField([
        //     'name' => 'disp_seg',
        //     'type' => 'text',
        //     'label' => 'Disp seg'
        // ]);
        // $this->crud->addField([
        //     'name' => 'indisp_seg',
        //     'type' => 'text',
        //     'label' => 'Indisp seg'
        // ]);
        //CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */

        //CRUD::column('tif')->type('number');
        //CRUD::column('tif')->decimals(4);

        //CRUD::column('tif_24h')->type('number');
        //CRUD::column('tif_24h')->decimals(4);

        $this->crud->enableExportButtons();
        //$this->crud->addButtonFromView('top', 'import', 'import', 'end');
        //$this->crud->removeButton('update');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(MENU_TIFRequest::class);
        CRUD::setFromDb();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
