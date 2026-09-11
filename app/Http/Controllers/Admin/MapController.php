<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\PoligonosCCPPExport;
use App\Exports\PuntosCVMExport;
use Illuminate\Http\Request;
use App\Facads\ODB;

class MapController extends Controller
{
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
        
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MENUDATABYID(".$menuDataID.", :resultado); END;";        
        $menuItem = $this->executeProcedure($sql)[0];

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_UBGDPTO(:resultado); END;";        
        $dptoList = $this->executeProcedure($sql);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_ANIOSEMINTERF(:resultado); END;";        
        $anioSemList = $this->executeProcedure($sql);

        $mapurl="";

        //$data["mapurl"] = base_url() . "index.php/index/getDataMapCoverage/$tracingID/$menuID";
				
        return view('backpack::mapCoverage',compact('menuItem','dptoList','anioSemList','mapurl','tracingID','menuID'));
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
}