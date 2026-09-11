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
    /*.modal-dialog {
      max-width: 1000px;
    }*/
    .interaccion-number {
        cursor: pointer;
        color: #3f3fff;
    }
    .polygon-table-data tbody tr td, .polygon-sub-table-data tbody tr td {
        width: 100%;
        font-size: 14px !important;
        padding: 1px;
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
    .panel-data tbody tr td{
        height: 30px;
    }
    .panel-options-content, .panel-data-content{
        overflow-y: auto;
    }
    .panel-data-actions{
        padding: 10px 10px 10px 10px;
    }
    .panel-data-actions .primary-actions{
        display: flex;
        gap: 3px;
        justify-content: right;
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
    .layer-item .item-color {
        width: 10px;
        height: 12px;
        display: inline-block;
        border-radius: 2px;
        border: 1px #000 solid;
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

    .input-search{
        display: block;
        font-size: 1rem;
        padding: 5px;
        margin-bottom: 5px;
    }

    .input-container{
        position: relative;
    }
    .suggestions-container{
        width: 100%;
        border: 1px solid #dadce0;
        background: #ffffff;
        position: absolute;
        z-index: 2;
        border-radius: 0px 0px 10px 10px;

        box-shadow: 0 1px 6px 0 rgba(32, 33, 36, 0.28);
        -moz-box-shadow: 0 3px 6px 0 rgba(32, 33, 36, 0.28);
        -webkit-box-shadow: 0 3px 6px 0 rgba(32, 33, 36, 0.28);
        -khtml-box-shadow: 0 3px 6px 0 rgba(32, 33, 36, 0.28);
    }
    .suggestions-row{
        padding: 2px;
        border: 1px dashed #ebebeb;
        margin: 5px;
        color: black;
        font-size: 12px;
    }
    .suggestions-row:hover{
        cursor: pointer;
        background: rgb(237, 237, 237);
    }

    /* slide styles */
    .carousel-item img{
        max-height: 200px;
        width: auto;
        image-rendering: auto;
        object-fit: contain;
    }

    .custom-marker-content{
        align-items: center;
        justify-content: center;
        display: flex;
        flex-direction: column;
    }
    .custom-marker-content label{
        margin-bottom: 0px;
        font-size: 13px;
    }
</style>
@endsection

@section('header')
<!-- Modal -->
<div class="modal fade" id="detalleTablaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document" >
        <div class="modal-content">
            <form id="upload_images_form">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Subir imagenes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="file_imagenes">Imagenes</label>
                    <input type="file" class="d-block" id="file_imagenes" name="images[]" accept=".jpg,.png" multiple required>
                </div>
                <div class="form-group">
                    <input type="radio" name="tipo_carga_imagen" id="tipo_carga_imgen_add" value="add" checked>
                    <label for="tipo_carga_imgen_add">Agregar nuevas imagenes</label><br>
                    <input type="radio" name="tipo_carga_imagen" id="tipo_carga_imgen_update" value="update">
                    <label for="tipo_carga_imgen_update">Actualizar y reemplazar</label><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="pb-2 d-flex align-items-center" style="justify-content: flex-start;">
</div>
<div class="d-flex" style="gap: 10px;">

<div class="panel-options" style="display: none;">
    <div class="panel-options-content d-flex card h-100">
        <div class="card-body p-3">
            <label class="font-weight-bold">Tipos de Respaldo</label>
            <div class="radio_tipos_respaldo_main mb-2" style="flex-grow: 1;"></div>
            
            <a href="{{ asset('api/operacion-fija/export') }}" target="_blank" class="btn btn-outline-primary btn-sm btn-block" type="button">
                <i class='la la-file-excel'></i>
                Exportar
            </a>
        </div>
    </div>
</div>

<div class="panel-map">
    <div id="map" style=""></div>
</div>

<div class="panel-data card mb-0" style="display: none;" data-mode="read">
    <div class="card-header d-flex px-2">
        <div class="">
            <h5 class="mb-0 title">Detalle de Plano</h5>
        </div>
        <div style="flex-grow: 1;">
            <b class="lgnd-close">x</b>
        </div>
    </div>
    <div class="panel-data-content">
        <div id="dataMapInd" class="panel-data-body" style="display: block; overflow-y: auto; padding: 0px 10px 1px 10px; overflow-x: hidden;">dataMapInd</div>
    </div>
    <div class="panel-data-actions">
        <div class="primary-actions">
            <button class="btn btn-secondary btn-sm btn-edit">
                <span class="la la-pencil-alt"></span>
            </button>
            <button class="btn btn-secondary btn-sm btn-upload-img">
                <span class="la la-camera"></span>
            </button>
            <button class="btn btn-secondary btn-sm btn-delete">
                <span class="la la-trash"></span>
            </button>
        </div>
        <div class="confirm-actions">
            <button class="btn btn-primary btn-sm btn-confirm-edit">
                <span class="la la-save"></span> Guardar
            </button>
            <button class="btn btn-danger btn-sm btn-confirm-delete">
                <span class="la la-close"></span> Eliminar
            </button>
            <button class="btn btn-secondary btn-sm btn-confirm-cancel">
                <span class="la la-ban"></span> Cancelar
            </button>
        </div>
        <div class="confirm-create-actions">
            <button class="btn btn-success btn-sm btn-confirm-create">
                <span class="la la-save"></span> Guardar
            </button>
            <button class="btn btn-secondary btn-sm btn-cancel-create">
                <span class="la la-ban"></span> Cancelar
            </button>
        </div>
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

<div id="legend_layers" style="display: none;">
    <div class="pb-3 input-container">
        <input type="text" class="form-control input-search" id="input_search" autocomplete="off">
        <div class="suggestions-container" data-target="#input_search" id="suggestions_marker">
        </div>
    </div>
    <button class="btn btn-secondary btn-sm d-block btn-create mb-2"><i class="la la-map-marker h4 mb-0"></i></button>
    <button class="btn btn-secondary btn-sm d-block btn-create-polyline mb-2"><i class="la la-draw-polygon h4 mb-0"></i></button>
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

@include("vendor.backpack.base.widgets.spinner_loader")

@endsection

@section('after_scripts')
{{-- <script type="text/javascript" src="{{ asset('packages/highcharts/highcharts.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-data.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-exporting.js') }}"></script> --}}
{{-- <script type="text/javasc" src="{{ asset('packages/highcharts/highcharts-export-data.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script> --}}
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&libraries=visualization&v=weekly"></script> --}}
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&callback=initMap&v=weekly&libraries=marker"
defer
></script>
<script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
@include("includes.apexcharts_js")
{{-- <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script> --}}
<script>
const config = {
    base_url: "{{ asset('api-json') }}".replace("/api-json", ""),
    export_url: "{{ asset('api/operacion-fija/export') }}",
};
</script>
<script src="{{ asset('js/fija/operacion_fija.js') }}?time={{time()}}"></script>
@endsection