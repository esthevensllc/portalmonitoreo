<?php

namespace App\Http\Controllers\Admin\PrtltxAlarma;
//use App\Exports\PrtltxAlarmasExport;
//use App\Http\Requests\MENU_TIFRequest;
use App\Entities\CriteriaOperatorEntity;
use App\Models\PrtltxAlarma\PRTLTX_ALARMA_DISP_DET;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
//use Carbon\Carbon;
use Illuminate\Http\Request;
//use Maatwebsite\Excel\Facades\Excel;
use DB;
use Backpack\CRUD\app\Library\Widget;

class PRTLTX_ALARMA_DISP_DETCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //se \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    public function setup()
    {
        CRUD::setModel(PRTLTX_ALARMA_DISP_DET::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/prtltx-alarmas-det');
        CRUD::setEntityNameStrings('prtltx-alarmas', 'Detalle Alarmas');
        $this->loadFilters();
        // $this->crud->allowAccess('update');
    }

    private function loadFilters(){
        // $this->loadCriteriaFilter('indisp_seg', 'INDISP SEG', 'number');
        // $this->loadCriteriaFilter('estado_valid_alarm', 'ESTADO VALID ALARM', 'number');
        $this->loadCriteriaFilter("to_char(mes, 'YYYY-MM')", 'MES');
        $this->loadCriteriaFilter('log_serial_number', 'LOG SERIAL NUMBER', 'number');
        $this->loadCriteriaFilter('nombre_red', 'NOMBRE RED');
        $this->loadCriteriaFilter('codigo_alarma', 'CODIGO ALARMA');
        $this->loadCriteriaFilter('nombre_alarma', 'NOMBRE ALARMA');
        $this->loadCriteriaFilter('tipo_alarma', 'TIPO ALARMA');
        // $this->loadCriteriaFilter('estado_actual', 'ESTADO ACTUAL');
        // $this->loadCriteriaFilter('id_estado', 'ID ESTADO', 'number');
        // $this->loadCriteriaFilter('fecha_inicalarma', 'FECHA INICALARMA');
        // $this->loadCriteriaFilter('fecha_finalarma', 'FECHA FINALARMA');
        $this->loadCriteriaFilter('fec_occurred', 'FECHA OCCURRED');
        $this->loadCriteriaFilter('fec_cleared', 'FECHA CLEARED');
        // $this->loadCriteriaFilter('fecha_cierre_periodo_val', 'FECHA CIERRE PERIODO');
        // $this->loadCriteriaFilter('fecha_cierre_periodo_ultd', 'FECHA CIERRE P. ULTD');
        // $this->loadCriteriaFilter('tipo_nodo', 'TIPO NODO');
        // $this->loadCriteriaFilter('anio_mes', 'AÑO-MES');
        // $this->loadCriteriaFilter('disponibilidad', 'DISPONIBILIDAD');
        // $this->loadCriteriaFilter('disp_seg', 'DISP_SEG');
        // $this->loadCriteriaFilter('indisp_seg', 'INDISP_SEG');
        $this->loadCriteriaFilter('indisp_seg', 'INDISP SEG', 'number');
        $this->loadCriteriaFilter('excluido', 'EXCLUIDO');
        $this->loadCriteriaFilter('codigo_incidencia', 'CODIGO INCIDENCIA');
        $this->loadCriteriaFilter('tipo_problema', 'TIPO PROBLEMA');
        $this->loadCriteriaFilter('detalle_problema', 'DETALLE PROBLEMA');
    }

    protected function setupListOperation()
    {
        // CRUD::column('indisp_seg')->type('number');//->visibleInTable(false);
        // CRUD::column('estado_valid_alarm')->type('text');//->visibleInTable(false);
        CRUD::column('mes')->type('closure')->function(function($entry) {
            return substr($entry->mes, 0, 7);
        });
        CRUD::column('log_serial_number')->type('number');
        CRUD::column('nombre_red')->type('text');
        CRUD::column('codigo_alarma')->type('text');
        CRUD::column('nombre_alarma')->type('text');
        CRUD::column('tipo_alarma')->type('text');
        // CRUD::column('estado_actual')->type('text');
        // CRUD::column('id_estado')->type('text');
        // CRUD::column('fecha_inicalarma')->type('text')->visibleInTable(false);
        // CRUD::column('fecha_finalarma')->type('text')->visibleInTable(false);
        CRUD::column('fec_occurred')->type('text');
        CRUD::column('fec_cleared')->type('text');
        // CRUD::column('fecha_cierre_periodo_val')->type('text')->visibleInTable(false);
        // CRUD::column('fecha_cierre_periodo_ultd')->type('text')->visibleInTable(false);
        CRUD::column('indisp_seg')->type('number');
        CRUD::column('excluido')->type('text');
        CRUD::column('codigo_incidencia')->type('text');
        CRUD::column('tipo_problema')->type('text');
        CRUD::column('detalle_problema')->type('text');

        $this->crud->enableExportButtons();
        //$this->crud->addButtonFromView('top', 'import', 'import', 'end');
        //$this->crud->removeButton('update');
	$defaultFilters = [
            [
                'name' => "to_char(mes, 'YYYY-MM')",
                'type' => 'custom_criteria',
                'operator' => CriteriaOperatorEntity::CONTIENE,
                'value' => date('Y-m'),
            ]
        ];
        Widget::add([
            'type'     => 'view',
            'view'     => 'widgets.shared.default_filters',
            'defaultFilters' => $defaultFilters,
        ])->to('after_content');
    }
}
