<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

/**
 * Class CVRESUMENChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CvResumenMultasChartController extends ChartController
{
    public function setup($proced,$tecno)
    {
        $proced = $proced ? $proced : 'Todos';
        $tecno = $tecno ? $tecno : 'Todos';

        if($proced == 'Todos' && $tecno == 'Todos'){
            $totales2g = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_2g <> 'Ninguno' group by a.estado,c.procedimiento_2G order by c.procedimiento_2G")); 
            $totales3g = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_3g <> 'Ninguno' group by a.estado,c.procedimiento_3G order by c.procedimiento_3G"));   
        }
        if($proced != 'Todos' && $tecno == 'Todos'){
            $totales2g = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_2g = '".$proced."' or c.procedimiento_2g like '%_".$proced."' or c.procedimiento_2g like '%_".$proced."_%'  or c.procedimiento_2g like '".$proced."_%' ) group by a.estado,c.procedimiento_2G order by c.procedimiento_2G")); 
            $totales3g = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_3g = '".$proced."' or c.procedimiento_3g like '%_".$proced."' or c.procedimiento_3g like '%_".$proced."_%'  or c.procedimiento_3g like '".$proced."_%' ) group by a.estado,c.procedimiento_3G order by c.procedimiento_3G"));   
        }
        if($proced == 'Todos' && $tecno != 'Todos'){
            if($tecno =='2G'){
                $totales = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_2G <> 'Ninguno' group by a.estado,c.procedimiento_2G,c.region order by c.procedimiento_2G"));  
            }else{
                $totales = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_3G <> 'Ninguno' group by a.estado,c.procedimiento_3G,c.region order by c.procedimiento_3G"));  
            }            
        }
        if($proced != 'Todos' && $tecno != 'Todos'){
            if($tecno =='2G'){
                $totales = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_2g = '".$proced."' or c.procedimiento_2g like '%_".$proced."' or c.procedimiento_2g like '%_".$proced."_%'  or c.procedimiento_2g like '".$proced."_%' ) group by a.estado,c.procedimiento_2G order by c.procedimiento_2G"));
            }else{
                $totales = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_3g = '".$proced."' or c.procedimiento_3g like '%_".$proced."' or c.procedimiento_3g like '%_".$proced."_%'  or c.procedimiento_3g like '".$proced."_%' ) group by a.estado,c.procedimiento_3G order by c.procedimiento_3G"));   
            }  
        }
       
        $this->chart = new Chart();

        if(isset($totales)){
            $data = $this->calculaTotales($totales,$proced);
        }else{
            $data1 = $this->calculaTotales($totales2g,$proced);
            $data2 = $this->calculaTotales($totales3g,$proced);

            $data = [$data1[0] + $data2[0],$data1[1] + $data2[1]];             
        }


        $this->chart->dataset('My dataset', 'pie', $data)
        ->backgroundColor([
            'blue',
            'red',
        ]);


        if($data[1]){
            // MANDATORY. Set the labels for the dataset points
            $this->chart->labels(['Solucionado '.$data[0].' - '.($data[0]/($data[0]+$data[1])*100).'%', 'Pendiente '.$data[1].' - '.($data[1]/($data[0]+$data[1])*100).'%']);
        }else{
            $this->chart->labels(['Solucionado '.$data[0].' - 0%', 'Pendiente '.$data[1].' - 0%']);
        }

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/cv_multas?procedimiento='.$proced.'&tecno='.$tecno));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayAxes(false);
        $this->chart->displayLegend(true);
    }

    public function calculaTotales($totales,$proced){
        $totalPendiente = 0;
        $totalSolucionado = 0;

        foreach($totales as $total){
            if($total->estado == 'Pendiente'){
                if(str_contains($total->procedimiento,'_')){
                    $proced_split = explode("_",$total->procedimiento);
                    if($proced == 'Todos'){
                        $totalPendiente = $totalPendiente + (intval($total->total)*count($proced_split));
                    }else{
                        $totalPendiente = $totalPendiente + intval($total->total);
                    }                    
                }else{
                    $totalPendiente = $totalPendiente + intval($total->total);
                }
            }else{
                if($total->estado == 'Solucionado'){
                    if(str_contains($total->procedimiento,'_')){
                        $proced_split = explode("_",$total->procedimiento);
                        if($proced == 'Todos'){
                            $totalSolucionado = $totalSolucionado + (intval($total->total)*count($proced_split));
                        }else{
                            $totalSolucionado = $totalSolucionado + intval($total->total);
                        }
                    }else{
                        $totalSolucionado = $totalSolucionado + intval($total->total);
                    }
                }
            }
        }
        return [$totalSolucionado,$totalPendiente];
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    // public function data()
    // {
    //     $users_created_today = \App\User::whereDate('created_at', today())->count();

    //     $this->chart->dataset('Users Created', 'bar', [
    //                 $users_created_today,
    //             ])
    //         ->color('rgba(205, 32, 31, 1)')
    //         ->backgroundColor('rgba(205, 32, 31, 0.4)');
    // }
}