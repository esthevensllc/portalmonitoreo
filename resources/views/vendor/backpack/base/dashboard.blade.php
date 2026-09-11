@extends(backpack_view('blank'))

@php
    //header("Location: ".asset("acceso_4g_movil_resumen/3/355"));
    //exit; 
    $urllid = env("APP_URLID", 2318);
    use Backpack\CRUD\app\Library\Widget;
    use Illuminate\Support\Facades\DB;

    $path_by_type = [
        "355" => "resumen",
        "13" => "provision",
        "6748" => "fija-coverage",
        "10804" => "empresas",
        "10142" => "detalle",
        "10143" => "operadoras",
        "14" => "configuracion",
        "16" => "desemp",
        "15" => "alarmas",
        "11926" => "buscar-direccion",
        "12026" => "operacion-fija/dashboard",
        "12106" => "optimizacion-bosf",
    ];

    
    $usuario = backpack_user()->usuario; 

    $perfiles = DB::table('padm_usuarioperfil')->where('usuario', $usuario)->where('estado', 1)->select('perfil')->get();


    foreach($perfiles as $perfil){

        $kpis = DB::table('PSO_TYPE AS PT1')->join('PSO_TYPE AS PT2', 'PT1.ID_TYPE','PT2.TYPE_FATHER')->where('PT2.STATUS','>',0)->join('PSO_SEGUIMIENTONE AS SN', 'SN.ID_TRACING','PT2.TYPE_NAME')->where('SN.STATUS','>',0)->join('PADM_TYPE AS PT3', 'SN.ID_TRACING' ,'PT3.TYPE_DESCRIPTION')->where('PT3.TYPE_NAME', $perfil->perfil)->where('PT3.STATUS',1)->where('PT3.TYPE_FATHER',3)->where('PT1.TYPE_FATHER',2263)->where('PT1.ID_TYPE',$urllid)->where('PT1.STATUS','>',0)->select('SN.ID_TRACING','SN.ID_TYPE','SN.TRACING_NAME')->orderBy('SN.ID_TYPE','ASC')->orderBy('SN.ID_TRACING','ASC')->get();

        $menuTemp = 0;

        $responses = DB::executeProcedureWithCursor("pk_padm_proceso.SP_GETMENUBYPERFIL005",[$perfil->perfil]);

        $result = [];

        foreach($responses as $response) {
            if(isset($result[$response->type_name][$response->menuid])){
                if(!$response->menuname == $result[$response->type_name][$response->menuid]){
                    $result[$response->type_name][$response->menuid] = $response->menuname;
                }
            }else{
                $result[$response->type_name][$response->menuid] = $response->menuname;
            }
        }

        $menu_permissions =  $result;

        foreach($kpis as $kpi){


            if($menuTemp != $kpi->id_type){

                $menu = DB::table('PSO_TYPE')->where('TYPE_FATHER', 2)->where('ID_TYPE', $kpi->id_type)->get('TYPE_NAME')->first();

                $menuTemp = $kpi->id_type;


                foreach($menu_permissions[$kpi->id_tracing] as $key => $menu_permission){
                    //header("Location: ".asset(str_replace(" ","_",strtolower($menu->type_name))."_".str_replace(" ","_",strtolower($kpi->tracing_name))."_".str_replace(" ","_",strtolower($menu_permission))."/".$kpi->id_tracing."/".$key));
                    $extra_url = "";
                    if($key === "16"){
                        $extra_url = "/16";
                    }else if($key === "355"){
                        $extra_url = "/355";
                    }
                    header("Location: " . asset($path_by_type[$key]) . "/{$kpi->id_tracing}{$extra_url}?menu=".rawurlencode($kpi->tracing_name)."-".$menu_permission);
                    exit;                    
                }

            
            }else{

                foreach($menu_permissions[$kpi->id_tracing] as $key => $menu_permission){
                    //header("Location: ".asset(str_replace(" ","_",strtolower($menu->type_name))."_".str_replace(" ","_",strtolower($kpi->tracing_name))."_".str_replace(" ","_",strtolower($menu_permission))."/".$kpi->id_tracing."/".$key));
                    $extra_url = "";
                    if($key === "16"){
                        $extra_url = "/16";
                    }else if($key === "355"){
                        $extra_url = "/355";
                    }
                    header("Location: " . asset($path_by_type[$key]) . "/{$kpi->id_tracing}{$extra_url}?menu=".rawurlencode($kpi->tracing_name)."-".$menu_permission);
                    exit;                    
                }

            }

        }
    }

    /* Widget::add([ 
            'type'    => 'div',
            'class'   => 'row',
            'content' => [ // widgets 
                [ 'type' => 'chart', 'controller' => \App\Http\Controllers\Admin\Charts\WeeklyBarCVMChartController::class, 'wrapper' => ['class'=> 'col-md-6'] , 'content' => ['header' => 'CVM BARS']],
                [ 'type' => 'chart', 'controller' => \App\Http\Controllers\Admin\Charts\WeeklyCVMChartController::class, 'wrapper' => ['class'=> 'col-md-6'] , 'content' => ['header' => 'CVM LINES']]
            ]            
        ])->to('before_content');
    */    

   /* $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => 'Portal Regulatorio',
        'content'     => 'Network Performance',
        'buttons'     => $html
    ];
    */
@endphp

@section('content')

@endsection