<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SITIOS_OBSERVADOS_TLLIRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

use App\Imports\SitiosTlliImport;
use App\Exports\SitiosTlliExport;

use App\Exports\SitiosTlliReporteExport;

/**
 * Class SITIOS_OBSERVADOSCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SITIOS_OBSERVADOS_TLLICrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\SITIOS_OBSERVADOS_TLLI::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sitios_observados_tlli');
        CRUD::setEntityNameStrings('sitios_observados_tlli', 'Sitios observados TLLI');
        $this->loadfilters();
    }

    private function loadfilters(){
        $this->loadCriteriaFilter('site_name', 'CÓDIGO');
        $this->loadCriteriaFilter('bcf_address', 'NOMBRE ESTACION');
        $this->loadCriteriaFilter('departamento', 'DEPARTAMENTO');
        $this->loadCriteriaFilter('tipo_obs_fitel', 'OBSERVACIÓN');
        $this->loadCriteriaFilter('tecnologia', 'TECNOLOGÍA');
        $this->loadCriteriaFilter('grupo_motivo', 'GRUPO - MOTIVO (OSIPTEL)');
        $this->loadCriteriaFilter('detalle_motivo', 'DETALLE MOTIVO');
        $this->loadCriteriaFilter('accion_solucion', 'ACCIÓN DE SOLUCIÓN');
        $this->loadCriteriaFilter('responsable_ingrad', 'RESPONSABLE');
        $this->loadCriteriaFilter('personal_ingrad', 'PERSONAL');
        $this->loadCriteriaFilter('solution_at', 'FECHA SOLUCIÓN');
        $this->loadCriteriaFilter('mes', 'MES');
        $this->loadCriteriaFilter('anio', 'AÑO');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        $this->crud->addColumn([
            'name' => 'site_name', // The db column name
            'label' => "CÓDIGO", // Table column heading
            'type' => 'text',
            'key' => 'site_name',
            'tableColumn' => true
        ]);

        $this->crud->addColumn([
            'name' => 'bcf_address', // The db column name
            'label' => "NOMBRE ESTACION", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'departamento', // The db column name
            'label' => "DEPARTAMENTO", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'provincia', // The db column name
            'label' => 'PROVINCIA', // Table column heading
            'type' => 'text',
            'limit'  => 200
        ]);

        $this->crud->addColumn([
            'name' => 'distrito', // The db column name
            'label' => 'DISTRITO', // Table column heading
            'type' => 'text',
            'limit'  => 200
        ]);

        $this->crud->addColumn([
            'name' => 'latitud', // The db column name
            'label' => 'LATITUD', // Table column heading
            'type' => 'number',
            'decimals' => 8,
            'dec_point' => '.',
        ]);

        $this->crud->addColumn([
            'name' => 'longitud', // The db column name
            'label' => 'LONGITUD', // Table column heading
            'type' => 'number',
            'decimals' => 8,
            'dec_point' => '.',
        ]);

        $this->crud->addColumn([
            'name' => 'tipo_obs_fitel', // The db column name
            'label' => "OBSERVACIÓN", // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'tecnologia', // The db column name
            'label' => 'TECNOLOGÍA', // Table column heading
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'grupo_motivo', // The db column name
            'label' => 'GRUPO - MOTIVO (OSIPTEL)', // Table column heading
            'function_name' => 'getMotivo',
            'type' => 'model_function',
            'limit' => 200
        ]);

        $this->crud->addColumn([
            'name' => 'detalle_motivo', // The db column name
            'label' => 'DETALLE MOTIVO', // Table column heading
            'type' => 'text',
            'limit'  => 200
        ]);

        $this->crud->addColumn([
            'name' => 'accion_solucion', // The db column name
            'label' => 'ACCIÓN DE SOLUCIÓN', // Table column heading
            'type' => 'text',
            'limit'  => 200
        ]);

        $this->crud->addColumn([
            'name' => 'responsable_ingrad', // The db column name
            'label' => 'RESPONSABLE', // Table column heading
            'type' => 'text',
            'limit'  => 200
        ]);

        $this->crud->addColumn([
            'name' => 'personal_ingrad', // The db column name
            'label' => 'PERSONAL', // Table column heading
            'type' => 'text',
            'limit'  => 200
        ]);

        $this->crud->addColumn([
            'name' => 'solution_at', // The db column name
            'label' => 'FECHA SOLUCIÓN', // Table column heading
            'type' => 'date'
        ]);
                
        $this->crud->addColumn([
            'name' => 'mes', // The db column name
            'label' => "MES", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'anio', // The db column name
            'label' => "AÑO", // Table column heading
            'type' => 'text',
        ]);

        $this->crud->addClause('where', 'estado', '=', 1);

        $this->crud->enableExportButtons();

        $this->crud->addButtonFromView('top', 'import', 'import', 'end');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SITIOS_OBSERVADOS_TLLIRequest::class);

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

    public function import(Request $request) 
    {
        $file = $request->file('fileImport');

        Excel::import(new SitiosTlliImport, $file);

        return back()->with('message', 'Importanción de usuarios completada');
    }

    public function export() 
    {
        $now = Carbon::now();

        return (new SitiosTlliExport)->download('TLLI'.$now->format('Ymd').'.xlsx');
    }

    public function exportReporte() 
    {

        $p_mes =$_REQUEST['mes'];
        $p_anio =$_REQUEST['anio'];
        
        $now = Carbon::now();

        return (new SitiosTlliReporteExport($p_mes,$p_anio))->download('SITIOS_OBSERVADOS_TLLI_'.$p_mes.'_'.$p_anio.'_'.$now->format('Ymd').'.xlsx');
    }
}
