<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TRAFICO_CORPORATIVO_REGULATORIORequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\TraficoCorporativoRegulatorioExport;
use Carbon\Carbon;
use PDO;

use Illuminate\Http\Request;
use DB;

/**
 * Class TRAFICO_CORPORATIVO_REGULATORIOCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TRAFICO_CORPORATIVO_REGULATORIOCrudController extends CrudController
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


    public function index()
    {
       return view('backpack::trafico_corporativo_regulatorio');
    }

    public function setup()
    {
        CRUD::setModel(\App\Models\TRAFICO_CORPORATIVO_REGULATORIO::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/trafico_corporativo_regulatorio');
        CRUD::setEntityNameStrings('trafico_corporativo_regulatorio', 't_r_a_f_i_c_o__c_o_r_p_o_r_a_t_i_v_o__r_e_g_u_l_a_t_o_r_i_os');
    }

    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(TRAFICO_CORPORATIVO_REGULATORIORequest::class);

        CRUD::setFromDb(); // fields

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    public function export() 
    {
        $p_mes =$_REQUEST['mes'];
        $p_anio =$_REQUEST['anio'];
        $now = Carbon::now();

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[$p_mes - 1];

        return (new TraficoCorporativoRegulatorioExport($p_mes,$p_anio))->download('Reporte Tráfico '.$mes.' '.$p_anio.'.xlsx');
    }

    
}
