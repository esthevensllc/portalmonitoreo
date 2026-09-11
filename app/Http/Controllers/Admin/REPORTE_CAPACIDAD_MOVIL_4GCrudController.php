<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\REPORTE_CAPACIDAD_MOVIL_4GRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;



use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;


use App\Exports\ReporteCapacidadMovil4GExport;
/**
 * Class REPORTE_CAPACIDAD_MOVIL_4GCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class REPORTE_CAPACIDAD_MOVIL_4GCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
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
        CRUD::setModel(\App\Models\REPORTE_CAPACIDAD_MOVIL_4G::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reporte_capacidad_movil_4G');
        CRUD::setEntityNameStrings('reporte_capacidad_movil_4G', 'REPORTE_CAPACIDAD_MOVIL_4G');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.REPORTE_CAPACIDAD_MOVIL_4G_exportExcel",
            'values' => [
                '0 = Con error',
                '1 = Procesado',
                '2 = Reprocesado',
                '3 = Error en totales',
                '4 = Faltan horas',
                '5 = Reprocesando',
            ]
        ])->to('before_content');
            // ->type('div')
        CRUD::setFromDb(); // columns

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
        CRUD::setValidation(REPORTE_CAPACIDAD_MOVIL_4GRequest::class);

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
        $fechaDia = $_GET['fechaDia'];
        $now = Carbon::now();

        //return (new ReporteCapacidadExport)->download('REPORTE_CAPACIDAD'.$now->format('Ymd').'.xlsx');
        return Excel::download(new ReporteCapacidadMovil4GExport($fechaDia), 'REPORTE_CAPACIDAD_MOVIL_4G'.$now->format('Ymd').'.xlsx');
    }

    public function reporteCapacidadProcedure(){
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_MTC_PROCESOS.SP_MTC_MOVIL_4G(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }
}
