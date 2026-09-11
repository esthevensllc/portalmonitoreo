<?php

namespace App\Http\Controllers\Admin\CM;

use App\Modules\KPI_CM\CVM\Repository\CM_CVM;
use App\Modules\KPI_CM\CVM\Services\ExportCM_CVM;
use App\Modules\KPI_CM\CVM\Services\ExportErrorsCM_CVM;
use App\Modules\KPI_CM\CVM\Services\ImportCM_CVM;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Library\Widget;

class CM_CVMCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    
    private $export;

    public function __construct(ExportCM_CVM $export)
    {
        parent::__construct();
        $this->export = $export;
    }

    public function setup()
    {
        CRUD::setModel(CM_CVM::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cm-cvm');
        CRUD::setEntityNameStrings('CM-CVM', 'CM-CVM');
        $this->loadFilters();
        $this->crud->allowAccess('update');
    }

    public function loadFilters(){
        $this->loadCriteriaFilter('id', 'ID', 'number');
        $this->loadCriteriaFilter('region', 'REGION');
        $this->loadCriteriaFilter('encargados', 'ENCARGADOS');
        $this->loadCriteriaFilter('ubigeo', 'UBIGEO');
        $this->loadCriteriaFilter('departamento', 'DEPARTAMENTO');
        $this->loadCriteriaFilter('ccpp', 'CCPP');
        $this->loadCriteriaFilter('indicador', 'INDICADOR');
        $this->loadCriteriaFilter('tecnologia', 'TECNOLOGIA');
        $this->loadCriteriaFilter('dl_3g', 'DL 3G');
        $this->loadCriteriaFilter('dl_4g', 'DL 4G');
        $this->loadCriteriaFilter('ul_3g', 'UL 3G');
        $this->loadCriteriaFilter('ul_4g', 'UL 4G');
        // $this->loadCriteriaFilter('cm_enviado', 'CM ENVIADO');
        $this->loadCriteriaFilter('fecha', 'FECHA');
        $this->loadCriteriaFilter('fecha_limite', 'FECHA LIMITE');
        $this->loadCriteriaFilter('periodo', 'PERIODO');
        $this->loadCriteriaFilter('multa', 'MULTA');
        $this->loadCriteriaFilter('grupo_multa', 'GRUPO MULTA');
        $this->loadCriteriaFilter('actividad', 'ACTIVIDAD');
        $this->loadCriteriaFilter('fecha_actividad', 'FECHA ACTIVIDAD');
        $this->loadCriteriaFilter('estado', 'ESTADO');
        $this->loadCriteriaFilter('acta_levantada', 'ACTA LEVANTADA');
        $this->loadCriteriaFilter('enviado_osiptel', 'ENVIADO OSIPTEL');
        $this->loadCriteriaFilter('fecha_env_osiptel', 'FECHA ENV OSIPTEL');
        // $this->loadCriteriaFilter('acta', 'ACTA ARCHIVO');
    }

    protected function setupListOperation()
    {
        if(\Session::has('message')){
            $message = json_decode(\Session::get('message'));
            \Alert::add($message->type, $message->message);
        }
        CRUD::column('id')->type('number');
        CRUD::column('region')->type('text');
        CRUD::column('encargados')->type('text');
        CRUD::column('ubigeo')->type('text');
        CRUD::column('departamento')->type('text');
        CRUD::column('ccpp')->type('text');
        CRUD::column('indicador')->type('text');
        CRUD::column('tecnologia')->type('text');
        CRUD::column('dl_3g')->type('number')->suffix('%')->decimals(2);
        CRUD::column('dl_4g')->type('number')->suffix('%')->decimals(2);
        CRUD::column('ul_3g')->type('number')->suffix('%')->decimals(2);
        CRUD::column('ul_4g')->type('number')->suffix('%')->decimals(2);
        CRUD::column('fecha')->type('date');
        CRUD::column('fecha_limite')->type('date');
        CRUD::column('periodo')->type('text');
        CRUD::column('multa')->type('text');
        CRUD::column('grupo_multa')->type('text');
        //CRUD::column('cm_enviado')->type('text');
        CRUD::column('actividad')->type('text');
        CRUD::column('fecha_actividad')->type('date');
        CRUD::column('estado')->type('text');
        CRUD::column('acta_levantada')->type('text');
        CRUD::column('enviado_osiptel')->type('text');
        CRUD::column('fecha_env_osiptel')->type('date');
        CRUD::column('created_at')->label('Creado el')->type('text')->visibleInTable(false);
        CRUD::column('updated_at')->label('Actualizado el')->type('text');
        CRUD::column('updated_at')->label('Acciones')->type('closure')->function(function($entry) {
            return '<button class="btn btn-outline-primary btn-sm" onclick="openUpdateModal('.$entry->id.')"> <i class="la la-edit"></i> Editar</button>';
        });
        $this->crud->removeButton('update');

        $this->crud->orderBy('id', 'ASC');

        $this->crud->enableExportButtons();
        $this->crud->addButtonFromView('top', 'import', 'async_import', 'end');

        Widget::add([
            'type'     => 'view',
            'view'     => 'widgets.cm.CM_CVM_update_modal',
        ])->to('after_content');
    }

    public function exportPlantillaExcel() 
    {
        $collection = $this->crud->getEntries();
        return $this->export->exportCollection($collection)->getData();
    }

    public function importFromExcel(Request $request) 
    {
        if($request->hasFile('fileImport')){
            $file = $request->file('fileImport');
            try {
                $import = new ImportCM_CVM();
                Excel::import($import, $file);
                $response = $import->response();
                if($response->fails()){
                    $exportErrors = new ExportErrorsCM_CVM();
                    return $exportErrors->__invoke($response->getErrors());
                }
                return back()->with('message', json_encode([
                    'type' => 'success',
                    'message' => 'Importación de CM CVM completada'
                ]));
            } catch (\Throwable $th) {
                return back()->with('message', json_encode([
                    'type' => 'error',
                    'message' => 'Falla al cargar. El archivo no es valido o es muy pesado'
                ]));
            }
        }
        return back()->with('message', json_encode([
            'type' => 'error',
            'message' => 'Deve seleccionar un archivo válido. Intente nuevamente'
        ]));
    }
}
