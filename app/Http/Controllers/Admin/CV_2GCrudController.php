<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CV_2GRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

use App\Exports\Cv2GExport;
use App\Imports\Cv2GImport;
/**
 * Class CV_2GCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CV_2GCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CV_2G::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cv_2g');
        CRUD::setEntityNameStrings('cv_2g', 'CV 2G');

        $this->loadFilters();
    }

    private function loadFilters(){
        $this->loadCriteriaFilter("UBIGEO", "UBIGEO");
        $this->loadCriteriaFilter('REGION', 'REGION', 'default', function($criteria) {
            $criteriaReturn = clone $criteria;
            $motivos = DB::table("PRG_CV_MULTAS_GENERAL")
                ->where(DB::Raw("LOWER(NOMBRE)"), 'LIKE', "%".strtolower($criteria->value)."%")->get();
            $motivosId = [];
            foreach($motivos as $mo){
                $motivosId[] = $mo->id;
            }
            $criteriaReturn->operator = 'in';
            $criteriaReturn->value = $motivosId;
            // dd($criteriaReturn);
            return $criteriaReturn;
        });

        $this->loadCriteriaFilter('DEPARTAMENTO', 'DEPARTAMENTO', 'default', function($criteria) {
            $criteriaReturn = clone $criteria;
            $motivos = DB::table("PRG_CV_MULTAS_GENERAL")
                ->where(DB::Raw("LOWER(NOMBRE)"), 'LIKE', "%".strtolower($criteria->value)."%")->get();
            $motivosId = [];
            foreach($motivos as $mo){
                $motivosId[] = $mo->id;
            }
            $criteriaReturn->operator = 'in';
            $criteriaReturn->value = $motivosId;
            // dd($criteriaReturn);
            return $criteriaReturn;
        });

        $this->loadCriteriaFilter('DISTRITO', 'DISTRITO', 'default', function($criteria) {
            $criteriaReturn = clone $criteria;
            $motivos = DB::table("PRG_CV_MULTAS_GENERAL")
                ->where(DB::Raw("LOWER(NOMBRE)"), 'LIKE', "%".strtolower($criteria->value)."%")->get();
            $motivosId = [];
            foreach($motivos as $mo){
                $motivosId[] = $mo->id;
            }
            $criteriaReturn->operator = 'in';
            $criteriaReturn->value = $motivosId;
            // dd($criteriaReturn);
            return $criteriaReturn;
        });

        $this->loadCriteriaFilter('CCPP', 'CCPP', 'default', function($criteria) {
            $criteriaReturn = clone $criteria;
            $motivos = DB::table("PRG_CV_MULTAS_GENERAL")
                ->where(DB::Raw("LOWER(NOMBRE)"), 'LIKE', "%".strtolower($criteria->value)."%")->get();
            $motivosId = [];
            foreach($motivos as $mo){
                $motivosId[] = $mo->id;
            }
            $criteriaReturn->operator = 'in';
            $criteriaReturn->value = $motivosId;
            // dd($criteriaReturn);
            return $criteriaReturn;
        });

        $this->loadCriteriaFilter("MULTA_GSM", "MULTA_GSM");
        $this->loadCriteriaFilter("PERIODO_MULTA", "PERIODO_MULTA");
        $this->loadCriteriaFilter("ESTADO", "ESTADO");
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

        //$this->crud->enableExportButtons();

        $periodos = DB::table('prg_cv_periodos')->orderBy('id','asc')->get('periodo')->toArray();

        //$this->crud->setColumns($periodos);

        CRUD::addColumn(['name' => 'cc', 'type' => 'text', 'limit' => 200]);

        $this->crud->addColumn([
            'name' => 'region', // The db column name
            'label' => 'REGIÓN', // Table column heading
            'function_name' => 'getRegion',
            'type' => 'model_function',
            'limit' => 200
        ]);

        $this->crud->addColumn([
            'name' => 'departamento', // The db column name
            'label' => 'DEPARTAMENTO', // Table column heading
            'function_name' => 'getDepartamento',
            'type' => 'model_function',
            'limit' => 200
        ]);

        $this->crud->addColumn([
            'name' => 'distrito', // The db column name
            'label' => 'DISTRITO', // Table column heading
            'function_name' => 'getDistrito',
            'type' => 'model_function',
            'limit' => 200
        ]);

        $this->crud->addColumn([
            'name' => 'ccpp', // The db column name
            'label' => 'CCPP', // Table column heading
            'function_name' => 'getCCPP',
            'type' => 'model_function',
            'limit' => 200
        ]);

        foreach(array_slice($periodos,-3) as $periodo){
            CRUD::addColumn(['name' => strtolower($periodo->periodo), 'type' => 'float']);
        }

        CRUD::addColumn(['name' => 'multa_gsm', 'type' => 'text']);
        CRUD::addColumn(['name' => 'periodo_multa', 'type' => 'text']);
        CRUD::addColumn(['name' => 'estado', 'type' => 'text']);

        $this->crud->addButtonFromView('top', 'import', 'import', 'end');

        $this->crud->removeButton('update');

        // add a button whose HTML is returned by a method in the CRUD model
        //$this->crud->addButtonFromView('line', 'subirKml', 'importCvKml', 'end');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CV_2GRequest::class);

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

        Excel::import(new Cv2GImport, $file);

        return back()->with('message', 'Importación de cv 2g completada');
    }

    public function export() 
    {
        $now = Carbon::now();

        return (new Cv2GExport)->download('CV2G_'.$now->format('Ymd').'.xlsx');
    }
}
