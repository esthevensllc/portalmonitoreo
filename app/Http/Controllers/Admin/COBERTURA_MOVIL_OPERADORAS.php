<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class COBERTURA_MOVIL_OPERADORAS extends Controller
{
    public function index(Request $request)
    {
        $tracingID = $request->route('tracingID');
        $menuID = $request->route('menuID');

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
        return view('backpack::coberturaOperadoras',compact('arrProcedimiento','tracingID','menuID'));
    }
}