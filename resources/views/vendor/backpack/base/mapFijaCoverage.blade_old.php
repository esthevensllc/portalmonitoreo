@extends(backpack_view('blank'))

@section('after_styles')
<style>
    div select {
        max-width: 130px;
    }

    .itmDis{
        color:#DBDBDB;
    }
    
    #map {
        height: 95vh;
        width: 100%;
        position: relative;
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
        position: fixed;
        font-family: Arial, sans-serif;
        background: #fff;
        padding: 10px;
        border: 3px solid #fff;
        bottom: 100px;
        right: 60px;
        border-radius: 10px;
        opacity: .7;
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
        border: 1px solid;
        margin: 0 5px 0 0;
        cursor: pointer;
    }
    .lgndColor.unchecked{
        background: white!important;
    }
      
    .lgndColor.c100{
        background: #580aff;
    }
    .lgndColor.c80{
        background: #147df5;
    }
    .lgndColor.c60{
        background: #0aff99;
    }
    .lgndColor.c50{
        background: #a1ff0a;
    }
    .lgndColor.c40{
        background: #deff0a;
    }
    .lgndColor.c30{
        background: #ffd300;
    }
    .lgndColor.c20{
        background: #ff8700;
    }
    .lgndColor.c10{
        background: #ff0000;
    }
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
        position: absolute;
        /*left: 20px;*/
        background: #FFF;
        padding: 10px;
        border-radius: 10px;
        margin: 60px 10px;
        max-height: 500px;
        overflow-y: scroll;
        padding-top: 24px;
        opacity: .9;
        display: none;
        top: 0;
    }
    #dataMapInd table td{
        border: 1px solid #bdbdbd;
    }
    #dataMapInd table td.table-secondary{
        background: #CED4DE;
    }
    .sectTabsContent div select{
        max-width: 130px;
    }
    .lengend{
        position: absolute;
        background: #fff;
        opacity: .9;
        border-radius: 5px;
    }
    .lgnd-graph{
        min-height: 100px;
        width: 600px;  
        left: 320px;
    }
    .lgnd-header{
        cursor: move;
    }
    .lgnd-close{
        font-size: 15px;
        float: right;
        font-weight: bold;
        cursor: pointer;
    }
    .lgnd-planos{
        min-height: 300px;
        width: 250px;
        left: 200px;
    }
    .modal-dialog {
      max-width: 1000px;
    }
    .interaccion-number {
        cursor: pointer;
        color: #3f3fff;
    }
    .polygon-table-data tbody tr td, .polygon-sub-table-data tbody tr td {
        font-size: 16px !important;
        padding: 1px;
    }
</style>
@endsection

@section('header')
<!-- Modal -->
<div class="modal fade" id="detalleTablaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">FIJAPLANOSCOMPETENCIA</h5>
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
<div class="pt-1">
    <div class="d-flex">
        <div style="flex-grow: 1;">
            <select class="slc-group btn-sm btn-primary d-none">
                <option value="1">Semanal</option>
                <option value="2">Ultimas 4 semanas</option>
            </select>
            <div class="d-inline-block">
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <select class="slc-anio btn-sm btn-primary" style="max-width: 67px;">
                            <option value="0" disabled="" class="itmDis">--Año--</option>
                            <?php foreach ($anio as $key => $item): ?>
                                <option value="<?=$item->anio?>" data-maxsemana="<?=$item->max_semana?>"><?=$item->anio?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <select class="slc-semana btn-sm btn-primary" style="max-width: 55px;">
                        <option selected="" value="0" disabled>--Semana--</option>
                    </select>
                </div>
            </div>
            <select class="slc-distribucion btn-sm btn-primary">
                <option value="1">Departamento</option>
                <option value="2">Provincia</option>
                <option value="3">Distrito</option>
                <option selected="" value="4">Planos</option>
            </select>
            <select class="slc-mapKpiType btn-sm btn-danger">
                <option value="0" disabled="" class="itmDis">---Tipo KPI---</option>
                <?php
                foreach($kpis as $row){
                    if($row->ID === 10 || $row->ID === 11){ continue; }
                    echo "<option value='{$row->ID}' data-data='".json_encode($row)."' ".($row->ID === 1 ? 'selected':'').">{$row->LABEL}</option>";
                }
                ?>
            </select>

            <select class="slc-ubgDpto form-control form-control-sm d-inline-block slc-filter" name="ubigeo_dpto">
                <option selected="" value="0">---Departamento--</option>
                <?php foreach ($dptoList as $key => $item): ?>
                    <option value="<?=$item['CODE']?>"><?=$item['DEPARTAMENTO']?></option>
                <?php endforeach ?>
            </select>
            <select class="slc-ubgProv form-control form-control-sm d-inline-block slc-filter" name="ubigeo_prov">
                <option selected="" value="0">---Provincia---</option>
            </select>
            <select class="slc-ubgDist form-control form-control-sm d-inline-block slc-filter" name="ubigeo">
                <option selected="" value="0">---Distrito---</option>
            </select>
            <select class="form-control form-control-sm d-inline-block slc-filter" name="tecnologia" style="max-width: 110px;">
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
        <div class="d-flex justify-content-end" style="width: 35px;">
            <div>
                <button class="btn btn-sm btn-outline-primary btn_toogle_collapse">
                    <i class="la la-list"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="collapse_container mb-2" style="display: none;">
        <div class="form-check d-inline mr-3">
            <input class="form-check-input chk_nuevos" type="checkbox" id="checkbox_nuevos">
            <label class="form-check-label" for="checkbox_nuevos">Mostrar Nuevos</label>
        </div>        
        <div class="form-check d-inline mr-3">
            <input class="form-check-input chk_nuevos" type="checkbox" id="checkbox_verticales">
            <label class="form-check-label" for="checkbox_verticales">Mostrar Verticales Liberados</label>
        </div>        
        <button class="btn btn-outline-primary btn-sm btn_open_planos_modal" type="button">
            <i class="la la-eye"></i>
            Ubicación
        </button>
        <button class="btn btn-outline-primary btn-sm btn_export" type="button">
            <i class='la la-export'></i>
            Exportar
        </button>
    </div>
</div>

<div style="position: relative;">
    <div id="map" style="overflow: hidden; position: relative;"></div>
    <div id="legend">
        <div class="legends leg1"><h5>Penetracion %</h5>
        <table class="data">
        <tr><td><label class="lgndColor c10"></label></td><td>0 - 10</td></tr>
        <tr><td><label class="lgndColor c20"></label></td><td>10 - 20</td></tr>
        <tr><td><label class="lgndColor c30"></label></td><td>20 - 30</td></tr>
        <tr><td><label class="lgndColor c40"></label></td><td>30 - 40</td></tr>
        <tr><td><label class="lgndColor c50"></label></td><td>40 - 50</td></tr>
        <tr><td><label class="lgndColor c60"></label></td><td>50 - 60</td></tr>
        <tr><td><label class="lgndColor c80"></label></td><td>60 - 80</td></tr>
        <tr><td><label class="lgndColor c100"></label></td><td>80 - 100</td></tr>
        </table></div>
        <?php
            foreach($kpis as $row){
                if(property_exists($row, 'COLOR_RANGE')){
                    echo "<div class=\"legends leg{$row->ID} hideManual\" style=\"display: none;\">
                    <h5>{$row->LABEL}</h5>
                    <table class=\"data\">
                    ";
                    foreach($row->COLOR_RANGE as $range){
                        $CONDITION = str_replace("KPI && KPI", "KPI", $range->CONDITION);
                        $CONDITION = str_replace("==", "=", $CONDITION);
                        $value = "<tr>
                            <td><label class=\"lgndColor2 mb-0\" style=\"background: {$range->COLOR};\"></label></td>
                            <td>{$CONDITION}</td>
                        </tr>";
                        echo $value;
                    }
                    echo "</table></div>";
                }
            }
        ?>
    </div>

    <div id="dataMapInd" style="display: none; overflow-y: auto; padding: 10px; overflow-x: hidden;">dataMapInd</div>

    <div id="modal_graph" class="lengend lgnd-graph" style="display: none; z-index: 1; top:0;">
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
                            <table class="table table-sm polygon-table-data">
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
            </div>
        </div>
    </div>

    <div id="planos_modal" class="lengend lgnd-planos" style="display: none; z-index: 2; top:0;">
        <div class="l p-3">
            <div class="row lgnd-header">
                <div class="col-10">
                    <h5 class="title">Planos fija</h5>
                </div>
                <div class="col-2">
                <b class="lgnd-close">x</b>
                </div>
            </div>
            <div>
                <h5></h5>
                <div class="mb-2">
                <input type="text" class="form-control form-control-sm input_search">
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

@include("vendor.backpack.base.widgets.spinner_loader")

@endsection

@section('after_scripts')
<script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-exporting.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-export-data.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&libraries=visualization&v=weekly"></script>
<script>
let mapConfig = {
    'mapurl': "{{ asset('map/'.$planoUrl.'.json') }}",
    kpiConfig: <?=json_encode($kpis)?>,
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
    detailFieldsByDistribucion: <?=json_encode($detailFieldsByDistribucion)?>
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
    markers_verticales: []
};
let map;
let test;
let base_url = "{{ asset('') }}";
let loadMarker = [];
let markers = [];
let planoCompetencia = '';

// Define el ícono personalizado
var iconoPersonalizado = {
  url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
  scaledSize: new google.maps.Size(32, 32),
};

$(document).ready(function () {
    class GraphModal{
        constructor(el_selector){
            this.el_selector = el_selector;
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

        ver_handler(name){
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
                        if(features[0].getProperty("centroide_longitud") !== null){
                            map.setCenter({lat: features[0].getProperty("centroide_latitud"), lng: features[0].getProperty("centroide_longitud")});
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
                store.markers_verticales.forEach(marker => {
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
            const props_label =this.props_to_use.dataProps.label;
            const value = props_label.map(prop => row[prop]).join('-');
            
            return value !== null && value !== undefined ? value : '';
        }

        render(){
            let _this = this;
            const list_filtered = this.planos.filter(row => this._getLabelOfRow(row).toUpperCase().includes(this.text_to_filter.toUpperCase()))
            .map(row => `<tr>
                <td>${this._getLabelOfRow(row)}</td>
                <td><button class="btn btn-sm btn-secondary p-0 pr-1 pl-1" data-name="${row[this.props_to_use.dataProps.id]}">Ver</button></td>
            </tr>`);
            const html = list_filtered.join('');
            $(`${this.el_selector} .title`).text(this.title);
            $(`${this.el_selector} .list-footer`).html(`<p>${list_filtered.length} de ${this.planos.length}</p>`);
            $(`${this.el_table} tbody`).html(html);

            $(`${this.el_table} tbody button`).off('click');
            $(`${this.el_table} tbody button`).on('click', (e) => {
                _this.ver_handler($(e.target).attr('data-name'));
            });
        }
        
    }

    const selectors = {
        groupBy: (data, keyProp) => {
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
        filters.push(`anio.eq.${$(".slc-anio").val()}`);
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
                        if(data_fija_by_key[name] !== undefined){
                            value = data_fija_by_key[name][0][kpiConfig['NAME']];
                            overlap = data_fija_by_key[name][0]["overlap"];
                            planos_mala_venta = data_fija_by_key[name][0]["planos_mala_venta"];
                        }

                        let strokeWeight = 1;
                        if(overlap){
                            if(parseInt(overlap) === 1){
                                strokeWeight = 3;
                            }
                        }
                        if(planos_mala_venta){
                            if(parseInt(planos_mala_venta) === 1){
                                strokeWeight = 3;
                            }
                        }

                        data = {
                            fillColor: data_fija_by_key[name] !== undefined ? getColorByKpi(kpiConfig, value) : '#818F9B',
                            //fillOpacity: 1,
                            fillOpacity: 0.6,
                            strokeColor: planos_mala_venta ? (parseInt(planos_mala_venta) === 1 ? "#9370db": '#464646') : '#95a5a6',
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
                color="#580aff";
                }else if(argument>60){
                color="#147df5";
                }else if(argument>50){
                color="#0aff99";
                }else if(argument>40){
                color="#a1ff0a";
                }else if(argument>30){
                color="#deff0a";
                }else if(argument>20){
                color="#ffd300";
                }else if(argument>10){
                color="#ff8700";
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
                anio: $(".slc-anio").val(),
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
                    const header_html = `<div class="row lgnd-header">
                        <div class="col-10">
                            <h5>Detalle ${dist.label}</h5>
                        </div>
                        <div class="col-2 d-flex align-items-center">
                            <span class="closeTab" style="position: unset;">
                            <b>x</b>
                            </span>
                        </div>
                        </div>`;
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
                            if(name === "ruc10" || name === "ruc20"){
                                tablaDetalle = `<input class="m-2 float-right loadMarkersCheck" type="checkbox" id="checkbox_${name}" data-type="${name}" data-plano="${rowData[dist.dataProps.id].toUpperCase()}">`;
                            }
                            //tablaDetalle = (name === "num_competencia" ? `<button class="btn btn-link pt-0 pb-0 float-right" id="buttonDetalleTablaModal" data-kpi="${rowData[dist.dataProps.id]}" data-toggle="modal" data-target="#detalleTablaModal">Ver</button>` : '');
                            td[index] = `<td>
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
                    $("#dataMapInd .closeTab").click(function () {
                        $("#dataMapInd").hide();
                    });
                    $("#dataMapInd").draggable({handle: ".lgnd-header"});
                    $('#dataMapInd .btn-ver-grafica-lineal').on('click', function(){
                        $("#graph-tab").show();
                        $("#graph-tab").trigger("click");
                        store.polygonSelected["kpiSelected"] = $(this).attr('data-kpi');
                        const name_of_row = $(this).attr('data-name');
                        const kpiConfig2 = mapConfig.kpiConfigById[$(this).attr('data-kpi')];
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
                                                y: parseFloat(row[_kpiConfig.NAME])
                                            };
                                        })
                                    });
                                });
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
                                            const date = new Date(row[result.result_time].replace(' ', 'T'));
                                            return {
                                                x: Date.UTC(
                                                    date.getFullYear(),
                                                    date.getMonth(),
                                                    date.getDate(),
                                                    date.getHours(),
                                                    date.getMinutes(),
                                                    date.getSeconds()
                                                ),
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

    async function changeAnio(){
        const value = $(".slc-anio").val();
        await $.ajax({
            url:base_url+"fija-coverage/semana-by-anio/"+value,
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
        Highcharts.chart({
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
            /*labels: {
                rotation: 270
            },*/
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
        });
    }

    async function renderCoberturaOtros(){
        const id_distribucion = $(".slc-distribucion").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];

        //let filters = [`anio.eq.${$(".slc-anio").val()}`, `semana.eq.${$(".slc-semana").val()}`];
        store.cobOtrosFeatures.forEach(feature => {
            map.data.remove(feature);
        });

        if($(".slc-cobertura-otros").val() !== '0'){
            api.getDataCoberturaOtros({
                data: {
                    anio: $(".slc-anio").val(),
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

    async function addMarkerForNewPolygons()
    {
        const checked = document.querySelector("#checkbox_nuevos").checked;
        const id_distribucion = $(".slc-distribucion").val();
        const distribucion = mapConfig.distribucionById[id_distribucion];

        (store.markers_new??[]).forEach(m => m.setMap(null));
        store.markers_new = [];

        if(checked === true){
            store.features.forEach(feature => {
                const name = feature.getProperty(distribucion.featProps.id);
                let data = {};
                if(name !== undefined){
                    if(store.data_fija_by_key[name] !== undefined){
                        const flag_new = store.data_fija_by_key[name][0]["flag_new"];
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
            if(loadMarker[type] != 1){
                $.ajax({
                    url: "{{ route('api.mapData') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: "type=loadMarker&param="+type+"&plano="+plano,
                    beforeSend:function () {
                        $("#spinnerData").show();
                    },
                    success: function(data) {      
                        $.each(data.data,function (key, val ) {
                            var feature = {};
                            feature.latitud = parseFloat(val.latitud);
                            feature.longitud = parseFloat(val.longitud);
                            feature.type = type
                            addMarkerSites(feature,"{{ asset('images/pin_green.png') }}");
                        });
                        setMapOnSites(map,type)
                    },
                    complete: function () {
                        $("#spinnerData").hide();
                        loadMarker[type] = 1;
                    },
                });
            }else{
                setMapOnSites(map,type);
            }
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            clearMarkersSites(type);
        }
    }

    function addMarkerSites(feature,icon) {
      const marker = new google.maps.Marker({
        position: new google.maps.LatLng(feature.latitud,feature.longitud),
        //icon: icon,
        icon: iconoPersonalizado,
        type: feature.type
      });
      markers.push(marker);
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
                anio: $(".slc-anio").val(),
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
                        return `<td class="${response.fields[fname]['class']} ${hasSubTable?'open-sub-table':''}" ${extraAttr}>${row[fname]}</td>`
                    })
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

    async function renderVerticalesLiberadosMarkers()
    {
        console.log([]);
        fetch(`${base_url}/map/verticales_liberados.json?time=${(new Date()).getTime()}`)
        .then(resp => resp.json())
        .then(geojson => {
            console.log(geojson);
            store.markers_verticales = geojson.features.map(feature => {
                let coordinates = feature.geometry.coordinates;
                let marker = new google.maps.Marker({
                    map: null,
                    data: {nodo: feature.geometry.properties.nodo},
                    position: new google.maps.LatLng(coordinates[1], coordinates[0]),
                    icon: {
                        url: "{{ asset('images/edificio.png') }}",
                        //scaledSize: new google.maps.Size(30, 30),
                        //origin: new google.maps.Point(0, 0),
                        //anchor: new google.maps.Point(0, 0)
                    },
                    //anchor: new google.maps.Point(0, 10)
                });
                return marker;
            });
        });
    }

    function toogleVerticalesLiberados(){
        const checked = document.querySelector("#checkbox_verticales").checked;
        if(checked){
            store.markers_verticales.forEach(marker => {
                marker.setMap(map);
            });
        }else{
            store.markers_verticales.forEach(marker => {
                marker.setMap(null);
            });

        }
    }
    //getDataFija();

    // events
    $(".slc-anio").on('change', async function(){
        await changeAnio();
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
    });
    $(".slc-mapKpiType").on('change', async function(){
        //getDataMapHeat();
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
        
        $("#legend .legends").hide();
        $("#legend .leg"+$(this).val()).show();
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
        planosModalView.setPlanos(store.data_fija);
        //planosModalView.hide();
        $("#dataMapInd").hide();
    });
    $(".slc-filter").on('change', async function(){
        //getDataMapHeat();
        const filters = getMappedFilters();
        await getDataFija(filters);
        renderCoberturaOtros();
        renderCompetencia();
        planosModalView.setPlanos(store.data_fija);
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

    $("#checkbox_nuevos").on('change', function(){
        addMarkerForNewPolygons();
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
        planosModalView.setPlanos(store.data_fija);
        planosModalView.show();
    });

    // $('.dropdown-menu').on('click', function(e) { e.stopPropagation(); });
    $(".btn_export").on("click", function(){
        $(this).prop("disabled", true);
        let filters = [];
        filters.push(`anio.eq.${$(".slc-anio").val()}`);
        filters.push(`semana.eq.${$(".slc-semana").val()}`);
        filters = [...filters, ...getMappedFilters()];

        let body = {
            distribucion: $(".slc-distribucion").val(),
            kpis_id: $(".export .slc-kpi").val(),
            filter: filters
        };
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
                        .map(fname => `<td>${row[fname]}</td>`)
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
    const main_init = async () => {
        initMap();

        let distribuciones = $(`.slc-distribucion option`);
        let distribucion = distribuciones[distribuciones.length-1];
        $(".slc-distribucion").val($(distribucion).val());

        await addMapLayers();
        graphModalView = new GraphModal('#modal_graph');
        planosModalView = new PlanosModal('#planos_modal');

        const maxsemana=$(`.slc-anio option[value=${$(".slc-anio").val()}]`).attr('data-maxsemana');
        await changeAnio();
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


    $('#detalleTablaModal').on('show.bs.modal', function(e) {
        // Realiza una solicitud AJAX para obtener los datos de la tabla
        var boton = $(e.relatedTarget);
        var plano = boton.data('kpi');
        $.ajax({
          url: "{{ route('api.getDatallePlanoTabla') }}",
          type: 'GET',
          data: "plano="+plano,
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
          },
          error: function(xhr, status, error) {
            console.log(error);
          }
        });
      });
    
});
</script>

@endsection