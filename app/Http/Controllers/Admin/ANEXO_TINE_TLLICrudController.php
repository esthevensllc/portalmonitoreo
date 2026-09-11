<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ANEXO_TINE_TLLIRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use PDF;

/**
 * Class ANEXO_TINE_TLLICrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ANEXO_TINE_TLLICrudController extends CrudController
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
    public function setup()
    {
        CRUD::setModel(\App\Models\ANEXO_TINE_TLLI::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/anexo_tine_tlli');
        CRUD::setEntityNameStrings('anexo_tine_tlli', 'a_n_e_x_o__t_i_n_e__t_l_l_is');
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
        CRUD::setValidation(ANEXO_TINE_TLLIRequest::class);

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

    public function pdf(){
        $mes =$_REQUEST['mes'];
        if($mes == 1){
            $fecha = 'ENERO';
        }else if($mes == 2){
            $fecha = 'FEBRERO';
        }else if($mes == 3){
            $fecha = 'MARZO';
        }else if($mes == 4){
            $fecha = 'ABRIL';
        }else if($mes == 5){
            $fecha = 'MAYO';
        }else if($mes == 6){
            $fecha = 'JUNIO';
        }else if($mes == 7){
            $fecha = 'JULIO';
        }else if($mes == 8){
            $fecha = 'AGOSTO';
        }else if($mes == 9){
            $fecha = 'SETIMBRE';
        }else if($mes == 10){
            $fecha = 'OCTUBRE';
        }else if($mes == 11){
            $fecha = 'NOVIEMBRE';
        }else if($mes == 12){
            $fecha = 'DICIEMBRE';
        }

        $anio =$_REQUEST['anio'];

        $pdf = PDF::loadView('anexo.pdf',['fecha'=>$fecha,'anio'=>$anio]);
        //$pdf->loadHTML('<h1>Test</h1>');
        //return $pdf->stream();
        //return view('anexo.pdf');
        return $pdf->download('Anexo_TINE_TLLI_'.$fecha.'_'.$anio.'.pdf');
    }
}
