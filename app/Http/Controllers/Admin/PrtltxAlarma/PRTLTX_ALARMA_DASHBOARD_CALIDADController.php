<?php

namespace App\Http\Controllers\Admin\PrtltxAlarma;

use App\Models\PrtltxAlarma\DispMetaByTipoNodo;
use App\Models\PrtltxAlarma\PRTLTX_ALARMA_DISP_MES;
use App\Models\PrtltxAlarma\PRTLTX_DIAS_MES;
use App\Modules\Shared\Exports\AdvancedExport;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style as SpreadsheetStyle;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class PRTLTX_ALARMA_DASHBOARD_CALIDADController
{
    private $exportService;

    public function __construct(AdvancedExport $exportService)
    {
        $this->exportService = $exportService;
    }

    private $kpis = [
        'delay' => ['label' => 'Delay (ms)', 'valorEsperado' => 30, 'valorEsperadoLabel' => '<30 ms', 'maxDecimals' => 2, 'fillWith' => ''],
        'pkloss' => ['label' => 'PKLOSS (%)', 'valorEsperado' => 0.3, 'valorEsperadoLabel' => '<0.3 %', 'maxDecimals' => 2, 'fillWith' => ''],
        'jitter' => ['label' => 'Jitter (ms)', 'valorEsperado' => 10, 'valorEsperadoLabel' => '<10 ms', 'maxDecimals' => 2, 'fillWith' => ''],
        'jittermax' => ['label' => 'Jitter max (ms)', 'valorEsperado' => 20, 'valorEsperadoLabel' => '<20 ms', 'maxDecimals' => 2, 'fillWith' => ''],
    ];

    public function index(){
        
        $diasMes = DB::table('PRTLTX_CALIDAD_DET')
            ->select(DB::Raw("TO_CHAR(mes, 'YYYY-MM-DD') as mes"))->groupBy('mes')->orderBy('mes')->get();
        
        $firstMes = count($diasMes) > 0 ? $diasMes[count($diasMes)-1]->mes : '';
        $mesIni = Carbon::createFromFormat('Y-m-d', $firstMes)->startOfMonth()->subMonths(4)->format('Y-m-d');
        $mesFin = Carbon::createFromFormat('Y-m-d', $firstMes)->startOfMonth()->format('Y-m-d');
        $response = $this->getDashboardData($this->kpis, $mesIni, $mesFin);

        return view('backpack::prtltx_alarmas.dashboard_calidad', array_merge($response, [
            'diasMes' => $diasMes,
            'mesIni' => $mesIni,
            'mesFin' => $mesFin,
            'kpis' => $this->kpis,
        ]));
    }

    public function getDashboardDataApi(Request $request){
        // dd($this->getDashboardData($request->get('mes_ini'),  $request->get('mes_fin'))['kpisGroupedByMesAndDevicename']);
        return $this->getDashboardData($this->kpis, $request->get('mes_ini'),  $request->get('mes_fin'));
    }

    public function exportExcel(Request $request){
        $report = $this->getDashboardData($this->kpis, $request->get('mes_ini'),  $request->get('mes_fin'));

        $kpiName = $request->get('kpi_name');

        /*$kpiNameSelected = 'delay';
        $kpisGroupedByDevicenameAndMes = $report['kpisGroupedByDevicenameAndMes'];
        $toTable = [];
        $headers = [];
        foreach($kpisGroupedByDevicenameAndMes as $dataOfDevice){
            $groupedByMes = [];
            foreach($report['kpisGroupedByMes'] as $row){
                if(key_exists('', $dataOfDevice)){
                    $groupedByMes[$row->mes] = $dataOfDevice[$row->mes][$kpiNameSelected];
                }else{
                    $groupedByMes[$row->mes] = '-';
                }
            }
            $groupedByMes['promedio'] = $dataOfDevice['promedios'][$kpiNameSelected];
            $toTable[] = $groupedByMes;
        }*/
        $headers = [
            'delay' => ['label' => 'delay'],
            'pkloss' => ['label' => 'pkloss'],
            'jitter' => ['label' => 'jitter'],
            'jittermax' => ['label' => 'jittermax'],
        ];
        $options = [
            'y_start_index' => 0,
            'x_start_index' => 1,
            'title' => 'Kpis por mes',
            // 'sheetIndex' => 1,
            'styles' => [
                'header' => [
                    'font' => ['bold' => true, 'color' => array('argb' => 'FFFFFF')],
                    'fill' => array(
                        'fillType' => SpreadsheetStyle\Fill::FILL_SOLID,
                        'startColor' => array('argb' => 'E74C3C')
                    )
                ]
            ]
        ];
        $this->exportService->loadData($headers, $report['kpisGrouped'], $options);

        $headers = [
            'mes' => ['label' => 'MES'],
            'delay' => ['label' => 'delay'],
            'pkloss' => ['label' => 'pkloss'],
            'jitter' => ['label' => 'jitter'],
            'jittermax' => ['label' => 'jittermax'],
        ];
        $options['x_start_index'] = 0;
        $options['y_start_index'] += count($report['kpisGrouped']) + 2;
        $this->exportService->loadData($headers, $report['kpisGroupedByMes'], $options);
        
        // Graficas
        $kpiPosition = 1;
        // $kpiName = null;
        foreach($this->kpis as $name => $config){
            if($kpiName === null || $kpiName === $name){
                $kpiName = $name;
                break;
            }
            $kpiPosition++;
        }
        $kpiLabelPos = [$kpiPosition, $options['y_start_index']];
        $xLabels = [
            [0, ($options['y_start_index'] +1)],
            [0, ($options['y_start_index'] + count($report['kpisGroupedByMes']))]
        ];
        $rangoData = [
            [$kpiPosition, ($options['y_start_index'] +1)],
            [$kpiPosition, ($options['y_start_index'] + count($report['kpisGroupedByMes']))]
        ];
        $this->exportCharts($kpiName, 'Kpis por mes', $kpiLabelPos, $xLabels, $rangoData);
        // end graficas

        $headers = [
            'devicename' => ['label' => 'Devicename'],
            'delay' => ['label' => 'delay'],
            'pkloss' => ['label' => 'pkloss'],
            'jitter' => ['label' => 'jitter'],
            'jittermax' => ['label' => 'jittermax'],
        ];
        $options['y_start_index'] += count($report['kpisGroupedByMes']) + 2;
        $this->exportService->loadData($headers, $report['kpiGroupedByDevicename'], $options);

        // Kpis grouped by devicename and mes
        $options['sheetIndex'] = 0;
        $options['y_start_index'] = 0;

        // headers to kpi
        $headers = ['devicename' => ['label' => 'Devicename']];
        foreach($report['kpisGroupedByMes'] as $row){
            $headers[$row->mes] = ['label' => $row->mes];
        }
        $headers['promedio'] = ['label' => 'Promedio'];

        
        foreach($this->kpis as $name => $kpiConfig){
            // if($name === $kpiName){
                $kpisGroupedByDevicenameAndMes = $this->getGroupedByMesAndDevicenameAsPlainArray(
                    $report['kpisGroupedByDevicenameAndMes'],
                    $report['kpisGroupedByMes'],
                    $name
                );
                $options['title'] = $kpiConfig['label'];
                $options['sheetIndex']++;
                $this->exportService->loadData($headers, $kpisGroupedByDevicenameAndMes, $options);
            // }
        }
        return $this->exportService->download('sla_calidad_prtltx.xlsx');
    }

    private function exportCharts($kpiName, $sheetName, $seriesLabel, $xLabels, $rangoData)
    {
        $kpiPosition = 1;
        foreach($this->kpis as $name => $config){
            if($kpiName === null || $kpiName === $name){
                $kpiName = $name;
                break;
            }
            $kpiPosition++;
        }
        
        $worksheet = $this->exportService->getExportReference()->getActiveSheet();

        // kpis
        $labelIndex = "'".$sheetName."'!".'$'.$this->indexToLetter($seriesLabel[0]).'$'.$seriesLabel[1]+1;
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $labelIndex, null, 1),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, '$C$4', null, 1), // 2011
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, '$D$4', null, 1), // 2012
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, '$E$4', null, 1), // 2012
        ];

        // meses
        $point1 = '$'.$this->indexToLetter($xLabels[0][0]).'$'.($xLabels[0][1]+1);
        $point2 = '$'.$this->indexToLetter($xLabels[1][0]).'$'.($xLabels[1][1]+1);
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'".$sheetName."'!".$point1.':'.$point2, null, 4),
        ];

        // data
        $point1 = '$'.$this->indexToLetter($rangoData[0][0]).'$'.($rangoData[0][1]+1);
        $point2 = '$'.$this->indexToLetter($rangoData[1][0]).'$'.($rangoData[1][1]+1);
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $point1.':'.$point2, null, 4),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, '$C$5:$C$9', null, 4),
            // new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, '$D$5:$D$9', null, 4),
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues          // plotValues
        );

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

        $title = new Title('Grafica '.$kpiName.' por mes');
        $yAxisLabel = new Title('Valor '.$this->kpis[$kpiName]['label']);

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );

        $chart->setTopLeftPosition('G4');
        $chart->setBottomRightPosition('N20');
        // $chart->setR('H20');

        // Add the chart to the worksheet
        $worksheet->addChart($chart);
        
    }

    public function getGroupedByMesAndDevicenameAsPlainArray($kpisGroupedByDevicenameAndMes, $kpisGroupedByMes, $kpiNameSelected){
        // $kpiNameSelected = 'delay';
        // $kpisGroupedByDevicenameAndMes = $report['kpisGroupedByDevicenameAndMes'];
        $toTable = [];
        $headers = [];
        foreach($kpisGroupedByDevicenameAndMes as $devicename => $dataOfDevice){
            $groupedByMes['devicename'] = $devicename;
            foreach($kpisGroupedByMes as $row){
                if(array_key_exists($row->mes, $dataOfDevice)){
                    $groupedByMes[$row->mes] = $dataOfDevice[$row->mes]->{$kpiNameSelected};
                }else{
                    $groupedByMes[$row->mes] = $this->kpis[$kpiNameSelected]['fillWith'];
                }
            }
            $groupedByMes['promedio'] = $dataOfDevice['promedios'][$kpiNameSelected];
            $toTable[] = $groupedByMes;
        }
        return $toTable;
    }



    public function getDashboardData($kpisOfDashboard, $mesIni, $mesFin){
        // $this->kpis['delay']['maxDecimals'];
        $kpisGrouped = DB::table('PRTLTX_CALIDAD_DET')
        ->select(
            DB::Raw("round(sum(delay_sum)/sum(counter), {$this->kpis['delay']['maxDecimals']}) as delay"),
            DB::Raw("round(sum(pkloss_sum)/sum(counter), {$this->kpis['pkloss']['maxDecimals']}) as pkloss"),
            DB::Raw("round(sum(jitter_sum)/sum(counter), {$this->kpis['jitter']['maxDecimals']}) as jitter"),
            DB::Raw("round(sum(jittermax_sum)/sum(counter), {$this->kpis['jittermax']['maxDecimals']}) as jittermax")
        )
        ->where('mes', '>=', $mesIni)
        ->where('mes', '<=', $mesFin)
        ->get();

        $kpisGroupedByMes = DB::table('PRTLTX_CALIDAD_DET')
        ->select(
            DB::Raw("to_char(mes, 'yyyy-mm') as mes"),
            DB::Raw("round(sum(delay_sum)/sum(counter), {$this->kpis['delay']['maxDecimals']}) as delay"),
            DB::Raw("round(sum(pkloss_sum)/sum(counter), {$this->kpis['pkloss']['maxDecimals']}) as pkloss"),
            DB::Raw("round(sum(jitter_sum)/sum(counter), {$this->kpis['jitter']['maxDecimals']}) as jitter"),
            DB::Raw("round(sum(jittermax_sum)/sum(counter), {$this->kpis['jittermax']['maxDecimals']}) as jittermax")
        )
        ->where('mes', '>=', $mesIni)
        ->where('mes', '<=', $mesFin)
        ->groupBy(DB::Raw("to_char(mes, 'yyyy-mm')"))
        ->orderBy('mes')
        ->get();
        
        $kpiGroupedByDevicename = DB::table('PRTLTX_CALIDAD_DET')
        ->select(
            'devicename',
            DB::Raw("round(sum(delay_sum)/sum(counter), {$this->kpis['delay']['maxDecimals']}) as delay"),
            DB::Raw("round(sum(pkloss_sum)/sum(counter), {$this->kpis['pkloss']['maxDecimals']}) as pkloss"),
            DB::Raw("round(sum(jitter_sum)/sum(counter), {$this->kpis['jitter']['maxDecimals']}) as jitter"),
            DB::Raw("round(sum(jittermax_sum)/sum(counter), {$this->kpis['jittermax']['maxDecimals']}) as jittermax")
        )
        ->where('mes', '>=', $mesIni)
        ->where('mes', '<=', $mesFin)
        ->groupBy("devicename")->orderBy('devicename')->get();

        $kpiGroupedByDevicename_promedios = $kpiGroupedByDevicename->groupBy("devicename")
            ->map(function($row){ return $row[0]; })
            ->toArray();

        $kpisGroupedByDevicenameAndMes = DB::table('PRTLTX_CALIDAD_DET')
        ->select(DB::Raw("to_char(mes, 'yyyy-mm') as mes"), 'devicename',
            DB::Raw("round(sum(delay_sum)/sum(counter), {$this->kpis['delay']['maxDecimals']}) as delay"),
            DB::Raw("round(sum(pkloss_sum)/sum(counter), {$this->kpis['pkloss']['maxDecimals']}) as pkloss"),
            DB::Raw("round(sum(jitter_sum)/sum(counter), {$this->kpis['jitter']['maxDecimals']}) as jitter"),
            DB::Raw("round(sum(jittermax_sum)/sum(counter), {$this->kpis['jittermax']['maxDecimals']}) as jittermax")
        )
        ->where('mes', '>=', $mesIni)
        ->where('mes', '<=', $mesFin)
        ->groupBy(DB::Raw("to_char(mes, 'yyyy-mm')"), 'devicename')
        ->orderBy('devicename')
        ->get()
        ->groupBy('devicename')
        ->map(function($items, $devicename) use ($kpisOfDashboard, $kpiGroupedByDevicename_promedios){
            $dispNeNameByMes = $items->groupBy('mes');
            $dispNeNameByMes = $dispNeNameByMes->map(function($kpis){ return $kpis[0]; });

            $promedios = array_map(function($i){ return 0; }, $kpisOfDashboard);

            foreach($kpisOfDashboard as $kpiName => $kpiConfig){
                if(array_key_exists($devicename, $kpiGroupedByDevicename_promedios)){
                    $promedios[$kpiName] = round($kpiGroupedByDevicename_promedios[$devicename]->{$kpiName}, $kpiConfig['maxDecimals']);
                }
            }

            return array_merge($dispNeNameByMes->toArray(), ['promedios' => $promedios]);
        });

        return [
            'kpisGrouped' => $kpisGrouped,
            'kpisGroupedByMes' => $kpisGroupedByMes,
            // 'kpisGroupedByMesAndDevicename' => [],
            'kpiGroupedByDevicename' => $kpiGroupedByDevicename,
            'kpisGroupedByDevicenameAndMes' => $kpisGroupedByDevicenameAndMes
        ];
    }

    public function indexToLetter(int $index){
        $letter = 'A';
        for ($i=1; $i <= $index; $i++) {
            $letter++;
        }
        return $letter;
    }
}
