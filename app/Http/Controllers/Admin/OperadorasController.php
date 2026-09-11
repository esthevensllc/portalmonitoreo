<?php

namespace App\Http\Controllers\Admin;

use App\Facads\ODB;
use App\Traits\DB\ProcedureTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OperadorasController
{
    use ProcedureTrait;

    private $config = [
        "TOTAL" => [
            'operadora' => ['label' => 'Operadora', 'isLabel' => true],
            'cobertura total' => ['label' => 'Cobertura total'],
            'habitantes total' => ['label' => 'Habitantes total'],
        ]
    ];
    private $colorsByOperadora = [
        "BITEL" => "#FFCC00",
        "CLARO" => "#E60000",
        "MOVISTAR" => "#00CC44",
        "ENTEL" => "#0073E6",
    ];

    public function view($id_tracing, Request $request)
    {
        $tracingID = $id_tracing;
        $colorsByOperadora = $this->colorsByOperadora;

        $departamentos = $this->executeProcedure("BEGIN PSO_OSSPORTAL.SP_GET_UBGDPTO(:resultado); END;", ['resultado' => ODB::CURSOR]);
        return view("backpack::operadoras", compact("tracingID","id_tracing", "colorsByOperadora", "departamentos"));
    }

    public function api($id_tracing, Request $request)
    {
        $data = $this->getData(10143, $request);
        return response()->json($data);
    }

    public function export($id_tracing, Request $request)
	{
		$getLabelForDatasetname = function($index, $name){
			if(array_key_exists($name, $this->coberturaChartsConfig[$index]['datasets'])){
				return $this->coberturaChartsConfig[$index]['datasets'][$name]['label'];
			}
			return $name.'>>';
		};

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getColumnDimension('A')->setWidth(22);
		$sheet->getColumnDimension('B')->setWidth(22);
		$sheet->getColumnDimension('C')->setWidth(22);
		$sheet->getColumnDimension('D')->setWidth(22);
		$sheet->getColumnDimension('E')->setWidth(17);
		$sheet->getColumnDimension('F')->setWidth(17);
		$sheet->getColumnDimension('G')->setWidth(17);
		$sheet->getColumnDimension('H')->setWidth(17);
		$sheet->getColumnDimension('I')->setWidth(17);
		$sheet->getColumnDimension('J')->setWidth(17);
		$sheet->getColumnDimension('K')->setWidth(17);
		$sheet->getColumnDimension('L')->setWidth(17);
		$sheet->getColumnDimension('M')->setWidth(17);
		$sheet->getColumnDimension('N')->setWidth(17);
		$sheet->getColumnDimension('O')->setWidth(17);
		$sheet->getColumnDimension('P')->setWidth(17);
		$sheet->getColumnDimension('Q')->setWidth(17);
		$sheet->getColumnDimension('R')->setWidth(17);
		$sheet->getColumnDimension('S')->setWidth(17);

		$abc = ['A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X'];
		$dataToView = $this->getData(10143, $request);

		//if ($menuID == 10143) {
			$calculateAdd = 0;
			for ($i = 0; $i < count($dataToView["dataToView"]); $i++) {
				$data = $dataToView["dataToView"][$i]["data"];
				$columnNames = $dataToView["dataToView"][$i]["columnNames"];

				if ($i == 0) {
					$calculateAdd = 1;
				} else {
					$calculateAdd += count($data) + 15;
				}

                $t = 0;
                foreach($columnNames as $index => $config){
                    $sheet->getStyle($abc[$t] . $calculateAdd)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('DB3445');
                    $sheet->getStyle($abc[$t] . $calculateAdd)
                        ->getFont()
                        ->getColor()
                        ->setRGB('FFFFFF');
                    $sheet->setCellValue($abc[$t] . $calculateAdd, $config["label"]);
                    $t += 1;
                }

				$dataSeriesValues = [];
				$dataSeriesValues2 = [];
				$countVarNum = 0;
				for ($o = 0; $o < count($data); $o++) {
					$countVarNum2 = 0;
                    $t = 0;
                    foreach ($columnNames as $name => $_) {
                        if (is_numeric($data[$o]->{$name})) {
							$sheet->setCellValue($abc[$t] . $o + 1 + $calculateAdd, $data[$o]->{$name});
							if ($countVarNum2 == 0) {
								$countVarNum++;
								$countVarNum2++;
							}
						} else {
							$sheet->setCellValue($abc[$t] . $o + 1 + $calculateAdd, $data[$o]->{$name});
						}
                        $t += 1;
                    }
				}

				$dataSeriesLabels = [
					new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 
				];

				$dataSeriesLabels2 = [
					new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 
				];

				// for ($g = 1; $g < count($columnNames); $g++) {
				array_push($dataSeriesValues, new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$' . $abc[1] . '$' . ($calculateAdd + 1) . ':$' . $abc[1] . '$' . ($calculateAdd + count($data)), null, 4));
				array_push($dataSeriesValues2, new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$' . $abc[2] . '$' . ($calculateAdd + 1) . ':$' . $abc[2] . '$' . ($calculateAdd + count($data)), null, 4));
				// }

				$xAxisTickValues = [
					new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
				];

				// $dataSeriesValues = [
				// 	new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
				// 	new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
				// ];

				$series = new DataSeries(
					DataSeries::TYPE_BARCHART, // plotType
					DataSeries::GROUPING_STANDARD, // plotGrouping
					range(0, count($dataSeriesValues) - 1), // plotOrder
					$dataSeriesLabels, // plotLabel
					$xAxisTickValues, // plotCategory
					$dataSeriesValues          // plotValues
				);

				$series2 = new DataSeries(
					DataSeries::TYPE_BARCHART, // plotType
					DataSeries::GROUPING_STANDARD, // plotGrouping
					range(0, count($dataSeriesValues2) - 1), // plotOrder
					$dataSeriesLabels2, // plotLabel
					$xAxisTickValues, // plotCategory
					$dataSeriesValues2          // plotValues
				);

				// Set the series in the plot area
				$plotArea = new PlotArea(null, [$series]);
				$plotArea2 = new PlotArea(null, [$series2]);
				// Set the chart legend
				$legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

				// set labels to chart except first
				$labels = [];
				/*for ($t = 1; $t < count($columnNames); $t++) {
					$labels[] = $getLabelForDatasetname($i, $columnNames[$t]);
				}*/
                $passedFirst = false;
                foreach ($columnNames as $name => $config) {
                    if($passedFirst){
                        $labels[] = $config["label"];
                    }
                    $passedFirst = true;
                }
				$title = new Title($labels[0]);
				$title2 = new Title($labels[1]);
				$yAxisLabel = new Title('....');

				$chart = new Chart(
					'chart1', // name
					$title, // title
					$legend, // legend
					$plotArea, // plotArea
					true, // plotVisibleOnly
					DataSeries::EMPTY_AS_GAP, // displayBlanksAs
					null, // xAxisLabel
					$yAxisLabel // yAxisLabel
				);
				$chart->setTopLeftPosition($abc[count($columnNames) + 1]  . $calculateAdd);
				$chart->setBottomRightPosition($abc[count($columnNames) + 6]  . $calculateAdd + 17);
				$sheet->addChart($chart);

				$chart2 = new Chart(
					'chart2', // name
					$title2, // title
					$legend, // legend
					$plotArea2, // plotArea
					true, // plotVisibleOnly
					DataSeries::EMPTY_AS_GAP, // displayBlanksAs
					null, // xAxisLabel
					$yAxisLabel // yAxisLabel
				);
				$chart2->setTopLeftPosition($abc[count($columnNames) + 8]  . $calculateAdd);
				$chart2->setBottomRightPosition($abc[count($columnNames) + 13]  . $calculateAdd + 17);
				$sheet->addChart($chart2);


				$writer = new Xlsx($spreadsheet);
				$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
				$writer->setIncludeCharts(true);
			}
		//}

		$nameFile = uniqid();
		// We'll be outputting an excel file
		header('Content-type: application/vnd.ms-excel');
		$today = date('d/m/Y');
		// It will be called file.xlsx
		header('Content-Disposition: attachment; filename="Grafico_coberturaMovil_' . $today . '_.xlsx"');
		// Write file to the browser
		$writer->save('php://output');
		return;
	}

    private function getData($menuID, Request $request)
    {
        //$menuID = null;
        $trimestre = $request->get("trimestre");
        $departamento = $request->get("departamento");
        $provincia = $request->get("provincia");
        $distrito = $request->get("distrito");

        $dataToView["dataToView"] = [];
        $dataToView["trimestres"] = [];
        $dataToView["menuID"] = $menuID;

        $dataToSend = DB::table("pso_type")
        ->where("type_father", 10143)
        ->where("type_name", DB::raw("to_char(1)"))
        ->where("status", 1)
        ->get();

        $dataToSend2 = DB::table("pso_type")
        ->where("type_father", 10143)
        ->where("type_name", DB::raw("to_char(2)"))
        ->where("status", 1)
        ->get();

        if (count($dataToSend2) == 1) {
            $configParts = explode("|", $dataToSend2[0]->type_description);
            $procedure = $configParts[0];
            $title = $configParts[1];
            $selectStr = $configParts[2];
            $whereStr = $configParts[3];
            $groupByStr = $configParts[4];
            $orderByStr = $configParts[5];
            //$gettrimestres = $this->index->getOperadoraData($selectStr, $whereStr, $groupByStr, $orderByStr);

            /*$str_procedure = "BEGIN PSO_OSSPORTAL.SP_GET_OPERADORA_DATA(:p_selectstr, :p_wherestr, :p_groupbystr, :p_orderbystr, :p_mycursor); END;";
            $gettrimestres = $this->executeProcedure($str_procedure, [
                "p_selectstr" => ["value" => $selectStr, "type" => ODB::CHAR],
                "p_wherestr" => ["value" => $whereStr, "type" => ODB::CHAR],
                "p_groupbystr" => ["value" => $groupByStr, "type" => ODB::CHAR],
                "p_orderbystr" => ["value" => $orderByStr, "type" => ODB::CHAR],
                "p_mycursor" => ["type" => ODB::CURSOR],
            ]);*/
            $builder = DB::table("reg_reporte_op_resumen")->selectRaw($selectStr);
            if($whereStr !== ""){
                $builder->where(function($q) use ($whereStr) {
                    $q->whereRaw($whereStr);
                }); 
            }
            if($groupByStr !== ""){
                $builder->groupBy($groupByStr); 
            }
            if($orderByStr !== ""){
                $builder->orderByRaw(DB::raw($orderByStr)); 
            }
            $gettrimestres = $builder->get();
            $dataToView["trimestres"] =  $gettrimestres;
        }

        for ($i = 0; $i < count($dataToSend); $i++) {
            $configParts = explode("|", $dataToSend[$i]->type_description);
            $procedure = $configParts[0];
            $title = $configParts[1];
            $selectStr = $configParts[2];
            $whereStr = $configParts[3];
            $groupByStr = $configParts[4];
            $orderByStr = $configParts[5];

            // CORROBORAMOS QUE EL PROCEDURE QUE SE LLAME SEA EL SP_GET_OPERADORA_DATA
            if ($procedure == "SP_GET_OPERADORA_DATA") {
                // CHEKAMOS SI TIENE PARAMETROS DE FILTRO, DE SER ASÍ SE PROCEDE A HACER EL WHERE
                if ($selectStr == "") {
                    continue;
                }
                $builder = DB::table("reg_reporte_op_resumen")->selectRaw($selectStr);
                if($whereStr !== ""){
                    $builder->where(function($q) use ($whereStr) {
                        $q->whereRaw($whereStr);
                    }); 
                }
                if ($trimestre !== null) {
                    $whereStr .= " TRIMESTRE = '" . $trimestre . "'";
                    $builder->where("trimestre", $trimestre);
                }
                if ($departamento !== null) {
                    $whereStr .= "AND DEPARTAMENTO = '" . $departamento . "' ";
                    $builder->where("departamento", $departamento);
                }
                if ($provincia !== null) {
                    $whereStr .= "AND PROVINCIA = '" . $provincia . "'";
                    $builder->where("provincia", $provincia);
                }
                if ($distrito !== null) {
                    $whereStr .= "AND DISTRITO = '" . $distrito . "'";
                    $builder->where("distrito", $distrito);
                }
                if($groupByStr !== ""){
                    $builder->groupBy($groupByStr);
                }
                if($orderByStr !== ""){
                    $builder->orderByRaw(DB::raw($orderByStr)); 
                }

                $whereStr = strtoupper($whereStr);
                //$getOperadoraData = $this->index->getOperadoraData($selectStr, $whereStr, $groupByStr, $orderByStr);
                /*$str_procedure = "BEGIN PSO_OSSPORTAL.SP_GET_OPERADORA_DATA(:p_selectstr, :p_wherestr, :p_groupbystr, :p_orderbystr, :p_mycursor); END;";
                $getOperadoraData = $this->executeProcedure($str_procedure, [
                    "p_selectstr" => ["value" => $selectStr, "type" => ODB::CHAR],
                    "p_wherestr" => ["value" => $whereStr, "type" => ODB::CHAR],
                    "p_groupbystr" => ["value" => $groupByStr, "type" => ODB::CHAR],
                    "p_orderbystr" => ["value" => $orderByStr, "type" => ODB::CHAR],
                    "p_mycursor" => ["type" => ODB::CURSOR],
                ]);*/
                $getOperadoraData = $builder->get();
                //$keys = [];
                $fields = [];
                if (count($getOperadoraData) > 0 && !array_key_exists($title, $this->config)) {
                    $first_row = DB::table("reg_reporte_op_resumen")->selectRaw($selectStr)->groupBy($groupByStr)->first();
                    $keys = array_keys(get_object_vars($first_row));
                    foreach($keys as $name){
                        $fields[$name] = ["label" => $name];
                    }
                }else{
                    $fields = $this->config[$title];
                }

                $arr = array('data' => $getOperadoraData, 'columnNames' => $fields, 'title' => $title, 'menuID' => $menuID);
                // print_r($arr);
                array_push($dataToView["dataToView"], $arr);
            }
        }
        return $dataToView;
    }

    public function executeProcedure($sql, $params = []){

        $data = DB::transaction(function($conn) use ($sql, $params){
            $pdo = $conn->getPdo();
            $stmt = $pdo->prepare($sql);

            foreach($params as $i => $row){
                if(in_array($i, ["resultado", "p_mycursor"])){
                    $stmt->bindParam(":{$i}", $lista, ODB::CURSOR);
                }else{
                    $stmt->bindParam(":{$i}", $row["value"], $row["type"]);
                }
            }


            $stmt->execute();

            oci_execute($lista, OCI_DEFAULT);
            oci_fetch_all($lista, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
            oci_free_cursor($lista);

            return $array;
        });

        return $data;
    }
}
