<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Illuminate\Support\Facades\DB;
use PDO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory as IOFactorySpread;
use PhpOffice\PhpSpreadsheet\Style as SpreadsheetStyle;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExportFilesDownController
{
    use \App\Traits\DB\ProcedureTrait;
    private $inputKey = "";
    private $colMerged = [];
    
    public function report($nroReport,$fechaVal,$pag=0)
	{
        ini_set('max_execution_time', '1000');
        // dd($this->cellsToMergeByColsRow(1,16,2));

        $dataGeneral["fechaVal"]=$fechaVal;
		$dateVal=new DateTime($fechaVal."-01");
		$dataGeneral["fecha"]=$dateVal->format('Y-m-d').' - '.$dateVal->format('Y-m-t');
		switch ($nroReport) {
			case '01':
				$fileName = "01.- Reporte de uso diario";
				// $dataCPE = $this->test->listReport01($fechaVal,$pag);
				// var_dump($nroReport,$fechaVal,$pag);
				$fechaVal=$this->dataReportTmp($nroReport);
				if ($fechaVal==""){
						break;
					$fechaVal=$this->dataReportTmpZip($nroReport);
					if ($fechaVal=="")
						break;
					else{
						shell_exec("zip /usr/reportes/$nroReport/TEST$fileName.$fechaVal.zip /usr/reportes/$nroReport/*$fechaVal.xlsx");
						$fechaVal="";
						break;
					}
				}else{
					$dataGeneral["fechaVal"]=$fechaVal;
					$dateVal=new DateTime($fechaVal."-01");
					$dataGeneral["fecha"]=$dateVal->format('Y-m-d').' - '.$dateVal->format('Y-m-t');
				}
				$dataCPE = $this->dataReportListCPE($nroReport,$fechaVal,$pag);
				$dataGeneral["reportTitle"]="REPORTE INTERNET DIARIO";
				foreach ($dataCPE as $itemCPE) {
					echo $itemCPE["CPE"].",";
					$dataGeneral["reportName"]=$fileName." - Internet.vf".$itemCPE["CPE"].".".$dateVal->format('Ym');
					$dataGeneral["ITM"]=$itemCPE;
					$data = $this->dataReporte01($fechaVal,$itemCPE["CPE"]);
					$this->exportReportXlsx(1,$dataGeneral,$data,0,$nroReport);
					$dataItm=array_filter($data,function ($itm){
							return $itm["FECHA"]!=''&&$itm["FECHA"]!=null;});
					$this->dataReportsLoad($nroReport,$pag,$itemCPE["CPE"],$fechaVal,
						count($dataItm),count($data));
				}
				break;
			case '02':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5615(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="02.- Reporte de uso mensual - Internet.vf".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="REPORTE MENSUAL DE TRAFICO CURSADO - INTERNET";
				$this->exportReportXlsx(2,$dataGeneral,$data);
				break;
			case '04':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5617(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="04.- Estadísticas de las veinte (20) páginas Web más visitadas".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="REPORTE MENSUAL DE LOS SITIOS MAS \nVISITADOS (TOP 20)";
				$this->exportReportXlsx(3,$dataGeneral,$data);
				break;
			case '05':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5616(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="05.- Reporte de pico de volumen de trafico - Internet".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="REPORTE MENSUAL DE PICO DE VOLUMEN DE TRAFICO - INTERNET";
				$this->exportReportXlsx(2,$dataGeneral,$data);
				break;
			case '06':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5618(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="06.- Reporte de Cantidad de sesiones Internet".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="REPORTE MENSUAL DE SESIONES DE INTERNET";
				$this->exportReportXlsx(4,$dataGeneral,$data,$down=1,$carpeta="",$ext="xls");
				break;
			case '07':
				$fechaVal=$this->dataReportTmp($nroReport);
				if ($fechaVal=="")
					break;
				else{
					$dataGeneral["fechaVal"]=$fechaVal;
					$dateVal=new DateTime($fechaVal."-01");
					$dataGeneral["fecha"]=$dateVal->format('Y-m-d').' - '.$dateVal->format('Y-m-t');
				}
				$dataCPE = $this->dataReportListCPE($nroReport,$fechaVal,$pag);
				// $dataCPE = $this->test->listReport07($fechaVal,$pag);
				$dataGeneral["reportTitle"]="REPORTE INTERNET DIARIO";
				foreach ($dataCPE as $itemCPE) {
					$dataGeneral["reportName"]="07.- Reporte de uso diario - Intranet.vf".$itemCPE["CPE"].".".$dateVal->format('Ym');
					$dataGeneral["ITM"]=$itemCPE;
					echo $itemCPE["CPE"].",";
                    $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5619(:p_fechaval, :p_cpe, :resultado); end;", [
                        "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                        "p_cpe" => ["value" => $itemCPE["CPE"], "type" => PDO::PARAM_STR],
                        "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                    ]);
					$this->exportReportXlsx(1,$dataGeneral,$data,0,$nroReport);
					$dataItm=array_filter($data,function ($itm){
							return $itm["FECHA"]!=''&&$itm["FECHA"]!=null;});
					$this->dataReportsLoad($nroReport,$pag,$itemCPE["CPE"],$fechaVal,
						count($dataItm),count($data));
				}
				break;
			case '08':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5620(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="08.- Reporte de uso mensual - Intranet".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="REPORTE MENSUAL DE TRAFICO CURSADO - INTRANET";
				$this->exportReportXlsx(2,$dataGeneral,$data);
				break;
			case '10':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5621(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="10.- Reporte de pico de volumen de trafico - Intranet".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="REPORTE MENSUAL DE PICO DE VOLUMEN DE TRAFICO - INTRANET";
				$this->exportReportXlsx(2,$dataGeneral,$data);
				break;
			case '15_1':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5622(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="15.- TIA Incidencias".$dateVal->format('Ym');
				$this->exportReportXlsx(5,$dataGeneral,$data);
				break;
			case '15_2':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5623(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="15.- TIA Indicador".$dateVal->format('Ym');
				$dataGeneral["reportTitle"]="CALIDAD DE LOS SERVICIOS DE TELECOMUNICACIONES LIMA";
				$this->exportReportXlsx(6,$dataGeneral,$data);
				break;
			case '16_1':
				$fechaVal=$this->dataReportTmp($nroReport);
				if ($fechaVal=="")
					break;
				else{
					$dataGeneral["fechaVal"]=$fechaVal;
					$dateVal=new DateTime($fechaVal."-01");
					$dataGeneral["fecha"]=$dateVal->format('Y-m-d').' - '.$dateVal->format('Y-m-t');
				}
                $dataCPE = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_LIST_18_5624_2(:p_fechaval, :p_pag, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "p_pag" => ["value" => $pag, "type" => PDO::PARAM_INT],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportTitle"]="TASA OCUPACION DE ENLACE: REPORTE DIARIO";
				$rowFile=0;
				foreach ($dataCPE as $itemCPE) {
					$dataGeneral["reportName"]="16.- Reporte toe diario.".$itemCPE["ITEM"].".".$dateVal->format('Ym');
					$dataGeneral["ITM"]=$itemCPE;
					echo $itemCPE["ENLACE_TX"].",";
                    $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5624_2(:p_fechaval, :p_enlace, :resultado); end;", [
                        "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                        "p_enlace" => ["value" => $itemCPE["ENLACE_TX"], "type" => PDO::PARAM_STR],
                        "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                    ]);
					$this->exportReportXlsx(7,$dataGeneral,$data,0,$nroReport);
					$dataItm=array_filter($data,function ($itm){
							return $itm["FECHA"]!=''&&$itm["FECHA"]!=null;});
					$this->dataReportsLoad($nroReport,$pag,$itemCPE["ENLACE_TX"],$fechaVal,
						count($dataItm),count($data));
				}
				break;
			case '16_2':
				$dataCPE = $this->expToeMensualExcel($fechaVal,$dataGeneral["fecha"]);

				break;
			case '17':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5626(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="17.- Indicador Latencia".$dateVal->format('Ym');
				$this->exportReportXlsx(8,$dataGeneral,$data);
				break;
			case '18':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_56261(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="18.- Indicador Perdida de Paquete".$dateVal->format('Ym');
				$this->exportReportXlsx(9,$dataGeneral,$data);
				break;
			case '19_1':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_56262(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="19.- Indicador Velocidad Bajada".$dateVal->format('Ym');
				$this->exportReportXlsx(10,$dataGeneral,$data);
				break;
			case '19_2':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_56262(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="19.- Indicador Velocidad Subida".$dateVal->format('Ym');
				$this->exportReportXlsx(11,$dataGeneral,$data);
				break;
			case '20':
                $data = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_56263(:p_fechaval, :resultado); end;", [
                    "p_fechaval" => ["value" => $fechaVal, "type" => PDO::PARAM_STR],
                    "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
                ]);
				$dataGeneral["reportName"]="20.- Indicador BER".$dateVal->format('Ym');
				$this->exportReportXlsx(12,$dataGeneral,$data);
				break;
			default:
				# code...
				break;
		}
    }

    private function dataReportTmp($p_reporttype)
    {
        $fechaval=null;
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin PK_PSO_REPORTES.SP_REPORTTEMP(:p_reporttype, :p_fechaval); end;");
        $stmt->bindParam(':p_reporttype', $p_reporttype, \PDO::PARAM_STR);
        $stmt->bindParam(':p_fechaval', $fechaval, \PDO::PARAM_STR|\PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return $fechaval;
    }

    private function dataReportTmpZip($p_reporttype)
    {
        $fechaval=null;
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin PK_PSO_REPORTES.SP_REPORTTEMPZIP(:p_reporttype, :p_fechaval); end;");
        $stmt->bindParam(':p_reporttype', $p_reporttype, \PDO::PARAM_STR);
        $stmt->bindParam(':p_fechaval', $fechaval, \PDO::PARAM_STR|\PDO::PARAM_INPUT_OUTPUT, 4000);
        $stmt->execute();
        return $fechaval;
    }

    private function dataReportListCPE($reportType,$fechaVal,$pag)
    {
        $result = [];
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("begin PK_PSO_REPORTES.SP_REPORTES_LIST_CPE(:p_reporttype, :p_fechaval, :p_pag, :p_cursor); end;");
        $stmt->bindParam(':p_reporttype', $reportType, \PDO::PARAM_STR);
        $stmt->bindParam(':p_fechaval', $fechaVal, \PDO::PARAM_STR);
        $stmt->bindParam(':p_pag', $pag, \PDO::PARAM_INT);
        $stmt->bindParam(':p_cursor', $result, \PDO::PARAM_STMT);
        $stmt->execute();
        
        oci_execute($result, OCI_DEFAULT);
        oci_fetch_all($result, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($result);
        return $array;
    }

    private function dataReportsLoad($reportVal,$groupVal,$CPE,$fechaVal,$ROWTOTAL,$ROWFIELD=0)
    {
        DB::statement("call PK_PSO_REPORTES.SP_REPORTS_LOAD(:reportval, :groupval, :cpe, :fechaval, :rowtotal, :rowfield)", [
            "reportval" => $reportVal,
            "groupval" => $groupVal,
            "cpe" => $CPE,
            "fechaval" => $fechaVal,
            "rowtotal" => $ROWTOTAL,
            "rowfield" => $ROWFIELD
        ]);
    }

    private function dataReporte01($fechaVal,$CPEVAL)
    {
        return $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5614(:p_fechaval, :p_cpeval, :resultado); end;", [
            "p_fechaval" => $fechaVal,
            "p_cpeval" => $CPEVAL,
        ]);
    }

    public function exportReportXlsx($format,$dataGeneral,$data,$down=1,$carpeta="",$ext="xlsx")
	{
		ini_set('memory_limit', '500M');
		// $this->load->library('excel');
		$objPHPExcel = new Spreadsheet();
		$object_writer = null;
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('Registros');

		$fileName = $dataGeneral["reportName"].".".$ext;
		if($down==1){
			// ob_end_clean();
			header('Content-Disposition: attachment;filename="'.$fileName.'"');
			header('Cache-Control: max-age=0');
			header("Pragma: no-cache");
			header("Expires: 0");
			if($ext=='xlsx'){
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			}elseif($ext=='xls'){
				header('Content-Type: application/vnd.ms-excel');
			}
		}
		if($ext=='xlsx'){
			//$object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$object_writer = IOFactorySpread::createWriter($objPHPExcel, 'Xlsx');
		}elseif($ext=='xls'){
			//$object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$object_writer = IOFactorySpread::createWriter($objPHPExcel, 'Xls');
		}
		$this->formats($format,$objPHPExcel,$dataGeneral,$data);
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		if($down==1)
			$object_writer->save('php://output');
		else{
			$carpetas=explode("/", $carpeta);
			// $routeLong="assets/reportes";
			$routeLong="/usr/reportes";
			foreach ($carpetas as $fold) {
				$routeLong.="/".$fold;
				if(!is_dir($routeLong)){
					mkdir($routeLong);
				}
			}
			$object_writer->save("$routeLong/$fileName");
			chmod("$routeLong/$fileName",0777);
		}
	}

    function formats($typeFormat,&$objPHPExcel,$dataGeneral,$data)
	{
		$col_offset = 1;
		switch ($typeFormat) {
			case 1://FORMATO ('REPORT_01','REPORT_07')
				$titleReport=$dataGeneral["reportTitle"];//"REPORTE INTERNET DIARIO";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_offset,1, $titleReport);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow($col_offset,9,1));
				
				$timeInterval = $dataGeneral["fecha"];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_offset,2, $timeInterval);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow($col_offset,1,2));
				$ITMDATAGEN = $dataGeneral["ITM"];
				//Cabecera File INI
				$fields = array(array("NAME"=>"CPE ID:","LOC"=>"0,3","FIELDVAL"=>$ITMDATAGEN["CPE"],"POSTVAL"=>"1,0"),
						array("NAME"=>"UBIGEO:","LOC"=>"3,3","FIELDVAL"=>$ITMDATAGEN["UBIGEO"],"POSTVAL"=>"1,0"),
						array("NAME"=>"PROYECTO:","LOC"=>"7,3","FIELDVAL"=>$ITMDATAGEN["PROYECTO"]??"","POSTVAL"=>"1,0"),
						array("NAME"=>"DEPARTAMENTO:","LOC"=>"0,4","FIELDVAL"=>$ITMDATAGEN["DEPARTAMENTO"],"POSTVAL"=>"1,0"),
						array("NAME"=>"DISTRITO:","LOC"=>"3,4","FIELDVAL"=>$ITMDATAGEN["DISTRITO"],"POSTVAL"=>"1,0"),
						array("NAME"=>"OPERADOR:","LOC"=>"7,4","FIELDVAL"=>$ITMDATAGEN["OPERADOR"]??"","POSTVAL"=>"1,0"),
						array("NAME"=>"PROVINCIA:","LOC"=>"0,5","FIELDVAL"=>$ITMDATAGEN["PROVINCIA"]??"","POSTVAL"=>"1,0"),
						array("NAME"=>"LOCALIDAD:","LOC"=>"3,5","FIELDVAL"=>$ITMDATAGEN["LOCALIDAD"]??"","POSTVAL"=>"1,0"),
						array("NAME"=>"TECNOLOGÍA:","LOC"=>"7,5","FIELDVAL"=>$ITMDATAGEN["TECNOLOGIA"]??"","POSTVAL"=>"1,0"));
				$this->cellsData($fields,$objPHPExcel);
				//Cabecera File FIN

				//Cabecera DataTable INI
				$fields = array(array("NAME"=>"VOLUMEN DE TRAFICO EN KILOBYTES","LOC"=>"2,7","GROUPFLD"=>"7,0","BG"=>"FF0000"),
						array("NAME"=>"ENTRANTE","LOC"=>"2,8","GROUPFLD"=>"3,0","BG"=>"FF0000"),
						array("NAME"=>"SALIENTE","LOC"=>"6,8","GROUPFLD"=>"3,0","BG"=>"FF0000"),
						array("NAME"=>"FECHA","LOC"=>"0,9","BG"=>"FF0000"),
						array("NAME"=>"MINUTOS","LOC"=>"1,9","BG"=>"FF0000"),
						array("NAME"=>"HTTP/HTTPs","LOC"=>"2,9","BG"=>"FF0000"),
						array("NAME"=>"FTP","LOC"=>"3,9","BG"=>"FF0000"),
						array("NAME"=>"SMTP","LOC"=>"4,9","BG"=>"FF0000"),
						array("NAME"=>"OTROS","LOC"=>"5,9","BG"=>"FF0000"),
						array("NAME"=>"HTTP/HTTPs","LOC"=>"6,9","BG"=>"FF0000"),
						array("NAME"=>"FTP","LOC"=>"7,9","BG"=>"FF0000"),
						array("NAME"=>"SMTP","LOC"=>"8,9","BG"=>"FF0000"),
						array("NAME"=>"OTROS","LOC"=>"9,9","BG"=>"FF0000"));
				$this->cellsData($fields,$objPHPExcel);
				//Cabecera DataTable FIN

				foreach (range('A', 'J') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				//Filas de datos
				$ROW=0;
				foreach ($data as $dataRow) {
					$fiedlVal=array(array("VAL"=>$dataRow["DIA"],"LOC"=>"0,10","ROW"=>$ROW),
						array("VAL"=>$dataRow["MINUTOS"],"LOC"=>"1,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["HTTP_ENT"]),"LOC"=>"2,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["FTP_ENT"]),"LOC"=>"3,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["SMTP_ENT"]),"LOC"=>"4,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["OTROS_ENT"]),"LOC"=>"5,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["HTTP_SAL"]),"LOC"=>"6,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["FTP_SAL"]),"LOC"=>"7,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["SMTP_SAL"]),"LOC"=>"8,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["OTROS_SAL"]),"LOC"=>"9,10","ROW"=>$ROW),
					);
					$this->cellsData($fiedlVal,$objPHPExcel);
					$ROW++;
				}
				break;
			case 2://FORMATO ('REPORT_02','REPORT_05','REPORT_08','REPORT_10')
				$titleReport=$dataGeneral["reportTitle"];//"REPORTE MENSUAL DE TRAFICO CURSADO - INTERNET";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_offset,2, $titleReport);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow($col_offset,16,2));
				
				/////////SECCION REPETIDA INICIO ROWS=17
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(19);
				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

				// $timeInterval = "FECHA";
				// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,6+$ROW*17, $timeInterval);
				//Cabecera File INI
				$ROW=0;
				if(count($data)==0)
					$data=array(array("NULL"=>NULL));
				foreach ($data as $rowData) {
					$fields = array(
						array("VAL"=>$dataGeneral["fecha"],"LOC"=>"14,6","GROUPVAL"=>"1,0","ROW"=>$ROW*17),
						array("NAME"=>"CPE ID:","LOC"=>"2,6","FIELDVAL"=>$rowData["CPE"]??"-","POSTVAL"=>"2,0","GROUPVAL"=>'1,0',"ROW"=>$ROW*17),
						array("NAME"=>"UBIGEO:","LOC"=>"7,6","FIELDVAL"=>$rowData["UBIGEO"]??"-","POSTVAL"=>'2,0',"GROUPFLD"=>'1,0',"GROUPVAL"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"DEPARTAMENTO:","LOC"=>"2,8","FIELDVAL"=>$rowData["DEPARTAMENTO"]??"-","POSTVAL"=>'2,0',"GROUPVAL"=>"1,0","ROW"=>$ROW*17),
						array("NAME"=>"DISTRITO:","LOC"=>"7,8","FIELDVAL"=>$rowData["DISTRITO"]??"-","POSTVAL"=>'2,0',"GROUPFLD"=>'1,0',"GROUPVAL"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"PROVINCIA:","LOC"=>"2,10","FIELDVAL"=>$rowData["PROVINCIA"]??"-","POSTVAL"=>'2,0',"GROUPVAL"=>"1,0","ROW"=>$ROW*17),
						array("NAME"=>"LOCALIDAD:","LOC"=>"7,10","FIELDVAL"=>$rowData["LOCALIDAD"]??"-","POSTVAL"=>'2,0',"GROUPFLD"=>'1,0',"GROUPVAL"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"Volumen de tráfico (Kilobytes)","LOC"=>'8,13',"GROUPFLD"=>"3,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"PROTOCOLOS","LOC"=>'5,14',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"ENTRANTE","LOC"=>'8,14',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"SALIENTE","LOC"=>'11,14',"ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"HTTP","LOC"=>'5,15',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"ALIGN"=>"CENTER"),
						array("NAME"=>"FTP","LOC"=>'5,16',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"ALIGN"=>"CENTER"),
						array("NAME"=>"SMTP","LOC"=>'5,17',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"ALIGN"=>"CENTER"),
						array("NAME"=>"OTROS","LOC"=>'5,18',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"ALIGN"=>"CENTER"),
						array("NAME"=>"TOTAL","LOC"=>'5,19',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"ALIGN"=>"CENTER"),
						array("VAL"=>$this->isEmpty($rowData["HTTP_ENT"]??NULL),"LOC"=>'8,15',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["HTTP_SAL"]??NULL),"LOC"=>'11,15',"ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["FTP_ENT"]??NULL),"LOC"=>'8,16',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["FTP_SAL"]??NULL),"LOC"=>'11,16',"ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["SMTP_ENT"]??NULL),"LOC"=>'8,17',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["SMTP_SAL"]??NULL),"LOC"=>'11,17',"ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["OTROS_ENT"]??NULL),"LOC"=>'8,18',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$this->isEmpty($rowData["OTROS_SAL"]??NULL),"LOC"=>'11,18',"ROW"=>$ROW*17),
						array("VAL"=>$rowData["TOTAL_ENT"]??$this->sumToFloat(array($rowData["HTTP_ENT"]??0,
							$rowData["FTP_ENT"]??0,$rowData["SMTP_ENT"]??0,$rowData["OTROS_ENT"]??0)),"LOC"=>'8,19',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$rowData["TOTAL_SAL"]??$this->sumToFloat(array($rowData["HTTP_SAL"]??0,
							$rowData["FTP_SAL"]??0,$rowData["SMTP_SAL"]??0,$rowData["OTROS_SAL"]??0)),"LOC"=>'11,19',"ROW"=>$ROW*17),
					);
					$this->cellsData($fields,$objPHPExcel);
					$ROW++;
				}
				//Cabecera DataTable FIN

				/////////SECCION REPETIDA FIN ROWS=17
				break;
			case 3://FORMATO ('REPORT_04')
				$titleReport=$dataGeneral["reportTitle"];//"REPORTE MENSUAL DE LOS SITIOS MAS VISITADOS (TOP 20)";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$col_offset,2, $titleReport);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow(3+$col_offset,13,2,4));

				foreach (range('N', 'O') as $letra) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(17);
				}

				//Cabecera File INI
				$ROW=0;
				$fldCount=0;
				$CPECONST="";
				$ROWCONST=0;
				$rowRef=1;
				foreach ($data as $key => $dataRow) {
					if($CPECONST!=$dataRow["CPE"]){
						if($rowRef<20&&$CPECONST!=''){
							for ($iRow=($rowRef); $iRow <=20 ; $iRow++) { 
								$fields=array(array("VAL"=>"$iRow","LOC"=>"3,14","ROW"=>($ROW-1)*11+$fldCount),
									array("VAL"=>CHR(45),"LOC"=>"4,14","GROUPFLD"=>"8,0","ROW"=>($ROW-1)*11+$fldCount),
									array("VAL"=>CHR(45),"LOC"=>"13,14","GROUPFLD"=>"1,0","ROW"=>($ROW-1)*11+$fldCount),
								);
								$this->cellsData($fields,$objPHPExcel);
								$fldCount++;
							}
						}
						$rowRef=1;
						$CPECONST=$dataRow["CPE"];
						$fields = array(array("VAL"=>$dataGeneral["fecha"],"LOC"=>"12,6","GROUPFLD"=>"1,0","ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"CPE ID:","LOC"=>'3,6',"FIELDVAL"=>$dataRow["CPE"],"GROUPFLD"=>"1,0","POSTVAL"=>'2,0',"ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"UBIGEO:","LOC"=>'8,6',"FIELDVAL"=>$dataRow["UBIGEO"],"POSTVAL"=>'2,0',"ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"DEPARTAMENTO:","LOC"=>'3,8',"FIELDVAL"=>$dataRow["DEPARTAMENTO"],"GROUPFLD"=>"1,0","POSTVAL"=>'2,0',"ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"DISTRITO:","LOC"=>'8,8',"FIELDVAL"=>$dataRow["DISTRITO"],"POSTVAL"=>'2,0',"ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"PROVINCIA:","LOC"=>'3,10',"FIELDVAL"=>$dataRow["PROVINCIA"],"GROUPFLD"=>"1,0","POSTVAL"=>'2,0',"ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"LOCALIDAD:","LOC"=>'8,10',"FIELDVAL"=>$dataRow["LOCALIDAD"],"POSTVAL"=>'2,0',"GROUPVAL"=>'3,0',"ROW"=>$ROW*11+$fldCount),
							array("NAME"=>"No","LOC"=>'3,13',"ROW"=>$ROW*11+$fldCount,"BG"=>"FF0000"),
							array("NAME"=>"URL","LOC"=>'4,13',"GROUPFLD"=>"8,0","ROW"=>$ROW*11+$fldCount,"BG"=>"FF0000"),
							array("NAME"=>"Número de Visitas Totales por Mes","LOC"=>'13,13',"GROUPFLD"=>"1,0","ROW"=>$ROW*11+$fldCount,"BG"=>"FF0000"),
						);
						$this->cellsData($fields,$objPHPExcel);
						$ROWCONST=14+$ROW*11;
						$ROW++;
					}
					$fields=array(array("VAL"=>$dataRow["N_ITEM"],"LOC"=>"3,14","ROW"=>($ROW-1)*11+$fldCount),
						array("VAL"=>$this->isEmpty($dataRow["URL"]),"LOC"=>"4,14","GROUPFLD"=>"8,0","ROW"=>($ROW-1)*11+$fldCount),
						array("VAL"=>$this->isEmpty($dataRow["N_CONEXIONES"]),"LOC"=>"13,14","GROUPFLD"=>"1,0","ROW"=>($ROW-1)*11+$fldCount),
					);
					$this->cellsData($fields,$objPHPExcel);
					$fldCount++;
					$rowRef++;
				}
				if($rowRef<20&&$CPECONST!=''){
					for ($iRow=($rowRef); $iRow <=20 ; $iRow++) { 
						$fields=array(array("VAL"=>"$iRow","LOC"=>"3,14","ROW"=>($ROW-1)*11+$fldCount),
							array("VAL"=>CHR(45),"LOC"=>"4,14","GROUPFLD"=>"8,0","ROW"=>($ROW-1)*11+$fldCount),
							array("VAL"=>CHR(45),"LOC"=>"13,14","GROUPFLD"=>"1,0","ROW"=>($ROW-1)*11+$fldCount),
						);
						$this->cellsData($fields,$objPHPExcel);
						$fldCount++;
					}
				}
				//Cabecera File FIN

				//Cabecera DataTable INI
				//Cabecera DataTable FIN
				break;
			case 4://FORMATO ('REPORT_06')
				$titleReport=$dataGeneral["reportTitle"];//"REPORTE MENSUAL DE SESIONES DE INTERNET";
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(19);
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(19);
				$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(19);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1+$col_offset,2, $titleReport);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow(1+$col_offset,21,2));
				$ROW=0;
				foreach ($data as $dataRow) {
					//Cabecera File INI
					$fields = array(array("VAL"=>$dataGeneral["fecha"],"LOC"=>"12,6","GROUPFLD"=>"1,0","ROW"=>$ROW*17),
						array("NAME"=>"CPE ID:","LOC"=>"2,6","FIELDVAL"=>$dataRow["CPE"],"GROUPFLD"=>'1,0',"POSTVAL"=>'1,0',"GROUPVAL"=>'2,0',"ROW"=>$ROW*17),
						array("NAME"=>"UBIGEO:","LOC"=>"9,6","FIELDVAL"=>$dataRow["UBIGEO"],"GROUPFLD"=>'1,0',"POSTVAL"=>'1,0',"GROUPVAL"=>'2,0',"ROW"=>$ROW*17),
						array("NAME"=>"DEPARTAMENTO:","LOC"=>"2,8","FIELDVAL"=>$dataRow["DEPARTAMENTO"],"GROUPFLD"=>'1,0',"POSTVAL"=>'1,0',"GROUPVAL"=>'2,0',"ROW"=>$ROW*17),
						array("NAME"=>"DISTRITO:","LOC"=>"9,8","FIELDVAL"=>$dataRow["DISTRITO"],"GROUPFLD"=>'1,0',"POSTVAL"=>'1,0',"GROUPVAL"=>'2,0',"ROW"=>$ROW*17),
						array("NAME"=>"PROVINCIA:","LOC"=>"2,10","FIELDVAL"=>$dataRow["PROVINCIA"],"GROUPFLD"=>'1,0',"POSTVAL"=>'1,0',"GROUPVAL"=>'2,0',"ROW"=>$ROW*17),
						array("NAME"=>"LOCALIDAD:","LOC"=>"9,10","FIELDVAL"=>$dataRow["LOCALIDAD"],"GROUPFLD"=>'1,0',"POSTVAL"=>'1,0',"GROUPVAL"=>'2,0',"ROW"=>$ROW*17),
						array("NAME"=>"ENTRANTE","LOC"=>'6,13',"GROUPFLD"=>"3,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"SALIENTE","LOC"=>'10,13',"GROUPFLD"=>"3,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"TOTAL","LOC"=>'14,13',"GROUPFLD"=>"3,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"PROTOCOLO","LOC"=>'3,14',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"Cantidad de sesiones","LOC"=>'6,14',"ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"Duración de sesiones (hora)","LOC"=>'7,14',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"Cantidad de sesiones","LOC"=>'10,14',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"Duración de sesiones (hora)","LOC"=>'13,14',"ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"Cantidad de sesiones","LOC"=>'14,14',"GROUPFLD"=>"2,0","ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"Duración de sesiones (hora)","LOC"=>'17,14',"ROW"=>$ROW*17,"BG"=>"FF0000"),
						array("NAME"=>"HTTP","LOC"=>'3,15',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"FTP","LOC"=>'3,16',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"SMTP","LOC"=>'3,17',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"OTROS","LOC"=>'3,18',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("NAME"=>"TOTAL","LOC"=>'3,19',"GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["HTTP_CON_ENT"],"LOC"=>"6,15","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["HTTP_DUR_ENT"],"LOC"=>"7,15","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["HTTP_CON_SAL"],"LOC"=>"10,15","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["HTTP_DUR_SAL"],"LOC"=>"13,15","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["HTTP_CON_TOTAL"],"LOC"=>"14,15","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["HTTP_DUR_TOTAL"],"LOC"=>"17,15","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["FTP_CON_ENT"],"LOC"=>"6,16","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["FTP_DUR_ENT"],"LOC"=>"7,16","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["FTP_CON_SAL"],"LOC"=>"10,16","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["FTP_DUR_SAL"],"LOC"=>"13,16","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["FTP_CON_TOTAL"],"LOC"=>"14,16","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["FTP_DUR_TOTAL"],"LOC"=>"17,16","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["SMTP_CON_ENT"],"LOC"=>"6,17","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["SMTP_DUR_ENT"],"LOC"=>"7,17","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["SMTP_CON_SAL"],"LOC"=>"10,17","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["SMTP_DUR_SAL"],"LOC"=>"13,17","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["SMTP_CON_TOTAL"],"LOC"=>"14,17","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["SMTP_DUR_TOTAL"],"LOC"=>"17,17","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["OTROS_CON_ENT"],"LOC"=>"6,18","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["OTROS_DUR_ENT"],"LOC"=>"7,18","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["OTROS_CON_SAL"],"LOC"=>"10,18","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["OTROS_DUR_SAL"],"LOC"=>"13,18","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["OTROS_CON_TOTAL"],"LOC"=>"14,18","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["OTROS_DUR_TOTAL"],"LOC"=>"17,18","ROW"=>$ROW*17),
						// array("VAL"=>$dataRow["TOTAL_CON_ENT"]??"","LOC"=>"6,19","ROW"=>$ROW*17),
						// array("VAL"=>$dataRow["TOTAL_DUR_ENT"]??"","LOC"=>"7,19","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						// array("VAL"=>$dataRow["TOTAL_CON_SAL"]??"","LOC"=>"10,19","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						// array("VAL"=>$dataRow["TOTAL_DUR_SAL"]??"","LOC"=>"13,19","ROW"=>$ROW*17),
						// array("VAL"=>$dataRow["TOTAL_CON_TOTAL"]??"","LOC"=>"14,19","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						// array("VAL"=>$dataRow["TOTAL_DUR_TOTAL"]??"","LOC"=>"17,19","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["TOTAL_CON_ENT"]??$this->sumToFloat([$dataRow["HTTP_CON_ENT"]??0,$dataRow["FTP_CON_ENT"]??0,
							$dataRow["SMTP_CON_ENT"]??0,$dataRow["OTROS_CON_ENT"]??0]),"LOC"=>"6,19","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["TOTAL_DUR_ENT"]??$this->sumToFloat([$dataRow["HTTP_DUR_ENT"]??0,$dataRow["FTP_DUR_ENT"]??0,
							$dataRow["SMTP_DUR_ENT"]??0,$dataRow["OTROS_DUR_ENT"]??0]),"LOC"=>"7,19","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["TOTAL_CON_SAL"]??$this->sumToFloat([$dataRow["HTTP_CON_SAL"]??0,$dataRow["FTP_CON_SAL"]??0,
							$dataRow["SMTP_CON_SAL"]??0,$dataRow["OTROS_CON_SAL"]??0]),"LOC"=>"10,19","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["TOTAL_DUR_SAL"]??$this->sumToFloat([$dataRow["HTTP_DUR_SAL"]??0,$dataRow["FTP_DUR_SAL"]??0,
							$dataRow["SMTP_DUR_SAL"]??0,$dataRow["OTROS_DUR_SAL"]??0]),"LOC"=>"13,19","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["TOTAL_CON_TOTAL"]??$this->sumToFloat([$dataRow["HTTP_CON_TOTAL"]??0,$dataRow["FTP_CON_TOTAL"]??0,
							$dataRow["SMTP_CON_TOTAL"]??0,$dataRow["OTROS_CON_TOTAL"]??0]),"LOC"=>"14,19","GROUPFLD"=>"2,0","ROW"=>$ROW*17),
						array("VAL"=>$dataRow["TOTAL_DUR_TOTAL"]??$this->sumToFloat([$dataRow["HTTP_DUR_TOTAL"]??0,$dataRow["FTP_DUR_TOTAL"]??0,
							$dataRow["SMTP_DUR_TOTAL"]??0,$dataRow["OTROS_DUR_TOTAL"]??0]),"LOC"=>"17,19","ROW"=>$ROW*17)
					);
					$this->cellsData($fields,$objPHPExcel);
					$ROW++;
				}
				//Cabecera DataTable FIN
				break;
			case 5://FORMATO ('REPORT_15'--1)$titleReport="REPORTE MENSUAL DE SESIONES DE INTERNET";
				//Cabecera DataTable INI
				$fields = array(array("NAME"=>"ITEM","LOC"=>'0,1',"BG"=>"FF0000"),
					array("NAME"=>"CÓDIGO DE REPORTE DE AVERIA","LOC"=>'1,1',"BG"=>"FF0000"),
					array("NAME"=>"FECHA","LOC"=>'2,1',"BG"=>"FF0000"),
					array("NAME"=>"HORA","LOC"=>'3,1',"BG"=>"FF0000"),
					array("NAME"=>"DESCRIPCIÓN DE LA AVERIA","LOC"=>'4,1',"BG"=>"FF0000"),
					array("NAME"=>"DATOS DE LA PERSONA QUE REPORTÓ LA AVERIA","LOC"=>'5,1',"BG"=>"FF0000"),
					array("NAME"=>"FECHA EN LA QUE SE SOLUCIONÓ LA AVERIA","LOC"=>'6,1',"BG"=>"FF0000"),
					array("NAME"=>"HORA EN LA QUE SE SOLUCIONÓ LA AVERIA","LOC"=>'7,1',"BG"=>"FF0000"),
					array("NAME"=>"MEDIO DE REPORTE DE AVERIA (TELF/EMAIL/PRESENCIAL/ESCRITO)","LOC"=>'8,1',"BG"=>"FF0000"),
					array("NAME"=>"DETALLE AVERIA","LOC"=>'9,1',"BG"=>"FF0000"),
					array("NAME"=>"CODIGO CPE","LOC"=>'10,1',"BG"=>"FF0000"),
					array("NAME"=>"LUGAR CPE","LOC"=>'11,1',"BG"=>"FF0000")
				);
				$this->cellsData($fields,$objPHPExcel);
				$ROW=0;
				foreach ($data as $dataRow) {
					$fields = array(array("VAL"=>$dataRow["N_ITEM"],"LOC"=>"0,2","ROW"=>$ROW,"BG"=>"CDCDCD","COLOR"=>"000000"),
						array("VAL"=>$dataRow["CODIGO_AVERIA"],"LOC"=>"1,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["FECHA_AVERIA"],"LOC"=>"2,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_AVERIA"],"LOC"=>"3,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["DESCRIPCION"],"LOC"=>"4,2","ROW"=>$ROW,"BG"=>"CDCDCD","COLOR"=>"000000"),
						array("VAL"=>$dataRow["DATOS_PERSONA"],"LOC"=>"5,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["FECHA_SOLUCION"],"LOC"=>"6,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_SOLUCION"],"LOC"=>"7,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["MEDIO_REPORTE_AVERIA"],"LOC"=>"8,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["DETALLE_AVERIA"],"LOC"=>"9,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["CODIGO_CPE"],"LOC"=>"10,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["LUGAR_CPE"],"LOC"=>"11,2","ROW"=>$ROW)
						);
					$this->cellsData($fields,$objPHPExcel);
					$ROW++;
				}
				//Cabecera DataTable FIN
				break;
			case 6://FORMATO ('REPORT_15'--2)
				foreach (range('D', 'L') as $letra) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(15);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);
				$anioRef=explode("-",$dataGeneral["fechaVal"]);
				$anioRef=$anioRef[0];
				$fields = array(array("NAME"=>"CALIDAD DE LOS SERVICIOS DE TELECOMUNICACIONES LIMA","LOC"=>'0,1',"GROUPFLD"=>"2,0"),
					array("NAME"=>"SERVICIO:","LOC"=>'0,3'),
					array("NAME"=>"ACCESO INTERNET","LOC"=>'1,3'),
					array("NAME"=>"AÑO:","LOC"=>'0,4',"FIELDVAL"=>$anioRef,"POSTVAL"=>"1,0"),
					array("NAME"=>"INDICADOR","LOC"=>'0,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"FORMULA","LOC"=>'1,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"META","LOC"=>'2,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Enero","LOC"=>'3,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Febrero","LOC"=>'4,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Marzo","LOC"=>'5,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Abril","LOC"=>'6,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Mayo","LOC"=>'7,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Junio","LOC"=>'8,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Julio","LOC"=>'9,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Agosto","LOC"=>'10,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Setiembre","LOC"=>'11,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Octubre","LOC"=>'12,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Noviembre","LOC"=>'13,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Diciembre","LOC"=>'14,5',"BG"=>"FF0000","COLOR"=>"FFFFFF"),
					array("NAME"=>"Tasa de incidencia de fallas","LOC"=>'0,6'),
					array("NAME"=>"Total de fallas o averías reportadas en el mes y reparadas después de 24 horas/ Total de conexiones operativas durante el mes",
						"LOC"=>'1,6',"ALIGN"=>"VER_JUSTIFY"),
					array("NAME"=>"<10%","LOC"=>'2,6'),
					array("NAME"=>"Incidencias > 24 Horas","LOC"=>'0,7'),
					array("VAL"=>CHR(45),"LOC"=>'1,7'),
					array("VAL"=>CHR(45),"LOC"=>'2,7'),
					array("NAME"=>"Total Conexiones","LOC"=>'0,8'),
					array("VAL"=>CHR(45),"LOC"=>'1,8'),
					array("VAL"=>CHR(45),"LOC"=>'1,8'),
				);
				$this->cellsData($fields,$objPHPExcel);
				foreach ($data as $dataRow) {
					$rowRef = explode("-", $dataRow["MES"]);
					$rowRef = intval($rowRef[1])+2;
					$fields=array(array("VAL"=>$dataRow["TIA"],"LOC"=>$rowRef.",6"),
						array("VAL"=>$dataRow["TOTAL_AVERIAS_24H"],"LOC"=>$rowRef.",7"),
						array("VAL"=>$dataRow["TOTAL_CONEXIONES"],"LOC"=>$rowRef.",8"));
					$this->cellsData($fields,$objPHPExcel);
				}
				break;
			case 7://FORMATO ('REPORT_16_1')
				$titleReport=$dataGeneral["reportTitle"];//"REPORTE INTERNET DIARIO";
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_offset,1, $titleReport);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow($col_offset,9,1));

				foreach (range('A', 'I') as $letra) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(15);
				}
				
				$timeInterval = $dataGeneral["fecha"];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7+$col_offset,2, $timeInterval);
				$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow(7+$col_offset,8,2));
				$ITMDATAGEN = $dataGeneral["ITM"];
				//Cabecera File INI
				$fields = array(array("NAME"=>"ENLACE:","LOC"=>"0,2","FIELDVAL"=>$ITMDATAGEN["ENLACE_TX"],"POSTVAL"=>"1,0"),
						array("NAME"=>"NODO:","LOC"=>"0,3","FIELDVAL"=>$ITMDATAGEN["NODOTX"],"POSTVAL"=>"1,0"),
						array("NAME"=>"UBIGEO:","LOC"=>"3,3","FIELDVAL"=>$ITMDATAGEN["UBIGEO_DIST"],"POSTVAL"=>"1,0"),
						array("NAME"=>"DEPARTAMENTO:","LOC"=>"0,4","FIELDVAL"=>$ITMDATAGEN["DEPARTAMENTO"],"POSTVAL"=>"1,0"),
						array("NAME"=>"DISTRITO:","LOC"=>"3,4","FIELDVAL"=>$ITMDATAGEN["DISTRITO"],"POSTVAL"=>"1,0"),
						array("NAME"=>"PROVINCIA:","LOC"=>"0,5","FIELDVAL"=>$ITMDATAGEN["PROVINCIA"]??"","POSTVAL"=>"1,0"),
						array("NAME"=>"LOCALIDAD:","LOC"=>"3,5","FIELDVAL"=>$ITMDATAGEN["LOCALIDAD"]??"-","POSTVAL"=>"1,0"),
				);
				$this->cellsData($fields,$objPHPExcel);
				//Cabecera File FIN

				//Cabecera DataTable INI
				$fields = array(array("NAME"=>"VOLUMEN DE TRAFICO EN KILOBYTES","LOC"=>"2,7","GROUPFLD"=>"7,0","BG"=>"FF0000"),
						array("NAME"=>"ENTRANTE","LOC"=>"2,8","GROUPFLD"=>"3,0","BG"=>"FF0000"),
						array("NAME"=>"SALIENTE","LOC"=>"6,8","GROUPFLD"=>"3,0","BG"=>"FF0000"),
						array("NAME"=>"FECHA","LOC"=>"0,9","BG"=>"FF0000"),
						array("NAME"=>"MINUTOS","LOC"=>"1,9","BG"=>"FF0000"),
						array("NAME"=>"TOTAL","LOC"=>"2,9","BG"=>"FF0000"),
						array("NAME"=>"H323","LOC"=>"3,9","BG"=>"FF0000"),
						array("NAME"=>"SIP","LOC"=>"4,9","BG"=>"FF0000"),
						array("NAME"=>"DATOS","LOC"=>"5,9","BG"=>"FF0000"),
						array("NAME"=>"TOTAL","LOC"=>"6,9","BG"=>"FF0000"),
						array("NAME"=>"H323","LOC"=>"7,9","BG"=>"FF0000"),
						array("NAME"=>"SIP","LOC"=>"8,9","BG"=>"FF0000"),
						array("NAME"=>"DATOS","LOC"=>"9,9","BG"=>"FF0000"));
				$this->cellsData($fields,$objPHPExcel);
				//Cabecera DataTable FIN

				foreach (range('A', 'J') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				//Filas de datos
				$ROW=0;
				foreach ($data as $dataRow) {
					$fiedlVal=array(array("VAL"=>$dataRow["DIA"],"LOC"=>"0,10","ROW"=>$ROW),
						array("VAL"=>$dataRow["MINUTOS"],"LOC"=>"1,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["TOTAL_ENT"]),"LOC"=>"2,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["H323_ENT"]),"LOC"=>"3,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["SIP_ENT"]),"LOC"=>"4,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["DATOS_ENT"]),"LOC"=>"5,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["TOTAL_SAL"]),"LOC"=>"6,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["H323_SAL"]),"LOC"=>"7,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["SIP_SAL"]),"LOC"=>"8,10","ROW"=>$ROW),
						array("VAL"=>$this->isEmpty($dataRow["DATOS_SAL"]),"LOC"=>"9,10","ROW"=>$ROW),
					);
					$this->cellsData($fiedlVal,$objPHPExcel);
					$ROW++;
				}
				break;
			case 8://FORMATO ('REPORT_17')
				foreach (range('B', 'F') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
				$fields=array(array("NAME"=>"ID_CPE","LOC"=>"0,1","BG"=>"FF0000"),
					array("NAME"=>"UBIGEO","LOC"=>"1,1","BG"=>"FF0000"),
					array("NAME"=>"DEPARTAMENTO","LOC"=>"2,1","BG"=>"FF0000"),
					array("NAME"=>"PROVINCIA","LOC"=>"3,1","BG"=>"FF0000"),
					array("NAME"=>"DISTRITO","LOC"=>"4,1","BG"=>"FF0000"),
					array("NAME"=>"LOCALIDAD","LOC"=>"5,1","BG"=>"FF0000"),
					array("NAME"=>"AÑO","LOC"=>"6,1","BG"=>"FF0000"),
					array("NAME"=>"MES","LOC"=>"7,1","BG"=>"FF0000"),
					array("NAME"=>"DIA","LOC"=>"8,1","BG"=>"FF0000"),
					array("NAME"=>"HORA_MAX_CARGA","LOC"=>"9,1","BG"=>"FF0000"),
					array("NAME"=>"LATENCIA PROM. DIA","LOC"=>"10,1","BG"=>"FF0000"),
					array("NAME"=>"LATENCIA PROM. DIA","LOC"=>"10,1","BG"=>"FF0000"),
					array("NAME"=>"Nota: Los valores corresponden al promedio de mediciones en la hora de mayor carga",
						"LOC"=>"12,1"),
				);
				$this->cellsData($fields,$objPHPExcel);
				$ROW=0;
				foreach ($data as $dataRow) {
					$fields = array(
						array("VAL"=>$dataRow["CPE"],"LOC"=>"0,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["UBIGEO"],"LOC"=>'1,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DEPARTAMENTO"],"LOC"=>'2,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PROVINCIA"],"LOC"=>'3,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DISTRITO"],"LOC"=>'4,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["LOCALIDAD"],"LOC"=>'5,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["ANIO"],"LOC"=>'6,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["MES"],"LOC"=>'7,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DIA"],"LOC"=>'8,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_MAX_CARGA"],"LOC"=>'9,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["LATENCIA"],"LOC"=>'10,2',"ROW"=>$ROW),
					);
					$ROW++;
					$this->cellsData($fields,$objPHPExcel);
				}
				break;
			case 9://FORMATO ('REPORT_18')
				foreach (range('B', 'F') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
				$fields=array(array("NAME"=>"ID_CPE","LOC"=>"0,1","BG"=>"FF0000"),
					array("NAME"=>"UBIGEO","LOC"=>"1,1","BG"=>"FF0000"),
					array("NAME"=>"DEPARTAMENTO","LOC"=>"2,1","BG"=>"FF0000"),
					array("NAME"=>"PROVINCIA","LOC"=>"3,1","BG"=>"FF0000"),
					array("NAME"=>"DISTRITO","LOC"=>"4,1","BG"=>"FF0000"),
					array("NAME"=>"LOCALIDAD","LOC"=>"5,1","BG"=>"FF0000"),
					array("NAME"=>"AÑO","LOC"=>"6,1","BG"=>"FF0000"),
					array("NAME"=>"MES","LOC"=>"7,1","BG"=>"FF0000"),
					array("NAME"=>"DIA","LOC"=>"8,1","BG"=>"FF0000"),
					array("NAME"=>"HORA_MAX_CARGA","LOC"=>"9,1","BG"=>"FF0000"),
					array("NAME"=>"% PERDIDA PAQUETES PROM. DIA","LOC"=>"10,1","BG"=>"FF0000"),
					array("NAME"=>"Nota: Los valores corresponden al promedio de mediciones en la hora de mayor carga",
						"LOC"=>"12,1"),
				);
				$this->cellsData($fields,$objPHPExcel);
				$ROW=0;
				foreach ($data as $dataRow) {
					$fields = array(
						array("VAL"=>$dataRow["CPE"],"LOC"=>"0,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["UBIGEO"],"LOC"=>'1,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DEPARTAMENTO"],"LOC"=>'2,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PROVINCIA"],"LOC"=>'3,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DISTRITO"],"LOC"=>'4,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["LOCALIDAD"],"LOC"=>'5,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["ANIO"],"LOC"=>'6,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["MES"],"LOC"=>'7,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DIA"],"LOC"=>'8,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_MAX_CARGA"],"LOC"=>'9,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PACKET_LOSS"],"LOC"=>'10,2',"ROW"=>$ROW),
					);
					$ROW++;
					$this->cellsData($fields,$objPHPExcel);
				}
				break;
			case 10://FORMATO ('REPORT_19_1')
				foreach (range('B', 'F') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
				$fields=array(array("NAME"=>"ID_CPE","LOC"=>"0,1","BG"=>"FF0000"),
					array("NAME"=>"UBIGEO","LOC"=>"1,1","BG"=>"FF0000"),
					array("NAME"=>"DEPARTAMENTO","LOC"=>"2,1","BG"=>"FF0000"),
					array("NAME"=>"PROVINCIA","LOC"=>"3,1","BG"=>"FF0000"),
					array("NAME"=>"DISTRITO","LOC"=>"4,1","BG"=>"FF0000"),
					array("NAME"=>"LOCALIDAD","LOC"=>"5,1","BG"=>"FF0000"),
					array("NAME"=>"AÑO","LOC"=>"6,1","BG"=>"FF0000"),
					array("NAME"=>"MES","LOC"=>"7,1","BG"=>"FF0000"),
					array("NAME"=>"DIA","LOC"=>"8,1","BG"=>"FF0000"),
					array("NAME"=>"HORA_MAX_CARGA","LOC"=>"9,1","BG"=>"FF0000"),
					array("NAME"=>"VELOCIDAD_BAJADA_PROM","LOC"=>"10,1","BG"=>"FF0000"),
					array("NAME"=>"Nota: Los valores corresponden al promedio de mediciones en la hora de mayor carga",
						"LOC"=>"12,1"),
				);
				$this->cellsData($fields,$objPHPExcel);
				$ROW=0;
				foreach ($data as $dataRow) {
					$fields = array(
						array("VAL"=>$dataRow["CPE"],"LOC"=>"0,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["UBIGEO"],"LOC"=>'1,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DEPARTAMENTO"],"LOC"=>'2,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PROVINCIA"],"LOC"=>'3,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DISTRITO"],"LOC"=>'4,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["LOCALIDAD"],"LOC"=>'5,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["ANIO"],"LOC"=>'6,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["MES"],"LOC"=>'7,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DIA"],"LOC"=>'8,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_MAX_CARGA"],"LOC"=>'9,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["VELOCIDAD_DL_KBPS"],"LOC"=>'10,2',"ROW"=>$ROW),
					);
					$ROW++;
					$this->cellsData($fields,$objPHPExcel);
				}
				break;
			case 11://FORMATO ('REPORT_19_2')
				foreach (range('B', 'F') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
				$fields=array(array("NAME"=>"ID_CPE","LOC"=>"0,1","BG"=>"FF0000"),
					array("NAME"=>"UBIGEO","LOC"=>"1,1","BG"=>"FF0000"),
					array("NAME"=>"DEPARTAMENTO","LOC"=>"2,1","BG"=>"FF0000"),
					array("NAME"=>"PROVINCIA","LOC"=>"3,1","BG"=>"FF0000"),
					array("NAME"=>"DISTRITO","LOC"=>"4,1","BG"=>"FF0000"),
					array("NAME"=>"LOCALIDAD","LOC"=>"5,1","BG"=>"FF0000"),
					array("NAME"=>"AÑO","LOC"=>"6,1","BG"=>"FF0000"),
					array("NAME"=>"MES","LOC"=>"7,1","BG"=>"FF0000"),
					array("NAME"=>"DIA","LOC"=>"8,1","BG"=>"FF0000"),
					array("NAME"=>"HORA_MAX_CARGA","LOC"=>"9,1","BG"=>"FF0000"),
					array("NAME"=>"VELOCIDAD_SUBIDA_PROM","LOC"=>"10,1","BG"=>"FF0000"),
					array("NAME"=>"Nota: Los valores corresponden al promedio de mediciones en la hora de mayor carga",
						"LOC"=>"12,1"),
				);
				$this->cellsData($fields,$objPHPExcel);
				$ROW=0;
				foreach ($data as $dataRow) {
					$fields = array(
						array("VAL"=>$dataRow["CPE"],"LOC"=>"0,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["UBIGEO"],"LOC"=>'1,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DEPARTAMENTO"],"LOC"=>'2,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PROVINCIA"],"LOC"=>'3,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DISTRITO"],"LOC"=>'4,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["LOCALIDAD"],"LOC"=>'5,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["ANIO"],"LOC"=>'6,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["MES"],"LOC"=>'7,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DIA"],"LOC"=>'8,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_MAX_CARGA"],"LOC"=>'9,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["VELOCIDAD_UL_KBPS"],"LOC"=>'10,2',"ROW"=>$ROW),
					);
					$ROW++;
					$this->cellsData($fields,$objPHPExcel);
				}
				break;
			case 12://FORMATO ('REPORT_20')
				foreach (range('B', 'F') as $letra) {            
					$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(19);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
				$fields=array(array("NAME"=>"ID_CPE","LOC"=>"0,1","BG"=>"FF0000"),
					array("NAME"=>"UBIGEO","LOC"=>"1,1","BG"=>"FF0000"),
					array("NAME"=>"DEPARTAMENTO","LOC"=>"2,1","BG"=>"FF0000"),
					array("NAME"=>"PROVINCIA","LOC"=>"3,1","BG"=>"FF0000"),
					array("NAME"=>"DISTRITO","LOC"=>"4,1","BG"=>"FF0000"),
					array("NAME"=>"LOCALIDAD","LOC"=>"5,1","BG"=>"FF0000"),
					array("NAME"=>"AÑO","LOC"=>"6,1","BG"=>"FF0000"),
					array("NAME"=>"MES","LOC"=>"7,1","BG"=>"FF0000"),
					array("NAME"=>"DIA","LOC"=>"8,1","BG"=>"FF0000"),
					array("NAME"=>"HORA_MAX_CARGA","LOC"=>"9,1","BG"=>"FF0000"),
					array("NAME"=>"BER PROM. DIA","LOC"=>"10,1","BG"=>"FF0000"),
					array("NAME"=>"Nota: Los valores corresponden al promedio de mediciones en la hora de mayor carga",
						"LOC"=>"12,1"),
				);
				$this->cellsData($fields,$objPHPExcel);
				$ROW=0;
				foreach ($data as $dataRow) {
					$fields = array(
						array("VAL"=>$dataRow["CPE"],"LOC"=>"0,2","ROW"=>$ROW),
						array("VAL"=>$dataRow["UBIGEO"],"LOC"=>'1,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DEPARTAMENTO"],"LOC"=>'2,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PROVINCIA"],"LOC"=>'3,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DISTRITO"],"LOC"=>'4,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["LOCALIDAD"],"LOC"=>'5,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["ANIO"],"LOC"=>'6,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["MES"],"LOC"=>'7,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["DIA"],"LOC"=>'8,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["HORA_MAX_CARGA"],"LOC"=>'9,2',"ROW"=>$ROW),
						array("VAL"=>$dataRow["PER"],"LOC"=>'10,2',"ROW"=>$ROW),
					);
					$ROW++;
					$this->cellsData($fields,$objPHPExcel);
				}
				break;
		}
	}

    function cellsToMergeByColsRow($start = -1, $end = -1, $row = -1,$rowEnd = -1){
		$merge = 'A1:A1';
		if ($rowEnd<0) {
			$rowEnd=$row;
		}
		//if($start>=0 && $end>=0 && $row>=0){
        $input = "{$start}-{$end}:{$row}-{$rowEnd}";
		if($start>0 && $end>0 && $row>=0){
			//$start = PHPExcel_Cell::stringFromColumnIndex($start);
			//$end = PHPExcel_Cell::stringFromColumnIndex($end);
			$start = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($start);
			$end = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($end);
			$merge = "$start$row:$end$rowEnd";
		}
        $this->colMerged[] = "({$this->inputKey}) {$input} => {$merge}";
        $this->inputKey = "";
		return $merge;
	}

    function cellsData($fields,&$objPHPExcel,$rowsAdd=0)
	{
		$index = 0;
		foreach ($fields as $fld) {
			$index++;
			$fldLoc = explode(",",$fld["LOC"]);
			$fldCol = intval($fldLoc[0])+1;
			$fldRow = intval($fldLoc[1])+intval($fld["ROW"]??0);
			/*$objPHPExcel->getActiveSheet()
				->setCellValueByColumnAndRow($fldCol,$fldRow, $fld["VAL"]??$fld["NAME"]??"");*/
			$group = explode(",",$fld["GROUPFLD"]??'0,0');
			$groupCol = intval($group[0]);
			$groupRow = intval($group[1]);
				
            $this->inputKey = $fld["NAME"]??"-";
            $objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow($fldCol,
            $fldCol+$groupCol,$fldRow+$groupRow));
            $objPHPExcel->getActiveSheet()
            ->setCellValueByColumnAndRow($fldCol,$fldRow, $fld["VAL"]??$fld["NAME"]??"");


			if(isset($fld["ALIGN"])){
				$alignExcel=[];
				switch ($fld["ALIGN"]) {
					case 'HOR_CENTER':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_CENTER;break;
					case 'HOR_LEFT':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_LEFT;break;
					case 'HOR_RIGHT':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_RIGHT;break;
					case 'HOR_GENERAL':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_GENERAL;break;
					case 'HOR_CENTER_CONT':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_CENTER_CONTINUOUS;break;
					case 'HOR_JUSTIFY':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_JUSTIFY;break;
					case 'HOR_FILL':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_FILL;break;
					case 'HOR_DISTRIBUTED':$alignExcel["horizontal"] = SpreadsheetStyle\Alignment::HORIZONTAL_DISTRIBUTED;break;
					case 'VER_BOTTOM':$alignExcel["vertical"] = SpreadsheetStyle\Alignment::VERTICAL_BOTTOM;break;
					case 'VER_TOP':$alignExcel["vertical"] = SpreadsheetStyle\Alignment::VERTICAL_TOP;break;
					case 'VER_CENTER':$alignExcel["vertical"] = SpreadsheetStyle\Alignment::VERTICAL_CENTER;break;
					case 'VER_JUSTIFY':$alignExcel["vertical"] = SpreadsheetStyle\Alignment::VERTICAL_JUSTIFY;break;
					case 'VER_DISTRIBUTED':$alignExcel["vertical"] = SpreadsheetStyle\Alignment::VERTICAL_DISTRIBUTED;break;
				}
				if(count($alignExcel)>0){
					$cellsRange=$this->cellsToMergeByColsRow($fldCol,$fldCol+$groupCol,$fldRow+$groupRow);
					// $objPHPExcel->getActiveSheet()->getStyle()->getAlignment()->setHorizontal($alignExcel);
					$objPHPExcel->getActiveSheet()->getStyle($cellsRange)->getAlignment()->applyFromArray(
						array($alignExcel)
					);
				}
			}

			$fldGroupVal = explode(",",$fld["GROUPVAL"]??'0,0');
			$colFldGroup = intval($fldGroupVal[0]);
			$rowFldGroup = intval($fldGroupVal[1]);
			$fldPostVal = explode(",", $fld["POSTVAL"]??'0,0');
			$fldPostCol = intval($fldPostVal[0]);
			$fldPostRow = intval($fldPostVal[1]);
			// var_dump($fldCol+$fldPostCol+$groupCol,$fldCol+$fldPostCol+
				// $colFldGroup,$fldRow+$fldPostRow,$fldRow+$fldPostRow+$rowFldGroup);
			// echo "<br>";


			if(isset($fld["POSTVAL"])){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($fldCol+$fldPostCol+$groupCol,
					$fldRow+$fldPostRow, $fld["FIELDVAL"]??"");

				if($colFldGroup>0||$rowFldGroup>0){
                    $this->inputKey = $fld["NAME"];
					$objPHPExcel->getActiveSheet()->mergeCells($this->cellsToMergeByColsRow($fldCol+$fldPostCol+$groupCol,
						$fldCol+$fldPostCol+$groupCol+$colFldGroup,
						$fldRow+$fldPostRow,
						$fldRow+$fldPostRow+$rowFldGroup));

                        /*$value_fix = $this->cellsToMergeByColsRow($fldCol+$fldPostCol+$groupCol,
						$fldCol+$fldPostCol+$groupCol+$colFldGroup,
						$fldRow+$fldPostRow,
						$fldRow+$fldPostRow+$rowFldGroup);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$fldRow+$fldPostRow, $value_fix);*/
				}
			}
			if(isset($fld["BG"])){
				$celdIndx=$this->cellsToMergeByColsRow($fldCol,$fldCol+$groupCol,$fldRow+$groupRow);
				$objPHPExcel->getActiveSheet()->getStyle($celdIndx)->getFont()->setSize($fld["FONTSIZE"]??9);
				$objPHPExcel->getActiveSheet()->getStyle($celdIndx)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle($celdIndx)->getAlignment()
					->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($celdIndx)->getFill()->applyFromArray(array(
			        'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			        'startColor' => array(
			             'rgb' => $fld["BG"]
			        )
			    ));
				$objPHPExcel->getActiveSheet()->getStyle($celdIndx)->getFont()->getColor()->setRGB($fld["COLOR"]??"FFFFFF");
			}
			if(isset($fld["BORDER"])&&$fld["BORDER"]=="1"){
				$celdIndx=$this->cellsToMergeByColsRow($fldCol,$fldCol+$groupCol,$fldRow+$groupRow);
				$style_array = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => SpreadsheetStyle\Border::BORDER_MEDIUM
						]
					]
				];
				$objPHPExcel->getActiveSheet()->getStyle($celdIndx)->applyFromArray($style_array);
			}
		}
        // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1, json_encode($this->colMerged));
	}

    function isEmpty($val)
	{
		if(!isset($val)||$val==""||$val==null||$val=='0'||$val==0)
			$val=CHR(45);
		return $val;
	}


    public function expToeMensualExcel($dateYearMonth,$interval){

		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);
	
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->setCellValue('C2','REPORTE MENSUAL DE TOE');
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C2:S2')->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('C2:S2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('C2:S2');

		/*$query="     select mes,ubigeo,departamento,
		provincia,distrito,localidad,trafico_inbound,trafico_outboun 
		from PSO_0DATA_18_5625  where mes ='2021-05' 
		";

		$list = $this->index->getExecSelect($query);*/
        $list = $this->executeProcedure("begin PK_PSO_REPORTES.SP_REPORTES_DATA_18_5625(:p_fechaval, :resultado); end;", [
            "p_fechaval" => ["value" => $dateYearMonth, "type" => PDO::PARAM_STR],
            "resultado" => ["value" => null, "type" => PDO::PARAM_STMT],
        ]);
		$i=4;
		foreach ($list as $k => $bodies) {
					
			////////////////////////////////////////////
				$column = 'A';
				$fila=$i;
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,'ENLACE');
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getFont()->setBold(true);

					$objPHPExcel->getActiveSheet()->setCellValue('O'.$i+2,$interval);
					$objPHPExcel->getActiveSheet()->getStyle('O'.$i+2)->getFont()->setSize(8);
					$tmp="O".($i+2).":P".($i+2);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);

			
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i+2,'NODO');
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i+2)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i+2)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i+4,'DEPARTAMENTO');
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i+4)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i+4)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i+6,'PROVINCIA');
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i+6)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('C'.$i+6)->getFont()->setBold(true);

					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,$bodies['ENLACE_TX']);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i+2,$bodies['NODO_TX']);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$i+2)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i+4,$bodies['DEPARTAMENTO']);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$i+4)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i+6,$bodies['PROVINCIA']);
					$objPHPExcel->getActiveSheet()->getStyle('E'.$i+6)->getFont()->setSize(8);

					$objPHPExcel->getActiveSheet()->setCellValue('I'.$i+2,'UBIGEO');
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i+2)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i+2)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$i+4,'DISTRITO');
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i+4)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i+4)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$i+6,'LOCALIDAD');
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i+6)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$i+6)->getFont()->setBold(true);

					
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i+2,$bodies['UBIGEO']);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$i+2)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i+4,$bodies['DISTRITO']);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$i+4)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i+6,$bodies['LOCALIDAD']);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$i+6)->getFont()->setSize(8);
				
					
				
					$this->paintCell($objPHPExcel,'H'.$i+9,'K'.$i+9,'Volumen de tráfico (Kilobytes)');
					$this->paintCell($objPHPExcel,'H'.$i+10,'I'.$i+10,'ENTRANTE');
					$this->paintCell($objPHPExcel,'J'.$i+10,'K'.$i+10,'SALIENTE');
					$this->paintCell($objPHPExcel,'F'.$i+10,'G'.$i+10,'PROTOCOLOS');

					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i+11,'DATOS');
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i+12,'H323');
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i+13,'SIP');
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i+14,'TOTAL');

					$tmp="H".($i+11).":I".($i+11);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);
					$tmp="H".($i+12).":I".($i+12);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);
					$tmp="H".($i+13).":I".($i+13);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);
					$tmp="H".($i+14).":I".($i+14);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);


					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i+11,$bodies['TRAFICO_INBOUND']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i+12,CHR(45));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i+13,CHR(45));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i+14,$bodies['TRAFICO_INBOUND']);

					$tmp="J".($i+11).":K".($i+11);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);
					$tmp="J".($i+12).":K".($i+12);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);
					$tmp="J".($i+13).":K".($i+13);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);
					$tmp="J".($i+14).":K".($i+14);
					$objPHPExcel->getActiveSheet()->mergeCells($tmp);


					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i+11,$bodies['TRAFICO_OUTBOUN']);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i+12,CHR(45));
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i+13,CHR(45));
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i+14,$bodies['TRAFICO_OUTBOUN']);

					
				
			///////////////////////////////////////////////
				$i= $i+17;
			}
	
		

	
			ob_end_clean();
			// header('Content-Type: application/vnd.ms-excel');
			// header('Content-Disposition: attachment;filename=16- REPORTE TOE MENSUAL_'.$year.$datePart[1].'.xls');
			// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename=16- REPORTE TOE MENSUAL_'.$year.$datePart[1].'.xlsx');
			$object_writer = IOFactorySpread::createWriter($objPHPExcel, 'Xlsx');
			$object_writer->save('php://output');
	}

    function monthNumber($numero){
		
		$months = array (1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
		return $months[(int)$numero];
	}

    function paintCell($objPHPExcel,$start='',$end='',$text=''){
		//$this->load->library('excel');
		$objPHPExcel->getActiveSheet()->setCellValue($start,$text);
		$objPHPExcel->getActiveSheet()->getStyle($start)->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle($start)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle($start)->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle($start)->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		$intervalo=$start.':'.$end;
		$objPHPExcel->getActiveSheet()->mergeCells($intervalo);
	}

    function sumToFloat($vals=array())
	{
		$valRet = 0.0;
		foreach ($vals as $val) {
			$valRet+=floatval(str_replace(",", ".", $val));
		}
		preg_match( "#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim(str_replace(",", ".", $valRet)), $o );
    return $o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');
		// return $valRet;
	}

	//TEST 3

	public function expInterrupcionesCpeExcelInc($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','REPORTE DE INTERRUPCIONES CPE');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
		
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);

		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(200);

		$column = 'A';
		$headers =  array('Incidencia','Región','Provincia','Distrito','Localidad','CPE ID','Institución','Fecha Inicio Interrupción','Fecha Fin Interrupción','Hora Inicio Interrupción','Hora Fin Interrupción','Horas Fuera de Servicio','Termino de la Distancia en Horas','Problema (Detalle)',"N° Constancia\r de Reparación",'Situación y Estado Actual','Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','Q11');
	
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5610_INC");	
	
		if (count($list)!=0) {
	
			$i=12;
			foreach ($list as $k => $v) {				
			
				$column = 'A';
				$fila=$i;
				$bodies = $list[$k];
				foreach ($bodies as $body) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
					$column++;
				}
			
				$i++;
			}
		} else {		
			$column = 'A';
			$fila=12;
			$bodies = array('-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
			foreach ($bodies as $body) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
				$column++;
				}
			$this->cellBorder($objPHPExcel,'A12','Q12');
			$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
			$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');	
		}
	
		$object_writer=null;
		$fileName='11-Reporte de interrupción del acceso a Internet_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);
		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=11-Reporte de interrupción del acceso a Internet_'.$year.$datePart[1].'.xlsx');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function expInterrupcionesCpeExcel($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','REPORTE DE INTERRUPCIONES CPE');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');

		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);

		
		

		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		

		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		

		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		


		$column = 'A';
		$headers =  array('N°','Región','Provincia','Distrito','Localidad','CPE ID','Institución','Fecha Inicio Interrupción','Fecha Fin Interrupción','Hora Inicio Interrupción','Hora Fin Interrupción','Horas Fuera de Servicio','Termino de la Distancia en Horas','Problema (Detalle)',"N° Constancia\r de Reparación",'Situación y Estado Actual','Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				'rgb' => 'FF0000'
			)));

			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','Q11');

		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5610");
		


		if (count($list)!=0) {

			$i=12;
			foreach ($list as $k => $v) {
				
			
				$column = 'A';
				$fila=$i;
				$bodies = $list[$k];
				foreach ($bodies as $body) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
					$column++;
				}
			
				$i++;
			}
		} else {
		
		
			$column = 'A';
			$fila=12;
			$bodies = array('-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
			foreach ($bodies as $body) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
				$column++;
				}
			$this->cellBorder($objPHPExcel,'A12','Q12');
			$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
			$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
		
			
		
		}

			$object_writer=null;
			$fileName='11-Reporte de interrupción del acceso a Internet_'.$year.$datePart[1];
			$this->formatSheet($objPHPExcel,$object_writer,$fileName);
			// ob_end_clean();
			// header('Content-Type: application/vnd.ms-excel');
			// header('Content-Disposition: attachment;filename=11-Reporte de interrupción del acceso a Internet_'.$year.$datePart[1].'.xlsx');
			// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
			$object_writer->save('php://output');
	}

	public function expIntranetExcelInc($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]) ;
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','REPORTE DE INTERRUPCIONES CPE');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
	
		$column = 'A';
		$headers =  array('Incidencia','Fecha Inicio Interrupción','Fecha Fin Interrupción',
							'Hora Inicio Interrupción','Hora Fin Interrupción','Horas Fuera de Servicio',
							'Problema (Detalle)','N° Constancia de Reparación','Situación y Estado Actual',
							'Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','J11');
		
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5611_INC");
		
		
		/*********************************************************** */
		if (count($list)!=0) {
			$i=12;
			foreach ($list as $k => $v) {
			
				$column = 'A';
				$fila=$i;
				$bodies = $list[$k];
				foreach ($bodies as $body) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
					$column++;
				}
			
				$i++;
			}

			
		} else {
		
		
			$column = 'A';
			$fila=12;
			$bodies = array('-','-','-','-','-','-','-','-','-','-');
			foreach ($bodies as $body) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
				$column++;
				}
			$this->cellBorder($objPHPExcel,'A12','J12');
			$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
			$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
		
			
		
		}


		$object_writer=null;
		$fileName='12-Reporte de interrupción del acceso a Intranet_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);
		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=12-Reporte de interrupción del acceso a Intranet_'.$year.$datePart[1].'.xlsx');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function expIntranetExcel($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]) ;
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','REPORTE DE INTERRUPCIONES CPE');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
	
		$column = 'A';
		$headers =  array('N°','Fecha Inicio Interrupción','Fecha Fin Interrupción',
							'Hora Inicio Interrupción','Hora Fin Interrupción','Horas Fuera de Servicio',
							'Problema (Detalle)','N° Constancia de Reparación','Situación y Estado Actual',
							'Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','J11');
		
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5611");
		
		
		/*********************************************************** */
		if (count($list)!=0) {
			$i=12;
			foreach ($list as $k => $v) {
			
				$column = 'A';
				$fila=$i;
				$bodies = $list[$k];
				foreach ($bodies as $body) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
					$column++;
				}
			
				$i++;
			}

			
		} else {
		
		
			$column = 'A';
			$fila=12;
			$bodies = array('-','-','-','-','-','-','-','-','-','-');
			foreach ($bodies as $body) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
				$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
				$column++;
				}
			$this->cellBorder($objPHPExcel,'A12','J12');
			$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
			$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
		
			
		
		}
		/*********************************************************** */

		$object_writer=null;
		$fileName='12-Reporte de interrupción del acceso a Intranet_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);
		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=12-Reporte de interrupción del acceso a Intranet_'.$year.$datePart[1].'.xlsx');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function expIntSubSistemaExcelInc($dateYearMonth){

		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','Reporte mensual de interrupción deL Subsistema de seguimiento (*)');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
					'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
					'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
	
		$column = 'A';
		$headers =  array('Incidencia','Fecha Inicio Interrupción','Hora Inicio Interrupcion','Fecha Final Interrupción',
		'Hora Fin Interrupcion','Horas fuera de servicio','Problema (Detalle)',
		'N° de Constancia de Reparación','Situación Estado Actual','Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
					'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','J11');
		
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5612_inc");

		if (count($list)!=0) {

				$i=12;
				foreach ($list as $k => $v) {
					
				////////////////////////////////////////////
					$column = 'A';
					$fila=$i;
					$bodies = $list[$k];
					foreach ($bodies as $body) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
						$column++;
						}
					
				///////////////////////////////////////////////
					$i++;
				}
			} else {
			
			////////////////////////////////////////////
				$column = 'A';
				$fila=12;
				$bodies = array('-','-','-','-','-','-','-','-','-','-');
				foreach ($bodies as $body) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
					$column++;
					}
				$this->cellBorder($objPHPExcel,'A12','J12');
				$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
				$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
			///////////////////////////////////////////////
				
			
		}


		$object_writer=null;
		$fileName='13- Reporte mensual de interrupción del Subsistema de seguimiento_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);
		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=13- Reporte mensual de interrupción del Subsistema de seguimiento_'.$year.$datePart[1].'.xlsx');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function expIntSubSistemaExcel($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','Reporte mensual de interrupción deL Subsistema de seguimiento (*)');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
	
		$column = 'A';
		$headers =  array('N°','Fecha Inicio Interrupción','Hora Inicio Interrupcion','Fecha Final Interrupción',
		'Hora Fin Interrupcion','Horas fuera de servicio','Problema (Detalle)',
		'N° de Constancia de Reparación','Situación Estado Actual','Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','J11');
		
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5612");

		if (count($list)!=0) {

				$i=12;
				foreach ($list as $k => $v) {
					
				////////////////////////////////////////////
					$column = 'A';
					$fila=$i;
					$bodies = $list[$k];
					foreach ($bodies as $body) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
						$column++;
						}
					
				///////////////////////////////////////////////
					$i++;
				}
			} else {
			
			////////////////////////////////////////////
				$column = 'A';
				$fila=12;
				$bodies = array('-','-','-','-','-','-','-','-','-','-');
				foreach ($bodies as $body) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
					$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
					$column++;
					}
				$this->cellBorder($objPHPExcel,'A12','J12');
				$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
				$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
			///////////////////////////////////////////////
				
			
		}


		$object_writer=null;
		$fileName='13- Reporte mensual de interrupción del Subsistema de seguimiento_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);
		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=13- Reporte mensual de interrupción del Subsistema de seguimiento_'.$year.$datePart[1].'.xlsx');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function expIntPopExcelInc($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','REPORTE DE INTERRUPCIONES POP');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
	
		$column = 'A';
		$headers =  array('Incidencia','Región','Provincia','Distrito','Localidad','POP ID','Fecha Inicio Interrupción','Fecha Fin Interrupción','Hora Inicio Interrupción','Hora Fin Interrupción','Horas Fuera de Servicio','Termino de la Distancia en Horas','Problema (Detalle)','N° Constancia de Reparación','Situación y Estado Actual','Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','P11');
		
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5613_INC");

		if (count($list)!=0) {

				$i=12;
				foreach ($list as $k => $v) {
					
				////////////////////////////////////////////
					$column = 'A';
					$fila=$i;
					$bodies = $list[$k];
					foreach ($bodies as $body) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
						$column++;
						}
					
				///////////////////////////////////////////////
					$i++;
				}
		} else {
				
				////////////////////////////////////////////
					$column = 'A';
					$fila=12;
					$bodies = array('-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
					foreach ($bodies as $body) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
						$column++;
						}
					$this->cellBorder($objPHPExcel,'A12','Q12');
					$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
					$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
				///////////////////////////////////////////////
					
				
		}
		
		
		$object_writer=null;
		$fileName='14- Reporte mensual de interrupciones POP_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);

		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=14- Reporte mensual de interrupciones POP_202106.xls');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function expIntPopExcel($dateYearMonth){
		
		$objPHPExcel = new Spreadsheet();
		$datePart=explode('-',$dateYearMonth);
		$year=$datePart[0];
		$month=$this->monthNumber($datePart[1]);
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($month);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','REPORTE DE INTERRUPCIONES POP');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
	
		$objPHPExcel->getActiveSheet()->setCellValue('B5','Mes');
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
	
	
		$objPHPExcel->getActiveSheet()->setCellValue('C5',$month);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('E5','Año');
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)
		));
		$objPHPExcel->getActiveSheet()->getStyle('E5')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
		
	
		$objPHPExcel->getActiveSheet()->setCellValue('F5',$year);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
		
	
	
		$column = 'A';
		$headers =  array('N°','Región','Provincia','Distrito','Localidad','POP ID','Fecha Inicio Interrupción','Fecha Fin Interrupción','Hora Inicio Interrupción','Hora Fin Interrupción','Horas Fuera de Servicio','Termino de la Distancia en Horas','Problema (Detalle)','N° Constancia de Reparación','Situación y Estado Actual','Motivo de la Exclusión');
		foreach ($headers as $head) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '11', $head);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setSize(8);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFill()->applyFromArray(array(
			'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
			'startColor' => array(
				 'rgb' => 'FF0000'
			)));
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->applyFromArray(
				array('horizontal' => SpreadsheetStyle\Alignment::HORIZONTAL_CENTER,
				'vertical' => SpreadsheetStyle\Alignment::VERTICAL_CENTER,)
			);
	
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->getColor()->setARGB(SpreadsheetStyle\Color::COLOR_WHITE);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($column . '11')->getFont()->setBold(true);
			$column++;
		}
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('200');
		
		$this->cellBorder($objPHPExcel,'B5','C5');
		$this->cellBorder($objPHPExcel,'E5','F5');
		$this->cellBorder($objPHPExcel,'A11','P11');
		
		$list = $this->getDataReport($dateYearMonth,"SP_REPORTES_DATA_18_5613");

		if (count($list)!=0) {

				$i=12;
				foreach ($list as $k => $v) {
					
				////////////////////////////////////////////
					$column = 'A';
					$fila=$i;
					$bodies = $list[$k];
					foreach ($bodies as $body) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
						$column++;
						}
					
				///////////////////////////////////////////////
					$i++;
				}
		} else {
				
				////////////////////////////////////////////
					$column = 'A';
					$fila=12;
					$bodies = array('-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
					foreach ($bodies as $body) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . $fila, $body);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getFont()->setSize(8);
						$objPHPExcel->getActiveSheet()->getStyle($column . $fila)->getAlignment()->setHorizontal(SpreadsheetStyle\Alignment::HORIZONTAL_CENTER);
						$column++;
						}
					$this->cellBorder($objPHPExcel,'A12','Q12');
					$objPHPExcel->getActiveSheet()->mergeCells('A14:G14');
					$objPHPExcel->getActiveSheet()->setCellValue('A14','(*) Servicio no registro interrupciones durante el mes ');
				///////////////////////////////////////////////
					
				
		}
		
		
		$object_writer=null;
		$fileName='14- Reporte mensual de interrupciones POP_'.$year.$datePart[1];
		$this->formatSheet($objPHPExcel,$object_writer,$fileName);

		// ob_end_clean();
		// header('Content-Type: application/vnd.ms-excel');
		// header('Content-Disposition: attachment;filename=14- Reporte mensual de interrupciones POP_202106.xls');
		// $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
		$object_writer->save('php://output');
	}

	public function getDataReport($fecha,$store,$localidad='')
	{
		$params = ["p_fechaval" => ["value" => $fecha, "type" => PDO::PARAM_STR]];
		if ($localidad!='') {
			$params["p_localidad"] = ["value" => $localidad,"type" => PDO::PARAM_STR];
		}
		$params["resultado"] = ["value" => null, "type" => PDO::PARAM_STMT];

		$str_params = [];
		foreach($params as $name => $_){
			$str_params[] = ":{$name}";
		}
		$str_params = implode(", ", $str_params);
		return $this->executeProcedure("begin PK_PSO_REPORTES.{$store}({$str_params}); end;", $params);
	}

	private function cellBorder($objPHPExcel,$letterStar='',$letterEnd='')
	{
		$style_array = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => SpreadsheetStyle\Border::BORDER_MEDIUM
				]
			]
		];
		$intervalo='';
		$letterStar == '' && $letterEnd == '' ? $intervalo='A1:A1': $intervalo=$letterStar.":".$letterEnd;
		$objPHPExcel->getActiveSheet()->getStyle($intervalo)->applyFromArray($style_array);
	}

	private function formatSheet(&$objPHPExcel,&$object_writer,$fileName,$ext='xlsx')
	{
		// ob_end_clean();
		header('Content-Disposition: attachment;filename="'.$fileName.'.'.$ext.'"');
		header('Cache-Control: max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0");
		if($ext=='xlsx'){
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			$object_writer = IOFactorySpread::createWriter($objPHPExcel, 'Xlsx');
		}elseif($ext=='xls'){
			header('Content-Type: application/vnd.ms-excel');
			$object_writer = IOFactorySpread::createWriter($objPHPExcel, 'Xls');
		}
	}
}
