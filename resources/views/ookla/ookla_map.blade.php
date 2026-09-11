@extends(backpack_view('blank'))

@section('after_styles')
<style>
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
    .hexbin-hexagon {
        stroke: #000;
        stroke-width: .5px;
    }
</style>
@include('includes.leaflet_css')
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
    <div class="form-group" style="width: 120px;">
        <label>Mes</label>
        <select class="form-control form-control-sm" id="kpi_months">
            @foreach ($months as $row)
                <option value="{{ $row['mes'] }}">{{ $row['mes'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group" style="width: 200px;">
        <label>Operador</label>
        <select class="form-control form-control-sm" id="kpi_operator">
            @foreach ($operators as $row)
                <option value="{{ $row['operator'] }}">{{ $row['operator'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group" style="width: 200px;">
        <label>Capa</label>
        <select class="form-control form-control-sm" id="kpi_layers">
            @foreach ($kpiList as $row)
                <option value="{{ $row['id'] }}">{{ $row['label'] }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="d-flex mb-3" style="gap: 10px;">
    <div class="panel-map">
        <div id="map" style=""></div>
    </div>
</div>

<div class="row">
    @foreach ($kpiList as $row)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div id="graph-{{ $row['id'] }}"></div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div id="layers_options_main" class="card p-3" style="display: none;">
    <div class="layer-item">
        <input class="check_layer_planos" type="checkbox" name="check_layer_planos" id="check_layer_planos">
        <label class="form-check-label" for="check_layer_planos">Planos</label>
    </div>
</div>

@include("vendor.backpack.base.widgets.spinner_loader")

@endsection

@section('after_scripts')
{{-- <script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script> --}}
@include("includes.apexcharts_js")
@include('includes.leaflet_js')
@include("includes.apexcharts_js")
<script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>
<script src="https://d3js.org/d3-hexbin.v0.2.min.js"></script>
<script src="{{ asset('libs/leaflet-d3/leaflet-d3.js') }}"></script>
<script src="{{ asset('js/ookla/map.js?v='.time()) }}"></script>
<script>
const mapController = new MapController({
    base_url: "{{ url('') }}",
    kpiList: @json($kpiList),
    openstreetmap_url: 'https://tile.openstreetmap.org'
});
</script>
@endsection