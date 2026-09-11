<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if(Route::currentRouteName() == 'backpack.dashboard')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
@else
    <?php 
    //$urllid = Request::route("urlid") ?? 2318;
    $urllid = env("APP_URLID", 2318);
    $usuario = backpack_user()->usuario; 
    //$currentURL = Request::path();
    $perfiles = DB::table('padm_usuarioperfil')->where('usuario', $usuario)->where('estado', 1)->select('perfil')->get();
    //$trac_father = DB::table('padm_menus')->where('URL', $currentURL)->where('TRAC_STATUS', 1)->select('trac_father')->first();

    foreach($perfiles as $perfil){

        $kpis = DB::table('PSO_TYPE AS PT1')
	->join('PSO_TYPE AS PT2', 'PT1.ID_TYPE','PT2.TYPE_FATHER')
	->join('PSO_SEGUIMIENTONE AS SN', 'SN.ID_TRACING','PT2.TYPE_NAME')
	->leftJoin('PSO_TYPE AS SEGMENU', 'SEGMENU.ID_TYPE','SN.ID_TYPE')
	->where('PT2.STATUS','>',0)
	->where('SN.STATUS','>',0)->join('PADM_TYPE AS PT3', 'SN.ID_TRACING' ,'PT3.TYPE_DESCRIPTION')
	->where('PT3.TYPE_NAME', $perfil->perfil)
	->where('PT3.STATUS',1)
	->where('PT3.TYPE_FATHER',3)
	->where('PT1.TYPE_FATHER',2263)
	->where('PT1.ID_TYPE',$urllid)
	->where('PT1.STATUS','>',0)
	->select('SN.ID_TRACING','SN.ID_TYPE','SN.TRACING_NAME')
	->orderBy('SEGMENU.TYPE_ORDER','ASC')->orderBy('SN.ORDEN','ASC')->get();
        
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

                if($menuTemp != 0){
                    echo '</ul></li>';
                }

                $menu = DB::table('PSO_TYPE')->where('TYPE_FATHER', 2)->where('ID_TYPE', $kpi->id_type)->get('TYPE_NAME')->first();

                $menuTemp = $kpi->id_type;

                //$menu = DB::table('padm_menus')->where('id_tracing', $type_name->type_name)->where('trac_status', 1)->get('url')->first();
                echo '<li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-newspaper-o"></i>'.$menu->type_name.'</a>
                        <ul class="nav-dropdown-items" style="padding-left: 1.5rem;">';
                
                if($kpi->tracing_name == "Empresas--"){
                    echo "<li class='nav-item nav-dropdown'>";
                    foreach($menu_permissions[$kpi->id_tracing] as $key => $menu_permission){
                        $key = strval($key);
                        $href_link = asset(str_replace(" ","_",strtolower($menu->type_name))."_".str_replace(" ","_",strtolower($kpi->tracing_name))."_".str_replace(" ","_",strtolower($menu_permission))."/".$kpi->id_tracing."/".$key);
                        if($menu_permission === "Desempeño"){
                            $href_link = asset('desemp')."/{$kpi->id_tracing}/16";
                        }else if($menu_permission === "Resumen"){
                            $href_link = asset('resumen')."/{$kpi->id_tracing}/355";
                        }else if($menu_permission === "Alarmas"){
                            $href_link = asset('alarmas')."/{$kpi->id_tracing}";
                        }else if($key === "13"){
                            $href_link = asset('provision')."/{$kpi->id_tracing}";
                        }else if($key === "6748"){
                            $href_link = asset('fija-coverage')."/{$kpi->id_tracing}";
                        }else if($key === "10804"){
                            $href_link = asset('empresas')."/{$kpi->id_tracing}";
                        }else if($key === "10142"){
                            $href_link = asset('detalle')."/{$kpi->id_tracing}";
                        }else if($key === "10143"){
                            $href_link = asset('operadoras')."/{$kpi->id_tracing}";
                        }else if($key === "14"){
                            $href_link = asset('configuracion')."/{$kpi->id_tracing}";
                        }else if($key === "9006"){
                            $href_link = asset('filesdown')."/{$kpi->id_tracing}";
                        }else if(in_array($key, ["5610","5611","5612","5613","5614","5615","5616","5617","5618","5619","5620", "5621", "5622", "5623", "5624", "5625", "5626"])){
                            $href_link = asset('replist')."/{$kpi->id_tracing}/{$key}";
                        }else if($key === "11926"){
                            $href_link = asset('buscar-direccion')."/{$kpi->id_tracing}";
                        }else if($key === "11966"){
                            $href_link = asset('sots')."/{$kpi->id_tracing}";
                        }else if($key === "12006"){
                            $href_link = asset('buscar-casa-direccion')."/{$kpi->id_tracing}";
                        }else if($key === "12007"){
                            $href_link = asset('buscar-casa-coordenada')."/{$kpi->id_tracing}";
                        }else if($key === "12008"){
                            $href_link = asset('buscar-casa-plano')."/{$kpi->id_tracing}";
                        }else if($key === "12009"){
                            $href_link = asset('buscar-edificios')."/{$kpi->id_tracing}";
                        }
                        echo "<a class='nav-link' href='{$href_link}?menu=".rawurlencode(ucfirst(strtolower($menu->type_name)))."-".rawurlencode($menu_permission)."'><i class='nav-icon la la-newspaper-o'></i><span>".$menu_permission."</span></a>";
                    }
                    echo "</li>";
                }else{
                    echo "<li class='nav-item nav-dropdown'>
                    <a class='nav-link nav-dropdown-toggle' href='#'><i class='nav-icon la la-newspaper-o'></i>".$kpi->tracing_name."</a>
                    <ul class='nav-dropdown-items'>";
                    foreach($menu_permissions[$kpi->id_tracing] as $key => $menu_permission){
                        $key = strval($key);
                        $href_link = asset(str_replace(" ","_",strtolower($menu->type_name))."_".str_replace(" ","_",strtolower($kpi->tracing_name))."_".str_replace(" ","_",strtolower($menu_permission))."/".$kpi->id_tracing."/".$key);
                        $icon = "la-newspaper-o";
                        if($menu_permission === "Desempeño"){
                            $href_link = asset('desemp')."/{$kpi->id_tracing}/16";
                        }else if($menu_permission === "Resumen"){
                            $href_link = asset('resumen')."/{$kpi->id_tracing}/355";
                        }else if($menu_permission === "Alarmas"){
                            $href_link = asset('alarmas')."/{$kpi->id_tracing}";
                        }else if($key === "13"){
                            $href_link = asset('provision')."/{$kpi->id_tracing}";
                        }else if($key === "6748"){
                            $href_link = asset('fija-coverage')."/{$kpi->id_tracing}";
                        }else if($key === "10804"){
                            $href_link = asset('empresas')."/{$kpi->id_tracing}";
                        }else if($key === "10142"){
                            $href_link = asset('detalle')."/{$kpi->id_tracing}";
                        }else if($key === "10143"){
                            $href_link = asset('operadoras')."/{$kpi->id_tracing}";
                        }else if($key === "14"){
                            $href_link = asset('configuracion')."/{$kpi->id_tracing}";
                        }else if($key === "9006"){
                            $href_link = asset('filesdown')."/{$kpi->id_tracing}";
                        }else if(in_array($key, ["5610","5611","5612","5613","5614","5615","5616","5617","5618","5619","5620", "5621", "5622", "5623", "5624", "5625", "5626"])){
                            $href_link = asset('replist')."/{$kpi->id_tracing}/{$key}";
                        }else if($key === "11926"){
                            $href_link = asset('buscar-direccion')."/{$kpi->id_tracing}";
                        }else if($key === "11966"){
                            $href_link = asset('sots')."/{$kpi->id_tracing}";
                        }else if($key === "12006"){
                            $href_link = asset('buscar-casa-direccion')."/{$kpi->id_tracing}";
                            $icon = "la-home";
                        }else if($key === "12007"){
                            $href_link = asset('buscar-casa-coordenada')."/{$kpi->id_tracing}";
                            $icon = "la-home";
                        }else if($key === "12008"){
                            $href_link = asset('buscar-casa-plano')."/{$kpi->id_tracing}";
                            $icon = "la-home";
                        }else if($key === "12009"){
                            $href_link = asset('buscar-edificios')."/{$kpi->id_tracing}";
                            $icon = "la-building";
                        }else if($key === "12026"){
                            $href_link = asset('operacion-fija/dashboard')."/{$kpi->id_tracing}";
                        }else if($key === "12106"){
                            $href_link = asset('optimizacion-bosf')."/{$kpi->id_tracing}";
                        }else if($key === "12126"){
                            $href_link = asset('cobertura-fija-ventas')."/{$kpi->id_tracing}";
                        }else if($key === "12250"){
                            $href_link = asset('afectacion_energia')."/{$kpi->id_tracing}";
                        }else if($key === "12551"){
                            $href_link = asset('ookla-map')."/{$kpi->id_tracing}";
                        }else if($key === "12590"){
                            $href_link = asset('ookla-map-fija')."/{$kpi->id_tracing}";
                        }
                        echo "<li class='nav-item'><a class='nav-link' href='{$href_link}?menu=".rawurlencode($kpi->tracing_name)."-".rawurlencode($menu_permission)."'><i class='nav-icon la {$icon}'></i><span>".$menu_permission."</span></a></li>";
                    }
                    echo "</ul>
                            </li>";
                }
            
            }else{
                if($kpi->tracing_name == "Empresas--"){
                    echo "<li class='nav-item nav-dropdown'>";
                    foreach($menu_permissions[$kpi->id_tracing] as $key => $menu_permission){
                        $key = strval($key);
                        $href_link = asset(str_replace(" ","_",strtolower($menu->type_name))."_".str_replace(" ","_",strtolower($kpi->tracing_name))."_".str_replace(" ","_",strtolower($menu_permission))."/".$kpi->id_tracing."/".$key);
                        if($menu_permission === "Desempeño"){
                            $href_link = asset('desemp')."/{$kpi->id_tracing}/16";
                        }else if($menu_permission === "Resumen"){
                            $href_link = asset('resumen')."/{$kpi->id_tracing}/355";
                        }else if($menu_permission === "Alarmas"){
                            $href_link = asset('alarmas')."/{$kpi->id_tracing}";
                        }else if($key === "13"){
                            $href_link = asset('provision')."/{$kpi->id_tracing}";
                        }else if($key === "6748"){
                            $href_link = asset('fija-coverage')."/{$kpi->id_tracing}";
                        }else if($key === "10804"){
                            $href_link = asset('empresas')."/{$kpi->id_tracing}";
                        }else if($key === "10142"){
                            $href_link = asset('detalle')."/{$kpi->id_tracing}";
                        }else if($key === "10143"){
                            $href_link = asset('operadoras')."/{$kpi->id_tracing}";
                        }else if($key === "14"){
                            $href_link = asset('configuracion')."/{$kpi->id_tracing}";
                        }else if($key === "9006"){
                            $href_link = asset('filesdown')."/{$kpi->id_tracing}";
                        }else if(in_array($key, ["5610","5611","5612","5613","5614","5615","5616","5617","5618","5619","5620", "5621", "5622", "5623", "5624", "5625", "5626"])){
                            $href_link = asset('replist')."/{$kpi->id_tracing}/{$key}";
                        }else if($key === "11926"){
                            $href_link = asset('buscar-direccion')."/{$kpi->id_tracing}";
                        }else if($key === "11966"){
                            $href_link = asset('sots')."/{$kpi->id_tracing}";
                        }else if($key === "12006"){
                            $href_link = asset('buscar-casa-direccion')."/{$kpi->id_tracing}";
                        }else if($key === "12007"){
                            $href_link = asset('buscar-casa-coordenada')."/{$kpi->id_tracing}";
                        }else if($key === "12008"){
                            $href_link = asset('buscar-casa-plano')."/{$kpi->id_tracing}";
                        }else if($key === "12009"){
                            $href_link = asset('buscar-edificios')."/{$kpi->id_tracing}";
                        }
                        echo "<a class='nav-link' data-id_type=\"{$key}\" href='{$href_link}?menu=".rawurlencode($kpi->tracing_name)."-".rawurlencode($menu_permission)."'><i class='nav-icon la la-newspaper-o'></i><span>".$menu_permission."</span></a>";
                    }
                    echo "</li>";
                }else{
                    echo "<li class='nav-item nav-dropdown'>
                            <a class='nav-link nav-dropdown-toggle' href='#'><i class='nav-icon la la-newspaper-o'></i>".$kpi->tracing_name."</a>
                            <ul class='nav-dropdown-items'>";
                    foreach($menu_permissions[$kpi->id_tracing] as $key => $menu_permission){
                        $key = strval($key);
                        $href_link = asset(str_replace(" ","_",strtolower($menu->type_name))."_".str_replace(" ","_",strtolower($kpi->tracing_name))."_".str_replace(" ","_",strtolower($menu_permission))."/".$kpi->id_tracing."/".$key);
                        $icon = "la-newspaper-o";
                        if($menu_permission === "Desempeño"){
                            $href_link = asset('desemp')."/{$kpi->id_tracing}/16";
                        }else if($menu_permission === "Resumen"){
                            $href_link = asset('resumen')."/{$kpi->id_tracing}/355";
                        }else if($menu_permission === "Alarmas"){
                            $href_link = asset('alarmas')."/{$kpi->id_tracing}";
                        }else if($key === "13"){
                            $href_link = asset('provision')."/{$kpi->id_tracing}";
                        }else if($key === "6748"){
                            $href_link = asset('fija-coverage')."/{$kpi->id_tracing}";
                        }else if($key === "10804"){
                            $href_link = asset('empresas')."/{$kpi->id_tracing}";
                        }else if($key === "10142"){
                            $href_link = asset('detalle')."/{$kpi->id_tracing}";
                        }else if($key === "10143"){
                            $href_link = asset('operadoras')."/{$kpi->id_tracing}";
                        }else if($key === "14"){
                            $href_link = asset('configuracion')."/{$kpi->id_tracing}";
                        }else if($key === "9006"){
                            $href_link = asset('filesdown')."/{$kpi->id_tracing}";
                        }else if(in_array($key, ["5610","5611","5612","5613","5614","5615","5616","5617","5618","5619","5620", "5621", "5622", "5623", "5624", "5625", "5626"])){
                            $href_link = asset('replist')."/{$kpi->id_tracing}/{$key}";
                        }else if($key === "11926"){
                            $href_link = asset('buscar-direccion')."/{$kpi->id_tracing}";
                        }else if($key === "11966"){
                            $href_link = asset('sots')."/{$kpi->id_tracing}";
                        }else if($key === "12006"){
                            $href_link = asset('buscar-casa-direccion')."/{$kpi->id_tracing}";
                            $icon = "la-home";
                        }else if($key === "12007"){
                            $href_link = asset('buscar-casa-coordenada')."/{$kpi->id_tracing}";
                            $icon = "la-home";
                        }else if($key === "12008"){
                            $href_link = asset('buscar-casa-plano')."/{$kpi->id_tracing}";
                            $icon = "la-home";
                        }else if($key === "12009"){
                            $href_link = asset('buscar-edificios')."/{$kpi->id_tracing}";
                            $icon = "la-building";
                        }else if($key === "12026"){
                            $href_link = asset('operacion-fija/dashboard')."/{$kpi->id_tracing}";
                        }else if($key === "12106"){
                            $href_link = asset('optimizacion-bosf')."/{$kpi->id_tracing}";
                        }else if($key === "12126"){
                            $href_link = asset('cobertura-fija-ventas')."/{$kpi->id_tracing}";
                        }else if($key === "12250"){
                            $href_link = asset('afectacion_energia')."/{$kpi->id_tracing}";
                        }else if($key === "12551"){
                            $href_link = asset('ookla-map')."/{$kpi->id_tracing}";
                        }else if($key === "12590"){
                            $href_link = asset('ookla-map-fija')."/{$kpi->id_tracing}";
                        }
                        echo "<li class='nav-item'><a class='nav-link' data-id_type=\"{$key}\" href='{$href_link}?menu=".rawurlencode($kpi->tracing_name)."-".rawurlencode($menu_permission)."'><i class='nav-icon la {$icon}'></i><span>".$menu_permission."</span></a></li>";
                    }
                    echo "</ul>
                            </li>";
                }
                
            }
           
            //$html = $html.' <a class="btn btn-danger" href="'.backpack_url($menu->url).'" role="button">'.$kpi->trac_name.'</a>';

        }
    }
    ?>
@endif