<?php

namespace App\Http\Controllers\Admin\Acceso\Tec5GMovil;

use App\Facads\ODB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Map5GMovilCoberturaController extends Controller {
    public function index(Request $request)
    {

        $tracingID = $request->route('tracingID');
        $menuID = $request->route('menuID');

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TRACINGMENU(".$tracingID.", :resultado); END;";        
        $menuItem = $this->executeProcedure($sql);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(10, '".$tracingID . "_" . $menuID."', :resultado); END;";       
        $dataConf = $this->executeProcedure($sql);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$menuItem[0]['MENUID'].", :resultado); END;";        
        $menuGroup = $this->executeProcedure($sql);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$menuGroup[0]["ID_TYPE"].", :resultado); END;";
        $menuGroup = $this->executeProcedure($sql);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$menuGroup[0]["ID_TYPE"].", :resultado); END;";        
        $menuGroup = $this->executeProcedure($sql);

        $menuGroupKey = $menuGroup[0]["TYPE_NAME"];

        foreach ($menuGroup as $menGKey => $menGVal) {

            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$menGVal["TYPE_NAME"].", :resultado); END;";        
            $typeById = $this->executeProcedure($sql);

            $menuGroup[$menGKey]["MENU_NAME"] = $typeById[0]["TYPE_NAME"];

            if (explode("|", $menGVal["TYPE_DESCRIPTION"])[0] == '1')
                $menuGroupKey = $menGVal["TYPE_NAME"];
        }

        $data["menuGroup"] = $menuGroup;
        $data["menuGroupKey"] = $menuGroupKey;
        $menuFiltMap = array();

        if (count($dataConf) > 0) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$dataConf[0]["ID_TYPE"].", :resultado); END;";        
            $menuFiltMap = $this->executeProcedure($sql);
        }

        $data["menuFiltMap"] = $menuFiltMap;

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_UBGDPTO(:resultado); END;";        
        $ubigeoDpto = $this->executeProcedure($sql);

        $data['dptoList'] = $ubigeoDpto;
        $data = array_merge($data, $this->dataListMap());

        $departamentos = DB::select(DB::raw("SELECT DISTINCT SUBSTR(LPAD(a.IDUBIGEO,10,0),0,2) CODE,a.DEPARTAMENTO FROM reg_ccpp_2018_osiptel a ORDER BY a.DEPARTAMENTO"));

        $title = $this->getTitle($tracingID);

        return view('backpack::map',compact('title','data','tracingID','menuID','departamentos'));
    }

    private function getTitle($id_tracing){
        $base_module_title = DB::table("pso_type")
        ->where('id_type', 355)
        ->where('status', 1)
        ->first();

        $main_module_title = DB::table("pso_seguimientone pso")
        ->select(
            "pso.tracing_name as tracing_name",
            "pso.tracing_description as tracing_description",
            "type.type_name as main_title",
            "type.type_description as main_icon"
        )
        ->join("pso_type type", "type.id_type", "=", "pso.id_type")
        ->where('pso.id_tracing', $id_tracing)
        ->where('pso.status', 1)
        ->first();

        return "{$main_module_title->main_title} | {$main_module_title->tracing_description} | {$base_module_title->type_name}";
    }

    public function mapCoverage(Request $request){
        $tracingID = $request->route('tracingID');
        // $tracingID = '3';
        $menuID = $request->route('menuID');

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TRACINGMENU(".$tracingID.", :resultado); END;";        
        $menuList = $this->executeProcedure($sql);

        $menuListTmp = array();
		foreach ($menuList as $key => $menuItm) {
			$menuListTmp[] = $menuItm;
		}
		$menuList = $menuListTmp;
		$menuName = $menuList[0]["TYPE_NAME"];
		$menuDataID = $menuList[0]["MENUID"];

        if ($menuID > 0) {
			$key = array_search($menuID, array_column($menuList, "ID_TYPE"));
			$menuName = $menuList[$key]["TYPE_NAME"];
			$menuDataID = $menuList[$key]["MENUID"];
		} else {
			$menuID = $menuList[0]["ID_TYPE"] * 1;
		}
        /*
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MENUDATABYID(".$menuDataID.", :resultado); END;";        
        $menuItem = $this->executeProcedure($sql)[0];*/
        $menuItem=null;

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_UBGDPTO(:resultado); END;";        
        $dptoList = $this->executeProcedure($sql);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_ANIOSEMINTERF(:resultado); END;";        
        $anioSemList = $this->executeProcedure($sql);

        $mapurl="";

        //$data["mapurl"] = base_url() . "index.php/index/getDataMapCoverage/$tracingID/$menuID";
				
        return view('acceso.5g_movil.5g_movil_cobertura',compact('menuItem','dptoList','anioSemList','mapurl','tracingID','menuID'));
    }

    function dataListMap($optSel = 0)
	{
		$dataMap = array();

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(554, :resultado); END;";        
        $listOpt = $this->executeProcedure($sql);

		$dataMap["listOpt"] = $listOpt;

		if ($dataMap == 0)
			$optSel = $dataMap["listOpt"][0]["ID_TYPE"];

		$dataMap["listOptSel"] = $optSel;
		$optSel = $dataMap["listOpt"][array_search(
			$dataMap["listOptSel"],
			array_column($dataMap["listOpt"], "TYPE_NAME")
		)];

		$dataMap["datamapurl"] = asset('map/peru_regiones.json');
		$dataMap["optSel"] = $optSel;

		return $dataMap;
	}

    public function getDataUbgProvByDptoCode(Request $request){
        
        $CodeUbgSlc = $request->route('CodeUbgSlc');

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_UBGPROVBYDPTOCODE($CodeUbgSlc, :resultado); END;";        
        $listDpto = $this->executeProcedure($sql);

		echo json_encode($listDpto);
	}

	public function getDataUbgDistByProvCode($provCode)
	{
		$listProv = $this->index->getDataUbgDistByProvCode($provCode);
		echo json_encode($listProv);
	}

    public function executeProcedure($sql){

        $data = DB::transaction(function($conn) use ($sql){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':resultado', $lista, ODB::CURSOR);

            $stmt->execute();

            oci_execute($lista, OCI_DEFAULT);
            oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
            oci_free_cursor($lista);

            return $array;
        });

        return $data;
    }

    public function getPoliGeoJson($arrPoligonos,$ubigeos_crit){
        # Build GeoJSON feature collection array
        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => array()
        );
        # Loop through rows to build feature arrays
        foreach($arrPoligonos as $row) {
            $arrPoli = array();
            if(in_array($row->ubigeo,$ubigeos_crit)){
                $color = '#cf243894';
            }else{
                $color = '#00FF00';
            }

            $i = 0;

            $poligono = explode(',',$row->polygono);
            foreach($poligono as $poli)
            {
                $poli = ltrim($poli);
                $poli = rtrim($poli);
                $subPoli = explode(" ",$poli);
                $arrPoli[$i][0] = floatval($subPoli[0]); 
                $arrPoli[$i][1] = floatval($subPoli[1]);
                $i++;
            }            
            $feature = array(
                'type' => 'Feature', 
                'geometry' => array(
                    'type' => 'Polygon',
                    # Pass Longitude and Latitude Columns here
                    'coordinates' => array($arrPoli)
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'color' => $color,
                    'ubigeo' => $row->ubigeo
                    )
                );
            # Add feature arrays to feature collection array
            array_push($geojson['features'], $feature);
        }
        
        return $geojson;
    }

    public function getPointGeoJson($puntos){

        $i = 0;
        
        # Loop through rows to build feature arrays
        foreach($puntos as $punto) {

            if($punto->tech == "lte"){
                if($punto->dl_kbps < 2048 || $punto->ul_kbps < 410 ){
                   $type = 'mala_medicion';
                }else{
                    $type = 'buena_medicion';
                }
            }else{
                if($punto->dl_kbps < 410 || $punto->ul_kbps < 82 ){
                    $type = 'mala_medicion';
                }else{
                    $type = 'buena_medicion';
                }
            } 

            $geojson[$i]['latitude'] = $punto->latitude;
            $geojson[$i]['longitude'] = $punto->longitude;
            $geojson[$i]['type'] = $type;
            $geojson[$i]['id'] = $punto->id;

            $i++;
          
        }
        
        return $geojson;
    }

    public function exportPoli(Request $request) 
    {
        $tracingID = $request->route('tracingID');
        $typeUbg = $request->route('typeMapSeg');
        $mapGroupSel = $request->route('mapGroupSel');
        $mapGroupUnit = $request->route('mapGroupUnit');
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TRACINGNEBYID(".$tracingID.", :resultado); END;";        
        $tracingData = $this->executeProcedure($sql);
		$fileName = $tracingData[0]['TRACING_NAME'] . "_" . date("YmdHis") . ".csv";
        return (new PoligonosCCPPExport($tracingID, $typeUbg, $mapGroupSel, $mapGroupUnit))->download($fileName);
    }

    public function exportPuntos() 
    {
        return (new PuntosCVMExport())->download('puntos.xlsx');
    }

    // endpoints json
    // {
    //     "DATACONTENT": object[],
    //     "headList": [],
    //     "siteNameList": string[],
    // }
    public function getDataMapCoverage(Request $request){
        $tracingID = $request->input("tracingID");
        $menuID = $request->input("menuID");
		$changeVwMap = $request->input("changeVwMap");
		$dpto = $request->input("dpto");
		$prov = $request->input("prov");
		$dist = $request->input("dist");
		$OVERSHOOTER = $request->input("OVERSHOOTER");
		$PROBLEMA_ASOCIADO = $request->input("PROBLEMA_ASOCIADO");
		$scene = $request->input("scene");
		$capacity = $request->input("capacity");
		$freqband = $request->input("freqband");
		$carrier = $request->input("carrier");
		$priority = $request->input("priority");
        $download = 0;

        $data = $this->getDataMapCoverageData(
			$tracingID,
			$menuID,
			$changeVwMap,
			$dpto,
			$prov,
			$dist,
			$OVERSHOOTER,
			$PROBLEMA_ASOCIADO,
			$scene,
			$capacity,
			$freqband,
			$carrier,
			$priority
		);
        return response()->json($data);

        if ($download == 0 && substr($dpto, 0, 3) == '---') {
			return ["DATACONTENT" => [], "SITELIST" => [], "siteNameList" => []];
		}
		$strWhere = array();
		$dataContent = array();
		$strQuer = "PD1.*";
		$tracingSTR = strlen($tracingID) == 1 ? str_pad($tracingID, 2, '0', STR_PAD_LEFT) : $tracingID;
		$tblName = "RSS_CACERIA_INTERF_MAPA_5G PD1";

        switch ($changeVwMap) {
			case "3":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6829 PD1";
				break;
			case "4":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6830 PD1";
				break;
			case "5":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6831 PD1";
				break;
		}

        $builder = DB::table($tblName);
        $criteriaFilters = [];

		if (substr($freqband, 0, 3) != '---') {
			// $strWhere[] = "PD1.FREQBAND=''$freqband''";
            $builder->where('PD1.FREQBAND', $freqband);
            $criteriaFilters[] = ['f' => 'PD1.FREQBAND', 'op' => '=', 'v' => $freqband];
		}
		if (substr($priority, 0, 3) != '---') {
			// $strWhere[] = "PD1.PRIORIDAD=''$priority''";
            $builder->where('PD1.PRIORIDAD', $priority);
            $criteriaFilters[] = ['f' => 'PD1.PRIORIDAD', 'op' => '=', 'v' => $priority];
		}
		if (substr($scene, 0, 3) != '---') {
			// $strWhere[] = "PD1.ESCENARIO_V2=''$scene''";
            $builder->where('PD1.ESCENARIO_V2', $scene);
            $criteriaFilters[] = ['f' => 'PD1.ESCENARIO_V2', 'op' => '=', 'v' => $scene];
		}
		if (substr($capacity, 0, 3) != '---') {
			// $strWhere[] = "PD1.CRITICO_CAPACIDAD=''$capacity''";
            $builder->where('PD1.CRITICO_CAPACIDAD', $capacity);
            $criteriaFilters[] = ['f' => 'PD1.CRITICO_CAPACIDAD', 'op' => '=', 'v' => $capacity];
		}
		if (substr($carrier, 0, 3) != '---') {
			// $strWhere[] = "PD1.CARRIER=''$carrier''";
            $builder->where('PD1.CARRIER', $carrier);
            $criteriaFilters[] = ['f' => 'PD1.CARRIER', 'op' => '=', 'v' => $carrier];
		}
		if (substr($dpto, 0, 3) != '---') {
			if ($dpto == 'LIMA') {
				// $strWhere[] = "PD1.SUB_REGION LIKE ''%$dpto%''";
                $builder->where('PD1.SUB_REGION', 'like', "%{$dpto}%");
                $criteriaFilters[] = ['f' => 'PD1.SUB_REGION', 'op' => 'like', 'v' => "%{$dpto}%"];
			} elseif ($dpto == 'CALLAO') {
				// $strWhere[] = "PD1.SUB_REGION LIKE ''%LIMA%''";
                $builder->where('PD1.SUB_REGION', 'like', "%LIMA%");
                $criteriaFilters[] = ['f' => 'PD1.SUB_REGION', 'op' => 'like', 'v' => "%LIMA%"];
			} else {
				// $strWhere[] = "PD1.SUB_REGION=''$dpto''";
                $builder->where('PD1.SUB_REGION', $dpto);
                $criteriaFilters[] = ['f' => 'PD1.SUB_REGION', 'op' => '=', 'v' => $dpto];
			}
			if (substr($prov, 0, 3) != '---') {
				// $strWhere[] = "PD1.PROVINCIA=''$prov''";
                $builder->where('PD1.PROVINCIA', $prov);
                $criteriaFilters[] = ['f' => 'PD1.PROVINCIA', 'op' => '=', 'v' => $prov];
				if (substr($dist, 0, 3) != '---') {
					// $strWhere[] = "PD1.DISTRITO=''$dist''";
                    $builder->where('PD1.DISTRITO', $dist);
                    $criteriaFilters[] = ['f' => 'PD1.DISTRITO', 'op' => '=', 'v' => $dist];
				}
			} elseif ($dpto == 'CALLAO') {
				// $strWhere[] = "PD1.PROVINCIA LIKE ''%CALLAO%''";
                $builder->where('PD1.PROVINCIA', 'like', "%CALLAO%");
                $criteriaFilters[] = ['f' => 'PD1.PROVINCIA', 'op' => 'like', 'v' => "%CALLAO%"];
			}
			// $strWhere[] = "COALESCE(PD1.AZIMUTH,0)<>0 AND COALESCE(PD1.LATITUD,0)<>0 AND COALESCE(PD1.LONGITUD,0)<>0";
            $builder->where(DB::raw('COALESCE(PD1.AZIMUTH,0)'), '<>', "0")
            ->where(DB::raw('COALESCE(PD1.LATITUD,0)'), '<>', "0")
            ->where(DB::raw('COALESCE(PD1.LONGITUD,0)'), '<>', "0");

            $criteriaFilters[] = ['f' => 'COALESCE(PD1.AZIMUTH,0)', 'op' => '<>', 'v' => "0"];
            $criteriaFilters[] = ['f' => 'COALESCE(PD1.LATITUD,0)', 'op' => '<>', 'v' => "0"];
            $criteriaFilters[] = ['f' => 'COALESCE(PD1.LONGITUD,0)', 'op' => '<>', 'v' => "0"];
		}
		if (substr($OVERSHOOTER, 0, 3) != '---') {
			// $strWhere[] = "PD1.FLAG_OVERSHOOTER = ''$OVERSHOOTER''";
            $builder->where('PD1.FLAG_OVERSHOOTER', $OVERSHOOTER);
            $criteriaFilters[] = ['f' => 'PD1.FLAG_OVERSHOOTER', 'op' => '=', 'v' => $OVERSHOOTER];
		}
		if (substr($PROBLEMA_ASOCIADO, 0, 3) != '---') {
			// $strWhere[] = "PD1.PROBLEMA_ASOCIADO = ''$PROBLEMA_ASOCIADO''";
            $builder->where('PD1.PROBLEMA_ASOCIADO', $PROBLEMA_ASOCIADO);
            $criteriaFilters[] = ['f' => 'PD1.PROBLEMA_ASOCIADO', 'op' => '=', 'v' => $PROBLEMA_ASOCIADO];
		}

		// $strWhere = ' ' . implode(" AND ", $strWhere) . ' ';
		// $strWhere1 = $strWhere . " ORDER BY PD1.CELLNAME ";

        $siteListQuery = "";
        $siteList = [];
		
		switch ($changeVwMap) {
			case "2":
				// $strWhere1 = $strWhere . (strlen(trim($strWhere)) > 0 ? " AND " : "") . " PRIORIDAD IS NOT NULL ORDER BY PD1.CELLNAME ";
                $builder->whereNotNull('PRIORIDAD');
                $builder->orderBy('PD1.CELLNAME');

                /*$siteListQuery = "SELECT
                PD1.SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
				MAX(PD1.LATITUD)LATITUD,COALESCE(MAX(PRIORIDAD),-1)PRIORIDAD
                FROM {$tblName}
                GROUP BY PD1.SITE_NAME";*/

                $siteBuilder = DB::table("{$tblName} PD1")
                ->selectRaw("PD1.SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
				MAX(PD1.LATITUD)LATITUD,COALESCE(MAX(PRIORIDAD),-1)PRIORIDAD");

                foreach ($criteriaFilters as $filter) {
                    $siteBuilder->where(DB::raw($filter['f']), $filter['op'], $filter['v']);
                }
                $siteBuilder->groupBy('PD1.SITE_NAME');
                $siteList = $siteBuilder->get();

				break;
			case "3":
				// $strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
				// $tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6829 PD1";
                $builder->orderBy('PD1.SECTOR_NAME');
				break;
			case "4":
				// $tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6830 PD1";
				// $strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
                $builder->orderBy('PD1.SECTOR_NAME');
				break;
			case "5":
				// $tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6831 PD1";
				// $strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
                $builder->orderBy('PD1.SECTOR_NAME');
				break;
		}
        
        $dataContent = $builder->get();
        // $siteList = $siteListQuery !== "" ? DB::select($siteListQuery) : [];
        $siteNameList = [];

        if (in_array($changeVwMap, ["3", "4", "5"])) {
            foreach ($dataContent as $key => $value) {
                $siteName = $value->sector_name;
                if (!in_array($siteName, $siteNameList)) {
                    $siteNameList[] = $siteName;
                }
            }   
        } else {
            foreach ($dataContent as $key => $value) {
                $siteName = $value->site_name;
                if (!in_array($siteName, $siteNameList)) {
                    $siteNameList[] = $siteName;
                }
            }
        }

        // dd($builder->toSql());

        return response()->json([
            "DATACONTENT" => $dataContent,
            "SITELIST" => $siteList,
            "headList" => [],
            "siteNameList" => $siteNameList,
        ]);
    }

    public function getDataDownCoverage(Request $request)
	{
        $tracingID = $request->route('tracingID');
		$menuID = $request->route('menuID');
		$dpto = $request->route('dpto');
		$prov = $request->route('prov');
		$dist = $request->route('dist');
		$OVERSHOOTER = $request->route('OVERSHOOTER');
		$PROBLEMA_ASOCIADO = $request->route('PROBLEMA_ASOCIADO');
		$scene = $request->route('scene');
		$capacity = $request->route('capacity');
		$freqband = $request->route('freqband');
		$carrier = $request->route('carrier');
		$priority = $request->route('priority');
		$changeVwMap = $request->route('changeVwMap');
		// $typeDown = $request->route('typeDown');
		$typeDown = "1";

		$typeFile = "";

		switch ($typeDown) {
			case "1":
				header("Content-Type: application/force-download");
				header("Content-Type: application/octect-stream");
				header("Content-Type: application/download");
				header("Content-Type: text/x-csv");
				header("Content-Type: application/csv");
				header("Content-Transfer-Encoding: binary");
				$typeFile = ".csv";
				break;
			case "2":
				$typeFile = ".kml";
				break;
		}

		$dpto = base64_decode($dpto);
		$prov = base64_decode($prov);
		$dist = base64_decode($dist);
		$OVERSHOOTER = base64_decode($OVERSHOOTER);
		$PROBLEMA_ASOCIADO = base64_decode($PROBLEMA_ASOCIADO);
		$scene = base64_decode($scene);
		$capacity = base64_decode($capacity);
		$freqband = base64_decode($freqband);
		$carrier = base64_decode($carrier);
		$priority = base64_decode($priority);

		$data = $this->getDataMapCoverageData(
			$tracingID,
			$menuID,
			$changeVwMap,
			$dpto,
			$prov,
			$dist,
			$OVERSHOOTER,
			$PROBLEMA_ASOCIADO,
			$scene,
			$capacity,
			$freqband,
			$carrier,
			$priority,
			// 1
		);
        // return response()->json($data);
        $tracingID = 3;

		$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TRACINGNEBYID(".$tracingID.", :resultado); END;";        
        $tracingData = $this->executeProcedure($sql);

		$fileName = "5G Movil_" . date("YmdHis") . $typeFile;
		header("Content-Disposition: attachment;filename={$fileName}");
		$dataTbl = $data["DATACONTENT"];
		// var_dump($dataTbl);exit();
		// array_values($data["headList"]);
		$headTbl = $data["headList"];
		if (count($headTbl) == 0 && count($dataTbl) > 0) {
			$headTbl = array_keys(json_decode(json_encode($dataTbl[0]), true));
		}
        // return response()->json($headTbl);
		// $headTbl = array_values($data["headList"]);
		switch ($typeDown) {
			case "1":
				$file = fopen('php://output', 'w', $encoding = "utf-8");
				fputcsv($file, $headTbl);

				foreach ($dataTbl as $rowData) {
					// $rowFile = array_map(function ($rvl) {
					// 	return utf8_decode($rvl);
					// }, $rowData);
                    $rowFile = [];
                    foreach ($headTbl as $headField) {
                        $rowFile[] = $rowData->{$headField};
                    }
					fputcsv($file, $rowFile);
				}
				fpassthru($file);
				fclose($file);
				break;
			// case "2":
			// 	$dtemp = $this->load->view("layout/_kmlTemplate.php", $data, true);
			// 	header('Content-type: text/plain');
			// 	echo $dtemp;
			// 	break;
		}
	}

    private function getDataMapCoverageData(
        $tracingID,
		$menuID,
		$changeVwMap,
		$dpto,
		$prov,
		$dist,
		$OVERSHOOTER,
		$PROBLEMA_ASOCIADO,
		$scene,
		$capacity,
		$freqband,
		$carrier,
		$priority,
		// $download = 0
    ){
        // $tracingID = $request->input("tracingID");
        // $menuID = $request->input("menuID");
		// $changeVwMap = $request->input("changeVwMap");
		// $dpto = $request->input("dpto");
		// $prov = $request->input("prov");
		// $dist = $request->input("dist");
		// $OVERSHOOTER = $request->input("OVERSHOOTER");
		// $PROBLEMA_ASOCIADO = $request->input("PROBLEMA_ASOCIADO");
		// $scene = $request->input("scene");
		// $capacity = $request->input("capacity");
		// $freqband = $request->input("freqband");
		// $carrier = $request->input("carrier");
		// $priority = $request->input("priority");
        $download = 0;

        if ($download == 0 && substr($dpto, 0, 3) == '---') {
			return ["DATACONTENT" => [], "SITELIST" => [], "siteNameList" => []];
		}
		$strWhere = array();
		$dataContent = array();
		$strQuer = "PD1.*";
		$tracingSTR = strlen($tracingID) == 1 ? str_pad($tracingID, 2, '0', STR_PAD_LEFT) : $tracingID;
		$tblName = "RSS_CACERIA_INTERF_MAPA_5G PD1";

        switch ($changeVwMap) {
			case "3":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6829 PD1";
				break;
			case "4":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6830 PD1";
				break;
			case "5":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6831 PD1";
				break;
		}

        $builder = DB::table($tblName);
        $criteriaFilters = [];

		if (substr($freqband, 0, 3) != '---') {
			// $strWhere[] = "PD1.FREQBAND=''$freqband''";
            $builder->where('PD1.FREQBAND', $freqband);
            $criteriaFilters[] = ['f' => 'PD1.FREQBAND', 'op' => '=', 'v' => $freqband];
		}
		if (substr($priority, 0, 3) != '---') {
			// $strWhere[] = "PD1.PRIORIDAD=''$priority''";
            $builder->where('PD1.PRIORIDAD', $priority);
            $criteriaFilters[] = ['f' => 'PD1.PRIORIDAD', 'op' => '=', 'v' => $priority];
		}
		if (substr($scene, 0, 3) != '---') {
			// $strWhere[] = "PD1.ESCENARIO_V2=''$scene''";
            $builder->where('PD1.ESCENARIO_V2', $scene);
            $criteriaFilters[] = ['f' => 'PD1.ESCENARIO_V2', 'op' => '=', 'v' => $scene];
		}
		if (substr($capacity, 0, 3) != '---') {
			// $strWhere[] = "PD1.CRITICO_CAPACIDAD=''$capacity''";
            $builder->where('PD1.CRITICO_CAPACIDAD', $capacity);
            $criteriaFilters[] = ['f' => 'PD1.CRITICO_CAPACIDAD', 'op' => '=', 'v' => $capacity];
		}
		if (substr($carrier, 0, 3) != '---') {
			// $strWhere[] = "PD1.CARRIER=''$carrier''";
            $builder->where('PD1.CARRIER', $carrier);
            $criteriaFilters[] = ['f' => 'PD1.CARRIER', 'op' => '=', 'v' => $carrier];
		}
		if (substr($dpto, 0, 3) != '---') {
			if ($dpto == 'LIMA') {
				// $strWhere[] = "PD1.SUB_REGION LIKE ''%$dpto%''";
                $builder->where('PD1.SUB_REGION', 'like', "%{$dpto}%");
                $criteriaFilters[] = ['f' => 'PD1.SUB_REGION', 'op' => 'like', 'v' => "%{$dpto}%"];
			} elseif ($dpto == 'CALLAO') {
				// $strWhere[] = "PD1.SUB_REGION LIKE ''%LIMA%''";
                $builder->where('PD1.SUB_REGION', 'like', "%LIMA%");
                $criteriaFilters[] = ['f' => 'PD1.SUB_REGION', 'op' => 'like', 'v' => "%LIMA%"];
			} else {
				// $strWhere[] = "PD1.SUB_REGION=''$dpto''";
                $builder->where('PD1.SUB_REGION', $dpto);
                $criteriaFilters[] = ['f' => 'PD1.SUB_REGION', 'op' => '=', 'v' => $dpto];
			}
			if (substr($prov, 0, 3) != '---') {
				// $strWhere[] = "PD1.PROVINCIA=''$prov''";
                $builder->where('PD1.PROVINCIA', $prov);
                $criteriaFilters[] = ['f' => 'PD1.PROVINCIA', 'op' => '=', 'v' => $prov];
				if (substr($dist, 0, 3) != '---') {
					// $strWhere[] = "PD1.DISTRITO=''$dist''";
                    $builder->where('PD1.DISTRITO', $dist);
                    $criteriaFilters[] = ['f' => 'PD1.DISTRITO', 'op' => '=', 'v' => $dist];
				}
			} elseif ($dpto == 'CALLAO') {
				// $strWhere[] = "PD1.PROVINCIA LIKE ''%CALLAO%''";
                $builder->where('PD1.PROVINCIA', 'like', "%CALLAO%");
                $criteriaFilters[] = ['f' => 'PD1.PROVINCIA', 'op' => 'like', 'v' => "%CALLAO%"];
			}
			// $strWhere[] = "COALESCE(PD1.AZIMUTH,0)<>0 AND COALESCE(PD1.LATITUD,0)<>0 AND COALESCE(PD1.LONGITUD,0)<>0";
            $builder->where(DB::raw('COALESCE(PD1.AZIMUTH,0)'), '<>', "0")
            ->where(DB::raw('COALESCE(PD1.LATITUD,0)'), '<>', "0")
            ->where(DB::raw('COALESCE(PD1.LONGITUD,0)'), '<>', "0");

            $criteriaFilters[] = ['f' => 'COALESCE(PD1.AZIMUTH,0)', 'op' => '<>', 'v' => "0"];
            $criteriaFilters[] = ['f' => 'COALESCE(PD1.LATITUD,0)', 'op' => '<>', 'v' => "0"];
            $criteriaFilters[] = ['f' => 'COALESCE(PD1.LONGITUD,0)', 'op' => '<>', 'v' => "0"];
		}
		if (substr($OVERSHOOTER, 0, 3) != '---') {
			// $strWhere[] = "PD1.FLAG_OVERSHOOTER = ''$OVERSHOOTER''";
            $builder->where('PD1.FLAG_OVERSHOOTER', $OVERSHOOTER);
            $criteriaFilters[] = ['f' => 'PD1.FLAG_OVERSHOOTER', 'op' => '=', 'v' => $OVERSHOOTER];
		}
		if (substr($PROBLEMA_ASOCIADO, 0, 3) != '---') {
			// $strWhere[] = "PD1.PROBLEMA_ASOCIADO = ''$PROBLEMA_ASOCIADO''";
            $builder->where('PD1.PROBLEMA_ASOCIADO', $PROBLEMA_ASOCIADO);
            $criteriaFilters[] = ['f' => 'PD1.PROBLEMA_ASOCIADO', 'op' => '=', 'v' => $PROBLEMA_ASOCIADO];
		}

		// $strWhere = ' ' . implode(" AND ", $strWhere) . ' ';
		// $strWhere1 = $strWhere . " ORDER BY PD1.CELLNAME ";

        $siteListQuery = "";
        $siteList = [];
		
		switch ($changeVwMap) {
			case "2":
				// $strWhere1 = $strWhere . (strlen(trim($strWhere)) > 0 ? " AND " : "") . " PRIORIDAD IS NOT NULL ORDER BY PD1.CELLNAME ";
                $builder->whereNotNull('PRIORIDAD');
                $builder->orderBy('PD1.CELLNAME');

                /*$siteListQuery = "SELECT
                PD1.SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
				MAX(PD1.LATITUD)LATITUD,COALESCE(MAX(PRIORIDAD),-1)PRIORIDAD
                FROM {$tblName}
                GROUP BY PD1.SITE_NAME";*/

                $siteBuilder = DB::table("{$tblName} PD1")
                ->selectRaw("PD1.SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
				MAX(PD1.LATITUD)LATITUD,COALESCE(MAX(PRIORIDAD),-1)PRIORIDAD");

                foreach ($criteriaFilters as $filter) {
                    $siteBuilder->where(DB::raw($filter['f']), $filter['op'], $filter['v']);
                }
                $siteBuilder->groupBy('PD1.SITE_NAME');
                $siteList = $siteBuilder->get();

				break;
			case "3":
				// $strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
				// $tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6829 PD1";
                $builder->orderBy('PD1.SECTOR_NAME');
				break;
			case "4":
				// $tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6830 PD1";
				// $strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
                $builder->orderBy('PD1.SECTOR_NAME');
				break;
			case "5":
				// $tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6831 PD1";
				// $strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
                $builder->orderBy('PD1.SECTOR_NAME');
				break;
		}
        
        $dataContent = $builder->get();
        // $siteList = $siteListQuery !== "" ? DB::select($siteListQuery) : [];
        $siteNameList = [];

        if (in_array($changeVwMap, ["3", "4", "5"])) {
            foreach ($dataContent as $key => $value) {
                $siteName = $value->sector_name;
                if (!in_array($siteName, $siteNameList)) {
                    $siteNameList[] = $siteName;
                }
            }   
        } else {
            foreach ($dataContent as $key => $value) {
                $siteName = $value->site_name;
                if (!in_array($siteName, $siteNameList)) {
                    $siteNameList[] = $siteName;
                }
            }
        }

        // dd($builder->toSql());

        return [
            "DATACONTENT" => $dataContent,
            "SITELIST" => $siteList,
            "headList" => [],
            "siteNameList" => $siteNameList,
        ];
    }

    public function getDataMapCoverageCellName(Request $request)
	{
		ini_set('memory_limit', '256M');

		$tracingID = urldecode($request->input("tracingID"));
		$menuID = urldecode($request->input("menuID"));
		$cellname = urldecode($request->input("cellname"));
		$changeVwMap = urldecode($request->input("changeVwMap"));
		$siteName = urldecode($request->input("siteName"));

		$dataWhere = "";
		$strWhere = array();
		$dataContent = array();
		$tracingSTR = strlen($tracingID) == 1 ? str_pad($tracingID, 2, '0', STR_PAD_LEFT) : $tracingID;
		$tblName = "RSS_CACERIA_INTERF_MAPA_5G";

        if ($changeVwMap == "3") {
			$tblName .= "_6829";
		} elseif ($changeVwMap == "4") {
			$tblName .= "_6830";
		} elseif ($changeVwMap == "5") {
			$tblName .= "_6831";
		}

        $builder = DB::table($tblName);

		if ($changeVwMap == "3") {
			// $tblName .= "_6829";
			// $strWhere[] = "SECTOR_NAME='$cellname'";
			// $strWhere[] = "ENODOB_NAME='$siteName'";
            $builder->where('SECTOR_NAME', $cellname);
            $builder->where('ENODOB_NAME', $siteName);
		} elseif ($changeVwMap == "4") {
			// $tblName .= "_6830";
			// $strWhere[] = "SECTOR_NAME='$cellname'";
			// $strWhere[] = "ENODOB_NAME='$siteName'";
            $builder->where('SECTOR_NAME', $cellname);
            $builder->where('ENODOB_NAME', $siteName);
		} elseif ($changeVwMap == "5") {
			$tblName .= "_6831";
			// $strWhere[] = "SECTOR_NAME='$cellname'";
			// $strWhere[] = "ENODOB_NAME='$siteName'";
            $builder->where('SECTOR_NAME', $cellname);
            $builder->where('ENODOB_NAME', $siteName);
		} else {
			// $strWhere[] = "CELLNAME='$cellname'";
            $builder->where('CELLNAME', $cellname);
		}
        $tracingID = 3;
		$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(2371, $tracingID, :resultado); END;";
        $itemList = $this->executeProcedure($sql);
		$itemList = $itemList[0] ?? array();
		$headList = array();
		$fieldList = array();
		// if($changeVwMap!="3"&&count($itemList)>0){
		if (count($itemList) > 0) {
			$typeName = "6251_" . $changeVwMap;
			$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(".$itemList['ID_TYPE'].", '$typeName', :resultado); END;";
        	$fieldConf = $this->executeProcedure($sql);
			if (count($fieldConf) > 0) {
				$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$fieldConf[0]['ID_TYPE'].", :resultado); END;";
        		$fieldList =$this->executeProcedure($sql);
				if (count($fieldList) > 0) {
					foreach ($fieldList as $fieldItem) {
						$strLast = explode(" ", $fieldItem["TYPE_NAME"]);
						$strLast = end($strLast);
						$strLast = strtolower($strLast);
						$headList[$strLast] = $fieldItem["TYPE_DESCRIPTION"];
					}
				}
			}
		}

		// $strWhere[] = "COALESCE(AZIMUTH,0)<>0 AND COALESCE(LATITUD,0)<>0 AND COALESCE(LONGITUD,0)<>0";
        $builder->where(DB::raw('COALESCE(AZIMUTH,0)'), '<>', "0")
            ->where(DB::raw('COALESCE(LATITUD,0)'), '<>', "0")
            ->where(DB::raw('COALESCE(LONGITUD,0)'), '<>', "0");

		$strWhere = ' WHERE ' . implode(" AND ", $strWhere) . ' ';
		if (!in_array($changeVwMap, ["3", "4", "5"]))
			// $strWhere .= " ORDER BY CELLNAME ";
            $builder->orderBy('CELLNAME');
		else
			// $strWhere .= ' ORDER BY ESCENARIO_V2 DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY ';
            $builder->orderBy('ESCENARIO_V2');
		$strQuer = 'SELECT A1.* FROM (SELECT ';
		if (count($fieldList) > 0) {
			$strQuer .= implode(",", array_column($fieldList, "TYPE_NAME"));
		} else {
			$strQuer .= "*";
		}
		/*if (strpos(strtoupper($tblName), "FROM")) {
			$strQuer .= ' FROM(' . $tblName . " $strWhere)A1 )A1";
		} else {
			$strQuer .= ' FROM(' . $tblName . ")A1 $strWhere)A1";
		}*/

		// var_dump($strQuer);
		// $sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTPAG('".str_replace("'","''",$strQuer)."', -1, -1, :resultado); END;";
        // $data["Content"] = $this->executeProcedure($sql);
        $data["Content"] = $builder->get();

		$data["fldList"] = $headList;
		// var_dump($headList);
		$data["fldClear"] = array();
		// $dataContent = $this->index->getExecSelectDymamicPag($strQuer,$tblName,$strWhere,-1,-1);
		return response()->json($data);
	}

    public function graphSurface(Request $request)
	{
		$cellname = $request->input("cellname");
		$tracingID = $request->input("tracingID");
		$data["cellname"] = $cellname;
		$data["tracingID"] = $tracingID;		
		$dataVw = view('acceso.5g_movil.graphSurface',compact('data'));
		echo $dataVw;
	}

    public function getListMapDataDet(Request $request)
	{
		$cellname = $request->input("cellname");
		$tracingID = $request->input("tracingID");

		if($request->input("mapDiff")){
			$mapDiff = $request->input("mapDiff");
		}else{
			$mapDiff = "1";
		}

		$data = array();
		if ($mapDiff == "3") {
			$strQuer = "anio,semana,region,sub_region,departamento,provincia,
				distrito,enodob_name,enodob_address,site_address,sector_name,
				longitud,latitud,azimuth,th_user_dl,prb_dl,n_band700,n_band1900,
				n_band2600,escenario_v2,mac_volume_num_v2,meta_trafico_v2,
				pd_0_234_mts,pd_234_256_mts,pd_546_1014_mts,pd_1014_1950_mts,
				pd_1950_3510_mts,pd_3510_6630_mts,pd_6630_14430_mts,
				pd_mayor_14430_mts,dl_prb_util_700,dl_prb_util_1900,
				dl_prb_util_2600,b_crit_capacidad_rec,critico_capacidad,
				n_sem_crit_capacidad,usuarios_ifis,usuarios_tdds";
			$tblName = "PSO_0DATA_03_6251_6829";
			$strWhere = $cellname;			
			$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer','$tblName','$strWhere', -1, -1, :resultado); END;";       
            $dataContent = $this->executeProcedure($sql);
		} elseif ($mapDiff == "4") {
			$strQuer = "ALTURA,ANIO,AZIMUTH,B_CRIT_CAPACIDAD_REC,CRITICO_CAPACIDAD,DEPARTAMENTO,
			DISTRITO,DL_PRB_UTIL_1900,DL_PRB_UTIL_2600,DL_PRB_UTIL_2600_B38,DL_PRB_UTIL_700,
			DL_USER_TH_MB_1900,DL_USER_TH_MB_2600,DL_USER_TH_MB_2600_B38,DL_USER_TH_MB_700,
			ELECTRICAL_TILT,ENODOB_ADDRESS,ENODOB_NAME,ESCENARIO_V2,ESCENARIO2_V2,FREQBAND,
			LATITUD,LONGITUD,MAC_VOLUME_NUM_V2,MAYOR_14430_MTS,MECHANICAL_TILT,META_TRAFICO_V2,
			N_BAND1900,N_BAND2600,N_BAND2600_B38,N_BAND700,N_SEM_CRIT_CAPACIDAD,PD_MAYOR_14430_MTS,
			PD_0_234_MTS,PD_1014_1950_MTS,PD_1950_3510_MTS,PD_234_256_MTS,PD_3510_6630_MTS,
			PD_546_1014_MTS,PD_6630_14430_MTS,PRB_DL,PROVINCIA,RANKING_CRITICO_TOTAL,
			RANKING_SEGMENTO_CRITICO,REGION,SECTOR_NAME,SEGMENTO_CRITICO,SEMANA,SITE_ADDRESS,
			SUB_REGION,TA_0_234_MTS,TA_1014_1950_MTS,TA_1950_3510_MTS,TA_234_256_MTS,
			TA_3510_6630_MTS,TA_546_1014_MTS,TA_6630_14430_MTS,TH_USER_DL,USUARIOS_IFIS,USUARIOS_TDDS,FREQBAND,SECTOR_BAND";
			$tblName = "PSO_0DATA_03_6251_6830";
			$strWhere = $cellname;
			$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer','$tblName','$strWhere', -1, -1, :resultado); END;";       
            $dataContent = $this->executeProcedure($sql);
		} elseif ($mapDiff == "5") {
			$strQuer = "ALTURA,ANIO,AZIMUTH,B_CRIT_CAPACIDAD_REC,CRITICO_CAPACIDAD,DEPARTAMENTO,
			DISTRITO,DL_PRB_UTIL_1900,DL_PRB_UTIL_2600,DL_PRB_UTIL_2600_B38,DL_PRB_UTIL_700,
			DL_USER_TH_MB_1900,DL_USER_TH_MB_2600,DL_USER_TH_MB_2600_B38,DL_USER_TH_MB_700,
			ELECTRICAL_TILT,ENODOB_ADDRESS,ENODOB_NAME,ESCENARIO_V2,ESCENARIO2_V2,FREQBAND,
			LATITUD,LONGITUD,MAC_VOLUME_NUM_V2,MAYOR_14430_MTS,MECHANICAL_TILT,META_TRAFICO_V2,
			N_BAND1900,N_BAND2600,N_BAND2600_B38,N_BAND700,N_SEM_CRIT_CAPACIDAD,PD_MAYOR_14430_MTS,
			PD_0_234_MTS,PD_1014_1950_MTS,PD_1950_3510_MTS,PD_234_256_MTS,PD_3510_6630_MTS,
			PD_546_1014_MTS,PD_6630_14430_MTS,PRB_DL,PROVINCIA,RANKING_CRITICO_TOTAL,
			RANKING_SEGMENTO_CRITICO,REGION,SECTOR_NAME,SEGMENTO_CRITICO,SEMANA,SITE_ADDRESS,
			SUB_REGION,TA_0_234_MTS,TA_1014_1950_MTS,TA_1950_3510_MTS,TA_234_256_MTS,
			TA_3510_6630_MTS,TA_546_1014_MTS,TA_6630_14430_MTS,TH_USER_DL,USUARIOS_IFIS,USUARIOS_TDDS";
			$tblName = "PSO_0DATA_03_6251_6831";
			$strWhere = $cellname;
			$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer','$tblName','$strWhere', -1, -1, :resultado); END;";       
            $dataContent = $this->executeProcedure($sql);
		} else {			
			// $sql = "BEGIN PSO_OSSPORTAL.SP_GET_LISTMAPDATADET('$cellname',$tracingID, :resultado); END;";       
            /*
            $sql = "SELECT TO_CHAR(DATAREG.RESULT_TIME,''YYYY-MM-DD HH24:MI:SS'')RESULT_TIME,
                DATAREG.CELLNAME,
                DATAREG.L_UL_INTERFERENCE_AVG_PRB AVG_PRB,
                DATAREG.NIVEL_PRB,
                ROUND((SYSDATE-DATAREG.RESULT_TIME)*24) DATENUM
                FROM '||TABLENAME||' DATAREG
            WHERE DATAREG.ID_CELDA='''||CELLNAME||'''
                --DATAREG.CELLNAME='''||CELLNAME||'''
                AND DATAREG.RESULT_TIME>TRUNC(SYSDATE)-3
            ORDER BY DATAREG.NIVEL_PRB,DATAREG.RESULT_TIME DESC";*/

            $query = "SELECT
            result_time,
            cellname,
            argMax(n_ul_ni_avg_prb, version) AS avg_prb,
            nivel_prb,
            --round((now()-result_time)*24) datenum
            round((now() - result_time) / 3600) datenum
            FROM dr_acceso_kpi.hxh_onda_ulinterf_celda_5G
            where cellname = '{$cellname}'
            GROUP BY result_time,cellname,nivel_prb
            order by result_time desc";

            $data = DB::connection("clickhouse")->select($query);
		}
		return response()->json($data);
	}

}
