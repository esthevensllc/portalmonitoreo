<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CV_GENERALRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

use App\Exports\CvGeneralExport;
use App\Imports\CvGeneralImport;

/**
 * Class CV_GENERALCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CV_GENERALCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \App\Traits\Filters\CriteriaFilterTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CV_GENERAL::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cv_general');
        CRUD::setEntityNameStrings('cv_general', 'CV OSIPTEL');

        $this->loadFilters();
    }

    private function loadFilters()
    {
        $this->loadCriteriaFilter('PROCEDIMIENTO_2G', 'PROCEDIMIENTO 2G');
        $this->loadCriteriaFilter('PROCEDIMIENTO_3G', 'PROCEDIMIENTO 3G');
        $this->loadCriteriaFilter('UBIGEO', 'UBIGEO');
        $this->loadCriteriaFilter('REGION', 'REGION');
        $this->loadCriteriaFilter('DEPARTAMENTO', 'DEPARTAMENTO');
        $this->loadCriteriaFilter('PROVINCIA', 'PROVINCIA');
        $this->loadCriteriaFilter('DISTRITO', 'DISTRITO');
        $this->loadCriteriaFilter('CCPP', 'CCPP');
        $this->loadCriteriaFilter('CC', 'CC');
        $this->loadCriteriaFilter('COBERTURA_2020', 'COBERTURA_2020');
        $this->loadCriteriaFilter('COBERTURA_2021', 'COBERTURA_2021');
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
        CRUD::column('cc')->limit(1000);

        //$this->crud->enableExportButtons();

        $this->crud->addButtonFromView('top', 'import', 'import', 'end');
        $this->crud->addButtonFromView('top', 'agregarPeriodo', 'agregarPeriodo', 'end');
        $this->crud->removeButton('update');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CV_GENERALRequest::class);

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

        Excel::import(new CvGeneralImport, $file);

        return back()->with('message', 'Importación de sitios completada');
    }

    public function export() 
    {
        $now = Carbon::now();

        return (new CvGeneralExport)->download('CV_GENERAL_'.$now->format('Ymd').'.xlsx');
    }

    public function addPeriodo(Request $request)
    {
        Storage::disk('CV')->makeDirectory('Periodo_'.$request->periodo);

        $files = CRUD::getModel()->select()->get();

        foreach($files as $file){
            if($file->ubigeo == null || $file->ubigeo == ''){
                Storage::disk('CV')->makeDirectory('Periodo_'.$request->periodo.'/sin_ubigeo_'.$file->departamento.'_'.$file->provincia.'_'.$file->distrito.'_'.$file->ccpp);
            }
            Storage::disk('CV')->makeDirectory('Periodo_'.$request->periodo.'/'.$file->ubigeo.'_'.$file->departamento.'_'.$file->provincia.'_'.$file->distrito.'_'.$file->ccpp);
        }

        DB::statement("CALL PK_PRG.SP_CV_AGREGAR_PERIODO('PERIODO_".$request->periodo."')");

        return back()->with('message', 'Correcto');
    }
}
