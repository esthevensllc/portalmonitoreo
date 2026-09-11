<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use App\Facads\ODB;

class MapDataApiController extends Controller
{
    public function index(Request $request)
    {
        // if ($request->ajax()) {
            $type = $request->input('type');
            switch($type){
                case "map":
                    $ubigeo = $request->input('param');
                    $data = DB::select(DB::raw("SELECT DEPARTAMENTO,PROVINCIA,DISTRITO,CCPP,UBIGEO,TECH_DL,NUM_MUESTRAS, CASE WHEN TECH_DL='LTE' THEN MUESTRAS_TH_DL_MENOR_2MBPS WHEN TECH_DL='UMTS' THEN MUESTRAS_TH_DL_MENOR_400KBPS END MSTRAS_DOWN_UMBRAL_DL, CASE WHEN TECH_DL='LTE' THEN CVM_LTE_DL WHEN TECH_DL='UMTS' THEN CVM_UMTS_DL END CVM_DL, CASE WHEN TECH_DL='LTE' THEN CVM_LTE_UL WHEN TECH_DL='UMTS' THEN CVM_UMTS_UL END CVM_UL FROM PRG_MTV_CVM_OPERADOR_LIN_RECR WHERE UBIGEO=$ubigeo"));
                    break;
                case "point":  
                    $id = $request->input('param');

                    $sql = "BEGIN PK_PRG.SP_GET_POINTS_MAP_CVM(".$id.", :resultado); END;";

                    $data = DB::transaction(function($conn) use ($sql){
                        $pdo = $conn->getPdo();
                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':resultado', $lista, ODB::CURSOR);

                        $stmt->execute();

                        oci_execute($lista, OCI_DEFAULT);
                        oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
                        oci_free_cursor($lista);

                        return $array[0];
                    });
                    
                    break;
                case "ccpp":  
                    ini_set('memory_limit', '-1');
                    $ubigeo = $request->input('param');
                    $query = DB::select(DB::raw("select a.polygono from prg_cvm_mtv_kml a inner join reg_ccpp_2018_osiptel b on b.departamento = '$ubigeo' where a.ubigeo in b.idubigeo"));
                    $data = array();
                    $i=0;
                    foreach($query as $keyi => $poligono){
                        $jsx = array();
                        $jsy = array();
                        $poliarray = explode("," , rtrim(ltrim($poligono->polygono)));
                        foreach($poliarray as $keyj => $poli){
                            $coord = explode(" " , rtrim(ltrim($poli)));
                            $jsx[] = $coord[1];
                            $jsy[] = $coord[0];
                        }
                        $data[$keyi][] = $jsx;
                        $data[$keyi][] = $jsy;
                        $i++;
                    }
                    break;
                case "selectDep": 
                    $ubigeo = $request->input('param');
                    $query = DB::select(DB::raw("SELECT DISTINCT SUBSTR(LPAD(a.IDUBIGEO,10,0),0,4) CODE,a.DEPARTAMENTO,a.PROVINCIA FROM reg_ccpp_2018_osiptel a WHERE SUBSTR(LPAD(a.IDUBIGEO,10,0),0,2)=$ubigeo ORDER BY a.PROVINCIA"));
                    $data = $query;
                    break;
                case "selectProv": 
                    $ubigeoDep = $request->input('param1');
                    $ubigeoprov = $request->input('param2');
                    $query = DB::select(DB::raw("SELECT DISTINCT SUBSTR(LPAD(a.IDUBIGEO,10,0),0,6) CODE,a.DEPARTAMENTO,a.PROVINCIA,a.DISTRITO FROM reg_ccpp_2018_osiptel a WHERE SUBSTR(LPAD(a.IDUBIGEO,10,0),0,4)=$ubigeoprov ORDER BY a.DISTRITO"));
                    $data = $query;
                    break;
                case "drivetest": 
                    $wherePointSemestre = "";
                    if($request->input('semestre')=="1"){
                        $wherePointSemestre = "and semestre = 'SEMESTRE_ANTERIOR'";
                    }
                    if($request->input('semestre')=="2"){
                        $wherePointSemestre = "and semestre = 'SEMESTRE_ACTUAL'";
                    }
                    $query = DB::select(DB::raw("select distinct semestre,id,idclient,latitude,longitude,job_name,throughput_dl_kbps as dl,downlinkdatatechnology as tech_dl from ( select distinct semestre,id,idclient,latitude,longitude,job_name,throughput_dl_kbps,downlinkdatatechnology,  case when latitude is not null and longitude is not null then 1 else 0 end mstras_validas from (select  case when to_number(to_char(to_timestamp(sysdate), 'mm'))<=6 and to_number(to_char(to_timestamp(timestamp), 'mm'))>6  and to_number(to_char(to_timestamp(sysdate), 'yyyy')) = to_number(to_char(to_timestamp(timestamp), 'yyyy')) + 1 then 'SEMESTRE_ANTERIOR'  when to_number(to_char(to_timestamp(sysdate), 'mm'))>6 and to_number(to_char(to_timestamp(timestamp), 'mm'))<=6  and to_number(to_char(to_timestamp(sysdate), 'yyyy')) = to_number(to_char(to_timestamp(timestamp), 'yyyy'))  then 'SEMESTRE_ANTERIOR' else 'SEMESTRE_ACTUAL' end semestre,a.* from (select case when to_number(to_char(to_timestamp(sysdate), 'mm'))>6 then to_number(to_char(to_timestamp(sysdate), 'mm'))-1 when to_number(to_char(to_timestamp(sysdate), 'mm'))<=6 then to_number(to_char(to_timestamp(sysdate), 'mm'))+5 end mes,s.* from smart.prg_cvm_mtv_mysql s where downlinkdatatechnology not in ('wifi')) a where timestamp>=add_months(sysdate,-1*mes))) where mstras_validas=1 $wherePointSemestre"));
                    $data = $query;
                    break;
                case "drivetestpoint": 
                    $id = $request->input('param');

                    $sql = "BEGIN PK_PRG.SP_GET_POINTS_MAP_DRIVETEST(".$id.", :resultado); END;";

                    $data = DB::transaction(function($conn) use ($sql){
                        $pdo = $conn->getPdo();
                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':resultado', $lista, ODB::CURSOR);

                        $stmt->execute();

                        oci_execute($lista, OCI_DEFAULT);
                        oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
                        oci_free_cursor($lista);

                        return $array[0];
                    });
                    
                    break;
                case "sitesmap4g": 
                    $query = DB::select(DB::raw("select sector_name, site_address, lat, lon, azimuth, height, electrical_tilt , mechanical_tilt , hbw ,ranking from ( select a.*, rank() over(partition by sector_name order by lat, lon, azimuth desc, hbw desc, height desc, mechanical_tilt desc, electrical_tilt desc, site_address desc) ranking from ( select distinct substr(cell_name,1,8) sector_name, upper(substr(cell_name,instr(cell_name,'_',1,1)+1)) site_address, lat, lon, azimuth, height, electrical_tilt , mechanical_tilt , hbw from   asset_lte_info )a)where ranking=1"));
                    $data = $query;
                    break;
                case "sitesmap3g": 
                    $query = DB::select(DB::raw("select sector_name, site_address, lat, lon, azimuth, height, electrical_tilt , mechanical_tilt , hbw ,ranking from ( select a.*, rank() over(partition by sector_name order by lat, lon, azimuth desc, hbw desc, height desc, mechanical_tilt desc, electrical_tilt desc, site_address desc) ranking from ( select distinct substr(cell_name,1,8) sector_name, upper(substr(cell_name,instr(cell_name,'_',1,1)+1)) site_address, lat, lon, azimuth, height, electrical_tilt , mechanical_tilt , hbw from   asset_umts_info )a)where ranking=1"));
                    $data = $query;
                    break; 
                case "loadMapUbigeo":
                    $ubigeo = $request->input('param');
                    if(strlen($ubigeo)>0){                        
                        $data = DB::select(DB::raw("select latitude,longitude from prg_cvm_mtv_mysql where ubigeo = $ubigeo and rownum <= 1"));
                    }else{
                        $data = 0;
                    }
                    break;   
				case "loadMarker":
					ini_set('memory_limit','2G');
					$tiporuc = $request->input('param');
					$plano = $request->input('plano');
					if($tiporuc==='ruc10'){                     
						$data = DB::select(DB::raw("select departamento,provincia,distrito,latitud,longitud,count(distinct numdoc) rucs from SMART.GIS_EMP_RUC_10 where (gis_plano = '$plano' or gis_plano = replace('$plano','-F','')) group by departamento,provincia,distrito,latitud,longitud"));
					}else if($tiporuc==='ruc20'){  
						$data = DB::select(DB::raw("select departamento,provincia,distrito,latitud,longitud,count(distinct numdoc) rucs from SMART.GIS_EMP_RUC_20 where (gis_plano = '$plano' or gis_plano = replace('$plano','-F','')) group by departamento,provincia,distrito,latitud,longitud"));
					}else if($tiporuc==='kpi_cliente_total'){  
						$data = DB::connection("clickhouse")
						->select(DB::raw("select
						a.plano_tracer as plano,
						--sd_int,
						a.direccion,
						--tip_documento,
						--nro_documento,
						a.latitud,
						a.longitud,
						b.customer_id tipo
						from analytics.direcciones_clientes a
						left join(
							select customer_id from analytics.direcciones_clientes_base_full_claro
							group by customer_id
						)b
						on b.customer_id = a.customer_id
						where estado in ('0','1') and plano= '{$plano}'"));
					}else if($tiporuc==='kpi_cliente_total_moviles'){  
						/*$data = DB::connection("clickhouse")
						->select(DB::raw("select latitud,
						longitud
						from ubicacion_direcciones.ubicacion_direcciones_moviles_ok where latitud is not null and longitud is not null"));*/
						$data = DB::connection("clickhouse")
						->select(DB::raw("select latitud_y latitud, longitud_x longitud, base_full_claro,
						id_empresas, id_card_value nro_documento, tipo_documento, agreement_product_offering_desc, direccion
						from smart.clientes_total_moviles_temp where plano_mas_cerca like concat(substring('{$plano}',1,7), '%')"));
					}else if($tiporuc==='num_fats'){
						$data = DB::select(DB::raw("SELECT
						codigo,
						nombre_plano,
						name,
						ESPECIFICACION,
						SALIDAS,
						longitud, -- no pintar
						latitud -- no pintar
						FROM GIS_FAT
						where nombre_plano = :plano"), ["plano" => $plano]);
					}
					break;
				case "planos_hfc":                     
					$data = DB::select(DB::raw("SELECT ID, A.PLANO AS NOMBRE, SDO_UTIL.TO_GEOJSON(GEOM) plano
												FROM FIJA_MAESTRO_PLANOS_PAP A
												LEFT JOIN GIS_COBERTURA_FIJA_C B
												ON A.PLANO = regexp_substr(REPLACE(REPLACE(B.NOMBRE,'_DISEÑO',''),'Ñ','N'),'[[:alnum:]]+*-?_?[[:alnum:]]+*')
												WHERE A.TECNOLOGIA = 'HFC'
												AND GIS_MAPA = 1
												AND B.NOMBRE IS NOT NULL"));
					$data = $this->getAsPoligonFormat($data);
					break;
                }
            return response()->json(compact('data'));
        // }
    }

	public function getAsPoligonFormat($query){
        $poligonos = $query;
        $featuresMap = [];
        foreach($poligonos as $row){
            $featuresMap[] = $this->poligonoPresenter($row);
        }
        return [
            'type' => 'FeatureCollection',
            'features' => $featuresMap
        ];
    }

    private function poligonoPresenter($row){
		$plano  = json_decode($row->plano);
        return [
            'type' => 'Feature',
            'properties' => [
                'id' => $row->id,
                'nombre' => $row->nombre,
				'capa' => 'hfc'
            ],
            'geometry' => $plano            
        ];
    }

    public function dataKpiUbgTypeDataList($typeUbg,Request $request)
	{
		$tracingID = $request->input('tracingID');
		$menuID = $request->input('menuID');
		$mapGroupSel = $request->input('mapGroupSel');
		$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(10, '".$tracingID . "_" . $menuID."'), :resultado); END;";       
        //$dataConf = $this->executeProcedure($sql);
		// $data["dataTbl"] = $this->index->getDataCodeKpiByUbgType($typeUbg);
		// $data["dataTbl"] = $this->index->getDataKpiByUbgTypeTracID($tracingID,$typeUbg);

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME($tracingID, $typeUbg, $mapGroupSel, :resultado); END;";       
        $data["dataList"] =$this->executeProcedure($sql);
        echo json_encode($data);
	}

    public function mapDataGetDaByFatID(Request $request)
	{
        $fatherID = $request->input('typeKpiSel');
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID($fatherID, :resultado); END;";       
        $data = $this->executeProcedure($sql);
		echo json_encode($data);
	}

    public function mapDataKpiUbgType(Request $request)
	{
        $typeUbg = $request->input('typeMapSeg');
		$tracingID = $request->input('tracingID');
		$menuID = $request->input('menuID');
		$mapGroupSel = $request->input('mapGroupSel');
        $mapGroupUnit = $request->input('mapGroupUnit') != null ? $request->input('mapGroupUnit') : 'NULL';
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(10, '".$tracingID . "_" . $menuID."', :resultado); END;";       
        //$dataConf  = $this->executeProcedure($sql);
		// $data["dataTbl"] = $this->index->getDataCodeKpiByUbgType($typeUbg);
		// $data["dataTbl"] = $this->index->getDataKpiByUbgTypeTracID($tracingID,$typeUbg);
		$sql = "BEGIN PSO_OSSPORTAL.SP_GET_MAPUBGTYPETRACID($tracingID, $typeUbg, $mapGroupSel, $mapGroupUnit,:resultado); END;";       
        $rows = $this->executeProcedure($sql);
        foreach ($rows as $key => $row) {
            $result[$row["CODE"]] = $row;
        }
        $data["dataTbl"] =  $result;
		echo json_encode($data);
	}

    public function mapDataKpiUbgTypeDataList(Request $request)
	{
        $typeUbg = $request->input('typeMapSeg');
		$tracingID = $request->input('tracingID');
		$menuID = $request->input('menuID');
		$mapGroupSel = $request->input('mapGroupSel');
		$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(10, '".$tracingID . "_" . $menuID."', :resultado); END;";       
        //$dataConf = $this->executeProcedure($sql);
		// $data["dataTbl"] = $this->index->getDataCodeKpiByUbgType($typeUbg);
		// $data["dataTbl"] = $this->index->getDataKpiByUbgTypeTracID($tracingID,$typeUbg);
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MAPUBGTYPETRACIDLST($tracingID, $typeUbg, $mapGroupSel, :resultado); END;";       
        $data["dataTbl"] = $this->executeProcedure($sql);
		echo json_encode($data);
	}

    public function dataMap(Request $request)
	{
		$typeView = 1;
		$SUBNOM = "";
		$codUBG = "";
		$tracingID = $request->input('tracingID');
		$dpto = '';
		if($request->input('cod_dep')){
					$typeView = 1;
					$SUBNOM = "DEPT";
					$dpto = $request->input('name_dep');
		}
		if($request->input('FIRST_IDPR')){
					$typeView = 2;
					$SUBNOM = "PROV";
					// $dpto = $this->input->post("FIRST_IDPR");
					$dpto = $request->input('FIRST_NOMB');
					$prov = $request->input('NOMBPROV');
		}
		if($request->input('IDDIST')){
					$typeView = 3;
					$SUBNOM = "DIST";
					$dpto = $request->input('NOMBDEP');
					$prov = $request->input('NOMBPROV');
					$dist = $request->input('NOMBDIST');
		}			
		
		$mapGroupUnit = $request->input('mapGroupUnit');
		if($mapGroupUnit == 0){
			$mapGroupUnit = 'NULL';
		}
		$mapGroupSel = $request->input('mapGroupSel');
		$dataMap = array();
		// $data["dataUbg"] = $this->index->getUbgataByCode($typeView,$codUBG);
		// if ($typeView==1){
		// 	$dataMap = $this->index->getDataMapDepTracID($tracingID,$dpto);
		// }elseif ($typeView==2){
		// 	$dataMap = $this->index->getDataMapProvTracID($tracingID,$prov);
		// }elseif($typeView==3){
		// 	$dataMap = $this->index->getDataMapDistTracID($tracingID,$prov,$dist);
		// }
		if ($typeView == 1) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MAPDEPTRACID($tracingID, '$dpto', $mapGroupSel, $mapGroupUnit, :resultado); END;";       
            $dataMap = $this->executeProcedure($sql);
		} elseif ($typeView == 2) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MAPPROVTRACID($tracingID, '$prov', $mapGroupSel, $mapGroupUnit, :resultado); END;";       
            $dataMap = $this->executeProcedure($sql);
			$dataMap = array_filter($dataMap, function ($e) use ($dpto) {
				return $e['SUB_REGION'] === $dpto;
			});
			$dataMap = array_merge($dataMap, []);
		} elseif ($typeView == 3) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_MAPIDISTTRACID($tracingID, '$prov', '$dist', $mapGroupSel, $mapGroupUnit, :resultado); END;";      
            $dataMap = $this->executeProcedure($sql);
		}
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(2511, '".$tracingID . "_" . $SUBNOM."', :resultado); END;";       
        $dataRowRep = $this->executeProcedure($sql);
		if (count($dataRowRep) === 0) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(2511, '".$tracingID . "_" . $SUBNOM . "_" . $mapGroupSel."', :resultado); END;";       
            $dataRowRep = $this->executeProcedure($sql);
		}

		$dataReplace = array();
		$arrRepl = array();
		if (count($dataRowRep) > 0){
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$dataRowRep[0]['ID_TYPE'].", :resultado); END;";       
            $dataReplace = $this->executeProcedure($sql);
        }
		foreach ($dataReplace as $itemReplace) {
			if (isset($itemReplace["TYPE_DESCRIPTION"]) || $itemReplace["TYPE_DESCRIPTION"] != '')
				$arrRepl[$itemReplace["TYPE_NAME"]] = $itemReplace["TYPE_DESCRIPTION"];
		}
		if (count($dataMap) > 0) {
			$head = array_keys($dataMap[0]);
			$body = array();
			foreach ($head as $item) {
				$strItem = $arrRepl[$item] ?? $item;
				if (isset($arrRepl[$item]) || count($dataRowRep) === 0) {
					$body[$strItem] = $dataMap[0][$item];
				}
				// $body[$strItem] = $dataMap[$item];
			}
		}
		// $body = $dataMap;
		// $data["dataMap"] = $dataMap;
		$body = $body ?? array();
		$data["body"] = $body;
		$data["dataMap"] = $this->vwTbl(NULL, $body, count($dataMap), "", array(1 => 'right'));
		echo json_encode($data);
	}

	function vwTbl($head, $body, $coincidencias, $tblId = "", $align = array())
	{
		$data["tblId"] = $tblId;
		$data["align"] = $align;
		$data["headTbl"] = $head;
		$data["bodyTbl"] = $body;
		$data["n_coincidencias"] = $coincidencias;
		$simpleTable = view('backpack::widgets.simpleTable',compact('data'))->render();
		return $simpleTable;
	}

    public function getDataUbgProvByDptoCode(Request $request)
	{
        $dptoCode = $request->input('CodeUbgSlc');
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_UBGPROVBYDPTOCODE('$dptoCode', :resultado); END;";       
        $listDpto = $this->executeProcedure($sql);
		echo json_encode($listDpto);
	}

    public function getDataUbgDistByProvCode(Request $request)
	{
        $dptoCode = $request->input('CodeUbgSlc');
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_UBGDISTBYPROVCODE('$dptoCode', :resultado); END;";       
        $listProv = $this->executeProcedure($sql);
		echo json_encode($listProv);
	}

    public function getDataMapCoverage(Request $request)
	{
		ini_set('memory_limit', -1);
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
		$data = $this->getDataByFilt(
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

		echo json_encode($data);
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
		$typeDown = $request->route('typeDown');

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

		$data = $this->getDataByFilt(
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
			1
		);

		$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TRACINGNEBYID(".$tracingID.", :resultado); END;";        
        $tracingData = $this->executeProcedure($sql);

		$fileName = $tracingData[0]['TRACING_NAME'] . "_" . date("YmdHis") . $typeFile;
		header("Content-Disposition: attachment;filename={$fileName}");
		$dataTbl = $data["DATACONTENT"];
		// var_dump($dataTbl);exit();
		// array_values($data["headList"]);
		$headTbl = $data["headList"];
		if (count($headTbl) == 0 && count($dataTbl) > 0) {
			$headTbl = array_keys($dataTbl[0]);
		}
		// $headTbl = array_values($data["headList"]);
		switch ($typeDown) {
			case "1":
				$file = fopen('php://output', 'w', $encoding = "utf-8");
				fputcsv($file, $headTbl);

				foreach ($dataTbl as $rowData) {
					$rowFile = array_map(function ($rvl) {
						return utf8_decode($rvl);
					}, $rowData);
					fputcsv($file, $rowFile);
				}
				fpassthru($file);
				fclose($file);
				break;
			case "2":
				$dtemp = $this->load->view("layout/_kmlTemplate.php", $data, true);
				header('Content-type: text/plain');
				echo $dtemp;
				break;
		}
	}

    public function getDataByFilt(
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
		$download = 0
	) {
		if ($download == 0 && substr($dpto, 0, 3) == '---') {
			return ["DATACONTENT" => [], "SITELIST" => [], "siteNameList" => []];
		}
		$strWhere = array();
		$dataContent = array();
		$strQuer = "PD1.*";
		$tracingSTR = strlen($tracingID) == 1 ? str_pad($tracingID, 2, '0', STR_PAD_LEFT) : $tracingID;
		$tblName = "PSO_0DATA_" . $tracingSTR . "_6251 PD1";
		if (substr($freqband, 0, 3) != '---') {
			$strWhere[] = "PD1.FREQBAND=''$freqband''";
		}
		if (substr($priority, 0, 3) != '---') {
			$strWhere[] = "PD1.PRIORIDAD=''$priority''";
		}
		if (substr($scene, 0, 3) != '---') {
			$strWhere[] = "PD1.ESCENARIO_V2=''$scene''";
		}
		if (substr($capacity, 0, 3) != '---') {
			$strWhere[] = "PD1.CRITICO_CAPACIDAD=''$capacity''";
		}
		if (substr($carrier, 0, 3) != '---') {
			$strWhere[] = "PD1.CARRIER=''$carrier''";
		}
		if (substr($dpto, 0, 3) != '---') {
			if ($dpto == 'LIMA') {
				$strWhere[] = "PD1.SUB_REGION LIKE ''%$dpto%''";
			} elseif ($dpto == 'CALLAO') {
				$strWhere[] = "PD1.SUB_REGION LIKE ''%LIMA%''";
			} else {
				$strWhere[] = "PD1.SUB_REGION=''$dpto''";
			}
			if (substr($prov, 0, 3) != '---') {
				$strWhere[] = "PD1.PROVINCIA=''$prov''";
				if (substr($dist, 0, 3) != '---') {
					$strWhere[] = "PD1.DISTRITO=''$dist''";
				}
			} elseif ($dpto == 'CALLAO') {
				$strWhere[] = "PD1.PROVINCIA LIKE ''%CALLAO%''";
			}
			$strWhere[] = "COALESCE(PD1.AZIMUTH,0)<>0 AND COALESCE(PD1.LATITUD,0)<>0 AND COALESCE(PD1.LONGITUD,0)<>0";
		}
		if (substr($OVERSHOOTER, 0, 3) != '---') {
			$strWhere[] = "PD1.FLAG_OVERSHOOTER = ''$OVERSHOOTER''";
		}
		if (substr($PROBLEMA_ASOCIADO, 0, 3) != '---') {
			$strWhere[] = "PD1.PROBLEMA_ASOCIADO = ''$PROBLEMA_ASOCIADO''";
		}

		$strWhere = ' ' . implode(" AND ", $strWhere) . ' ';
		$strWhere1 = $strWhere . " ORDER BY PD1.CELLNAME ";
		
		switch ($changeVwMap) {
			case "2":
				$strWhere1 = $strWhere . (strlen(trim($strWhere)) > 0 ? " AND " : "") . " PRIORIDAD IS NOT NULL ORDER BY PD1.CELLNAME ";
				break;
			case "3":
				$strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6829 PD1";
				// $tblName = "PRB_SEM_MAX_SECTOR PD1";
				break;
			case "4":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6830 PD1";
				$strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
				break;
			case "5":
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6831 PD1";
				$strWhere1 = $strWhere . " ORDER BY PD1.SECTOR_NAME ";
				break;
		}
		// var_dump($strQuer,$tblName,$strWhere1);
		$headList = array();
		$fieldList = array();
		if ($download == 1) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(2371, $tracingID, :resultado); END;";       
            $itemList = $this->executeProcedure($sql);
			$itemList = $itemList[0] ?? array();
			if (count($itemList) > 0) {
				$typeName = "6251_" . $changeVwMap;
                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(".$itemList["ID_TYPE"].", '$typeName', :resultado); END;";
                $fieldConf = $this->executeProcedure($sql);
				if (count($fieldConf) > 0) {
                    $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$fieldConf[0]['ID_TYPE'].", :resultado); END;";       
                    $fieldList = $this->executeProcedure($sql);
					if (count($fieldList) > 0) {
						foreach ($fieldList as $fieldItem) {
							$strLast = explode(" ", $fieldItem["TYPE_NAME"]);
							$strLast = end($strLast);
							$headList[$strLast] = $fieldItem["TYPE_DESCRIPTION"];
						}
					}
				}
				$strQuer = implode(",", array_column($fieldList, "TYPE_NAME"));
				$strQuer = str_replace("'","''",$strQuer);
			} else
				$strQuer = "*";
		}
		$data["headList"] = $headList;
		// var_dump($strQuer,$tblName,$strWhere1);
        $sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer', '$tblName', '$strWhere1', -1, -1, :resultado); END;";
        $dataContent = $this->executeProcedure($sql);
		$data["DATACONTENT"] = $dataContent;
		if ($download == 0) {
			if ($changeVwMap == "2") {
				$strQuer = " PD1.SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
					MAX(PD1.LATITUD)LATITUD,COALESCE(MAX(PRIORIDAD),-1)PRIORIDAD ";
				$tblName = " PSO_0DATA_" . $tracingSTR . "_6251 PD1 ";
				$strWhere2 = $strWhere . " GROUP BY PD1.SITE_NAME ";
                $sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer',' $tblName','$strWhere2', -1, -1, :resultado); END;";       
                $dataContent2 = $this->executeProcedure($sql);
				$data["SITELIST"] = $dataContent2;
			} elseif ($changeVwMap == "3") {
				$strQuer = " PD1.ENODOB_NAME SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
					MAX(PD1.LATITUD)LATITUD ";
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6829 PD1";
				// $tblName = " PRB_SEM_MAX_SECTOR PD1 ";
				$strWhere2 = $strWhere . " GROUP BY PD1.ENODOB_NAME ";
                $sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer',' $tblName','$strWhere2', -1, -1, :resultado); END;";       
                $dataContent2 = $this->executeProcedure($sql);
				$data["SITELIST"] = $dataContent2;
			} elseif ($changeVwMap == "4") {
				$strQuer = " PD1.ENODOB_NAME SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
					MAX(PD1.LATITUD)LATITUD ";
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6830 PD1";
				// $tblName = " PRB_SEM_MAX_SECTOR PD1 ";
				$strWhere2 = $strWhere . " GROUP BY PD1.ENODOB_NAME ";
				$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer',' $tblName','$strWhere2', -1, -1, :resultado); END;";       
                $dataContent2 = $this->executeProcedure($sql);
				$data["SITELIST"] = $dataContent2;
			} elseif ($changeVwMap == "5") {
				$strQuer = " PD1.ENODOB_NAME SITE_NAME,MAX(PD1.LONGITUD)LONGITUD,MAX(SITE_ADDRESS)SITE_ADDRESS,
					MAX(PD1.LATITUD)LATITUD ";
				$tblName = "PSO_0DATA_" . $tracingSTR . "_6251_6831 PD1";
				// $tblName = " PRB_SEM_MAX_SECTOR PD1 ";
				$strWhere2 = $strWhere . " GROUP BY PD1.ENODOB_NAME ";
				$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMICPAG('$strQuer',' $tblName','$strWhere2', -1, -1, :resultado); END;";       
                $dataContent2 = $this->executeProcedure($sql);
				$data["SITELIST"] = $dataContent2;
			}
			$siteNameList = array();

			if (in_array($changeVwMap, ["3", "4", "5"])) {
				foreach ($dataContent as $key => $value) {
					$siteName = $value["SECTOR_NAME"];
					if (!in_array($siteName, $siteNameList)) {
						$siteNameList[] = $siteName;
					}
				}
			} else {
				foreach ($dataContent as $key => $value) {
					$siteName = $value["SITE_NAME"];
					if (!in_array($siteName, $siteNameList)) {
						$siteNameList[] = $siteName;
					}
				}
			}
			$data["siteNameList"] = $siteNameList;
		}
		return $data;
	}

    public function getMapPenales(Request $request)
	{
		$dpto = 0;
		$prov = 0;
		$dist = 0;

		$query = array("WHERE LATITUD IS NOT NULL AND LONGITUD IS NOT NULL");
		if ($dpto != "0")
			$query[] = "SUBSTR(UBIGEO_DISTRITO,1,2)='$dpto'";
		if ($prov != "0")
			$query[] = "SUBSTR(UBIGEO_DISTRITO,0,4)='$prov'";
		if ($dist != "0")
			$query[] = "UBIGEO_DISTRITO='$dist'";
		$query = implode(" AND ", $query);
        $list = DB::select(DB::raw("SELECT MBTS_NAME,LATITUD LAT,LONGITUD LON,DEPARTAMENTO,PENAL FROM PM_BASE_PENALES $query"));
		echo json_encode($list);
	}

    public function getMapTest7(Request $request)
	{
		$anio = $request->input("anio");
		$sem = $request->input("sem");
		$banda = $request->input("banda");
		$dpto = $request->input("dpto");
		$prov = $request->input("prov");
		$dist = $request->input("dist");

		$query = array("WHERE LATITUD IS NOT NULL AND LONGITUD IS NOT NULL");
		if ($dpto != "0")
			$query[] = "SUBSTR(UBIGEO_DISTRITO,1,2)='$dpto'";
		if ($prov != "0")
			$query[] = "SUBSTR(UBIGEO_DISTRITO,0,4)='$prov'";
		if ($dist != "0")
			$query[] = "UBIGEO_DISTRITO='$dist'";
		if ($banda != "0")
			$query[] = "FREQBAND='$banda'";
		if ($anio != "0")
			$query[] = "ANIO='$anio'";
		if ($sem != "0")
			$query[] = "SEMANA='$sem'";
		$query = implode(" AND ", $query);
        $list = DB::select(DB::raw("SELECT SITE_NAME,LATITUD LAT,LONGITUD LON,PESO WEIGHT,DEPARTAMENTO,PROVINCIA,DISTRITO FROM PSO_0DATA_03_6251_7 $query"));
		echo json_encode($list);
	}

	public function graphSurface(Request $request)
	{
		$cellname = $request->input("cellname");
		$tracingID = $request->input("tracingID");
		$data["cellname"] = $cellname;
		$data["tracingID"] = $tracingID;		
		$dataVw = view('backpack::widgets.graphSurface',compact('data'));
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
			$sql = "BEGIN PSO_OSSPORTAL.SP_GET_LISTMAPDATADET('$cellname',$tracingID, :resultado); END;";       
            $data = $this->executeProcedure($sql);
		}
		echo json_encode($data);
	}

	public function getDatallePlanoTabla(Request $request){
		$plano = $request->input("plano");                        
		$anio = $request->input("ano");                        
		$semana = $request->input("semana");                        
        	$data = DB::select(DB::raw("SELECT
		ATTR_PROVIDER_NAME_COMMON as operador, MUESTRAS_TOTALES muestras_total, round(AVG_DOWNLOAD_MBPS, 2) download_mbps_avg,
		round(AVG_UPLOAD_MBPS, 2) upload_mbps_avg, round(AVG_LATENCY_MIN_MS, 2) latencia_ms_avg, null posible_tech,
		RANK_MEDIAN_DOWNLOAD_MBPS best_dn, RANK_MEDIAN_UPLOAD_MBPS best_up, RANK_MEDIAN_LATENCY_MIN_MS best_lat, null best_ope
		FROM fija_planos_competencia
		where plano = ? and MUESTRAS >= 5 and ano = ? and semana = ?
		ORDER BY RANKING_MEJOR_OPERADOR ASC"), [$plano, $anio, $semana]);
        return response()->json(compact('data'));
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

}