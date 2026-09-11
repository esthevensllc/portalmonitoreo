@extends(backpack_view('blank'))

@section('after_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/factibilidad_fija/buscador_cobertura.css') }}?q={{ time() }}">
@endsection

@section('header')
@include('factibilidad_fija.cobertura_modal')
@endsection

@section('content')
<div
style="width: 100%; height: 87vh; border: 0; border-radius: 5px; position: relative; overflow: hidden;"
>
    <div id="map"></div>
    <div class="card" id="search-container">
        <div class="search-body">
            <h4 class="search-title">Zona de Cobertura</h4>
            <p class="search-description">Ingresa el departamento - provincia - distrito y edificio</p>
            <div class="pb-3 input-container">
                <input type="text" class="form-control" placeholder="DISTRITO - PROVINCIA - DEPARTAMENTO" id="input_ubigeo" autocomplete="off">
                <div class="suggestions-container" data-target="#input_ubigeo" id="suggestions_ubigeo"></div>
            </div>
            <div class="pb-3 input-container">
                <input type="text" class="form-control" placeholder="EDIFICIO" id="input_edificio" autocomplete="off">
                <div class="suggestions-container" data-target="#input_ubigeo" id="suggestions_edificio">
                </div>
            </div>
            <div class="buttons-container">
                <button type="button" class="btn btn-outline-light btn-sm mr-1 search-button" id="btn_search"><i class="la la-search"></i> Buscar</button>
                <button type="button" class="btn btn-outline-light btn-sm search-button" id="btn_limpiar"><i class="la la-eraser"></i> Limpiar</button>
            </div>
            <button class="btn btn-danger btn-block search-button" id="btn_confirmar" disabled="true">Confirmar</button>
        </div>
    </div>
</div>
@include("vendor.backpack.base.widgets.spinner_loader")
@include('includes.googleapis_js')
@endsection

@section('after_scripts')
<script>
const config = {
    base_url: "{{ asset('api-json') }}".replace("/api-json", ""),
    ubigeos: @json($ubigeos)
};
</script>
<script src="{{ asset('js/factibilidad_fija/buscador_cobertura.js') }}?q={{ time() }}"></script>
<script>
$(document).ready(function () {
    let service = new EdificiosViewModel();
});
</script>
@endsection