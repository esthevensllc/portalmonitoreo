<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Grafico_comparativo_tllcRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

class Grafico_comparativo_tllcCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function index()
    {
       
        $registros = DB::select("SELECT to_char(FECHA,'DD/MM/YYYY'), IDCENTRAL, TIPO_LLAMADA,  SUM(TOTAL) as total FROM DW_TLLC_LLAMADAS WHERE to_char(FECHA,'DD/MM/YYYY') = '01/11/2020' GROUP BY FECHA, IDCENTRAL, TIPO_LLAMADA order by fecha");

        $tipos_llamada = DB::select("SELECT TIPO_LLAMADA FROM DW_TLLC_LLAMADAS GROUP BY TIPO_LLAMADA");

        $id_centrals = DB::select("SELECT IDCENTRAL FROM DW_TLLC_LLAMADAS GROUP BY IDCENTRAL");

        //var_dump($id_central);exit;
       
       return view('backpack::grafico_comparativo_tllc',["registros"=>$registros,"tipos_llamada"=> $tipos_llamada,"id_centrals"=> $id_centrals]);
    }

    public function buscarGraficoComparativoTllcCentral(){
        $id_central =$_REQUEST['id_central'];
        $inicioFecha =$_REQUEST['inicioFecha'];
        $finFecha =$_REQUEST['finFecha'];
        $registros = DB::select("SELECT to_char(FECHA,'DD/MM/YYYY') as FECHA, IDCENTRAL,   SUM(TOTAL) as total FROM DW_TLLC_LLAMADAS
        WHERE FECHA >= to_date('$inicioFecha','DD/MM/YYYY')
        AND FECHA < to_date('$finFecha','DD/MM/YYYY')
        AND IDCENTRAL = $id_central
        GROUP BY FECHA, IDCENTRAL
        ORDER BY FECHA");

        return $registros;

    }

    public function buscarGraficoComparativoTllcTipoLlamada(){
        $tipo_llamada =$_REQUEST['tipo_llamada'];
        $inicioFecha =$_REQUEST['inicioFecha'];
        $finFecha =$_REQUEST['finFecha'];
        $registros = DB::select("SELECT to_char(FECHA,'DD/MM/YYYY') as FECHA, TIPO_LLAMADA,  SUM(TOTAL) AS TOTAL FROM DW_TLLC_LLAMADAS
        WHERE FECHA >= to_date('$inicioFecha','DD/MM/YYYY')
        AND FECHA < to_date('$finFecha','DD/MM/YYYY')
        AND TIPO_LLAMADA = '$tipo_llamada'
        GROUP BY FECHA, TIPO_LLAMADA
        ORDER BY FECHA");

        return $registros;

    }
    public function setup()
    {
        CRUD::setModel(\App\Models\Grafico_comparativo_tllc::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/grafico_comparativo_tllc');
        CRUD::setEntityNameStrings('grafico_comparativo_tllc', 'grafico_comparativo_tllcs');
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

    protected function setupCreateOperation()
    {
        CRUD::setValidation(Grafico_comparativo_tllcRequest::class);

        CRUD::setFromDb(); // fields

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
