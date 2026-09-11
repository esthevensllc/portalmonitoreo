<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CV_RESUMEN extends Controller
{
    public function index()
    {
        $procedimientos = DB::table('PRG_CV_MULTAS_GENERAL')->where('procedimiento_2g','<>','Ninguno')->groupBy('procedimiento_2g')->get('procedimiento_2g')->toArray();
        $arrProcedimiento = array();
        foreach($procedimientos as $procedimiento){
            if(str_contains($procedimiento->procedimiento_2g,'_')){
                $proced_split = explode("_",$procedimiento->procedimiento_2g);
                foreach($proced_split as $proced){
                    $arrProcedimiento[] = $proced;
                }
            }else{
                $arrProcedimiento[] = $procedimiento->procedimiento_2g;
            }
        }
        $arrProcedimiento = array_unique($arrProcedimiento);
        return view('backpack::cv_resumen',compact('arrProcedimiento'));
    }
}