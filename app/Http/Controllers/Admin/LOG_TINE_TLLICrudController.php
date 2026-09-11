<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LOG_TINE_TLLIRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDO;

/**
 * Class LOG_TINE_TLLICrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LOG_TINE_TLLICrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\Filters\CriteriaFilterTrait;
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
        CRUD::setModel(\App\Models\LOG_TINE_TLLI::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/log_tine_tlli');
        CRUD::setEntityNameStrings('log_tine_tlli', 'Log tine tlli');
        $this->loadFilters();
    }

    public function loadFilters(){
        $this->loadCriteriaFilter('FECHA', 'FECHA');
        $this->loadCriteriaFilter('RESULT_TIME', 'RESULT TIME');
        $this->loadCriteriaFilter('HORAS', 'HORAS');
        $this->loadCriteriaFilter('TECNOLOGIA', 'TECNOLOGIA');
        $this->loadCriteriaFilter('ESTADO', 'ESTADO');
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
            'view' => "backpack::base.inc.widgets.LOG_TINE_TLLI_StatusLabels",
            'values' => [
                '0 = Con error',
                '1 = Procesado',
                '2 = Reprocesado',
                '3 = Error en totales',
                '4 = Faltan horas',
                '5 = Reprocesando',
                '6 = Archivo recargado'
            ]
        ])->to('before_content');
            // ->type('div')
        
        CRUD::setFromDb(); // columns

        $this->crud->orderBy('estado','asc');

        $this->crud->orderBy('fecha','desc');

        $this->crud->addButtonFromView('line', 'verRegistros', 'verRegistros', 'beginning');

        // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->addButtonFromView('line', 'recargar', 'recargar', 'end');

        // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->addButtonFromView('line', 'forzarRecarga', 'forzarRecarga', 'end');

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
        CRUD::setValidation(LOG_TINE_TLLIRequest::class);

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

    public function verRegistros($id)
    {
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        $tecnologia = $entry->tecnologia;
        $fecha = $entry->result_time;
        // whatever you decide to do
        $query = DB::select(DB::raw("select * from CM_INDCAL_TINE_TLLI_".$tecnologia."_HXH_DIA where trunc(result_time) = to_date('".$fecha."','DD/MM/YYYY') order by hora"));
        $data = $query;
        return response()->json(compact('data'));
    }

    public function recargar($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        $tecnologia = $entry->tecnologia;
        $fecha = $entry->result_time;
        $horas = ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
        $query = DB::select(DB::raw("select hora from CM_INDCAL_TINE_TLLI_".$tecnologia."_HXH_DIA where trunc(result_time) = to_date('".$fecha."','DD/MM/YYYY')"));
        $query = collect($query)->map(function ($item){
            return $item->hora;
        })->toArray();

        foreach($horas as $hora){
            if(in_array($hora,$query)){
                //nada
            }else{
                $fecha_fin = $fecha;
                if(in_array($hora,['00','01','02','03','04','05','06','07','08','09'])){
                    $int_hora = (int)substr($hora,-1) + 1;
                    $int_hora = $int_hora < 10 ? '0'+$int_hora : $int_hora;
                }else{
                    if($hora == '23'){
                        $fecha_fin = Carbon::createFromFormat('d/m/Y', $fecha)->add(1, 'day')->format('d/m/Y');
                        $int_hora = '00';
                    }else{
                        $int_hora = (int)$hora + 1;
                    }                    
                }
                $this->procedureRecarga($tecnologia,$fecha,$hora,$fecha_fin,$int_hora);                
            }
        }

        $query = DB::select(DB::raw("select hora,estado from CM_INDCAL_TINE_TLLI_".$tecnologia."_HXH_DIA where trunc(result_time) = to_date('".$fecha."','DD/MM/YYYY')"));
        foreach($query as $estado){
        
            if($estado->estado <> "1" && $estado->estado <> "2"){
                $fecha_fin = $fecha;
                if(in_array($estado->hora,['00','01','02','03','04','05','06','07','08','09'])){
                    $int_hora = (int)substr($estado->hora,-1) + 1;
                    $int_hora = $int_hora < 10 ? '0'.$int_hora : $int_hora;
                }else{
                    if($estado->hora == '23'){
                        $fecha_fin = Carbon::createFromFormat('d/m/Y', $fecha)->add(1, 'day')->format('d/m/Y');
                        $int_hora = '00';
                    }else{
                        $int_hora = (int)$hora + 1;
                    }                    
                }
                $this->procedureRecarga($tecnologia,$fecha,$estado->hora,$fecha_fin,$int_hora);
            }
        }

    }

    public function forzarRecarga($id)
    {
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $query = DB::table('CM_INDCAL_TINE_TLLI_HXH_LOG')
        ->where('id',$id)
        ->update([
            'estado' => '2',
        ]);

        $data = $query;

        return response()->json(compact('data'));
    }

    public function procedureRecarga($tecnologia,$fecha,$hora,$fecha_fin,$int_hora){
        if($tecnologia == '2G'){

            DB::table("CM_INDCAL_TINE_TLLI_HXH_LOG")->where('tecnologia','2G')->where('result_time',$fecha)->update(['estado'=>'5']);

            DB::statement("CALL pk_cm_atae_netac.sp_cm_carga_femto_indicadores('".$fecha." ".$hora."','".$fecha_fin." ".$int_hora."')");
            
            $fecha = $fecha." ".$hora;

            $statement = DB::connection()->getPdo()->prepare("CALL PK_INDCAL_TINE_TLLI_CAR_DIA.SP_INDCAL_TINE_TLLI_2G_RECARGA_HORA(?)");
            
            $statement->bindParam(1, $fecha);

            $statement->execute();


        }else{
            if($tecnologia == '3G'){

                DB::table("CM_INDCAL_TINE_TLLI_HXH_LOG")->where('tecnologia','3G')->where('result_time',$fecha)->update(['estado'=>'5']);

                DB::statement("CALL pk_cm_atae_netac.SP_CM_CARGA_UMTS_INDICAD_CELL('".$fecha." ".$hora."','".$fecha_fin." ".$int_hora."')");                 
                
                $fecha = $fecha." ".$hora;
    
                $statement = DB::connection()->getPdo()->prepare("CALL PK_INDCAL_TINE_TLLI_CAR_DIA.SP_INDCAL_TINE_TLLI_3G_RECARGA_HORA(?)");
                
                $statement->bindParam(1, $fecha);
    
                $statement->execute();

            }
        }
    }
}
