<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PRG_OSIPTEL_TH_4G_AUXRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\MaestroBtsExport;
use Carbon\Carbon;
use PDO;
/**
 * Class PRG_OSIPTEL_TH_4G_AUXCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PRG_OSIPTEL_TH_4G_AUXCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;



    public function index()
    {
        //$sql = 'select result_time, count(result_time)as total from PRG_OSIPTEL_TH_3G_AUX group by result_time';
        //$registers = DB::select($sql);
        //$registers = PRG_OSIPTEL_TH_3G_AUX::paginate(5);
       // $registers = DB::table('PRG_OSIPTEL_TH_4G')
            //->select(DB::raw('result_time,count(result_time) total'))
            //->groupBy('result_time')
            //->paginate(5);
        //$a = $registers[0]->result_time;

       return view('backpack::carga_opsitel_th_4g');
    }

    public function reporteOsiptel4gf1Procedure(){
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_MTC_PROCESOS.SP_OSIPTEL_TH_4G(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }
    public function reporteOsiptel4gf2Procedure(){
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $statement = DB::connection()->getPdo()->prepare("CALL PK_PRG_MTC_PROCESOS.SP_OSIPTEL_TH_4G(?,?)");
            
        $statement->bindParam(1, $fechaInicio);
        $statement->bindParam(2, $fechaFin);

        $statement->execute();
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PRG_OSIPTEL_TH_4G_AUX::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/osiptel_4g_auxc');
        CRUD::setEntityNameStrings('prg_osiptel_th_4g_aux', 'p_r_g__o_s_i_p_t_e_l__t_h_4_g__a_u_xes');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    /*protected function setupListOperation()
    {

        \Backpack\CRUD\app\Library\Widget::add([
            'type' => "view",
            'view' => "backpack::base.inc.widgets.PRG_OSIPTEL_TH_4G",
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

        
    }*/

    
}
