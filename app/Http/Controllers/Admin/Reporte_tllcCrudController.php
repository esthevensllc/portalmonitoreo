<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Reporte_tllcRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
//use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

use App\Exports\Reporte_tllcExport;
 
/**
 * Class Reporte_tllcCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Reporte_tllcCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function index(){
        $registros = DB::select("SELECT  * FROM PRG_OSIPTEL_REPORTE_TLLC WHERE TRUNC (FECHA, 'MM') = '01/02/2020'");

        //var_dump($registros);exit;

        return view('backpack::reporte_tllc');


        
    }

    public function buscarReporteTllc(){
        $p_mes =$_REQUEST['mes'];
        $p_anio =$_REQUEST['anio'];
        //var_dump($p_mes,$p_anio);exit;

        $registros = DB::select("Select * from PRG_OSIPTEL_REPORTE_TLLC WHERE  FECHA_MES = '$p_anio-$p_mes'");
        return($registros);

    }

    public function setup()
    {
        CRUD::setModel(\App\Models\Reporte_tllc::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reporte_tllc');
        CRUD::setEntityNameStrings('reporte_tllc', 'reporte_tllc');
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
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(Reporte_tllcRequest::class);

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
        $fechas =$_REQUEST['fechas'];
        $now = Carbon::now();

        //return (new ReporteCapacidadExport)->download('REPORTE_CAPACIDAD'.$now->format('Ymd').'.xlsx');
        return Excel::download(new Reporte_tllcExport($fechas), 'REPORTE_TLLC'.$now->format('Ymd').'.xlsx');
    }

    public function reporteTllcProcedure(){
        $fechaInicio = $_GET['inicioFecha'];
        $fechaFin = $_GET['finFecha'];

        //var_dump($fechaInicio);
        //var_dump($fechaFin);exit;
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_OSIPTEL_PROCESOS.OSIPTEL_REPORTE_TLLC(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }
}
