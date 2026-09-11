@extends(backpack_view('blank'))

@section('after_styles')
<style>
    h1,h1,h3,h4,h5,h6, td, th, tbody, thead, button, input, select{
        font-family: sans-serif !important;
    }
    div select {
        /*max-width: 130px;*/
    }

    .itmDis{
        color:#DBDBDB;
    }
    
    #map {
        height: 82vh;
        width: 100%;
        /*position: relative;*/
        /*margin-left: -15px;*/
    }
      /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        /*font-size: 12px;*/
    }
    #legend,#legend2,#legend3,#legend4{
        /*position: fixed;*/
        font-family: Arial, sans-serif;
        background: #fff;
        padding: 10px;
        border: 1px solid #acacac;
        /*bottom: 100px;
        right: 60px;*/
        border-radius: 10px;
        margin: 10px;
    }
    #legend2{
        bottom: 372px;
    }
    #legend3{
        display:none;
        bottom: 500px;
    }
    #legend4{
        position: absolute;
        width: 250px;
        height: 400px;
        bottom: 100px;
        left: 20px;
    }
    #legend label:not(.lgndColor){
        padding: 10px;
        border-radius: 19px;
        width: 10px;
        height: 10px;
    }
    #legend h3{
        margin-top: 0;
    }
    #legend img{
        vertical-align: middle;
    }
    #legend2 label:not(.lgndColor){
        padding: 10px;
        border-radius: 19px;
        width: 10px;
        height: 10px;
    }
    #legend2 h3{
        margin-top: 0;
    }
    #legend2 img{
        vertical-align: middle;
    }
    #filtro{
        position: fixed;
        top: 100px;
        left: 20px;
        background:#fff;
        padding: 10px;
        border-radius: 10px;
    }
    .lgndColor{
        background: #000;
        width:10px;
        height: 10px;
        text-align: center;
        /*border: 1px solid;*/
        margin: 0 5px 0 0;
        cursor: pointer;
    }
    .lgndColor.unchecked{
        background: white!important;
    }
      
    .lgndColor.c100{ background: #006600; }
    .lgndColor.c80{ background: #009933; }
    .lgndColor.c60{ background: #33CC33; }
    .lgndColor.c50{ background: #66FF33; }
    .lgndColor.c40{ background: #D3F527; }
    .lgndColor.c30{ background: #FFCC00; }
    .lgndColor.c20{ background: #FF6600; }
    .lgndColor.c10{ background: #FF0000; }

    .lgndColor2.c60{
        background: #147df5;
    }
    .lgndColor2.c30{
        background: #0aff99;
    }
    .lgndColor2.c10{
        background: #ffd300;
    }
    .lgndColor.cNew{
        background: #001390;
    }
    .lgndColor.avance{
        background: #00FF00;
    }
    .lgndColor.cgreen{
        background: #008f39;
    }      
    #dataMapInd{
        /*position: absolute;*/
        /*left: 20px;*/
        background: #FFF;
        padding: 10px;
        border-radius: 10px;
        /*margin: 60px 10px;*/
        /*max-height: 500px;
        overflow-y: scroll;*/
        padding-top: 24px;
        opacity: .9;
        display: none;
        top: 0;
    }
    #dataMapInd table td{
        border: 1px solid #bdbdbd;
        font-size: 14px;
    }
    #dataMapInd table td.table-secondary{
        background: #CED4DE;
    }
    .sectTabsContent div select{
        max-width: 130px;
    }
    .lengend{
        /*position: absolute;*/
        background: #fff;
        opacity: .95;
        border-radius: 5px;
    }
    .lgnd-graph{
        position: absolute;
        min-height: 100px;
        width: 600px;  
        /*left: 320px;*/
    }
    .lgnd-header{
        cursor: move;
    }
    .lgnd-close, .lgnd-planos-close{
        font-size: 15px;
        float: right;
        font-weight: bold;
        cursor: pointer;
    }
    .lgnd-planos{
        min-height: 300px;
        width: 100%;
    }
    .lgnd-planos table{
        font-size: 14px;
    }
    .modal-dialog {
      max-width: 1000px;
    }
    .interaccion-number {
        cursor: pointer;
        color: #3f3fff;
    }
    .polygon-table-data tbody tr td, .polygon-sub-table-data tbody tr td {
        width: 100%;
        font-size: 14px !important;
        white-space: nowrap;
    }
    .panel-map{
        flex-grow: 1;
        border-radius: 8px;
        overflow: hidden;
    }
    .panel-options{
        width: 246px;
        max-height: 82vh;
    }
    .panel-data{
        /*position: absolute;
        right: 60px !important;
        top: 0px !important;*/
    }
    .panel-data, .panel-planos{
        /*width: 330px;*/
        max-height: 82vh;
        /*overflow-y: scroll;
        max-height: 88.5vh;*/
    }
    .panel-options-content, .panel-data-content{
        overflow-y: scroll;
    }
    #legend_layers{
        margin-left: 10px;
    }
    .layer-item {
        display: flex;
        align-items: center;
        padding-bottom: 5px;
    }
    .layer-item label {
        padding-left: 5px;
    }
    .btn-toogle-layers{
        cursor: pointer;
    }

    .table_top{
        font-size: 14px !important;
        padding: 1px;
        background: white;
        display: none;
    }
    .table_top .search{
        cursor: pointer;
        color: blue;
    }
</style>
@endsection

@section('header')
<!-- Modal -->
<div class="modal fade" id="detalleTablaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">FIJA PLANOS COMPETENCIA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table" id="tabla-dinamica">
            <thead>
                <tr>
                <th>operador</th>
                <th>muestra total</th>
                <th>descarga promedio (Mbps)</th>
                <th>upload promedio (Mbps)</th>
                <th>latencia promedio (MS)</th>
                <th>posible tecnologia</th>
                <th>mejor operador descarga</th>
                <th>mejor operador upload</th>
                <th>mejor operador latencia</th>
                <th>mejor operador</th>
                </tr>
            </thead>
            <tbody></tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="pb-2 d-flex align-items-center" style="justify-content: flex-start;">
    <select class="slc-mapKpiType form-control form-control-sm mr-2" style="max-width: 150px; background: #df4759; color: #fff;">
        <option value="0" disabled="" class="">---Tipo KPI---</option>
        <?php
        foreach($kpis as $row){
            if($row->ID === 10 || $row->ID === 11){ continue; }
            $tooltip = property_exists($row, "DESC") ? "data-toggle=\"tooltip\" data-placement=\"right\" title=\"{$row->DESC}\"" : "";
            echo "<option value='{$row->ID}' data-data='".json_encode($row)."' ".($row->ID === 1 ? 'selected':'')."
            {$tooltip}
            >{$row->LABEL}</option>";
        }
        ?>
    </select>
    <button class="btn btn-danger btn-sm btn-toogle-options" data-toogle="false">
    <i class="la la-cog"></i>
    Personalizar
    </button>
</div>
<div class="d-flex" style="gap: 10px;">

<div class="panel-options" style="display: none;">
    <div class="panel-options-content d-flex card h-100">
        <div class="card-body p-3">
            <div style="flex-grow: 1;">
                <select class="slc-group btn-sm btn-primary d-none">
                    <option value="1">Semanal</option>
                    <option value="2">Ultimas 4 semanas</option>
                </select>
                <label class="font-weight-bold" data-toggle="tooltip" data-placement="right" title="sdfsd">Año y Semana</label>
                <div class="d-inline-block">
                    <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend">
                            <select class="slc-ano btn btn-sm btn-danger" style="max-width: 67px;">
                                <option value="0" disabled="" class="itmDis">-- Año --</option>
                                <?php foreach ($ano as $key => $item): ?>
                                    <option value="<?=$item->ano?>" data-maxsemana="<?=$item->max_semana?>"><?=$item->ano?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <select class="slc-semana btn btn-sm btn-danger" style="max-width: 55px;">
                            <option selected="" value="0" disabled>-- Semana --</option>
                        </select>
                    </div>
                </div>
                <div class="pb-2">
                <select class="slc-distribucion btn btn-sm btn-danger text-left">
                    <option value="1">Departamento</option>
                    <option value="2">Provincia</option>
                    <option value="3">Distrito</option>
                    <option selected="" value="4">Planos</option>
                </select>
                </div>

                <h6 class="font-weight-bold">Filtros</h6>

                <select class="slc-ubgDpto form-control form-control-sm d-inline-block slc-filter mb-2" name="ubigeo_dpto">
                    <option selected="" value="0">---Departamento--</option>
                    <?php foreach ($dptoList as $key => $item): ?>
                        <option value="<?=$item['CODE']?>"><?=$item['DEPARTAMENTO']?></option>
                    <?php endforeach ?>
                </select>
                <select class="slc-ubgProv form-control form-control-sm d-inline-block slc-filter mb-2" name="ubigeo_prov">
                    <option selected="" value="0">---Provincia---</option>
                </select>
                <select class="slc-ubgDist form-control form-control-sm d-inline-block slc-filter mb-2" name="ubigeo">
                    <option selected="" value="0">---Distrito---</option>
                </select>

                <select class="form-control form-control-sm d-inline-block slc-filter mb-2" name="tecnologia">
                    <option selected="" value="0">---Tecnologia---</option>
                    <option value="FTTH">FTTH</option>
                    <option value="HFC">HFC</option>
                </select>
                <select class="form-control form-control-sm d-inline-block mb-2 slc-cobertura-otros">
                    <option selected="" value="0">---Cobertura otros---</option>
                    <option value="WIN" data-geojson="false">WIN</option>
                    <option value="WOW" data-geojson="true">WOW</option>
                </select>
                <select class="form-control form-control-sm d-inline-block mb-2 slc-competencia">
                    <option selected="" value="0">---Competencia---</option>
                    @foreach ($competencias as $row)
                        <option value="{{ $row->operador }}">{{ $row->operador }}</option>
                    @endforeach
                </select>
            </div>
                   
            <button class="btn btn-outline-primary btn-sm btn_open_planos_modal" type="button">
                <i class="la la-eye"></i>
                Buscar
            </button>
            <button class="btn btn-outline-primary btn-sm btn_export" type="button">
                <i class='la la-export'></i>
                Exportar
            </button>

            
            <div id="planos_modal" class="lengend lgnd-planos panel-planos" style="display: none; z-index: 2; top:0;">
                <div class="card-header d-flex px-2">
                    <div class="">
                        <h5 class="mb-0 title">Busqueda Planos</h5>
                    </div>
                    <div style="flex-grow: 1;">
                        <b class="lgnd-planos-close">x</b>
                    </div>
                </div>
                <div class="">
                    <div>
                        <h5></h5>
                        <div class="mb-2">
                        <input type="text" class="form-control form-control-sm input_search" placeholder="Ir a lat lng / Ingresar busqueda">
                        </div>
                        <div class="table-responsive mb-2" style="max-height: 300px;">
                        <table class="table table-sm" id="tbl_duplicados">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>
                        <div class="list-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel-map">
    <div id="map" style=""></div>
</div>

<div class="panel-data card mb-0" style="display: none;">
    <div class="card-header d-flex px-2">
        <div class="">
            <h5 class="mb-0 title">Detalle de Plano</h5>
        </div>
        <div style="flex-grow: 1;">
            <b class="lgnd-close">x</b>
        </div>
    </div>
    <div class="panel-data-content">
        <div id="dataMapInd" style="display: none; overflow-y: auto; padding: 10px; overflow-x: hidden;">dataMapInd</div>
    </div>
</div>


<div style="position: relative; display: none;">
    <div id="legend">
        <div class="legends-other leg_clientes_fija mb-2" style="display: none;"><h6 class='font-weight-bold'>Clientes Total</h6>
        <table class="data" style="font-size: 14px;">
        <tr><td class="lgndColor"  style="background: #FFF;"><img src="{{ asset('images/person_icon.png') }}"></td><td class="pl-2">Cliente Fija</td></tr>
        <tr><td class="lgndColor" style="background: #FFF;"><img src="{{ asset('images/person_icon_verde.png') }}"></td><td class="pl-2">Cliente Fija Full Claro</td></tr>
        </table></div>

        <div class="legends-other leg_clientes_fija_movil mb-2" style="display: none;"><h6 class='font-weight-bold'>Clientes Total Movil</h6>
        <table class="data" style="font-size: 14px;">
        <tr><td class="lgndColor c20" style="background: #FFF;"><img src="{{ asset('images/person_icon_purpura.png') }}"></td><td class="pl-2">Cliente Movil</td></tr>
        <tr><td class="lgndColor c10" style="background: #FFF;"><img src="{{ asset('images/person_icon_azul.png') }}"></td><td class="pl-2">Cliente Movil Full Claro</td></tr>
        </table></div>

        <div class="legends leg1"><h6 class='font-weight-bold'>Penetracion %</h6>
        <table class="data" style="font-size: 14px;">
        <tr><td class="lgndColor c10"></td><td class="pl-2">0 - 10</td></tr>
        <tr><td class="lgndColor c20"></td><td class="pl-2">10 - 20</td></tr>
        <tr><td class="lgndColor c30"></td><td class="pl-2">20 - 30</td></tr>
        <tr><td class="lgndColor c40"></td><td class="pl-2">30 - 40</td></tr>
        <tr><td class="lgndColor c50"></td><td class="pl-2">40 - 50</td></tr>
        <tr><td class="lgndColor c60"></td><td class="pl-2">50 - 60</td></tr>
        <tr><td class="lgndColor c80"></td><td class="pl-2">60 - 80</td></tr>
        <tr><td class="lgndColor c100"></td><td class="pl-2">80 - 100</td></tr>
        </table></div>
        <?php
            foreach($kpis as $row){
                if(property_exists($row, 'COLOR_RANGE')){
                    echo "<div class=\"legends leg{$row->ID} hideManual\" style=\"display: none;\">
                    <h6 class='font-weight-bold'>{$row->LABEL}</h6>
                    <table class=\"data\" style=\"font-size: 14px;\">
                    ";
                    foreach($row->COLOR_RANGE as $range){
                        $CONDITION = str_replace("KPI && KPI", "KPI", $range->CONDITION);
                        $CONDITION = str_replace("==", "=", $CONDITION);
                        $value = "<tr>
                            <td class=\"lgndColor2 mb-0\" style=\"background: {$range->COLOR}; width: 10px;\"></td>
                            <td class=\"pl-2\">{$CONDITION}</td>
                        </tr>";
                        echo $value;
                    }
                    echo "</table></div>";
                }
            }
        ?>
    </div>
</div>

</div>

<div id="modal_graph" class="lengend lgnd-graph" style="display: none; z-index: 1; top: 111px; left: 227px;">
    <div class="p-3">
        <div class="row lgnd-header">
            <div class="col-10">
            <h5></h5>
            </div>
            <div class="col-2">
            <b class="lgnd-close">x</b>
            </div>
        </div>
        <div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="graph-tab" data-bs-toggle="tab" data-bs-target="#tab-graph" type="button" role="tab" aria-controls="home">Grafica</button>
                </li>
                <li class="nav-item " role="presentation">
                    <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#tab-table" type="button" role="tab" aria-controls="profile">Tabla</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane" id="tab-graph" role="tabpanel">
                    <div id="graphContainer1"></div>
                </div>
                <div class="tab-pane fade show active" id="tab-table" role="tabpanel">
                    <div style="overflow: scroll; max-height: 350px;">
                        <table class="table table-sm polygon-table-data" style="width: 100%;">
                            <thead>
                            <tbody>
                            </tbody>
                        </table>
                        <table class="table table-sm polygon-sub-table-data">
                            <thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div></div>
</div>

<div id="legend_layers">
    <div class="card p-2 mb-2 d-inline-block btn-toogle-layers" data-toogle="false"><i class="la la-layer-group h4 mb-0"></i></div>
    <div class="card layers-content" style="display: none;">
        <div class="card-body p-2">
            <div class="layer-item">
                <input class="chk_nuevos" type="checkbox" id="checkbox_nuevos">
                <label class="form-check-label" for="checkbox_nuevos">Mostrar Nuevos</label>
            </div>
            <div class="layer-item">
                <input class="chk_nuevos check_nuevos_item" type="radio" name="check_nuevos" id="checkbox_nuevos_60" data-flagprop="flag_new" checked>
                <label class="form-check-label" for="checkbox_nuevos_60">2 meses</label>
                <input class="chk_nuevos check_nuevos_item ml-2" type="radio" name="check_nuevos" id="checkbox_nuevos_90" data-flagprop="flag_new_90">
                <label class="form-check-label" for="checkbox_nuevos_90">3 meses</label>
                <input class="chk_nuevos check_nuevos_item ml-2" type="radio" name="check_nuevos" id="checkbox_nuevos_180" data-flagprop="flag_new_180">
                <label class="form-check-label" for="checkbox_nuevos_180">6 meses</label>
            </div>
            <div class="layer-item">
            </div>
            <div class="layer-item">
                <input class="" type="checkbox" id="checkbox_verticales">
                <label class="form-check-label" for="checkbox_verticales">Mostrar Verticales Liberados</label>
            </div>
            <div class="layer-item">
                <input class="" type="checkbox" id="check_overlap">
                <label class="form-check-label" for="check_overlap">Overlap</label>
            </div>
            <div class="layer-item">
                <input class="" type="checkbox" id="check_num_competencia">
                <label class="form-check-label" for="check_num_competencia">Número Competencia</label>
            </div>
            <div class="layer-item">
                <input class="" type="checkbox" id="check_top_sites_tethering">
                <label class="form-check-label" for="check_top_sites_tethering">Top Sites Tethering</label>
            </div>
            <div class="layer-item">
                <input class="" type="checkbox" id="check_flag_mala_venta">
                <label class="form-check-label" for="check_flag_mala_venta">Flag Mala Venta</label>
            </div>
            <div class="layer-item">
                <input class="" type="checkbox" id="check_limite_distritos">
                <label class="form-check-label" for="check_limite_distritos">Limite Distritos</label>
            </div>
            <div class="layer-item">
                <button class="btn btn-sm btn-secondary px-1 py-0 btn-buscar-fats">Buscar Factibilidad</button>
            </div>
            <h6>Tecnologia</h6>
            <div class="layer-item">
                <input class="check_tecnologia" type="radio" name="check_tecnologia" id="check_tec_hfc" value="HFC">
                <label class="form-check-label" for="check_tec_hfc">HFC</label>
            </div>        
            <div class="layer-item">
                <input class="check_tecnologia" type="radio" name="check_tecnologia" id="check_tec_ftth" value="FTTH">
                <label class="form-check-label" for="check_tec_ftth">FTTH</label>
            </div>        
            <div class="layer-item">
                <input class="check_tecnologia" type="radio" name="check_tecnologia" id="check_tec_todos" value="" checked>
                <label class="form-check-label" for="check_tec_todos">Todos</label>
            </div> 
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <table class="table table-sm table_top">
            <thead></thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@include("vendor.backpack.base.widgets.spinner_loader")

@endsection

@section('after_scripts')
<script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('packages/highcharts/highcharts.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-data.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-exporting.js') }}"></script> --}}
{{-- <script type="text/javasc" src="{{ asset('packages/highcharts/highcharts-export-data.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAu0W37BXvxQqFY5I8DofqBmS8CEHQ__P8&v=weekly" async></script> --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAu0W37BXvxQqFY5I8DofqBmS8CEHQ__P8&v=weekly"></script>
@include("includes.apexcharts_js")
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script>
let mapConfig = {
    'mapurl': "{{ asset('map/'.$planoUrl.'.json') }}",
    kpiConfig: @json($kpis),
    kpiConfigById: {},
    distribucion: [
        {
            id: 1,
            label: 'Departamento',
            name: 'departamento',
            zoomIn: undefined,
            featProps: {id: 'cod_dep'},
            dataProps: {id: 'ubigeo_dpto', label: ['departamento']},
            mapurl: "{{ asset('map/peru_regiones.json') }}"
        },
        {
            id: 2,
            label: 'Provincia',
            name: 'provincia',
            zoomIn: 8,
            featProps: {id: 'FIRST_IDPR'},
            dataProps: {id: 'ubigeo_prov', label: ['departamento','provincia']},
            mapurl: "{{ asset('map/peru_provincias.json') }}"
        },
        {
            id: 3,
            label: 'Distrito',
            name: 'distrito',
            zoomIn: 10,
            featProps: {id: 'IDDIST'},
            dataProps: {id: 'ubigeo', label: ['departamento','provincia','distrito']},
            mapurl: "{{ asset('map/peru_distritos.json') }}"
        },
        {
            id: 4,
            label: 'Plano',
            name: 'plano',
            zoomIn: 15,
            featProps: {id: 'NOMBRE'},
            dataProps: {id: 'plano', label: ['plano']},
            mapurl: "{{ asset('map/'.$planoUrl.'.json') }}"
        }
    ],
    distribucionById: {},
    detailFieldsByDistribucion: @json($detailFieldsByDistribucion)
};
/*
const propIdByGroup = {
    plano: {feat: 'Nombre', data: 'PLANO', dataLabel: ['PLANO']},
    distrito: {feat: 'IDDIST', data: 'UBIGEO', dataLabel: ['DEPARTAMENTO','PROVINCIA','DISTRITO']},
    provincia: {feat: 'FIRST_IDPR', data: 'UBIGEO_PROV', dataLabel: ['DEPARTAMENTO','PROVINCIA']},
    departamento: {feat: 'cod_dep', data: 'UBIGEO_DPTO', dataLabel: ['DEPARTAMENTO']}
};
*/
let store = {
    features: [],
    cobOtrosFeatures: [],
    data_fija: [],
    polygonSelected: {key: undefined},
    //markers_verticales: [],
    verticalesLiberados: {
        markers: [],
        data: []
    },
    chart: undefined,
    infoWindow: undefined,
    labelNumCompetencia: {
        show: false,
        markers: [],
        timer: undefined
    },
    busquedaFats:{
        originMarker: undefined,
        features: [],
        fatsMarkers: []
    },
    topSitesTetheringMarkers: [],
    ubigeos: [],
    limiteDistritosFeatures: []
};
let map;
let test;
let base_url = "{{ asset('') }}";
let loadMarker = [];
let markers = [];
let planoCompetencia = '';
let searchMarker = null;

// Define el ícono personalizado
var iconoPersonalizado = {
  url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
  scaledSize: new google.maps.Size(32, 32),
};

$(document).ready(function () {
    class GraphModal{
        constructor(el_selector){
            this.el_selector = el_selector;
            this.attachHandlers();
        }

        attachHandlers(){
            let el_selector = this.el_selector;
            $(el_selector+' .lgnd-close').on('click', function(){
                $(el_selector).hide();
            });
            $(el_selector).draggable({handle: ".lgnd-header"});
        }
        
        show(){
            $(this.el_selector).show();
        }
        hide(){
            $(this.el_selector).hide();
        }

        setTitle(title){
            $(this.el_selector+' .title').text(title);
        }
    }

    class PlanosModal extends GraphModal
    {
        constructor(el_selector){
            super(el_selector);
            this.el_input = `${el_selector} .input_search`;
            this.el_table = `${el_selector} table`;
            this.handlers = {'input_keyup': []};
            this.planos = [];
            this.title = '';
            this.props_to_use = {};
            this.text_to_filter = '';
            let _this = this;

            //this.handlers['input_keyup'].push(this.keyup_handler);
            let keyupTimer;
            $(this.el_input).keyup((e) => {
                clearTimeout(keyupTimer);
                keyupTimer = setTimeout(function () {
                _this.keyup_handler(e);
                _this.handlers['input_keyup'].forEach(event => {
                    event(e);
                });
                }, 250);
            });
            
            $(this.el_input).trigger('keyup');
            $(this.el_input).trigger('keyup');
        }

        attachHandlers(){
            let el_selector = this.el_selector;
            $(el_selector+' .lgnd-planos-close').on('click', function(){
                $(el_selector).hide();
            });
            $(el_selector).draggable({handle: ".lgnd-header"});
        }

        setTitle(title){
            this.title = title;
        }

        setDistribucionConfig(to_use){
            this.props_to_use = to_use;
        }

        addListener(key, callback){
            this.handlers['input_keyup'].push(callback);
        }

        delay_input_handler(callback, ms) {
            let timer = 0;
            return function() {
                let context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        keyup_handler(e){
          this.text_to_filter = e.target.value;
          this.render();
        }

        setPlanos(planos){
          this.planos = planos;
          this.render();
        }

        // 1) variable en el scope del módulo/componente (una sola instancia)


        // 2) en tu función, reemplaza el bloque de coordenadas:
        ver_handler(name, latitud, longitud) {
            // ... tu código previo
            console.log(this);
            let features = [];
            const props_to_use = this.props_to_use;
            store.features.forEach(el => {
                let prop_value = el.getProperty(this.props_to_use.featProps.id);
                if(prop_value !== undefined){
                    prop_value = prop_value.replaceAll('\n', '').replace('\r', '')
                }
                if(prop_value === name){
                    features.push(el);
                }else{
                    map.data.overrideStyle(el, {strokeWeight: el.getProperty('strokeWeight')});
                }
            });
            if(latitud !== undefined && longitud !== undefined){
                const lat = parseFloat(latitud);
                const lng = parseFloat(longitud);
                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    // crear o mover el marcador temporal
                    if (!searchMarker) {
                        searchMarker = new google.maps.Marker({
                            position: { lat, lng },
                            map,
                            title: name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`,
                            // opcional: icono simple para diferenciarlo
                            // icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                        });
                    } else {
                        searchMarker.setPosition({ lat, lng });
                        searchMarker.setMap(map);
                        searchMarker.setTitle(name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                    }
                    map.setZoom(props_to_use['zoomIn']??15);
                    map.setCenter({lat: parseFloat(latitud), lng: parseFloat(longitud)});
                    return;
                }
            }
            const id_distribucion = $(".slc-distribucion").val();
            const kpi_id = $(".slc-mapKpiType").val();
            const distribucion = mapConfig.distribucionById[id_distribucion];
            
            const filter = `${distribucion.dataProps.id}.eq.${name}`;
            store.polygonSelected = {id: name};
            viewDataMap(distribucion.id, kpi_id, [filter]);
            graphModalView.hide();
            
            if(features.length > 0){
                let isCenter = false;
                features.forEach(ft => {
                    map.data.overrideStyle(ft, {strokeWeight: 5});
                });
                features[0].getGeometry().forEachLatLng(function(path) {
                    if(isCenter === false){
                        if(features[0].getProperty("centroide_longitud")){
                            const centroideLat = Number(features[0].getProperty("centroide_latitud"));
                            const centroideLng = Number(features[0].getProperty("centroide_longitud"));
                            console.log({lat: features[0].getProperty("centroide_latitud"), lng: features[0].getProperty("centroide_longitud")});
                            if (!searchMarker) {
                                searchMarker = new google.maps.Marker({
                                    position: {lat: centroideLat, lng: centroideLng},
                                    map,
                                });
                            } else {
                                searchMarker.setPosition({lat: centroideLat, lng: centroideLng});
                                searchMarker.setMap(map);
                                searchMarker.setTitle(name);
                            }
                            map.setCenter({lat: centroideLat, lng: centroideLng});
                        }else{
                            map.setCenter({lat: path.lat(), lng: path.lng()});
                        }
                        if(props_to_use['zoomIn'] !== undefined){
                            map.setZoom(props_to_use.zoomIn);
                        }
                    }
                    isCenter=true;
                });
            }else{
                store.store.verticalesLiberados.markers.forEach(marker => {
                    if(marker.data.nodo === name){
                        map.setCenter({lat: marker.getPosition().lat(), lng: marker.getPosition().lng()});
                        if(props_to_use.zoomIn){
                            map.setZoom(props_to_use.zoomIn+3);
                        }
                        marker.setMap(map);
                    }else{
                        const checked = document.querySelector("#checkbox_verticales").checked;
                        marker.setMap(checked ? map : null);
                    }
                });
            }            
        }
        _getLabelOfRow(row){
            if (row?.label) {
                return row.label;
            }
            const props_label =this.props_to_use.dataProps.label;
            const value = props_label.map(prop => row[prop]).join('-');
            
            return value !== null && value !== undefined ? value : '';
        }

        render(){
            let _this = this;
            let list_filtered = this.planos.filter(row => this._getLabelOfRow(row).toUpperCase().includes(this.text_to_filter.toUpperCase()))
            .map(row => `<tr>
                <td>${this._getLabelOfRow(row)}</td>
                <td><button class="btn btn-sm btn-secondary p-0 pr-1 pl-1" data-name="${row[this.props_to_use.dataProps.id]}" data-lat="${row.latitud ?? ''}" data-lng="${row.longitud ?? ''}">Ver</button></td>
            </tr>`);
            
            let latLng = this.text_to_filter.split(" ").filter(v => !isNaN(parseFloat(v)));
            if(latLng.length === 2){
                list_filtered = [`<tr>
                    <td>Ir a ${latLng[0]} ${latLng[1]}</td>
                    <td><button class="btn btn-sm btn-secondary p-0 pr-1 pl-1" data-name="" data-lat="${latLng[0]}" data-lng="${latLng[1]}">Ver</button></td>
                </tr>`];
            }

            const html = list_filtered.join('');
            $(`${this.el_selector} .title`).text(this.title);
            $(`${this.el_selector} .list-footer`).html(`<p>${list_filtered.length} de ${this.planos.length}</p>`);
            $(`${this.el_table} tbody`).html(html);

            $(`${this.el_table} tbody button`).off('click');
            $(`${this.el_table} tbody button`).on('click', (e) => {
                _this.ver_handler(
                    $(e.target).attr('data-name'),
                    $(e.target).attr('data-lat'),
                    $(e.target).attr('data-lng')
                );
            });
        }
        
    }

    const selectors = {
        groupBy: (data, keyProp) => {
            if(Array.isArray(keyProp)){
                let data_grouped = {};
                for (let i = 0; i < data.length; i++) {
                    const key = keyProp.map(k => data[i][k]).join("-");
                    if(data_grouped[key] === undefined){
                        data_grouped[key] = [];
                    }
                    data_grouped[key].push(data[i]);
                }
                return data_grouped;
            }
            let data_grouped = {};
            for (let i = 0; i < data.length; i++) {
                const key = data[i][keyProp];
                if(data_grouped[key] === undefined){
                    data_grouped[key] = [];
                }
                data_grouped[key].push(data[i]);
            }
            return data_grouped;
        }
    };

    const api = {
        getDataFija: (options) => {
            return $.ajax({
                url:base_url+"fija-coverage/data-fija",
                method: "POST",
                dataType: 'json',
                ...options
            });
        },
        getDataCoberturaOtros: (options) => {
            return $.ajax({
                url:base_url+"fija-coverage/cobertura-otros",
                method:"POST",
                dataType: 'json',
                ...options
            });
        },
        getDataCoberturaOtrosGeojson: (options) => {
            return $.ajax({
                url:base_url+"fija-coverage/cobertura-otros-geojson",
                method: "GET",
                dataType: 'json',
                ...options
            });
        },
        getGraphData: (options) => {
            return $.ajax({
                url: base_url+"fija-coverage/graph-data",
                method: "GET",
                dataType: 'json',
                ...options
            });
        }
    };

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-10.0431805,-74.0282364),
            zoom: 5.8,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
    }

    async function getDataFija(add_filters = [])
    {
        const kpi_id = $(".slc-mapKpiType").val();
        const id_distribucion = $(".slc-distribucion").val();
        //let distribucion = mapConfig.distribucion.filter(r => `${r.id}` === id_distribucion);
        //distribucion = distribucion.length > 0 ? distribucion[0] : {};
        const distribucion = mapConfig.distribucionById[id_distribucion];
        const kpiConfig = mapConfig.kpiConfigById[kpi_id];

        let filters = [];
        filters.push(`ano.eq.${$(".slc-ano").val()}`);
        filters.push(`semana.eq.${$(".slc-semana").val()}`);

        await api.getDataFija({
            data: {
                distribucion: id_distribucion,
                filter: [...filters, ...add_filters]
            },
            beforeSend:function () {
                $("#spinnerData").show();
            },
            success: function(response) {
                store.data_fija = response;
                
                let data_fija_by_key = selectors.groupBy(store.data_fija, distribucion.dataProps.id);
                store.data_fija_by_key = data_fija_by_key;
                // set styles by kpi
                let colors = [];
                let counter = 0;

                store.features.forEach(feature => {
                    const name = feature.getProperty(distribucion.featProps.id);
                    let data = {};
                    if(name !== undefined){
                        let value;
                        let overlap;
                        let planos_mala_venta;
                        let flag_mala_venta;
                        if(data_fija_by_key[name] !== undefined){
                            value = data_fija_by_key[name][0][kpiConfig['NAME']];
                            overlap = data_fija_by_key[name][0]["overlap"];
                            planos_mala_venta = data_fija_by_key[name][0]["planos_mala_venta"];
                            flag_mala_venta = data_fija_by_key[name][0]["flag_mala_venta"];
                        }

                        let strokeWeight = 1;
                        if(overlap && document.querySelector("#check_overlap").checked){
                            if(parseInt(overlap) === 1){
                                strokeWeight = 5;
                            }
                        }
                        if(planos_mala_venta){
                            if(parseInt(planos_mala_venta) === 1){
                                strokeWeight = 3;
                            }
                        }

                        let strokeColor = planos_mala_venta ? (parseInt(planos_mala_venta) === 1 ? "#9370db": '#464646') : '#95a5a6';

                        let flag_mala_venta_is_active = document.querySelector("#check_flag_mala_venta").checked;
                        if(flag_mala_venta_is_active && Number(flag_mala_venta) === 1){
                            strokeColor = '#3434eb';
                        }

                        data = {
                            fillColor: data_fija_by_key[name] !== undefined ? getColorByKpi(kpiConfig, value) : '#818F9B',
                            //fillOpacity: 1,
                            fillOpacity: 0.6,
                            strokeColor: strokeColor,
                            //strokeWeight: 3,
                            strokeWeight: strokeWeight
                        };
                        feature.setProperty('fillColor', data['fillColor']);
                        feature.setProperty('strokeColor', data['strokeColor']);
                        feature.setProperty('strokeWeight', data['strokeWeight']);
                    }
                    colors.push(data);
                    counter += 1;
                    map.data.overrideStyle(feature, data);
                });
            },
            complete: function () {
                $("#spinnerData").hide();
            }
        });
    }
    function getColorByKpi(kpiConfig, value){
        const kpiType = kpiConfig['ID'];
        let color = "#fff";

        if (value == null || value == undefined) {
          //argument="-1";
          color = "#fff";
        }else{
            const argument = value;
            value = parseFloat(value);
            if(kpiType == "1"){
                if(argument>80){
                color="#006600";
                }else if(argument>60){
                color="#009933";
                }else if(argument>50){
                color="#33CC33";
                }else if(argument>40){
                color="#66FF33";
                }else if(argument>30){
                color="#D3F527";
                }else if(argument>20){
                color="#FFCC00";
                }else if(argument>10){
                color="#FF6600";
                }else if(argument>=0){
                color="#ff0000";
                }else{
                color="#9b9b9b";
                }
            /*}else if(kpiType=="2"){
                if(argument=="-1"){
                color="#fff";
                }else if(argument>60){
                color="#147df5";
                }else if(argument>30){
                color="#0aff99";
                }else{
                color="#ffd300";
                }*/
            }else if(kpiType=="3"){
                if(argument==null || argument=="-1"){
                color="#fff";
                }else if(argument<60){
                color="#F31A1A";
                }else if(60 <=argument && argument < 80){
                color="#F3661B";
                }else if(argument>=80){
                color="#44A344";
                }else{
                color="#fff";
                }
            }else if(kpiType=="4"){
                if(argument==null || argument=="-1"){
                color="#fff";
                }else if(argument==0){
                color="#44A344";
                }else if(0 <argument && argument <=5){
                color="#ffd300";
                }else if(5 <argument && argument <=10){
                color="#F3661B";
                }else if(argument>10){
                color="#9F1A1A";
                }
            }else if(kpiType=="5"){
                if(argument==null || argument=="-1"){
                color="#fff";
                }else if(argument<90){
                color="#9F1A1A";
                }else if(90 <=argument && argument <99){
                color="#F31A1A";
                }else if(99 <=argument && argument <99.5){
                color="#F3661B";
                }else if(99.5 <=argument && argument <99.9){
                color="#ffd300";
                }else if(argument>=99.9){
                color="#44A344";
                }
            }
            else if(kpiType=="19"){
                if(argument==null || argument=="-1"){
                    color="#fff";
                }else if(argument>80){
                    color="#EA2027";
                }else if(40 <argument && argument <=80){
                    color="#F3661B";
                }else if(20 <argument && argument <=40){
                    color="#ffd300";
                }else if(5 <argument && argument <=20){
                    color="#3CD5A9";
                }else if(argument<=5){
                    color="#44A344";
                }
            }
            else{
                const KPI = value;
                if(kpiConfig['COLOR_RANGE'] !== undefined){
                    kpiConfig['COLOR_RANGE'].every(row => {
                        if(eval(row['CONDITION']) === true){
                            color = row['COLOR'];
                            return false;
                        }
                        return true;
                    });
                }
            }
        }
        return color;
    }

    function viewDataMap(distribucion_id, kpi_id, filter = [])
    {
        const dist = mapConfig.distribucionById[distribucion_id];
        const kpiConfig = mapConfig.kpiConfigById[kpi_id];

        $.ajax({
            url: `${base_url}fija-coverage/data-fija-by-name/${distribucion_id}/${kpi_id}`,
            method: "POST",
            data: {
                ano: $(".slc-ano").val(),
                semana: $(".slc-semana").val(),
                filter: filter
            },
            dataType: 'json',
            beforeSend:function () {
                clearMarkersAll();
                $("#spinnerData").show();
            },
            success: function(response) {
                if(response.length > 0){
                    console.log(response);
                    const header_html = ``;
                    let htmlTxt = "";
                    htmlTxt = "<table class='tbl tblMonit mb-0'>";
                    let fieldsByName = mapConfig.detailFieldsByDistribucion[distribucion_id];

                    //console.log(fieldsByName);
                    htmlTxt += Object.keys(fieldsByName).map(name => {
                        let field = fieldsByName[name];
                        console.log(name);
                        let kpiConfigFinded = mapConfig.kpiConfig.filter(kpi => kpi.NAME === name);
                        kpiConfigFinded = kpiConfigFinded.length > 0 ? kpiConfigFinded[0] : {};
                        let prop_label = field.label;
                        let description = kpiConfigFinded.DESCRIPTION;
                        let id = response[0][dist.dataProps.id];
                        let tablaDetalle = "";
                        let button = "";
                        let td = [];

                        $.each(response,function(index, value){
                            let rowData = value;

                            button = "";
                            
                            if(kpiConfigFinded.ID && kpiConfigFinded.ID != 4){
                                button = `<button class="btn btn-link btn-ver-grafica-lineal pt-0 pb-0 px-1 float-right" data-kpi="${kpiConfigFinded.ID}" data-name="${rowData[dist.dataProps.id].toUpperCase()}">Ver</button>`;
                                /*if((kpiConfigFinded.EXTRA_GRAPH_KPIS??[]).some(dist_id => `${dist_id}` === `${distribucion_id}`)){
                                    button += `<button class="btn btn-link btn-ver-extra-graph pt-0 pb-0 px-1 float-right" data-kpi="${kpiConfigFinded.ID}" data-name="${rowData[dist.dataProps.id]}"><span class="la la-area-chart"></span></button>`;
                                }*/

                            }
                            if(Object.keys(fieldsByName[name].modal_info??[]).some(dist_id => `${dist_id}` === `${distribucion_id}`)){
                                button += `<button
                                    class="btn btn-link btn-ver-extra-graph pt-0 pb-0 px-1 float-right"
                                    data-field="${name}"
                                    data-name="${rowData[dist.dataProps.id].toUpperCase()}"
                                    data-config="${fieldsByName[name].modal_info}"
                                >
                                    <span class="la la-area-chart"></span>
                                </button>`;
                            }
                            if(name === "num_competencia"){
                                tablaDetalle = `<button class="btn btn-link pt-0 pb-0 float-right" id="buttonDetalleTablaModal" data-kpi="${rowData[dist.dataProps.id].toUpperCase()}" data-toggle="modal" data-target="#detalleTablaModal">Ver</button>`;
                            }
                            // if(name === "ruc10" || name === "ruc20" || name == "kpi_cli_ruc_10" || name == "kpi_cli_ruc_20" || name == "kpi_cliente_total"){
                            if(["ruc10", "ruc20", "kpi_cli_ruc_10", "kpi_cli_ruc_20", "kpi_cliente_total", "kpi_cliente_movil", "numero_fat"].some(value_to_filter => value_to_filter === name)){
                                tablaDetalle = `<input class="m-2 float-right loadMarkersCheck" type="checkbox" id="checkbox_${name}" data-type="${name}" data-plano="${rowData[dist.dataProps.id].toUpperCase()}">`;
                            }
                            //tablaDetalle = (name === "num_competencia" ? `<button class="btn btn-link pt-0 pb-0 float-right" id="buttonDetalleTablaModal" data-kpi="${rowData[dist.dataProps.id]}" data-toggle="modal" data-target="#detalleTablaModal">Ver</button>` : '');
                            let style = "";
                            if(name === "kpi_ocupacion" && 10 > rowData[name]){
                                style = " style='color: #ff0000;'";
                            }
                            td[index] = `<td ${style}>
                                    ${rowData[name] === null ? 'NN': `${rowData[name]}`.toUpperCase()}
                                    ${button}
                                    ${tablaDetalle}
                                </td>`;
                        });
                        
                        return `<tr>
                            <td ${button !== "" ? "class='table-secondary'": ''} style='vertical-align: middle;'>
                                ${prop_label}${description !== undefined ? `<span class="la la-info-circle pl-2" data-toogle="tooltip" data-placement="top" title="${description}"></span>` : ''}
                            </td>  
                            ${td[0]}
                            ${td[1] !== undefined ? td[1] : ''}
                        </tr>`;
                    }).join('');

                    /*htmlTxt += Object.keys(response[0]).map(key => {
                        let button = "";
                        let kpiConfigFinded = mapConfig.kpiConfig.filter(kpi => kpi.NAME === key);
                        let prop_label = kpiConfigFinded.length > 0 ? kpiConfigFinded[0].LABEL : key;
                        let description = kpiConfigFinded.length > 0 ? kpiConfigFinded[0].DESCRIPTION : undefined;
                            console.log(description);
                        button = kpiConfigFinded.map(kpi => {
                            let to_return = `<button class="btn btn-link btn-ver-grafica-lineal pt-0 pb-0 px-1 float-right" data-kpi="${kpi.ID}" data-name="${response[0][dist.dataProps.id]}">Ver</button>`;
                            if((kpi.EXTRA_GRAPH_KPIS??[]).some(dist_id => `${dist_id}` === `${distribucion_id}`)){
                                to_return += `<button class="btn btn-link btn-ver-extra-graph pt-0 pb-0 px-1 float-right" data-kpi="${kpi.ID}" data-name="${response[0][dist.dataProps.id]}"><span class="la la-area-chart"></span></button>`
                            }
                            return to_return;
                        }).join('');
                        tablaDetalle = prop_label == "num_competencia" ? `<button class="btn btn-link pt-0 pb-0 float-right" id="buttonDetalleTablaModal" data-kpi="${response[0][key] === null ? 'NN': response[0][key]}" data-toggle="modal" data-target="#detalleTablaModal">Ver</button>` : '';
                        return `<tr>
                            <td ${button !== "" ? "class='table-secondary'": ''} style='vertical-align: middle;'>${prop_label}${description !== undefined ? `<span class="la la-info-circle pl-2" data-toogle="tooltip" data-placement="top" title="${description}"></span>` : ''}</td>
                            <td>
                                ${response[0][key] === null ? 'NN': response[0][key]}
                                ${button}
                                ${tablaDetalle}
                            </td>
                        </tr>`;
                    }).join('');*/

                    /*
                    htmlTxt += `<tr class='table-secondary'>
                        <td style='vertical-align: middle;'>
                            <p class='mb-0'>GRAFICA LINEAL</p>
                        </td>
                        <td>
                            <button class='btn btn-link btn-ver-grafica-lineal' data-name='${response[0][dist.dataProps.id]}'>Ver</button>
                        </td>
                    </tr>`;
                    */
                    htmlTxt+="</table>";

                    $("#dataMapInd").html(header_html+htmlTxt);
                    $("#dataMapInd").show();
                    panelDataView.setTitle(`Detalle ${dist.label}`);
                    panelDataView.show();
                    $("#dataMapInd .closeTab").click(function () {
                        $("#dataMapInd").hide();
                    });
                    $("#dataMapInd").draggable({handle: ".lgnd-header"});
                    $('#dataMapInd .btn-ver-grafica-lineal').on('click', function(){
                        $("#graph-tab").show();
                        $("#graph-tab").trigger("click");
                        const name_of_row = $(this).attr('data-name');
                        const kpiConfig2 = mapConfig.kpiConfigById[$(this).attr('data-kpi')];
                        store.polygonSelected["kpiSelected"] = kpiConfig2.NAME;
                        api.getDataFija({
                            data: {
                                distribucion: distribucion_id,
                                filter: [`${dist.dataProps.id}.eq.${name_of_row}`]
                            },
                            beforeSend:function () {
                                $("#spinnerData").show();
                            },
                            success: function(result) {
                                let kpiConfigByName = selectors.groupBy(mapConfig["kpiConfig"], "NAME");
                                Object.keys(kpiConfigByName).forEach(kpiName => {
                                    kpiConfigByName[kpiName] = kpiConfigByName[kpiName][0];
                                });
                                let graph_data = [];
                                let data = selectors.groupBy(result, dist.dataProps.id);
                                let kpis_names = kpiConfig2.GRAPH_KPIS !== undefined ? kpiConfig2.GRAPH_KPIS : [kpiConfig2.NAME];
                                let title = "-";
                                kpis_names.forEach(kpiName => {
                                    let _kpiConfig = kpiConfigByName[kpiName];
                                    title = dist.dataProps.label.map(prop => result[0][prop]).join('-');
                                    graph_data.push({
                                        name: _kpiConfig.LABEL,
                                        data: result.map(row => {
                                            const date = new Date(row.resulttime.replace(' ', 'T')+'Z');
                                            return {
                                                /*x: Date.UTC(
                                                    date.getFullYear(),
                                                    date.getMonth(),
                                                    date.getDate(),
                                                    date.getHours(),
                                                    date.getMinutes(),
                                                    date.getSeconds()
                                                ),*/
                                                x: date.getTime(),
                                                y: row[_kpiConfig.NAME] ? parseFloat(row[_kpiConfig.NAME]) : row[_kpiConfig.NAME]
                                            };
                                        })
                                    });
                                });
                                if(kpiConfig2.GRAPH_TITLE){
                                    title = kpiConfig2.GRAPH_TITLE.replace("{value}", title);
                                }else{
                                    title = `${title} - ${kpiConfig2.LABEL}`;
                                }
                                /*for (const name in data) {
                                    if(data[name] !== undefined){
                                        let title = dist.dataProps.label.map(prop => data[name][0][prop]).join('-');
                                        graph_data.push({
                                            name: dist.dataProps.label.map(prop => data[name][0][prop]).join('-'),
                                            data: data[name].map(row => {
                                                const date = new Date(row.resulttime.replace(' ', 'T'));
                                                return {
                                                    x: Date.UTC(
                                                        date.getFullYear(),
                                                        date.getMonth(),
                                                        date.getDate(),
                                                        date.getHours(),
                                                        date.getMinutes(),
                                                        date.getSeconds()
                                                    ),
                                                    y: parseFloat(row[kpiConfig2.NAME])
                                                };
                                            })
                                        });
                                    }
                                }*/
                                renderGraph('graphContainer1', {title: title, series: graph_data});
                                graphModalView.show();
                            },
                            complete: function () {
                                $("#spinnerData").hide();
                            }
                        });
                        renderPolygonTableData();
                    });
                    $('#dataMapInd .btn-ver-extra-graph').on('click', function(e){
                        $("#table-tab").trigger("click");
                        $("#graph-tab").hide();
                        store.polygonSelected["kpiSelected"] = $(this).attr('data-field');
                        
                        store.polygonSelected.id = $(this).attr('data-name');

                        api.getGraphData({
                            data: {
                                distribucion: distribucion_id,
                                kpi_id: $(this).attr('data-field'),
                                plano: $(this).attr('data-name'),
                            },
                            beforeSend:function () {
                                $("#spinnerData").show();
                            },
                            success: function(result) {
                                
                                let series = Object.keys(result.seriesConfig).map(serieName => {
                                    let serieConfig = result.seriesConfig[serieName];
                                    return ({
                                        name: serieConfig.label,
                                        data: result.data.map(row => {
                                            const date = new Date(row[result.result_time].replace(' ', 'T')+'Z');
                                            return {
                                                /*x: Date.UTC(
                                                    date.getFullYear(),
                                                    date.getMonth(),
                                                    date.getDate(),
                                                    date.getHours(),
                                                    date.getMinutes(),
                                                    date.getSeconds()
                                                ),*/
                                                x: date.getTime(),
                                                y: parseFloat(row[serieName])
                                            };
                                        })
                                    });
                                });
                                console.log(series);
                                renderGraph('graphContainer1', {title: result.title, series: series});
                                graphModalView.show();
                            },
                            complete: function () {
                                $("#spinnerData").hide();
                            }
                        });
                        renderPolygonTableData();
                    });
                }else{
                    $("#dataMapInd").hide();
                    panelDataView.hide();
                }
            },
            complete: function () {
                $("#spinnerData").hide();
            }
        });
    }

    function loadGeoJson(url, options) {
        var promise = new Promise(function (resolve, reject) {
        try {
            map.data.loadGeoJson(url, options, function (features) {
                resolve(features);
            });
        } catch (e) {
            reject(e);
        }
        });

        return promise;
    }

    async function addMapLayers()
    {
        const id_distribucion = $(".slc-distribucion").val();
        let distribucion = mapConfig.distribucionById[id_distribucion];

        store.features.forEach(feature => {
            console.log(feature.getGeometry());
            map.data.remove(feature);
        });

        if(distribucion !== undefined){
            store.features = await loadGeoJson(`${distribucion.mapurl}?time=${(new Date()).getTime()}`);
            /*await map.data.loadGeoJson(distribucion.mapurl, {}, function(feat){
                store.features = feat;
            });
            */
        }
    }

    async function changeano(){
        const value = $(".slc-ano").val();
        await $.ajax({
            url:base_url+"fija-coverage/semana-by-ano/"+value,
            dataType: 'json',
            success:function (response) {
                const initial_html = "<option value='0' disabled style='color: #DBDBDB;'>--Semana--</option>"
                const html = response.map(r => `<option value="${r['semana']}">${r['semana']}</option>`);
                $(".slc-semana").html(initial_html+html);
            }
        });
    }

    function getMappedFilters()
    {
        const elements = $(".slc-filter");
        let filters = [];
        for (let i = 0; i < elements.length; i++) {
            const value = $(elements[i]).val();
            if(value !== "0"){
                filters.push(`${$(elements[i]).attr('name')}.eq.${value}`);
            }
        }
        return filters;
    }

    function renderGraph(el_container, options){
        console.log(options);
        /*Highcharts.chart({
            chart:{
                renderTo: el_container,
                type: 'line',
                zoomType: 'x',
                height: 300
            },
            title: {text: options.title},
            zoomType : 'x',
            xAxis: {
            type: 'datetime',
            accessibility: {
                rangeDescription: 'Range: 2010 to 2017',
                
            },
            // categories: [14,2,3,4,5,6,4,6,5,7,7,9,3,7,4,8],
            // categories: graph_series,
            dateTimeLabelFormats: {
                day: "%Y-%m-%d",
                month: "%Y-%m",
                year: "%Y",
                hour: "%Y-%m-%d %H",
                minute: "%Y-%m-%d %H:%M",
                second: "%H:%M:%S"
            },
            labels: {
                rotation: 270
            }
            },
            yAxis: {
                title: {text: ''}
            },

            legend: {
                layout: 'horizontal',
                align: 'center',
                // verticalAlign: 'middle'
            },

            plotOptions: {
                series: {
                    label: {
                    //connectorAllowed: true
                    },
                    //pointStart: graph_series[0],
                    //pointInterval: result.step * 1000
                    marker: {
                        enabled: true,
                        radius: 4,
                        // fillColor: "#FFFFFF",
                        lineColor: "#666666",
                        lineWidth: 1
                    }
                },
                spline: {
                }
            },
            series: options.series,
            responsive: {
                rules: [{
                    condition: {
                    maxWidth: 100,
                    // maxHeight: 50
                    },
                    chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                    }
                }]
            }
        });*/
        let chart_config = {
            series: options.series,
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    type: "x",
                    enabled: true,
                    autoScaleYaxis: true
                },
                toolbar: {
                    export: {
                        csv: {
                            filename: options.title,
                            dateFormatter: function(timestamp) {
                                return (new Date(timestamp)).toISOString().replace("T", " ").replace(".000Z", "");
                            }
                        }
                        
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                size: 0
            },
            stroke:{
                width: 2,
            },
            title: {
                text: options.title,
                align: 'center'
            },
            grid: {
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
                },
            },
            xaxis: {
                type: "datetime",
                //tickAmount: 6,
                labels: {
                    datetimeFormatter: {
                        year: "yyyy",
                        month: "yyyy-MM",
                        day: "yyyy-MM-dd",
                        hour: "yyyy-MM-dd HH",
                    },
                }
            },
            tooltip: {
                x: {
                    format: "yyyy-MM-dd HH:mm"
                }
            }
        };
        if(store.chart){
            console.log(store.chart);
            store.chart.destroy();
        }
        store.chart = new ApexCharts(document.querySelector(`#${el_container}`), chart_config);
        store.chart.render();
    }

    async function renderCoberturaOtros(){
        const id_distribucion = $(".slc-distribucion").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];

        //let filters = [`ano.eq.${$(".slc-ano").val()}`, `semana.eq.${$(".slc-semana").val()}`];
        store.cobOtrosFeatures.forEach(feature => {
            map.data.remove(feature);
        });

        if($(".slc-cobertura-otros").val() !== '0'){
            api.getDataCoberturaOtros({
                data: {
                    ano: $(".slc-ano").val(),
                    semana: $(".slc-semana").val(),
                    distribucion: $(".slc-distribucion").val(),
                    cobertura_otro: $(".slc-cobertura-otros").val(),
                },
                beforeSend: function() {
                    $("#spinnerData").show();
                },
                success: function (response) {
                    let cobertura_by_key = selectors.groupBy(response.data, distribucion.dataProps.id);
                    store.features.forEach(feature => {
                        const feature_key = feature.getProperty(distribucion.featProps.id);
                        let styles = {};
                        if(cobertura_by_key[feature_key] !== undefined){
                            styles['strokeWeight'] = 3;
                            styles['strokeColor'] = '#002955';
                        } else {
                            styles['strokeWeight'] = feature.getProperty('strokeWeight');
                            styles['strokeColor'] = feature.getProperty('strokeColor');
                        }
                        map.data.overrideStyle(feature, styles);
                    });
                },
                complete: function () {
                    $("#spinnerData").hide();
                }
            });

            store.cobOtrosFeatures = await loadGeoJson(`${base_url}fija-coverage/cobertura-otros-geojson?distribucion=${$(".slc-distribucion").val()}&cobertura_otro=${$(".slc-cobertura-otros").val()}`);
            store.cobOtrosFeatures.forEach(feature => {
                map.data.overrideStyle(feature, {fillColor: '#000000', fillOpacity: 0.6, strokeColor: '#BB0000'});
            });
        }else{
            store.features.forEach(feature => {
                const feature_key = feature.getProperty(distribucion.featProps.id);
                let styles = {
                    strokeWeight: feature.getProperty('strokeWeight'),
                    strokeColor: feature.getProperty('strokeColor'),
                };
                map.data.overrideStyle(feature, styles);
            });
        }
    }


    async function renderCompetencia(){
        const competencia_to_filter = $(".slc-competencia").val();

        const id_distribucion = $(".slc-distribucion").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];

        let cobertura_by_key = selectors.groupBy(store.data_fija, distribucion.dataProps.id);
        if(competencia_to_filter !== "0"){
            store.features.forEach(feature => {
                const feature_key = feature.getProperty(distribucion.featProps.id);
                let styles = {};
                if(cobertura_by_key[feature_key] !== undefined){
                    let competencia = cobertura_by_key[feature_key][0]["competencia"];
                    competencia = competencia !== undefined && competencia !== null ? competencia : '';

                    if(competencia.includes(competencia_to_filter)){
                        styles['strokeWeight'] = 3;
                        styles['strokeColor'] = '#002955';
                    }else{
                        styles['strokeWeight'] = feature.getProperty('strokeWeight');
                        styles['strokeColor'] = feature.getProperty('strokeColor');
                    }
                } else {
                    styles['strokeWeight'] = feature.getProperty('strokeWeight');
                    styles['strokeColor'] = feature.getProperty('strokeColor');
                }
                map.data.overrideStyle(feature, styles);
            });
        }else{
            store.features.forEach(feature => {
                const feature_key = feature.getProperty(distribucion.featProps.id);
                let styles = {
                    strokeWeight: feature.getProperty('strokeWeight'),
                    strokeColor: feature.getProperty('strokeColor'),
                };
                map.data.overrideStyle(feature, styles);
            });
        }
    }

    async function addMarkerForNewPolygons(element)
    {
        const checked = document.getElementById("checkbox_nuevos").checked;
        let flag_prop = $(element).attr("data-flagprop");
        const id_distribucion = $(".slc-distribucion").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];

        (store.markers_new??[]).forEach(m => m.setMap(null));
        store.markers_new = [];

        if(checked === true && flag_prop !== ""){
            store.features.forEach(feature => {
                const name = feature.getProperty(distribucion.featProps.id);
                let data = {};
                if(name !== undefined){
                    if(store.data_fija_by_key[name] !== undefined){
                        const flag_new = store.data_fija_by_key[name][0][flag_prop];
                        if(parseInt(flag_new) === 1){
                            let bounds = new google.maps.LatLngBounds();
                            feature.getGeometry().forEachLatLng(function(path) {
                                const latlng = new google.maps.LatLng(path.lat(), path.lng());
                                bounds.extend(latlng);
                            });

                            let marker = new google.maps.Marker({
                                map: map,
                                position: bounds.getCenter(),
                                icon: {
                                    url: "{{ asset('images/pin_green.png') }}"
                                },
                                size: new google.maps.Size(10, 15),
                                anchor: new google.maps.Point(0, 15)
                            });
                            store.markers_new.push(marker);
                        }
                    }
                }
            });
        }
    }

    function loadCapa(element)
    {
        distval = $(".slc-ubgDist").val();
        dep =  $(".slc-ubgDpto option:selected").text();
        prov = $(".slc-ubgProv option:selected").text();
        dist = $(".slc-ubgDist option:selected").text();
        type = element.data("type");
        plano = element.data("plano");
        if( element.is(':checked')) {
            // Hacer algo si el checkbox ha sido seleccionado
            if(loadMarker[`${type}_${plano}`] != 1){
                $.ajax({
                    url: "{{ route('api.mapData') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: "type=loadMarker&param="+type+"&plano="+plano,
                    beforeSend:function () {
                        $("#spinnerData").show();
                    },
                    success: function(data) {   
                        if(type === "kpi_cliente_total"){
                            let clientesTotalGrouped = {};
                            data.data.forEach(row => {
                                let keyGroup = `${row.latitud},${row.longitud}`;
                                if(clientesTotalGrouped[keyGroup] === undefined){
                                    clientesTotalGrouped[keyGroup] = {
                                        latitud: row.latitud,
                                        longitud: row.longitud,
                                        tipo: row.tipo,
                                        data: [row]
                                    };
                                } else {
                                    clientesTotalGrouped[keyGroup]['data'].push(row);
                                }
                            });
                            data.data = Object.keys(clientesTotalGrouped).map(keyGroup => clientesTotalGrouped[keyGroup]);
                            //$("#legend .leg_clientes_fija").show();
                        } else if (type === "kpi_cliente_total_moviles"){
                            let clientesTotalGrouped = {};
                            data.data.forEach(row => {
                                let keyGroup = `${row.latitud},${row.longitud}`;
                                if(clientesTotalGrouped[keyGroup] === undefined){
                                    clientesTotalGrouped[keyGroup] = {
                                        latitud: row.latitud,
                                        longitud: row.longitud,
                                        base_full_claro: row.base_full_claro,
                                        data: [row]
                                    };
                                } else {
                                    clientesTotalGrouped[keyGroup]['data'].push(row);
                                }
                            });
                            data.data = Object.keys(clientesTotalGrouped).map(keyGroup => clientesTotalGrouped[keyGroup]);
                            //$("#legend .leg_clientes_fija_movil").show();
                        }
                        $.each(data.data,function (key, val ) {
                            var feature = {};
                            let marker;
                            feature.latitud = parseFloat(val.latitud);
                            feature.longitud = parseFloat(val.longitud);
                            feature.type = `${type}_${plano}`
                            let icon = iconoPersonalizado;
                            if(type === "kpi_cliente_total"){
                                //icon = "{{ asset('images/person_icon.png') }}";
                                if(val.tipo === null){
                                    icon = "{{ asset('images/person_icon.png') }}";
                                }else{
                                    icon = "{{ asset('images/person_icon_verde.png') }}";
                                }
                                if(val.data.length > 1){
                                    icon = {
                                        url: icon,
                                        scaledSize: new google.maps.Size(20, 30),
                                        origin: new google.maps.Point(0, 0),
                                        anchor: new google.maps.Point(15, 20),
                                    }
                                }
                                marker = addMarkerSites(feature,icon, {
                                    label: val.data.length > 1 ? `${val.data.length}` : undefined,
                                });
                            }else if(type === "kpi_cliente_total_moviles"){
                                //icon = "{{ asset('images/person_icon_purpura.png') }}";
                                if(`${val.base_full_claro}` === "1"){
                                    icon = "{{ asset('images/person_icon_azul.png') }}?v=2";
                                }else{
                                    icon = "{{ asset('images/person_icon_purpura.png') }}";
                                }
                                if(val.data.length > 1){
                                    icon = {
                                        url: icon,
                                        scaledSize: new google.maps.Size(20, 30),
                                        origin: new google.maps.Point(0, 0),
                                        anchor: new google.maps.Point(15, 20),
                                    }
                                }
                                marker = addMarkerSites(feature,icon, {
                                    label: val.data.length > 1 ? {text: `${val.data.length}`, color: 'white'} : undefined,
                                });
                            }else if(type === "num_fats"){
                                icon = "{{ asset('images/trapecio.png') }}";
                            }
                            if(marker === undefined){
                                marker = addMarkerSites(feature,icon);
                            }
                            if(type === "kpi_cliente_total"){
                                marker.addListener("click", function(){
                                    if(store.infoWindow !== undefined){
                                        store.infoWindow.close();
                                    }
                                    //<tr><td>${val.tip_documento}</td><td>${val.nro_documento}</td></tr>
                                    //<tr><td>Velocidad</td><td>${val.sd_int}</td></tr>
                                    let htmlDirecciones = val.data.map((row, index) => `<tr><td>Direccion ${index+1}</td><td>${row.direccion}</td></tr>`).join("");
                                    store.infoWindow = new google.maps.InfoWindow({
                                        content: `<table class="table table-condensed table-sm">
                                        <thead><tr class="active"></tr>
                                        </thead>
                                        <tbody>  
                                        <tr><td>Nombre</td><td>xxxx</td></tr>
                                        ${htmlDirecciones}
                                        </tbody>
                                        </table>`
                                    });
                                    store.infoWindow.open(self.map, marker);
                                    $("#legend .leg_clientes_fija").show();
                                    $("#legend .leg_clientes_fija_movil").hide();
                                });
                            } else if(type === "kpi_cliente_total_moviles"){
                                marker.addListener("click", function(){
                                    if(store.infoWindow !== undefined){
                                        store.infoWindow.close();
                                    }
                                    let htmlClientesTotal = val.data.map((row, index) => `<tr>
                                        <td>${row.id_empresas}</td>
                                        <td>${row.nro_documento}</td>
                                        <td>${row.tipo_documento}</td>
                                        <td>${row.agreement_product_offering_desc}</td>
                                        <td>${row.direccion}</td>
                                        </tr>`).join("");
                                    store.infoWindow = new google.maps.InfoWindow({
                                        content: `<table class="table table-condensed table-sm">
                                        <thead>
                                        <tr class="active">
                                        <th>MSISDN</th>
                                        <th>N Documento</th>
                                        <th>Tipo Documento</th>
                                        <th>Agreement Product Offering Desc</th>
                                        <th>Direccion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        ${htmlClientesTotal}
                                        </tbody>
                                        </table>`
                                    });
                                    store.infoWindow.open(self.map, marker);
                                    $("#legend .leg_clientes_fija").hide();
                                    $("#legend .leg_clientes_fija_movil").show();
                                });
                            }else if(type === "num_fats"){
                                marker.addListener("click", function(){
                                    if(store.infoWindow !== undefined){
                                        store.infoWindow.close();
                                    }
                                    //<tr><td>${val.tip_documento}</td><td>${val.nro_documento}</td></tr>
                                    //<tr><td>Velocidad</td><td>${val.sd_int}</td></tr>
                                    store.infoWindow = new google.maps.InfoWindow({
                                        content: `<table class="table table-condensed table-sm">
                                        <thead><tr class="active"></tr>
                                        </thead>
                                        <tbody>  
                                        <tr><td>Codigo</td><td>${val.codigo}</td></tr>
                                        <tr><td>Plano</td><td>${val.nombre_plano}</td></tr>
                                        <tr><td>Nombre</td><td>${val.name}</td></tr>
                                        <tr><td>Especificación</td><td>${val.especificacion}</td></tr>
                                        <tr><td>Salida</td><td>${val.salida}</td></tr>
                                        </tbody>
                                        </table>`
                                    });
                                    store.infoWindow.open(self.map, marker);
                                });
                            }
                        });
                        setMapOnSites(map,`${type}_${plano}`)
                    },
                    complete: function () {
                        $("#spinnerData").hide();
                        loadMarker[`${type}_${plano}`] = 1;
                    },
                });
            }else{
                setMapOnSites(map,`${type}_${plano}`);
            }
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            clearMarkersSites(`${type}_${plano}`);
        }
    }

    function addMarkersNumCompetencia(checked){
        const id_distribucion = $(".slc-distribucion").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];

        store.labelNumCompetencia.markers.forEach(m => m.setMap(null));

        if(checked === true){
            store.labelNumCompetencia.markers = [];
            store.features.forEach(feature => {
                const name = feature.getProperty(distribucion.featProps.id);
                let longitud = feature.getProperty("centroide_longitud");
                let latitud = feature.getProperty("centroide_latitud");
                let data = {};
                if(name !== undefined){
                    if(store.data_fija_by_key[name] !== undefined){
                        const num_competencia = store.data_fija_by_key[name][0]["num_competencia"];
                        if(num_competencia){
                            /*let bounds = new google.maps.LatLngBounds();
                            feature.getGeometry().forEachLatLng(function(path) {
                                const latlng = new google.maps.LatLng(path.lat(), path.lng());
                                bounds.extend(latlng);
                            });*/

                            let marker = new google.maps.Marker({
                                map: null,
                                position: new google.maps.LatLng(parseFloat(latitud), parseFloat(longitud)),
                                /*icon: {
                                    url: "{{ asset('images/pin_green.png') }}"
                                },
                                size: new google.maps.Size(10, 15),
                                anchor: new google.maps.Point(0, 15),*/

                                label: {text: num_competencia, color: 'white'},
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    fillColor: 'red',
                                    fillOpacity: 1,
                                    scale: 12,
                                    strokeWeight: 0
                                }
                            });
                            store.labelNumCompetencia.markers.push(marker);
                        }
                    }
                }
            });
            //store.labelNumCompetencia.markers.forEach(m => m.setMap(map));
            console.log("finish", store.labelNumCompetencia.markers.length);
            //new markerClusterer.MarkerClusterer({markers: store.labelNumCompetencia.markers, map});
        }
    }

    function renderMarkersNumCompetencia(){
        if(store.labelNumCompetencia.timer){
            clearTimeout(store.labelNumCompetencia.timer);
        }
        if(map.getZoom() >= 15 && document.querySelector("#check_num_competencia").checked){
            store.labelNumCompetencia.timer = setTimeout(function(){
                let bounds = map.getBounds();
                let markersHide = [];
                let markersShow = store.labelNumCompetencia.markers.filter(marker => {
                    let contains = bounds.contains(marker.getPosition());
                    if(!contains){
                        markersHide.push(marker);
                    }
                    return contains;
                });
                markersHide.forEach(m => {
                    if(m.map !== null){
                        m.setMap(null);
                    }
                });
                markersShow.forEach(marker => {
                    if(marker.map === null){
                        marker.setMap(map);
                    }
                });
            }, 500);
        }else{
            store.labelNumCompetencia.markers.forEach(marker => {
                if(marker.map !== null){
                    marker.setMap(null);
                }
            });
        }
    }

    function renderMarkersTopSitesTethering(){
        $.ajax({
            url: "{{ url('fija-coverage/top-sites-tethering') }}",
            type: 'GET',
            dataType: 'json',
            beforeSend:function () {
                $("#spinnerData").show();
            },
            success: function(data) {
                store.topSitesTetheringMarkers.forEach(marker => {
                    marker.setMap(null);
                });
                store.topSitesTetheringMarkers = data.map(row => {
                    let icon = "{{ asset('images/red-dot.png') }}";
                    if(parseFloat(row.trafico) > 800){
                        icon = "{{ asset('images/red-2-dot.png') }}";
                    }
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(parseFloat(row.latitud), parseFloat(row.longitud)),
                        map: map,
                        icon
                    });
                    marker.addListener("click", function(){
                        if(store.infoWindow !== undefined){
                            store.infoWindow.close();
                        }

                        store.infoWindow = new google.maps.InfoWindow({
                            content: `<table class="table table-condensed table-sm">
                            <thead><tr class="active"></tr>
                            </thead>
                            <tbody>
                            <tr><th>Codigo</th><td>${row.codigo??''}</td></tr>
                            <tr><th>Trafico</th><td>${row.trafico??''}</td></tr>
                            <tr><th>Cant Usuarios</th><td>${row.cant_usuarios??''}</td></tr>
                            <tr><th>Mbts</th><td>${row.mbts??''}</td></tr>
                            <tr><th>Region</th><td>${row.region??''}</td></tr>
                            <tr><th>Latitud</th><td>${row.latitud??''}</td></tr>
                            <tr><th>Longitud</th><td>${row.longitud??''}</td></tr>
                            </tbody>
                            </table>`
                        });
                        store.infoWindow.open(map, marker);
                    });
                    return marker;
                });
                $("#spinnerData").hide();
            }
        });
    }

    function addMarkerSites(feature,icon, options = {}) {
      const marker = new google.maps.Marker({
        position: new google.maps.LatLng(feature.latitud,feature.longitud),
        //icon: icon,
        icon: icon,
        type: feature.type,
        ...options
      });
      markers.push(marker);
      return marker;
    }

    function setMapOnSites(map,type) {
      for (let i = 0; i < markers.length; i++) {
        if(markers[i].type == type){
            markers[i].setMap(map);
        }
      }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkersSites(type) {
      setMapOnSites(null,type);
    }

    function clearMarkersAll() {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
        loadMarker = [];
    }

    async function renderPolygonTableData()
    {
        const id_distribucion = $(".slc-distribucion").val();
        let targetSelector = ".polygon-table-data";

        if(store.table){
            store.table.destroy();
            store.table = undefined;
        }

        $(`${targetSelector} thead`).html("");
        $(`${targetSelector} tbody`).html("Cargando ...");

        $(`.polygon-sub-table-data thead`).html("");
        $(`.polygon-sub-table-data tbody`).html("");

        $.ajax({
            url: base_url+"fija-coverage/table-data",
            data: {
                distribucion: id_distribucion,
                kpi_id: store.polygonSelected.kpiSelected,
                id: store.polygonSelected.id,
                ano: $(".slc-ano").val(),
                semana: $(".slc-semana").val(),
            },
            dataType: 'json',
            success:function (response) {
                let htmlHead = Object.keys(response.fields)
                .map(fname => `<th>${response.fields[fname]["label"]}</th>`);

                let htmlBody = response.data.map(row => {
                    let html = Object.keys(response.fields)
                    .map(fname => {
                        let rowField = response.fields[fname];
                        let hasSubTable = false;
                        let extraAttr = "";
                        if(rowField.sub_table){
                            hasSubTable = true;
                            let filters = Object.keys(rowField.sub_table).map(ffilter => `${rowField.sub_table[ffilter]}.eq.${row[ffilter]}`);
                            extraAttr = `data-field="${fname}" data-subfilters='${JSON.stringify(filters)}'`;
                        }
                        return `<td class="${response.fields[fname]['class']} ${hasSubTable?'open-sub-table':''}" ${extraAttr}>${row[fname]??''}</td>`
                    })
                    .join("");
                    return `<tr>${html}</tr>`;
                });
                $(`${targetSelector} thead`).html(`<tr>${htmlHead}</tr>`);
                $(`${targetSelector} tbody`).html(htmlBody);
                //store.table = $(targetSelector).DataTable({searching: false, scrollX: true, paging: false});
            },
            error: function(){
                $(`${targetSelector} tbody`).html("");
            }
        });
    }

    async function renderVerticalesLiberadosMarkers()
    {
        console.log([]);
        fetch(`${base_url}fija-coverage/verticales-liberados`)
        .then(resp => resp.json())
        .then(data => {
            let result = selectors.groupBy(data, ['latitud','longitud']);
            console.log(result);
            store.verticalesLiberados.data = result;
            store.verticalesLiberados.markers = Object.keys(result).map(latLng => {
                let row = result[latLng][0];
                let marker = new google.maps.Marker({
                    map: null,
                    data: {nodo: row.nodo},
                    position: new google.maps.LatLng(parseFloat(row.latitud), parseFloat(row.longitud)),
                    icon: {
                        url: "{{ asset('images/edificio.png') }}",
                        //scaledSize: new google.maps.Size(30, 30),
                        //origin: new google.maps.Point(0, 0),
                        //anchor: new google.maps.Point(0, 0)
                    },
                    //anchor: new google.maps.Point(0, 10)
                });
                marker.addListener("click", function(){
                    //const id_distribucion = $(".slc-distribucion").val();
                    //const kpi_id = $(".slc-mapKpiType").val();
                    //const distribucion = mapConfig.distribucionById[id_distribucion];
                    //store.polygonSelected = {id: row.nodo};
                    //viewDataMap(distribucion.id, kpi_id, [`${distribucion.dataProps.id}.eq.${row.nodo}`]);
                    //graphModalView.hide();
                    if(store.infoWindow !== undefined){
                        store.infoWindow.close();
                    }
                    let htmlDirecciones = result[latLng].map((vertical, index) => `<tr><th>Dirección ${index+1}</th><td>${vertical.direccion}</td>
                    <th>Edificio</th><td>${vertical.edificio}</td></tr>`).join("");

                    store.infoWindow = new google.maps.InfoWindow({
                        content: `<table class="table table-condensed table-sm">
                        <thead><tr class="active"></tr>
                        </thead>
                        <tbody>
                        <tr><th>Plano</th><td>${row.nodo}</td></tr>
                        ${htmlDirecciones}
                        </tbody>
                        </table>`
                    });
                    store.infoWindow.open(self.map, marker);
                });
                return marker;
            });
        });
    }

    async function renderUbigeos(){
        return fetch(`${base_url}fija-coverage/ubigeos`)
        .then(resp => resp.json())
        .then(data => {
            store.ubigeos = data.map(r => ({
                label: `${r.distrito} ${r.provincia} ${r.departamento}`,
                latitud: r.latitud,
                longitud: r.longitud
            }));
            planosModalView.setPlanos([
                ...store.data_fija,
                ...store.ubigeos
            ]);
        });
    }

    async function renderCapaLimiteDistritos(isChecked){
        store.limiteDistritosFeatures.forEach(feature => {
            map.data.remove(feature);
        });
        if (isChecked) {
            store.limiteDistritosFeatures = await loadGeoJson(`{{ asset('map/limite_distritos.json') }}?time=${(new Date()).getTime()}`);
            store.limiteDistritosFeatures.forEach(feature => {
                map.data.overrideStyle(feature, {
                    strokeWeight: 0.7,
                    strokeColor: "#ff0000",
                    fillOpacity: 0.7
                });
            });
        } else {
            store.limiteDistritosFeatures = [];
        }
    }

    function toogleVerticalesLiberados(){
        const checked = document.querySelector("#checkbox_verticales").checked;
        if(checked){
            store.verticalesLiberados.markers.forEach(marker => {
                marker.setMap(map);
            });
        }else{
            store.verticalesLiberados.markers.forEach(marker => {
                marker.setMap(null);
            });

        }
    }

    async function renderTopTable(){
        const distribucion_id = $(".slc-distribucion").val();
        const kpi_id = $(".slc-mapKpiType").val();

        const ano = $(".slc-ano").val();
        const semana = $(".slc-semana").val();

        // fetch(`${base_url}fija-coverage/${distribucion_id}/${kpi_id}/top-data?filter[]=ano.eq.${ano}&filter[]=semana.eq.${semana}`)
        fetch(`${base_url}fija-coverage/19?menu=FIJA-Cobertura`)
        .then(response => response.json())
        .then(response => {
            let headHtml = Object.keys(response.fields).map(field => `<th>${response.fields[field].label}</th>`).join("");
            let bodyHtml = response.data.map(row => {
                return `<tr data-id="${row.plano}">`+Object.keys(response.fields)
                .map(field => `<td class="${response.fields[field].class??''}">${row[field]}</td>`)+"</tr>";
            }).join("");
            $(".table_top thead").html(`<tr>${headHtml}</tr>`);
            $(".table_top tbody").html(bodyHtml);
            $(".table_top tbody .search").on("click", function(e){
                const dist = mapConfig.distribucionById[distribucion_id];
                planosModalView.setDistribucionConfig(dist);
                planosModalView.ver_handler($(e.target.parentElement).attr("data-id"), undefined, undefined);
                /*let id = $(e.target.parentElement).attr("data-id");
                const distribucion = mapConfig.distribucionById[distribucion_id];
                store.polygonSelected = {id: id};
                viewDataMap(distribucion.id, kpi_id, [`${distribucion.dataProps.id}.eq.${id}`]);
                graphModalView.hide();*/
            });
        })
        .catch(error => {
            $(".table_top thead").html("");
            $(".table_top tbody").html("");
        });
    }
    //getDataFija();

    // events
    $(".slc-ano").on('change', async function(){
        await changeano();
        const semanas = $(".slc-semana option");
        if(semanas.length > 0){
            $(".slc-semana").val($(semanas[1]).attr('value'));
        }
        //getDataMapHeat();
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
    });
    $(".slc-semana").on('change', async function(){
        //getDataMapHeat();
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
        renderTopTable();
        if(store.labelNumCompetencia.markers.length === 0){
            addMarkersNumCompetencia(true);
        }
        if (store.ubigeos.length === 0) {
            renderUbigeos();
        }
    });
    $(".slc-mapKpiType").on('change', async function(){
        //getDataMapHeat();
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
        
        $("#legend .legends").hide();
        $("#legend .leg"+$(this).val()).show();

        //$("#main-title").text($("#legend .leg"+$(this).val()).find("h6").text());
        renderTopTable();
    });
    $(".slc-distribucion").on('change', async function(){
        //getDataMapHeat();
        $("#spinnerData").show();
        const filters = getMappedFilters();
        await addMapLayers();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();

        const dist = mapConfig.distribucionById[$(this).val()];
        planosModalView.setTitle(dist.label);
        planosModalView.setDistribucionConfig(dist);
        planosModalView.setPlanos([
            ...store.data_fija,
            ...store.ubigeos
        ]);
        //planosModalView.hide();
        $("#dataMapInd").hide();
        panelDataView.hide();
    });
    $(".slc-filter").on('change', async function(){
        //getDataMapHeat();
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
        planosModalView.setPlanos([
            ...store.data_fija,
            ...store.ubigeos
        ]);
    });
    $(".slc-ubgDpto").change(function(){
        const ubigeo_dpto = $(this).val();
        $.ajax({
            url: base_url+"fija-coverage/departamentos/"+ubigeo_dpto+"/provincias",
            dataType: 'json',
            success:function (response) {
                let html = "<option selected value='0'>---Provincia---</option>";
                html += response.map(r => `<option value='${r.CODE}' data-coords='{"lat": ${r.Y_COORD}, "lng": ${r.X_COORD}}'>${r.PROVINCIA}</option>`).join('');
                $(".slc-ubgProv").html(html);
            }
        });
    });
    $(".slc-ubgProv").change(function () {
        const ubigeo_prov = $(this).val();
        const center = JSON.parse($(`.slc-ubgProv option[value=${ubigeo_prov}]`).attr('data-coords'));
        $.ajax({
            url: base_url+"fija-coverage/departamentos/provincias/"+ubigeo_prov+"/distritos",
            dataType: 'json',
            success:function (response) {
                let html = "<option selected value='0'>---Distrito---</option>";
                html += response.map(r => `<option value='${r.CODE}' data-coords='{"lat": ${r.Y_COORD}, "lng": ${r.X_COORD}}'>${r.DISTRITO}</option>`).join('');
                $(".slc-ubgDist").html(html);
            }
        });
        map.setCenter(center);
    });
    $(".slc-ubgDist").change(function () {
        const ubigeo_dist = $(this).val();
        const center = JSON.parse($(`.slc-ubgDist option[value=${ubigeo_dist}]`).attr('data-coords'));
        map.setCenter(center);
    });

    $(".slc-cobertura-otros").on('change', function(){
        renderCoberturaOtros();
    });

    $(".slc-competencia").on('change', function(){
        renderCompetencia();
    });

    $(".chk_nuevos").on('change', function(e){
        // let element = document.querySelector("#checkbox_nuevos");
        let element = document.querySelector(".check_nuevos_item:checked");
        addMarkerForNewPolygons(element);
    });

    $("#checkbox_verticales").on('change', function(){
        toogleVerticalesLiberados();
    });

    $(document).on('change', '.loadMarkersCheck', function(){
        loadCapa($(this));
    });

    $(".btn_open_planos_modal").on('click', function(){
        const dist = mapConfig.distribucionById[$(".slc-distribucion").val()];
        planosModalView.setTitle(dist.label);
        planosModalView.setDistribucionConfig(dist);
        planosModalView.setPlanos([...store.data_fija, ...store.ubigeos]);
        planosModalView.show();
        panelDataView.hide();
    });

    // $('.dropdown-menu').on('click', function(e) { e.stopPropagation(); });
    $(".btn_export").on("click", function(){
        $(this).prop("disabled", true);
        let filters = [];
        filters.push(`ano.eq.${$(".slc-ano").val()}`);
        filters.push(`semana.eq.${$(".slc-semana").val()}`);
        filters = [...filters, ...getMappedFilters()];

        let body = {
            distribucion: $(".slc-distribucion").val(),
            kpis_id: $(".export .slc-kpi").val(),
            filter: filters
        };

        let strFilters = filters.map(f => `filter[]=${f}`).join('&');
        window.open(base_url+`fija-coverage/data-fija/export?distribucion=${body.distribucion}&${strFilters}`);
        return;

        const formData = new FormData();
        formData.append("distribucion", $(".slc-distribucion").val());
        ($(".export .slc-kpi").val()??[]).forEach(f => {
            formData.append("kpis_id[]", f);
        });
        filters.forEach(f => {
            formData.append("filter[]", f);
        });
        formData.append("_token", document.querySelector("meta[name=csrf-token]").attributes['content'].value);

        fetch(base_url+"fija-coverage/data-fija/export", {
            method: "POST",
            /*headers: {
                "Content-Type": "application/json"
            },*/
            body: formData
        })
        .then(async (response) => {
            if(!response.ok){
                const text = await response.text();
                throw new Error(text);
            }
            let fileName = 'reporte';
            const content_disp = response.headers.get('Content-Disposition');
            const header_parts = (content_disp??"").replaceAll('"', '').split(";");
            header_parts.forEach(row => {
                if(row.split("=")[1] !== undefined){
                    fileName = row.split("=")[1];
                }
            });

            const blob = await response.blob();

            const url = window.URL.createObjectURL(blob)
            const link = document.createElement('a')
            link.href= url
            link.setAttribute('download', fileName);
            document.body.appendChild(link);
            link.click();
            link.parentNode.removeChild(link);

            $(this).prop("disabled", false);
        })
        .catch(error => {
            $(this).prop("disabled", false);
            alert(error);
        });
    });

    // tabs options
    $("#table-tab").on('click', function(e){
        
    });

    $(".btn_toogle_collapse").on('click', function(){
        let toogle = $(this).attr('data-toogle');
        toogle = toogle === 'true';
        $(this).attr('data-toogle', !toogle);
        if(toogle){
            $(".collapse_container").hide();
        }else{
            $(".collapse_container").show();
        }

    });

    $(".nav .nav-item").on("click", function(e){
        e.target.parentElement.parentElement.children.forEach(el => {
            console.log(el);
            el.firstElementChild.classList.remove("active");
        });
        e.target.classList.add("active");

        let tabPane = document.querySelector(e.target.attributes["data-bs-target"].value);
        tabPane.parentElement.children.forEach(el => {
            el.classList.remove("show");
            el.classList.remove("active");
        });
        tabPane.classList.add("show");
        tabPane.classList.add("active");
    });

    $(".polygon-table-data tbody").on("click", function(e){
        let classList = e.target.classList;
        if(classList.contains("open-sub-table")){
            const id_distribucion = $(".slc-distribucion").val();
            let subField = e.target.attributes["data-field"].value;
            let subFilters = e.target.attributes["data-subfilters"].value;
            console.log({
                distribucion: id_distribucion,
                subField: subField,
                subFilters: JSON.parse(subFilters)
            });
            let targetSelector = ".polygon-sub-table-data";
            $(`${targetSelector} thead`).html("");
            $(`${targetSelector} tbody`).html("Cargando ...");
            $.ajax({
                url: base_url+"fija-coverage/sub-table-data",
                data: {
                    distribucion: id_distribucion,
                    subField: subField,
                    filter: JSON.parse(subFilters)
                },
                dataType: 'json',
                success:function (response) {
                    let htmlHead = Object.keys(response.fields)
                    .map(fname => `<th>${response.fields[fname]["label"]}</th>`);

                    let htmlBody = response.data.map(row => {
                        let html = Object.keys(response.fields)
                        .map(fname => `<td>${row[fname]??''}</td>`)
                        .join("");
                        return `<tr>${html}</tr>`;
                    });
                    $(`${targetSelector} thead`).html(htmlHead);
                    $(`${targetSelector} tbody`).html(htmlBody);
                },
                error: function(){
                    $(`${targetSelector} tbody`).html("");
                }
            });
        }
    });

    // panels
    $(".btn-toogle-options").on("click", function(){
        let active = !($(this).attr("data-toogle") === "true");
        $(this).attr("data-toogle", active);
        if(active){
            panelOptionsView.show();
        }else{
            panelOptionsView.hide();
        }
    });
    $(".btn-toogle-layers").on("click", function(){
        let active = !($(this).attr("data-toogle") === "true");
        $(this).attr("data-toogle", active);
        if(active){
            $(".layers-content").show();
            $(this).find("i").removeClass("la-layer-group").addClass("la-close");
        }else{
            $(".layers-content").hide();
            $(this).find("i").removeClass("la-close").addClass("la-layer-group");
        }
    });

    $(".check_tecnologia").on("change", function(){
        let tecnologia = document.querySelector(".check_tecnologia:checked").value;
        if(tecnologia !== ""){
            store.features.forEach(feature => {
                const containsFeature = map.data.contains(feature);
                if(feature.getProperty("tecnologia") === tecnologia){
                    if(!containsFeature){
                        map.data.add(feature);
                    }
                }else{
                    if(containsFeature){
                        map.data.remove(feature);
                    }
                }
            });
        }else{
            store.features.forEach(feature => {
                const containsFeature = map.data.contains(feature);
                if(!containsFeature){
                    map.data.add(feature);
                }
            });
        }
    });
    $("#check_overlap").on("change", async function(e){
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
    });
    $("#check_num_competencia").on("change", function(e){
        renderMarkersNumCompetencia();
    });
    $(".btn-buscar-fats").on("click", function(e){
        if(store.busquedaFats.originMarker){
            store.busquedaFats.originMarker.setMap(null);
            store.busquedaFats.originMarker = undefined;
        }
        store.busquedaFats.features.forEach(feature => {
            map.data.remove(feature);
        });
        store.busquedaFats.fatsMarkers.forEach(marker => {
            marker.setMap(null);
        });

        let isActive = $(".btn-buscar-fats").attr("data-active") === 'true';
        $(".btn-buscar-fats").attr("data-active", !isActive);
        console.log("isActive", isActive);
        if(!isActive){
            store.features.forEach(feature => {
                const containsFeature = map.data.contains(feature);
                if(containsFeature){
                    map.data.remove(feature);
                }
            });
        }else{
            $(".check_tecnologia").trigger("change");
        }
    });

    $("#check_top_sites_tethering").on("change", function(e){
        if(e.target.checked){
            renderMarkersTopSitesTethering();
        }else{
            store.topSitesTetheringMarkers.forEach(marker => {
                marker.setMap(null);
            });
            store.topSitesTetheringMarkers = [];
        }
    });

    $("#check_flag_mala_venta").on('change', async function(e){
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
        planosModalView.setPlanos([...store.data_fija, ...store.ubigeos]);
    });

    $("#check_limite_distritos").on('change', async function(e){
        renderCapaLimiteDistritos(e.target.checked);
    });


    mapConfig.kpiConfigById = selectors.groupBy(mapConfig.kpiConfig, 'ID');
    Object.keys(mapConfig.kpiConfigById).forEach(id => {
        mapConfig.kpiConfigById[id] = mapConfig.kpiConfigById[id][0];
    });

    mapConfig.distribucionById = selectors.groupBy(mapConfig.distribucion, 'id');
    Object.keys(mapConfig.distribucionById).forEach(id => {
        mapConfig.distribucionById[id] = mapConfig.distribucionById[id][0];
    });

    let graphModalView;
    let planosModalView;
    let panelDataView;
    let panelOptionsView;
    const main_init = async () => {
        initMap();

        google.maps.event.addListenerOnce(map,'idle', function(){
            let legend = document.getElementById('legend');
            map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
            legend.style.display = '';

            let legend_layers = document.getElementById('legend_layers');
            map.controls[google.maps.ControlPosition.LEFT_TOP].push(legend_layers);
            legend_layers.style.display = '';

            //let legend_data = document.querySelector('.panel-data');
            //map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend_data);
        });

        let distribuciones = $(`.slc-distribucion option`);
        let distribucion = distribuciones[distribuciones.length-1];
        $(".slc-distribucion").val($(distribucion).val());

        await addMapLayers();
        graphModalView = new GraphModal('#modal_graph');
        planosModalView = new PlanosModal('#planos_modal');
        const dist = mapConfig.distribucionById[$(".slc-distribucion").val()];
        planosModalView.setTitle(dist.label);
        planosModalView.setDistribucionConfig(dist);

        panelDataView = new GraphModal(".panel-data");
        panelOptionsView = new GraphModal(".panel-options");

        const maxsemana=$(`.slc-ano option[value=${$(".slc-ano").val()}]`).attr('data-maxsemana');
        await changeano();
        $(".slc-semana").val(maxsemana);
        $(".slc-semana").trigger('change');

        renderVerticalesLiberadosMarkers();
    }
    main_init();

    map.data.addListener('click', function(event) {
        const feature = event.feature;
        const id_distribucion = $(".slc-distribucion").val();
        const kpi_id = $(".slc-mapKpiType").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];
        
        const filter = `${distribucion.dataProps.id}.eq.${feature.getProperty(distribucion.featProps.id)}`;
        store.polygonSelected = {
            id: feature.getProperty(distribucion.featProps.id)
        };
        viewDataMap(distribucion.id, kpi_id, [filter]);
        graphModalView.hide();
    });

    /*map.addListener("zoom_changed", function(){
        if(map.getZoom() >= 12){
            if(store.labelNumCompetencia.show === false){
                addMarkersNumCompetencia(true);
                store.labelNumCompetencia.show = true;
                console.log("cargando markers");
            }else{
                store.labelNumCompetencia.markers.forEach(m => m.setMap(map));
                console.log("activando markers");
            }
        }else{
            console.log("quitando markers");
            store.labelNumCompetencia.markers.forEach(m => m.setMap(null));
            //store.labelNumCompetencia.markers = [];
            //store.labelNumCompetencia.show = false;
        }
    });*/

    map.addListener("bounds_changed", function(){
        if(store.labelNumCompetencia.timer){
            clearTimeout(store.labelNumCompetencia.timer);
        }
        if(map.getZoom() >= 15 && document.querySelector("#check_num_competencia").checked){
            store.labelNumCompetencia.timer = setTimeout(function(){
                let bounds = map.getBounds();
                let markersHide = [];
                let markersShow = store.labelNumCompetencia.markers.filter(marker => {
                    let contains = bounds.contains(marker.getPosition());
                    if(!contains){
                        markersHide.push(marker);
                    }
                    return contains;
                });
                markersHide.forEach(m => {
                    if(m.map !== null){
                        m.setMap(null);
                    }
                });
                markersShow.forEach(marker => {
                    if(marker.map === null){
                        marker.setMap(map);
                    }
                });
            }, 500);
        }else{
            store.labelNumCompetencia.markers.forEach(marker => {
                if(marker.map !== null){
                    marker.setMap(null);
                }
            });
        }
    });

    map.addListener("click", function(event){
        let isActive = $(".btn-buscar-fats").attr("data-active") === 'true';
        if(isActive){
            $("#spinnerData").show();
            store.busquedaFats.originMarker = new google.maps.Marker({
                map: map,
                position: event.latLng,
            });
            $(".btn-buscar-fats").attr("data-active", false);
            $(".check_tecnologia").trigger("change");

            //fetch(`${base_url}api/busqueda-fats`)
            //fetch(`http://127.0.0.1:8000/api/busqueda-fats?longitud=${event.latLng.lng()}&latitud=${event.latLng.lat()}`)
            fetch(`http://172.19.122.127:8001/api/busqueda-fats?longitud=${event.latLng.lng()}&latitud=${event.latLng.lat()}`)
            .then(resp => resp.json())
            .then(response => {
                //if(response.polylines.length > 0 && response.fats.length > 0){
                if(response.passes === true){
                    let geojson = {
                        "type": "FeatureCollection",
                        "features": response.data.polylines
                    };
                    let features = map.data.addGeoJson(geojson);
                    features.forEach(feature => {
                        let style = {
                            strokeColor: "#3498db",
                            strokeOpacity: 1.0,
                            strokeWeight: 3,
                            zIndex: 2
                        };
                        map.data.overrideStyle(feature, style);
                        feature.setProperty("capa", "busqueda_fats");
                    });
                    store.busquedaFats.features = features;

                    // markers
                    store.busquedaFats.fatsMarkers = response.data.fats.map(row => {
                        return new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng(parseFloat(row.latitud), parseFloat(row.longitud)),
                            icon: "{{ asset('images/trapecio.png') }}",
                        });
                    });

                    if(store.busquedaFats.infoWindow){
                        store.busquedaFats.infoWindow.close();
                    }

                    store.busquedaFats.infoWindow = new google.maps.InfoWindow({
                        content: `<table class="table table-condensed table-sm">
                        <thead><tr class="active"></tr>
                        </thead>
                        <tbody>
                        <tr><td>Origen latitud</td><td>${event.latLng.lat()}</td></tr>
                        <tr><td>Origlen longitud</td><td>${event.latLng.lng()}</td></tr>
                        <tr><td>Plano</td><td>${response.data.fats[0].plano}</td></tr>
                        <tr><td>Nombre Fat</td><td>${response.data.fats[0].fat}</td></tr>
                        <tr><td>Distancia</td><td>${features[0].getProperty('distance')}</td></tr>
                        </tbody>
                        </table>`
                    });
                    store.busquedaFats.infoWindow.open(map, store.busquedaFats.originMarker);
                    store.busquedaFats.originMarker.addListener("click", function(){
                        store.busquedaFats.infoWindow.open(map, store.busquedaFats.originMarker);
                    });
                } else {
                    alert(response.errors.message);
                }
                $("#spinnerData").hide();
            })
            .catch(error => {
                alert(error.message);
                $("#spinnerData").hide();
            });
        }
    });


    $('#detalleTablaModal').on('show.bs.modal', function(e) {
        if(store.dinamic_table){
            store.dinamic_table.destroy();
            store.dinamic_table = undefined;
        }
        // Realiza una solicitud AJAX para obtener los datos de la tabla
        var boton = $(e.relatedTarget);
        var plano = boton.data('kpi');
        let ano = $(".slc-ano").val();
        let semana = $(".slc-semana").val();
        $.ajax({
          url: "{{ route('api.getDatallePlanoTabla') }}",
          type: 'GET',
          data: {plano, ano, semana},
          dataType: 'json',
          success: function(response) {
              // Borra el contenido anterior de la tabla
              $('#tabla-dinamica tbody').empty();
              $("#exampleModalLabel").html(plano);

              // Agrega las filas de datos a la tabla
              $.each(response.data, function(index, row) {
                var html = '<tr>';

                $.each(row, function(k, v) {
                    if( v == null){
                        html += '<td> - </td>';
                    }else{
                        html += '<td>' + v + '</td>';
                    }
                });

                html += '</tr>';

                $('#tabla-dinamica tbody').append(html);
              });            
              store.dinamic_table = $('#tabla-dinamica').DataTable({searching: false, scrollX: true, paging: false});
          },
          error: function(xhr, status, error) {
            console.log(error);
          }
        });
      });
    
});
</script>

@endsection
