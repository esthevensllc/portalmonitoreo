<?php

namespace App\Http\Controllers\Admin\CM;

use App\Modules\KPI_CM\CCS_CV_TEMT\Repository\CCS_CV_TEMP;
use App\Modules\KPI_CM\CCS_CV_TEMT\Services\ExportCCS_CV_TEMP;
use App\Modules\KPI_CM\CCS_CV_TEMT\Services\ExportErrorsCCS_CV_TEMT;
use App\Modules\KPI_CM\CCS_CV_TEMT\Services\ImportCCS_CV_TEMT;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Library\Widget;

class CM_CCS_CV_TEMTCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;

    private $export;

    public function __construct(ExportCCS_CV_TEMP $export)
    {
        parent::__construct();
        $this->export = $export;
    }

    public function setup()
    {
        CRUD::setModel(CCS_CV_TEMP::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cm-ccs-cv-temt');
        CRUD::setEntityNameStrings('CCS-CV-TEMT', 'CCS-CV-TEMT');
        $this->loadFilters();
        $this->crud->allowAccess('update');
    }

    public function loadFilters(){
        $this->loadCriteriaFilter('id', 'ID', 'number');
        $this->loadCriteriaFilter('encargados', 'ENCARGADOS');
        $this->loadCriteriaFilter('region', 'REGION');
        $this->loadCriteriaFilter('ubigeo', 'UBIGEO');
        $this->loadCriteriaFilter('departamento', 'DEPARTAMENTO');
        $this->loadCriteriaFilter('provincia', 'PROVINCIA');
        $this->loadCriteriaFilter('distrito', 'DISTRITO');
        $this->loadCriteriaFilter('ccpp', 'CCPP');
        $this->loadCriteriaFilter('tecnologia', 'TECNOLOGIA');
        $this->loadCriteriaFilter('indicador', 'INDICADOR');
        $this->loadCriteriaFilter('gsm', 'GSM');
        $this->loadCriteriaFilter('umts', 'UMTS');
        // $this->loadCriteriaFilter('comentario_osiptel', 'COMENTARIO OSIPTEL');
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
        CRUD::column('encargados')->type('text');
        CRUD::column('region')->type('text');
        CRUD::column('ubigeo')->type('text');
        CRUD::column('departamento')->type('text');
        CRUD::column('provincia')->type('text');
        CRUD::column('distrito')->type('text');
        CRUD::column('ccpp')->type('text');
        CRUD::column('tecnologia')->type('text');
        CRUD::column('indicador')->type('text');
        CRUD::column('gsm')->type('text');
        CRUD::column('umts')->type('text');
        CRUD::column('fecha')->type('date');
        CRUD::column('fecha_limite')->type('date');
        CRUD::column('periodo')->type('text');
        CRUD::column('multa')->type('text');
        CRUD::column('grupo_multa')->type('text');
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
        // $this->crud->addButtonFromView('top', 'import', 'import', 'top');
        
        Widget::add([
            'type'     => 'view',
            'view'     => 'widgets.cm.TEST_CV_TEMT_update_modal',
            'someAttr' => 'some value',
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
                $import = new ImportCCS_CV_TEMT();
                Excel::import($import, $file);
                $response = $import->response();
                $exportErrors = new ExportErrorsCCS_CV_TEMT();
                if($response->fails()){
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

    protected function setupUpdateOperation()
    {
        CRUD::setFromDb();
    }
}
