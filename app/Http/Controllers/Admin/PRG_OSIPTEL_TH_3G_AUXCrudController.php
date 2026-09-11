<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Http\Requests\PRG_OSIPTEL_TH_3G_AUXRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use App\Models\PRG_OSIPTEL_TH_3G_AUX;


use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PRG_OSIPTEL_TH_3G_AUXExport;
use Carbon\Carbon;
use PDO;

class PRG_OSIPTEL_TH_3G_AUXCrudController extends CrudController
{

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

   public function index()
    {
        //$sql = 'select result_time, count(result_time)as total from PRG_OSIPTEL_TH_3G_AUX group by result_time';
        //$registers = DB::select($sql);
        //$registers = PRG_OSIPTEL_TH_3G_AUX::paginate(5);
        //$registers = DB::table('PRG_OSIPTEL_TH_3G')
            //->select(DB::raw('result_time,count(result_time) total'))
            //->groupBy('result_time')
            //->paginate(5);
        //$a = $registers[0]->result_time;

       return view('backpack::carga_opsitel_th_3g');
    }

    public function reporteOsiptel3gf1Procedure(){
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_MTC_PROCESOS.SP_OSIPTEL_TH_3G(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }
    public function reporteOsiptel3gf2Procedure(){
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_MTC_PROCESOS.SP_OSIPTEL_TH_3G(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }

    public function setup()
    {
        CRUD::setModel(\App\Models\PRG_OSIPTEL_TH_3G_AUX::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/osiptel_3g_auxc');
        CRUD::setEntityNameStrings('osiptel_3g_auxc', 'o_s_i_p_t_e_l__3_g__a_u_x_c');
    }

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
        CRUD::setValidation(PRG_OSIPTEL_TH_3G_AUXRequest::class);

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

        return (new PRG_OSIPTEL_TH_3G_AUXExport())->download('PRG_OSIPTEL_TH_3G_AUXExport_'.$now->format('Ymd').'.csv');
    }
}
