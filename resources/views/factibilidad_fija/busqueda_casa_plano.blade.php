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
            <p class="search-description">Ingresa el plano</p>
            <div class="pb-3 input-container">
                <input type="text" class="form-control" placeholder="PLANO(LML)" id="input_plano" autocomplete="off">
            </div>
            <div class="buttons-container">
                <button type="button" class="btn btn-outline-light btn-sm mr-1 search-button" id="btn_search"><i class="la la-search"></i> Buscar</button>
                <button type="button" class="btn btn-outline-light btn-sm search-button" id="btn_limpiar"><i class="la la-eraser"></i> Limpiar</button>
            </div>
            <div class="pb-3 input-container">
                <select class="form-control form-control-sm" id="select_planos">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
    </div>
</div>
@include("vendor.backpack.base.widgets.spinner_loader")
@include('includes.googleapis_js')
@endsection

@section('after_scripts')
<script>
const config = {
    base_url: "{{ asset('api-json') }}".replace("/api-json", "")
};
</script>
<script src="{{ asset('js/factibilidad_fija/buscador_cobertura.js') }}?q={{ time() }}"></script>
<script>
$(document).ready(function () {
    let service = new PLanosViewModel();
});
</script>
@endsection