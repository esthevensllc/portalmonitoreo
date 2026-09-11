<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\OperadorDiaGrafica;
use App\OperadorRcrDiaGrafica;
use App\LineasRcrDiaGrafica;
use App\EventoDiaGrafica;
use App\TopCelDlDiaGrafica;
use App\TopCelUlDiaGrafica;
use App\TopImsiDiaGrafica;
use App\DriveTestDiaGrafica;
use Illuminate\Support\Facades\DB; 
use App\Facads\ODB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use PDO;

class ChartsApiController extends Controller
{
	private $graph_input = [];
    public function index(Request $request)
    {
        //OperadorDiaGrafica::create(['speed' => rand(30,70)]);
        if ($request->ajax()) {
            $ubigeo = trim($request->input('ubigeo'));
            $chart = $request->input('chart');
            switch ($chart) {
                case '/RESUMEN-CVM-OPERADOR': 
                    $tecnologia = trim($request->input('tecnologia'));
                    $speeds = OperadorDiaGrafica::select(DB::raw("TO_CHAR(dia,'YYYY/MM/DD')as fecha"),DB::raw('count(*) as total'))
                        ->where('ubigeo',$ubigeo)
                        ->where('tech_dl',$tecnologia)
                        ->where('mstras_validas',1)
                        ->groupBy(DB::raw("TO_CHAR(dia,'YYYY/MM/DD')"))
                        ->orderBy(DB::raw("TO_CHAR(dia,'YYYY/MM/DD')"),'ASC')
                        ->get();
                    $labels = $speeds->pluck('fecha');
                    $data = $speeds->pluck('total');
                    break;
                case '/resumen-cvm-operador-rcr': 
                    $tecnologia = trim($request->input('tecnologia'));
                    $speeds = OperadorRcrDiaGrafica::select(DB::raw("TO_CHAR(dia,'YYYY/MM/DD') as fecha"), DB::raw('count(*) as total'))
                        ->where('ubigeo',$ubigeo)
                        ->where('tech_dl',$tecnologia)
                        ->where('mstras_validas',1)
                        ->groupBy(DB::raw("TO_CHAR(dia,'YYYY/MM/DD')"))
                        ->orderBy(DB::raw("TO_CHAR(dia,'YYYY/MM/DD')"),'ASC')
                        ->get();
                    $labels = $speeds->pluck('fecha');
                    $data = $speeds->pluck('total');
                    break;
                case '/lineasrecurrentes': 
                    $speeds = LineasRcrDiaGrafica::select('imsi',DB::raw("TO_CHAR(TIMESTAMP,'YYYY/MM/DD') as fecha"), DB::raw('count(*) as total'))
                        ->where('imsi',$ubigeo)
                        ->groupBy(DB::raw("TO_CHAR(TIMESTAMP,'YYYY/MM/DD')"),'imsi')
                        ->orderBy(DB::raw("TO_CHAR(TIMESTAMP,'YYYY/MM/DD')"),'ASC')
                        ->get();
                    $labels = $speeds->pluck('fecha');
                    $data = $speeds->pluck('total');
                    break;  
                case '/evento_linea': 
                    $speeds = EventoDiaGrafica::select('cellname',DB::raw("sum(recurrencia) as total"))
                        ->groupBy('cellname')
                        ->get();
                    $labels = $speeds->pluck('cellname');
                    $data = $speeds->pluck('total');
                    break;
                case '/cvm_mtv_op_top_celldl': 
                    $cell_id = trim($request->input('cell_id'));
                    $speeds = TopCelDlDiaGrafica::select('throughput_dl_kbps','timestamp')
                        ->where('idcell_dl',$cell_id)
                        ->orderby('timestamp','asc')
                        ->get();
                    $labels = $speeds->pluck('timestamp');
                    $data = $speeds->pluck('throughput_dl_kbps');
                    break;
                case '/cvm_mtv_op_top_cellul': 
                    $cell_id = trim($request->input('cell_id'));
                    $speeds = TopCelUlDiaGrafica::select('throughput_ul_kbps','timestamp')
                        ->where('idcell_ul',$cell_id)
                        ->orderby('timestamp','asc')
                        ->get();
                    $labels = $speeds->pluck('timestamp');
                    $data = $speeds->pluck('throughput_ul_kbps');
                    break;
                case '/cvm_mtv_op_top_imsi': 
                    $imsi = trim($request->input('imsi'));
                    $speeds = TopImsiDiaGrafica::select('throughput_dl_kbps','throughput_ul_kbps','timestamp')
                        ->where('imsi',$imsi)
                        ->orderby('timestamp','asc')
                        ->get();
                    $labels = $speeds->pluck('timestamp');
                    $data[0] = $speeds->pluck('throughput_dl_kbps');
                    $data[1] = $speeds->pluck('throughput_ul_kbps');
                    break;
                case '/drive_test': 
                    $idclient = trim($request->input('idclient'));
                    $tecnologia = trim($request->input('tecnologia'));
                    $speeds = DriveTestDiaGrafica::select('throughput_dl_kbps','timestamp')
                        ->where('idclient',$idclient)
                        ->where('tech_dl',$tecnologia)
                        ->orderby('timestamp','asc')
                        ->get();
                    $labels = $speeds->pluck('timestamp');
                    $data = $speeds->pluck('throughput_dl_kbps');
                    break; 
            }
            return response()->json(compact('labels', 'data'));
        }
    }

	public function getGraph(Request $request)
	{
		$kpi = $request->get("tracingID");
		$filtID = $request->get("slcMap");
		$filtVal = $request->get("fildVal");
		//$this->clearUrlSubmenu();
		//$_SESSION["urlSubmenu"] = null;
		$filtVal = urldecode($filtVal);
		$ind = 0;
		switch ($filtID) {
			case '555':
				$ind = 373;
				break;
			case '556':
				$ind = 371;
				break;
			case '557':
				$ind = 370;
				break;
			default:
				$ind = 373;
				break;
		}
		if ($filtVal == "LIMA" && $ind == 373) {
			$ind = 372;
		}
		$filtVal = base64_encode($filtVal);
		//sectID = $this->index->getFieldIDByKpiInd($kpi, $ind);

		$pdo = DB::getPdo();
		$sectID = 0;
		
		$stmt = $pdo->prepare("begin PSO_OSSPORTAL.SP_GET_FIELDBYMENUID(:kpi, :indid, :fieldid); end;");
		$stmt->bindParam(':kpi', $kpi, PDO::PARAM_STR);
		$stmt->bindParam(':indid', $ind, PDO::PARAM_STR);
		$stmt->bindParam(':fieldid', $sectID, PDO::PARAM_INT);
		$stmt->execute();

		$iniDate = new DateTime();
		date_add($iniDate, date_interval_create_from_date_string("-7 days"));
		$iniDate = date_format($iniDate, 'd/m/Y');
		$endDate = new DateTime();
		$endDate = date_format($endDate, 'd/m/Y');

		$this->graph_input = [
			"sectID" => $sectID,
			"filter" => $filtVal,
			"menuID" => -1,
			"submenu" => 0,
			"idatarange" => $iniDate . " - " . $endDate,
			"returnType" => -1
		];

		return $this->dataGraph($request);
	}

    public function dataGraph(Request $request)
	{
		session_start();
		if($request->input('sectID') !== null){
			$sectID = $request->input('sectID');
			$filter = $request->input('filter');
			$menuID = $request->input('menuID') != null ? $request->input('menuID') : -1;
			$subMenu = $request->input('submenu')  != null ? $request->input('submenu') : 0;
			$idatarange = $request->input('idatarange')  != null ? $request->input('idatarange') : "";
			$returnType = $request->input('returnType')  != null ? $request->input('returnType') : 0;
		}else{
			$sectID = $this->graph_input['sectID'];
			$filter = $this->graph_input['filter'];
			$menuID = $this->graph_input['menuID'] != null ? $this->graph_input['menuID'] : -1;
			$subMenu = $this->graph_input['submenu']  != null ? $this->graph_input['submenu'] : 0;
			$idatarange = $this->graph_input['idatarange']  != null ? $this->graph_input['idatarange'] : "";
			$returnType = $this->graph_input['returnType']  != null ? $this->graph_input['returnType'] : 0;
		}

		// default values
		if($idatarange === ""){
			$endTime = new DateTime();
			$strEnd = $endTime->format("d/m/Y");
			$strStart = $endTime->modify("-7 days")->format("d/m/Y");
			$idatarange = "{$strStart} - {$strEnd}";
		}

		$groupRange = $request->input('groupRange');

		/*
		$menuID = DB::table("pso_type a")
		->selectRaw("b.id_type")
		->join("pso_type b", 'b.type_father', '=', 'a.id_type')
		->where("a.type_father", $request->input("sectID"))
		->where("a.type_name", $request->input("groupRange"))
		->where("b.type_name", $request->input("menuID"))
		->get();
		*/

		$menuID = DB::select(DB::raw("select * from pso_type where type_father in (
			select a.id_type from pso_type a
			where a.type_father = :p_sectid and a.type_name= :p_grouprange
		) and type_name = :p_menuid"), [
			"p_sectid" => $request->input("sectID"),
			"p_grouprange" => $request->input("groupRange"),
			"p_menuid" => $request->input("menuID"),
		]);

		if(count($menuID) > 0){
			$menuID = $menuID[0]->id_type;
		}else{
			$menuID = null;
		}

		/*
		$groupRange = DB::table("pso_type")
		->selectRaw("id_type")
		->where("type_father", $request->input("sectID"))
		->where("type_name", $request->input("groupRange"))
		->first();
		if($groupRange !== null){
			$groupRange = $groupRange->id_type;
		}
		*/

		// $menuID = intval($menuID);
		$subMenu = intval($subMenu);
		$filter = urldecode($filter);
		$data["mainMenuID"] = $menuID;

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID($sectID, :resultado); END;";
        $menuSelNow = $this->executeProcedure($sql);

		$isGroupVw = $request->input('isGroupVw');

		if (in_array($request->input("clearSubMenu"), array(1, "1"))) {
			$_SESSION["urlSubmenu"] = null;
			$urlSubmenu = null;
		}

		$data["menuGroupNot"] = $menuSelNow[0]["TYPE_NAME"];

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$data["menuGroupNot"].", :resultado); END;";
        $menuSelData = $this->executeProcedure($sql);

		if (intval($subMenu) == 0) {
			$urlCurrent = url()->current();
			if (strpos($urlCurrent, "/getGraph/")) {
				$urlCurrent = asset('api/dataGraph');
			}
			$_SESSION["urlSubmenuBase"] = 
				array(
					"menu" => $menuSelData[0]["TYPE_NAME"], "url" => $urlCurrent,
					"post" => $request->input()
				);
		}

		if ($isGroupVw == '1' && ($filter == 'undefined' || $filter == ''))
			$filter = $request->input('itemTop');
		$data["filter"] = $filter;
		$strFilter = array();
		foreach (explode("|", $filter) as $filterValue) {
			$strFilter[] = urldecode(base64_decode($filterValue));
		}
		$filter = implode("|", $strFilter);

		// $data["groupVwGraph"] = $request->input("groupVwGraph");
		$data["groupVwGraph"] = false;
		if ($isGroupVw == "1")
			$data["groupVwGraph"] = true;

		$data["sectID"] = $sectID;
		$data["titleprincipal"] = "OSS2600";
		$data["menu"] = "Plantillas";
		$data["view_menu"] = view('backpack::widgets.menuGraph',compact('data'));

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID($sectID, :resultado); END;";       
        $sectType =$this->executeProcedure($sql);

		// $sectType = $this->index->getTypeByFatherID($sectID);
		// print_r("---------------------");
		// print_r($sectID);
		// print_r("---------------------");
		// print_r($sectType);
		// print_r("---------------------");
		// return;
		$secSepID = null;
		foreach ($sectType as $key => $sectTypeItm) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$sectTypeItm["TYPE_NAME"].", :resultado); END;";
            $sectType[$key]["DATA"] = $this->executeProcedure($sql);
			
			if($sectType[$key]["TYPE_NAME"] === $groupRange){
				$secSepID = $sectType[$key]["ID_TYPE"];
			}
		}
		if($secSepID === null){
			$secSepID = $sectType[0]["ID_TYPE"];
		}
		$data["sectType"] = $sectType;
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_GRAPHMENU($secSepID, :resultado); END;";
        $sectMenu = $this->executeProcedure($sql);
		// print_r("-----secSepID-----------para ver el id que da el menú de los gráficos-----");
		// print_r($secSepID);
		// print_r("---------------------");
		// print_r($sectMenu);
		// return;

		$sectMenuSelect = $sectMenu[0];
		$data["sectMenu"] = $sectMenu;
		$data["idatarange"] = $idatarange;
		$data["secSepID"] = $secSepID;
		$menuSel = $sectMenu[0];
		$keyMenu = 0;
		$menuIDSlc = $menuID !== null ? $menuID : "";
		if ($menuID !== null) {
			$keyMenu = array_search($menuID, array_column($sectMenu, 'MENUID'));
			if ($keyMenu > 0)
				$menuSel = $sectMenu[$keyMenu];
		} elseif (!isset($menuIDSlc) || strlen($menuIDSlc) > 0) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID($menuIDSlc, :resultado); END;";
            $menuIDSlc = $this->executeProcedure($sql);
			$keyMenu = array_search($menuIDSlc["TYPE_NAME"], array_column($sectMenu, 'MID'));
			if ($keyMenu > 0)
				$menuSel = $sectMenu[$keyMenu];
		}
		//dd(["menuIDSlc" => $menuIDSlc, "keyMenu" => $keyMenu, "menuSel" => $menuSel]);
		$data["menuID"] = $menuSel["MENUID"];
		$data["menuSel"] = $menuSel;
		$subSecID = 0;
		$itemTop = $request->input('itemTop') ?? NULL;
		$listItmTop = array();
		if (strlen($itemTop))
			foreach (explode("|", $itemTop) as $itmVal) {
				$listItmTop[] = urldecode(base64_decode($itmVal));
			}
		$itemTop = implode("|", $listItmTop);
		$itemList = NULL;
		$subMenuID = 0;
		$menuSelID = 0;

		if (intval($subMenu) > 0) {
			$urlSubmenu = $_SESSION["urlSubmenu"];
			if (!isset($urlSubmenu) || count($urlSubmenu) == 0) {
				$urlSubmenu = array($_SESSION["urlSubmenuBase"]);
			}
			$urlSubmenu[] = array("menu" => $menuSelData[0]["TYPE_NAME"], "url" => url()->current(), "post" => $request->input());
			$_SESSION["urlSubmenu"] = $urlSubmenu;
			$itemTop = "";
		}

		$data['urlSubmenu'] = $urlSubmenu;

		if (count(explode("|", $filter)) == 1 || $isGroupVw == '1') {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(352, $sectID, :resultado); END;";
            $subSect = $this->executeProcedure($sql);
			// print_r($sectID);
			// 	print_r("-");
			// 	print_r($subSect);
			// 	return;
			if (count($subSect) > 0) {
				$subSectStruct = explode('|', $subSect[0]["TYPE_DESCRIPTION"]);
				$menuSelID = $subSectStruct[0];
				$fieldFilter = $subSectStruct[1];
				$fieldFilter = explode(",", $fieldFilter);
				$filterType = $subSectStruct[2];
				$seqTotal = array();
				foreach (explode("|", $filter) as $key => $value) {
					$filterSeq = array();
					foreach (explode("__", $value) as $filterItem) {
						if (strlen($filterItem) > 0)
							$filterSeq[] = str_replace("[FILTER]", $filterItem, $subSectStruct[3]);
						else
							$filterSeq[] = "";
					}
					$seqFilter = array();
					foreach ($fieldFilter as $keyFldFilt => $fieldItem) {
						if ($filterSeq[$keyFldFilt] != "")
							$seqFilter[] = $fieldItem . " $filterType '$filterSeq[$keyFldFilt]'";
					}
					$seqTotal[] = '(' . implode(" AND ", $seqFilter) . ')';
				}
				$seqTotal = implode(" OR ", $seqTotal);
				$fieldName = $subSectStruct[4];
				$fieldOther = $subSectStruct[5] ?? "";

                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(331, $menuSelID, :resultado); END;";
                $tblData = $this->executeProcedure($sql);

                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME($menuSelID, 42, :resultado); END;";
                $tblDataSub = $this->executeProcedure($sql);

				$subSecID = $tblDataSub[0]["ID_TYPE"];

                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME($subSecID, ".$menuSel['MID'].", :resultado); END;";
                $infoDataSubMenu_ = $this->executeProcedure($sql);
                $infoDataSubMenu = $infoDataSubMenu_[0];

				$subMenuID = $infoDataSubMenu["ID_TYPE"];
				$tblName = $tblData[0]["TYPE_DESCRIPTION"];
				$itemfieldName = explode(",", $fieldName);
				$itemfieldName = end($itemfieldName);
				$fieldNameAll = $fieldName;
				if (strlen(trim($fieldOther)) > 0) {
					foreach (explode(",", $fieldOther) as $fieldOtherItem) {
						if (!in_array($fieldOtherItem, explode(",", $fieldNameAll))) {
							$fieldNameAll .= "," . $fieldOtherItem;
						}
					}
				}
				$fieldOther = explode(",", $fieldOther);
				$data["fieldOther"] = $fieldOther;
				// var_dump($fieldNameAll,$tblName," $seqTotal ");
				// dd($seqTotal);
                // $sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMIC('$fieldNameAll', '$tblName', '".str_replace("'","''",$seqTotal)."', :resultado); END;";
                // $itemList = $this->executeProcedure($sql);

				$sql = "SELECT {$fieldNameAll} FROM {$tblName}";
				if(strlen($seqTotal) > 0){
					$sql .= " WHERE {$seqTotal}";
				}
				$sql .= " ORDER BY {$fieldNameAll}";
				$itemList = [];
				$itemList_result = DB::select(DB::raw($sql));
				foreach ($itemList_result as $row) {
					$new_row = [];
					foreach($row as $field => $value){
						$new_row[strtoupper($field)] = $value;
					}
					$itemList[] = $new_row;
				}

				$itemStrList = array();
				foreach ($itemList as $itemRow) {
					$itmStr = array();
					$keySTR = array();
					foreach ($itemRow as $itmRowKey => $itmRowVal) {
						if (in_array($itmRowKey, explode(",", $fieldNameAll))) {
							$itmStr[] = $itmRowVal;
						}
						if (in_array($itmRowKey, explode(",", $fieldName))) {
							$keySTR[] = $itmRowVal;
						}
					}
					$itemStrList[implode("__", $keySTR)] = implode(" ", $itmStr);
				}
				$data["itemStrList"] = $itemStrList;
				if (strlen($itemTop) == 0) {
					$itemTop = array_slice($itemStrList, 0, 10);
					$itemTop = implode("|", array_map(
						function ($vald) {
							return base64_encode($vald);
						},
						array_values($itemTop)
					));
				}
				$itemList = implode("|", array_values($itemStrList));
				$subMenu = 1;
			} else {
				$subMenu = 0;
			}
			$data["valMult"] = 0;
		} else {
			$subMenu = 2;
			$data["valMult"] = 1;
		}

		$data["menuSelID"] = $menuSelID;
		$data["subMenu"] = $subMenu;
		$data["subSecID"] = $subSecID;
		$data["itemList"] = $itemList;
		$data["itemTop"] = $itemTop;
		$data["subMenuID"] = $subMenuID;
		$data["returnType"] = $returnType;
		$data["view_menuGraphOpt"] = view('backpack::widgets.menuGraphOpt',compact('data'));
		// print_r($data);
		// return;
		//dd($data['sectType']);
		if ($returnType == -1) {
            return view('backpack::widgets.datagraph',compact('data'));
		} elseif ($returnType == 0) {
			return view('backpack::widgets.datagraph',compact('data'));
		} else {
			echo view('backpack::widgets.datagraph',compact('data'));
		}
	}

    public function getGraphBody(Request $request,$keyMenuGraph = -1)
	{
		$secSepID = $request->input("secSepID");
		$data["secSepID"] = $secSepID;
		$data["nullZero"] = 0;

		$menu_id = DB::table("pso_type")
		->where("type_name", $request->input("menuID"))
		->where("type_father", $request->input("secSepID"))
		->first();

		if($menu_id !== null){
			$menu_id = $menu_id->id_type;
		}else{
			// $menu_id = $request->input("menuID");
		}
        //$sql = "BEGIN PSO_OSSPORTAL.SP_GET_TRACINGBYSECTID($secSepID,0, :resultado); END;";
        //$tracingIDBySect = $this->executeProcedure($sql);
        $tracingIDBySect = 0;
		if (in_array($tracingIDBySect, array(15, "15"))) {
			$data["nullZero"] = 1;
		}
		$groupVwGraph = $request->input("groupVwGraph");
		$data["groupVwGraph"] = $groupVwGraph;

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_GRAPHMENU($secSepID, :resultado); END;";
        $sectMenu = $this->executeProcedure($sql);

		// $keyMenu = $request->input("keyMenu");
		// $data["sectMenu"] = $request->input("sectMenu");

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID($secSepID, :resultado); END;";
        $typeGraph = $this->executeProcedure($sql);

		$data["dataGroupRange"] = $typeGraph[0]["TYPE_NAME"];
        
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$typeGraph[0]['TYPE_FATHER'].", :resultado); END;";
        $typeGraph = $this->executeProcedure($sql);
        $typeGraph = $typeGraph[0]["TYPE_NAME"];

		$data["typeGraph"] = $typeGraph;
        
		$data["filter"] = urldecode($request->input("filter"));
		$idatarange = $request->input("idatarange");
		$data["idatarange"] = str_replace(array('/', ' '), "", $idatarange);
		$data["menuID"] = $menu_id;
		$keyMenu = array_search($data["menuID"], array_column($sectMenu, 'MENUID'));
		if ($keyMenu === false) {
			$keyMenu = 0;
		}
		$data["keyMenu"] = $keyMenu;
		$menuSel = $sectMenu[$keyMenu];

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$menuSel['MENUID'].", :resultado); END;";
        $fields = $this->executeProcedure($sql);

		$menudata_parts = explode("|", $menuSel['MENUDATA']);
		$timeInterval = null;
		if(count($menudata_parts)>=5){
			$timeInterval = $menudata_parts[4];
		}

		$htmlGraph = array();
		$menuKeyStr = array();
		foreach ($fields as $key => $field) {

			if (intval($keyMenuGraph) > 0)
				if ($field["TYPE_NAME"] != $keyMenuGraph)
					continue;
			$menuKeyStr[] = $key;
			$data["selectID"] = $field["ID_TYPE"];

            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$field["TYPE_NAME"].", :resultado); END;";
            $fieldDesc = $this->executeProcedure($sql);
			// print_r($field);
			// $GRAPHTYPE = $this->index->getTypeByID($fieldDesc["TYPE_NAME"]);
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$field["TYPE_NAME"].", :resultado); END;";
            $graphIDKpi = $this->executeProcedure($sql);

            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$graphIDKpi[0]["TYPE_NAME"].", :resultado); END;";
            $graphProp = $this->executeProcedure($sql);

			$group = 0;
			$fndesc = $fieldDesc[0]["TYPE_DESCRIPTION"];
			if (strpos($graphProp[0]["TYPE_NAME"], '(grupo)') !== false) {
				$group = 1;
			}

            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID(".$field["ID_TYPE"].", :resultado); END;";
            $tituloAdded = $this->executeProcedure($sql);

			if (count($tituloAdded) > 0) {
				if (strpos($tituloAdded[0]["TYPE_DESCRIPTION"], "AS LINEY") !== false) {
					$tituloAdded = "";
				} else {
					$tituloAdded = $tituloAdded[0]["TYPE_DESCRIPTION"];
				}
			} else {
				$tituloAdded = "";
			}

			$data["group"] = $group;
			$data["graphName"] = $fndesc;
			$data["menuKey"] = $key;
			$data["tituloAdded"] = $tituloAdded;
			// time interval to complete data(5min=300, 15min=1500)
			$data["timeInterval"] = $timeInterval;
			// print_r("==========");
			// print_r($data);
			// print_r("==========");

			$htmlGraph[] = view('backpack::widgets.graph',compact('data'));
			// 
		}

		$scriptStr = "<script>var taskgraph=[];</script>";
		$strTextMenuKey = "<input type='hidden'id='menuKey'value='" . implode("|", $menuKeyStr) . "'>";
		echo $scriptStr . $strTextMenuKey . implode('<br>', $htmlGraph);
		// echo $htmlGra	ph;
	}

    public function changeMenuOpt(Request $request)
	{
		//$user = $this->session->userdata('user');
		// $menuID = intval($request->input("menuID"));
		$subMenu = intval($request->input("subMenu"));
		$filter = urldecode($request->input("filter"));
        $sectID = $request->input("sectID");
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID($sectID, :resultado); END;";
        $menuSelNow = $this->executeProcedure($sql);

		$groupRange = DB::table("pso_type")
		->where("type_name", $request->input("groupRange"))
		->where("type_father", $request->input("sectID"))
		->first();
		if($groupRange !== null){
			$groupRange = $groupRange->id_type;
		}

		$menuID = DB::table("pso_type")
		->where("type_name", $request->input("menuID"))
		->where("type_father", $groupRange)
		->first();

		if($menuID !== null){
			$menuID = intval("{$menuID->id_type}");
		}
        
		$groupRange = intval($groupRange);
		$data["menuGroupNot"] = $menuSelNow[0]["TYPE_NAME"];

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$menuSelNow[0]['TYPE_NAME'].", :resultado); END;";
        $menuSelData = $this->executeProcedure($sql);
		if (intval($subMenu) == 0 && 1 == 2)
			session(['urlSubmenuBase' => array("menu" => $menuSelData[0]["TYPE_NAME"], "url" => url()->current(), "post" => $request->input())]);
		$data["filter"] = $filter;
		$strFilter = array();
		foreach (explode("|", $filter) as $filterValue) {
			$strFilter[] = urldecode(base64_decode($filterValue));
		}
		$filter = implode("|", $strFilter);
		$data["groupVwGraph"] = $request->input("groupVwGraph");
		//$data["user"] = $user;
		$data["sectID"] = $sectID;
		$data["titleprincipal"] = "OSS2600";
		$data["menu"] = "Plantillas";
		$data["view_menu"] = view('backpack::widgets.menuGraph',compact('data'));

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID($sectID, :resultado); END;";
        $sectType = $this->executeProcedure($sql);

		foreach ($sectType as $key => $sectTypeItm) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$sectTypeItm["TYPE_NAME"].", :resultado); END;";
            $sectType[$key]["DATA"] = $this->executeProcedure($sql)[0];
		}
		$data["sectType"] = $sectType;
		// var_dump($sectType);
		// $secSepID = $sectType[0]["ID_TYPE"];
		// if($groupRange>0){
		// 	$keyMenuRange = array_search($groupRange, array_column($secSepID,'ID_TYPE'));
		// 	$secSepID = $sectType[$keyMenu]["ID_TYPE"];
		// }
		$secSepID = $groupRange;

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_GRAPHMENU($secSepID, :resultado); END;";
        $sectMenu = $this->executeProcedure($sql);

		$sectMenuSelect = $sectMenu[0];
		$data["sectMenu"] = $sectMenu;
		$data["idatarange"] = $request->input("idatarange");
		$data["secSepID"] = $secSepID;
		$menuSel = $sectMenu[0];
		$keyMenu = 0;
		if ($menuID > 0) {
			$keyMenu = array_search($menuID, array_column($sectMenu, 'MENUID'));
			$menuSel = $sectMenu[$keyMenu];
		}
		$data["menuID"] = $menuID ?? "";
		if (!isset($data["menuID"]) || !strlen($data["menuID"]) > 0)
			$data["menuID"] = $menuSel["MENUID"];
		$data["menuSel"] = $menuSel;
		$view_menuGraphOpt = view('backpack::widgets.menuGraphOpt',compact('data'));
		echo $view_menuGraphOpt;
	}

    public function Graph(Request $request)
	{
		$itemTop = urldecode($request->input("itemTop"));
		$strFilter = array();
		$filter = urldecode($request->input("filter"));
		foreach (explode("|", $filter) as $filterValue) {
			$strFilter[] = urldecode(base64_decode($filterValue));
		}
		$filter = implode("|", $strFilter);
		$dataIds = array();
        $selectID = $request->input("selectID");

        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID($selectID, :resultado); END;";
        $fieldData = $this->executeProcedure($sql);

		if (intval($request->input("group")) > 0) {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERID($selectID, :resultado); END;";
            $dataIds = $this->executeProcedure($sql);
		} else {
			$dataIds[] = $fieldData[0];
		}

		$subSecID = 0;
		$itemTop = array_map(
			function ($val) {
				return urldecode(base64_decode($val));
			},
			explode("|", $itemTop)
		);
		$itemList = NULL;
		$subMenuID = 0;
		$menuSelID = 0;
		$dtFat = null;

        $secSepID =  $request->input("secSepID");
        $groupVwGraph = $request->input("groupVwGraph");

		if ($groupVwGraph == "true") {
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID($secSepID, :resultado); END;";
            $dataType = $this->executeProcedure($sql);

            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(352, ".$dataType[0]['TYPE_FATHER'].", :resultado); END;";
            $dtFat = $this->executeProcedure($sql);

			// $idSubMenu = explode('|', $dtFat[0]["TYPE_DESCRIPTION"])[0];
			// $secSepID = $this->index->getTypeByFatherIdTypeName($idSubMenu,$dataType["TYPE_NAME"])[0]["ID_TYPE"];
			// // $secSepID = explode('|', $subSect[0]["TYPE_DESCRIPTION"])[0];
			// $sectMenu = $this->index->getGraphMenu($secSepID);
			// $dataType = $this->index->getTypeByID($secSepID);
			// $menuSel = $sectMenu[$keyMenu];
            $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(352, ".$dataType[0]['TYPE_FATHER'].", :resultado); END;";
            $subSect = $this->executeProcedure($sql);

			if (count($subSect) > 0) {
				$subSectStruct = explode('|', $subSect[0]["TYPE_DESCRIPTION"]);
				$menuSelID = $subSectStruct[0];
				$fieldFilter = $subSectStruct[1];
				$filterType = $subSectStruct[2];
				$filterSeq = str_replace("[FILTER]", $filter, $subSectStruct[3]);
				$fieldName = $subSectStruct[4];
				$fieldOther = $subSectStruct[5] ?? "";
                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(331, $menuSelID, :resultado); END;";
                $tblData = $this->executeProcedure($sql);

                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME($menuSelID, 42, :resultado); END;";
                $tblDataSub = $this->executeProcedure($sql);

				$subSecID = $tblDataSub[0]["ID_TYPE"];
				// $infoDataSubMenu = $this->index->getTypeByFatherIdTypeName($subSecID,$menuSel["MID"])[0];
				// $subMenuID = $infoDataSubMenu["ID_TYPE"];
				$tblName = $tblData[0]["TYPE_DESCRIPTION"];
				// if(strpos(substr($tblName,strlen($tblName)-4), "_373")!==FALSE&&$fiterItem=='LIMA'){
				// 	$tblName=str_replace("_373", "_372", $tblName);
				// 	$fieldFilter=str_replace("SUB_REGION", "REGION", $fieldFilter);
				// }
				$filterStr = array();
				$filterItemPart = explode("|", $filterSeq);
				foreach ($filterItemPart as $filtItmVal) {
					$strValFilt = array();
					$filterValPart = explode("__", $filtItmVal);
					foreach ($filterValPart as $key => $filterItmSpl) {
						$filterItmType = explode(",", $fieldFilter)[$key];
						if (trim(strtoupper($filterType)) == 'LIKE') {
							$strValTmp = "(\"$filterItmType\"='" . trim($filterItmSpl, '%') . "'";
							$strValTmp .= " OR (\"$filterItmType\"<>'$filterItmSpl' AND";
							$strValTmp .= " \"$filterItmType\" $filterType '$filterItmSpl'))";
							$strValFilt[] = $strValTmp;
						} else {
							$strValFilt[] = "\"$filterItmType\" $filterType '$filterItmSpl'";
						}
					}
					$filterStr[] = "(" . implode(" AND ", $strValFilt) . ")";
				}
				$filterStr = " " . implode(" OR ", $filterStr) . " ";
				$fieldNameAll = $fieldName;
				if (strlen(trim($fieldOther)) > 0) {
					foreach (explode(",", $fieldOther) as $fieldOtherItem) {
						if (!in_array($fieldOtherItem, explode(",", $fieldNameAll))) {
							$fieldNameAll .= "," . $fieldOtherItem;
						}
					}
				}

                $sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMIC('$fieldNameAll', '$tblName','".str_replace("'","''",$filterStr)."', :resultado); END;";
                $itemList = $this->executeProcedure($sql);

				$itemStrList = array();
				foreach ($itemList as $itemRow) {
					$itmStr = array();
					$keySTR = array();
					foreach ($itemRow as $itmRowKey => $itmRowVal) {
						if (in_array($itmRowKey, explode(",", $fieldNameAll))) {
							$itmStr[] = $itmRowVal;
						}
						if (in_array($itmRowKey, explode(",", $fieldName))) {
							$keySTR[] = $itmRowVal;
						}
					}
					$itemStrList[implode("__", $keySTR)] = implode(" ", $itmStr);
				}
				$data["itemStrList"] = $itemStrList;
				$itemList = implode("|", array_values($itemStrList));
				if (isset($itemTop) && count($itemTop) == 0) {
					$itemTop = array_slice($itemStrList, 0, 10);
					$filter = implode("|", array_keys($itemTop));
				} else {
					$filter = implode("|", $itemTop);
				}
				// $itemTop=implode("|",array_values($itemTop));
				// $itemList = $this->index->getExecSelectDymamic($fieldName,$tblName," $fieldFilter $filterType '$filterSeq' ");
				// $itemTop = array_slice($itemList, 0,10);
				// $itemList=implode("|",array_column($itemList,$fieldName));
				$subMenu = 1;
				$idSubMenu = explode('|', $dtFat[0]["TYPE_DESCRIPTION"])[0];

                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME($idSubMenu, ".$dataType[0]['TYPE_NAME'].", :resultado); END;";
                $secSepID = $this->executeProcedure($sql)[0]["ID_TYPE"];
			}
		}
        
        $sql = "BEGIN PSO_OSSPORTAL.SP_GET_GRAPHMENU($secSepID, :resultado); END;";
        $sectMenu = $this->executeProcedure($sql);

        $keyMenu = $request->input("keyMenu");
        $idatarange = $request->input("idatarange");
		// $data["itemTop"]=$filter;
		$menuSel = $sectMenu[$keyMenu];
		$dates = explode('-', $idatarange);
		$dateStr = array();
		$filterData = explode('|', $filter);
		foreach ($dates as $itemDate) {
			$strDay = substr($itemDate, 0, 2);
			$strMonth = substr($itemDate, 2, 2);
			$strYear = substr($itemDate, 4, 4);
			$dateStr[] = "$strYear-$strMonth-$strDay";
		}
		$slcfilt = explode('|', $menuSel["MENUDATA"]);
		$dataContent = array();
		$connection = "";
		if(count($slcfilt)>3){
			$connection = $slcfilt[3];
		}
		// dd($slcfilt);

		$operators_by_db = [
			"oracle" => [
				"dt_to_str" => "TO_CHAR(%s, 'YYYY-MM-DD HH24:MI:SS')",
				"str_to_date" => "TO_DATE(%s, 'YYYY-MM-DD')",
				"str_to_dt" => "TO_DATE(%s, 'YYYY-MM-DD HH24:MI:SS')",
			],
			"clickhouse" => [
				"dt_to_str" => "toString(%s)",
				"str_to_date" => "toDateTime(concat(%s, ' 00:00:00'))",
				"str_to_dt" => "toDateTime(%s)",
			],
		];

		$operators = $operators_by_db["oracle"];
		if(str_contains($connection, "clickhouse")){
			$operators = $operators_by_db["clickhouse"];
		}

		// print_r("*****dataContent**********");
		foreach ($dataIds as $field) {

			foreach ($filterData as $fiterItem) {
				// foreach (explode(",", $slcfilt[1]) as $keyVal=>$strVal) {
				// 	$filterData[]="\"".$slcfilt[1]."\"='".explde(",",$fiterItem)[$keyVal]."'";
				// }
				// $filterStr = " ".implode(" AND ", $filterData);

				$strValFilt = array();
				$filterItemPart = explode("__", $fiterItem);
				foreach ($filterItemPart as $key => $filterItmSpl) {
					if ($filterItmSpl != "") {
						$slcfiltTmp = explode(",", $slcfilt[1]);
						$strValTmp = "$slcfiltTmp[$key]=''$filterItmSpl''";
						/*$strValTmp .= " OR ($slcfiltTmp[$key]<>''$filterItmSpl'' AND";
						$strValTmp .= " $slcfiltTmp[$key] LIKE ''$filterItmSpl''))";*/
						$strValFilt[] = $strValTmp;
						// $strValFilt[]= "\"".explode(",", $slcfilt[1])[$key]."\" LIKE '$filterItmSpl%'";
					}
				}

				$fechaIni = str_replace("'", "''", sprintf($operators["str_to_date"], "'$dateStr[0]'"));
				$fechaFin = str_replace("'", "''", sprintf($operators["str_to_dt"], "'$dateStr[1] 23:59:59'"));
				$resultTime = str_replace("'", "''", sprintf($operators["dt_to_str"], "$slcfilt[2]"));

				$filterStr = " " . implode(" AND ", $strValFilt) . " ";
				// $filterStr = " \"".$slcfilt[1]."\"='".$fiterItem."' ";
				$filterStr .= " AND " . $slcfilt[2] . " BETWEEN {$fechaIni} AND {$fechaFin} ";
				$orderFld = " ORDER BY ".$slcfilt[2]." ";
				$tblName = $slcfilt[0];
				if (strpos(substr($tblName, strlen($tblName) - 4), "_373") !== FALSE && $fiterItem == 'LIMA') {
					$tblName2 = str_replace("_373", "_372", $tblName);
					$filterStr2 = str_replace("SUB_REGION", "REGION", $filterStr);
				}

                $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYFATHERIDTYPENAME(5479, ".$field['TYPE_NAME'].", :resultado); END;";
                $listItm = $this->executeProcedure($sql);

				$strItem = $field["TYPE_DESCRIPTION"];
				$detailGraph = array("title_y" => "", "unit_y" => "", "titleGraph" => "", "unitgraph" => "");
				if (count($listItm) > 0) {
					$listItm = explode('|', $listItm[0]["TYPE_DESCRIPTION"]);
					$detailGraph["title_y"] = $listItm[0] ?? "";
					$detailGraph["unit_y"] = $listItm[1] ?? "";
					$detailGraph["titleGraph"] = $listItm[3] ?? "";
					$detailGraph["unitgraph"] = $listItm[4] ?? "";
					$strItem = str_replace('[FILTER]', trim(str_replace("AS LINEY", "", $strItem)), $listItm[2]) . ' AS LINEY';
				}
				$slctSTR = $strItem . ",{$resultTime} RESULTTIME";
				$infrm = array("detailGraph" => $detailGraph);
				// print_r($this->index->getExecSelectDymamic($slctSTR,$tblName,$filterStr.$orderFld));
				// print_r("*****INIT*****");
				// print_r("select ");
				// print_r($slctSTR);
				// print_r(" from ");
				// print_r($tblName);
				// print_r(" where ");
				// print_r($filterStr . $orderFld);
				// print_r("*****END*****");
				// continue;

				$group = $request->input("group");

				if ($group > 0) {
                    $sql = "BEGIN PSO_OSSPORTAL.SP_GET_TYPEBYID(".$field['TYPE_NAME'].", :resultado); END;";
                    $fieldDesc = $this->executeProcedure($sql);

                    if($connection === ""){
						$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMIC('$slctSTR', '$tblName', '".$filterStr . $orderFld."', :resultado); END;";
                    	$infrm["dataList"] = $this->executeProcedure($sql);
					}elseif (str_contains($connection, "clickhouse")) {
						$sql = "select {$slctSTR} from {$tblName} where {$filterStr} {$orderFld}";
						$sql = str_replace("''", "'", $sql);
						// dd($sql);
						$infrm["dataList"] = DB::connection($connection)->select(DB::raw($sql));
					}

					$dataContent[$fieldDesc[0]["TYPE_DESCRIPTION"]] = $infrm;
				} else {

                    if($connection === ""){
						$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTDYNAMIC('$slctSTR', '$tblName', '".$filterStr . $orderFld."', :resultado); END;";
						$infrm["dataList"] = $this->executeProcedure($sql);
					}elseif (str_contains($connection, "clickhouse")) {
						$sql = "select {$slctSTR} from {$tblName} where {$filterStr} {$orderFld}";
						$sql = str_replace("''", "'", $sql);
						// dd($sql);
						$infrm["dataList"] = DB::connection($connection)->select(DB::raw($sql));
					}

					$dataContent[implode("-", $filterItemPart)] = $infrm;
				}
			}
		}

		// print_r($dataContent);
		// return;
		$data["dataval"] = $dataContent;
		echo json_encode($data);
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
		$tblName = "PSO_0DATA_" . $tracingSTR . "_6251";
		if ($changeVwMap == "3") {
			$tblName .= "_6829";
			$strWhere[] = "SECTOR_NAME='$cellname'";
			$strWhere[] = "ENODOB_NAME='$siteName'";
		} elseif ($changeVwMap == "4") {
			$tblName .= "_6830";
			$strWhere[] = "SECTOR_NAME='$cellname'";
			$strWhere[] = "ENODOB_NAME='$siteName'";
		} elseif ($changeVwMap == "5") {
			$tblName .= "_6831";
			$strWhere[] = "SECTOR_NAME='$cellname'";
			$strWhere[] = "ENODOB_NAME='$siteName'";
		} else {
			$strWhere[] = "CELLNAME='$cellname'";
		}
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
						$headList[$strLast] = $fieldItem["TYPE_DESCRIPTION"];
					}
				}
			}
		}

		$strWhere[] = "COALESCE(AZIMUTH,0)<>0 AND COALESCE(LATITUD,0)<>0 AND COALESCE(LONGITUD,0)<>0";
		$strWhere = ' WHERE ' . implode(" AND ", $strWhere) . ' ';
		if (!in_array($changeVwMap, ["3", "4", "5"]))
			$strWhere .= " ORDER BY CELLNAME ";
		else
			$strWhere .= ' ORDER BY ESCENARIO_V2 DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY ';
		$strQuer = 'SELECT A1.* FROM (SELECT ';
		if (count($fieldList) > 0) {
			$strQuer .= implode(",", array_column($fieldList, "TYPE_NAME"));
		} else {
			$strQuer .= "*";
		}
		if (strpos(strtoupper($tblName), "FROM")) {
			$strQuer .= ' FROM(' . $tblName . " $strWhere)A1 )A1";
		} else {
			$strQuer .= ' FROM(' . $tblName . ")A1 $strWhere)A1";
		}
		// var_dump($strQuer);
		$sql = "BEGIN PSO_OSSPORTAL.SP_EXECSELECTPAG('".str_replace("'","''",$strQuer)."', -1, -1, :resultado); END;";
        $data["Content"] = $this->executeProcedure($sql);

		$data["fldList"] = $headList;
		// var_dump($headList);
		$data["fldClear"] = array();
		// $dataContent = $this->index->getExecSelectDymamicPag($strQuer,$tblName,$strWhere,-1,-1);
		echo json_encode($data);
	}

	public function returnDataSubmenu(Request $request)
	{
		session_start();
		$key = $request->input("dataKey");
		$urlSubmenu = $_SESSION["urlSubmenu"];
		$data = $urlSubmenu[$key];
		$data["Total"] = $urlSubmenu;
		$_SESSION["urlSubmenu"] = array_slice($urlSubmenu, 0, $key);
		echo json_encode($data);
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