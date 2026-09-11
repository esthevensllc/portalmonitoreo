@php
use Illuminate\Support\Facades\DB;

$proced = app('request')->input('procedimiento') ? app('request')->input('procedimiento') : 'Todos';
$tecno = app('request')->input('tecno') ? app('request')->input('tecno') : 'Todos';

if($proced == 'Todos' && $tecno == 'Todos'){
    $totales2g = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_2g <> 'Ninguno' group by a.estado,c.procedimiento_2G,c.region order by c.procedimiento_2G")); 
    $totales3g = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_3g <> 'Ninguno' group by a.estado,c.procedimiento_3G,c.region order by c.procedimiento_3G")); 
    foreach($totales2g as $key2 => $total2g){
        foreach($totales3g as $key3 => $total3g){
            if($total2g->procedimiento == $total3g->procedimiento && $total2g->estado == $total3g->estado && $total2g->region == $total3g->region){
                $total2g[$key2]->total = $total2g[$key2]->total + $total3g[$key3]->total;
                unset($total3g[$key3]);
            }
        }
    }
    $totales = array_merge($totales2g,$totales3g);
}

if($proced == 'Todos' && $tecno != 'Todos'){
    if($tecno == '2G'){
        $totales = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_2g <> 'Ninguno' group by a.estado,c.procedimiento_2G,c.region order by c.procedimiento_2G"));
    }else{
        $totales = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where c.procedimiento_3g <> 'Ninguno' group by a.estado,c.procedimiento_3G,c.region order by c.procedimiento_3G"));
    }
}

if($proced != 'Todos' && $tecno == 'Todos'){
    $totales2g = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_2g = '".$proced."' or c.procedimiento_2g like '%_".$proced."' or c.procedimiento_2g like '%_".$proced."_%'  or c.procedimiento_2g like '".$proced."_%' ) group by a.estado,c.procedimiento_2G,c.region  order by c.procedimiento_2G")); 
    $totales3g = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_3g = '".$proced."' or c.procedimiento_3g like '%_".$proced."' or c.procedimiento_3g like '%_".$proced."_%'  or c.procedimiento_3g like '".$proced."_%' ) group by a.estado,c.procedimiento_3G,c.region  order by c.procedimiento_3G"));   
    foreach($totales2g as $key2 => $total2g){
        foreach($totales3g as $key3 => $total3g){
            if($total2g->procedimiento == $total3g->procedimiento && $total2g->estado == $total3g->estado && $total2g->region == $total3g->region){
                $total2g[$key2]->total = $total2g[$key2]->total + $total3g[$key3]->total;
                unset($total3g[$key3]);
            }
        }
    }
    $totales = array_merge($totales2g,$totales3g);
}

if($proced != 'Todos' && $tecno != 'Todos'){
    if($tecno == '2G'){
        $totales = DB::select(DB::raw("select a.estado,c.procedimiento_2G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_2G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_2g = '".$proced."' or c.procedimiento_2g like '%_".$proced."' or c.procedimiento_2g like '%_".$proced."_%'  or c.procedimiento_2g like '".$proced."_%' ) group by a.estado,c.procedimiento_2G,c.region  order by c.procedimiento_2G")); 
    }else{
        $totales = DB::select(DB::raw("select a.estado,c.procedimiento_3G as procedimiento,c.region as region,count(*) as total from PRG_CV_MULTAS_3G a inner join PRG_CV_MULTAS_GENERAL c ON a.cc = c.cc where (c.procedimiento_3g = '".$proced."' or c.procedimiento_3g like '%_".$proced."' or c.procedimiento_3g like '%_".$proced."_%'  or c.procedimiento_3g like '".$proced."_%' ) group by a.estado,c.procedimiento_3G,c.region  order by c.procedimiento_3G")); 
    }
}

$keysR = array();
foreach($totales as $keyp => $total){
    if(str_contains($total->procedimiento,'_')){
        $proceds_split = explode("_",$total->procedimiento);
        foreach($proceds_split as $proced_split){
            if($proced == 'Todos' || $proced == $proced_split ){
                $modif = 0;
                $keys = array_keys(array_column($totales,'procedimiento'),$proced_split);
                foreach($keys as $key){
                    if( $key >= 0 && isset($totales[$key])){
                        if($total->estado == $totales[$key]->estado && $total->region == $totales[$key]->region){
                            $totales[$key]->total = $totales[$key]->total + $total->total;
                            $modif = 1;
                        }                    
                    }
                }
                if($modif == 0){
                    $newdata = array('estado' => $total->estado,'procedimiento' => $proced_split,'region' => $total->region,'total' => $total->total);
                    $totales[] = (object)$newdata;
                }
            }
        }
        $keysR[] = $keyp;
    }
}

foreach($keysR as $keyR){
    unset($totales[$keyR]);
}

$totales = array_values($totales);

foreach($totales as $keyp => $total){
    $keys = array_keys(array_column($totales,'procedimiento'),$total->procedimiento);
    if(count($keys) > 1){
        foreach($keys as $key){
            if( $key != $keyp && isset($totales[$keyp]) && isset($totales[$key])){
                if($total->estado == $totales[$key]->estado && $total->region == $totales[$key]->region){
                    $totales[$keyp]->total = $totales[$key]->total + $total->total;
                    unset($totales[$key]);
                }                    
            }
        }
    }
}

$totales = array_values($totales);
$newtotales = array();
$keysR2 = array();
foreach($totales as $keyp => $total){
    $keys = array_keys(array_column($totales,'procedimiento'),$total->procedimiento);
    $newtotales[$keyp] = array('estado' => $total->estado,'procedimiento' => $total->procedimiento, $total->region => $total->total);
    foreach($keys as $key){
        if( $key != $keyp && isset($totales[$key])){
            if($total->estado == $totales[$key]->estado){
                $newtotales[$keyp][$totales[$key]->region] = $totales[$key]->total;
                if( $key > $keyp){
                    $keysR2[] = $key;
                }
            }
        }
    }
}

foreach($keysR2 as $keyR2){
    if(isset($newtotales[$keyR2])){
        unset($newtotales[$keyR2]);
    }
}

$centroT = $limaT = $norteT1 = $norteT2 = $surT = 0;

foreach($newtotales as $total){
    $centroT+=(isset($total['CENTRO'])?$total['CENTRO']:0);
    $limaT+=(isset($total['LIMA'])?$total['LIMA']:0);
    $norteT1+=(isset($total['NORTE1'])?$total['NORTE1']:0);
    $norteT2+=(isset($total['NORTE2'])?$total['NORTE2']:0);
    $surT+=(isset($total['SUR'])?$total['SUR']:0);
}

@endphp
<style>
 .table td, .table th{   
    padding: 0.08em!important;
 }
</style>
<div class="col-md-6">  <div class="card">
    <div class="card-header">TABLA RECUENTO DE MULTAS</div>
        <div class="card-body">
            <div class="card-wrapper">     
                <table class="table table-striped">
                    <thead>
                        <tr class="bg-danger">
                        <th scope="col">Procedimiento</th>
                        <th scope="col">Estado</th>
                        <th scope="col">CENTRO</th>
                        <th scope="col">LIMA</th>
                        <th scope="col">NORTE1</th>
                        <th scope="col">NORTE2</th>
                        <th scope="col">SUR</th>
                        <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($newtotales as $keyp => $total)
                        <tr>
                            <td>{{$total['procedimiento']}}</td>
                            <td>{{$total['estado']}}</td>
                            <td class="centro">@if(isset($total['CENTRO'])){{$total['CENTRO']}}@endif</td>
                            <td class="lima">@if(isset($total['LIMA'])){{$total['LIMA']}}@endif</td>
                            <td class="norte1">@if(isset($total['NORTE1'])){{$total['NORTE1']}}@endif</td>
                            <td class="norte2">@if(isset($total['NORTE2'])){{$total['NORTE2']}}@endif</td>
                            <td class="sur">@if(isset($total['SUR'])){{$total['SUR']}}@endif</td>
                            <td class="total"><strong>{{(isset($total['CENTRO'])?$total['CENTRO']:0)+(isset($total['LIMA'])?$total['LIMA']:0)+(isset($total['NORTE1'])?$total['NORTE1']:0)+(isset($total['NORTE2'])?$total['NORTE2']:0)+(isset($total['SUR'])?$total['SUR']:0)}}</strong></td>
                        </tr>                        
                    @endforeach
                        <tr class="table-active">
                            <td><strong>Total</td>
                            <td></td>
                            <td class="centroT"><strong>{{$centroT}}</strong></td>
                            <td class="limaT"><strong>{{$limaT}}</strong></td>
                            <td class="norteT1"><strong>{{$norteT1}}</strong></td>
                            <td class="norteT2"><strong>{{$norteT2}}</strong></td>
                            <td class="surT"><strong>{{$surT}}</strong></td>
                            <td class="totalT"><strong>{{$centroT+$limaT+$norteT1+$norteT2+$surT}}</strong></td>
                        </tr>                                         
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
