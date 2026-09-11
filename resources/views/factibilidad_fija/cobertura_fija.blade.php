@extends(backpack_view('blank'))

@section('after_styles')
@include("includes.spinner_loader_css")
<link rel="stylesheet" type="text/css" href="{{ asset('css/factibilidad_fija/buscador_cobertura.css') }}?q={{ time() }}">
<style>
.layer-item {
    display: flex;
    align-items: center;
    padding-bottom: 5px;
}
.layer-item .form-check-label{
    font-size: 14px;
    padding-left: 5px;
}
</style>
@endsection

@section('content')
<div
style="width: 100%; height: 87vh; border: 0; border-radius: 5px; position: relative; overflow: hidden;"
>
    <div id="map"></div>
    <div id="layers_main" class="pt-3 pl-3">
        <button class="btn btn-light mb-2 border border-secondary" id="btn_layers"><i class="la la-layer-group h4 mb-0"></i></button>

        <div id="layers_options_main" class="card p-3" style="display: none;">
            <h6 class="mb-1">Tecnologias</h6>
            <div class="layer-item">
                <input class="check_tecnologia_hfc" type="checkbox" name="check_tecnologia_hfc" id="check_hfc" value="HFC">
                <label class="form-check-label" for="check_hfc">HFC</label>
            </div>
            {{-- <div class="layer-item">
                <input class="check_tecnologia" type="checkbox" name="check_tecnologia" id="check_ftth" value="">
                <label class="form-check-label" for="check_ftth">FTTH TODOS</label>
            </div> --}}
            <div class="layer-item">
                <input class="check_tecnologia_ftth" type="checkbox" name="check_tecnologia_ftth" id="check_ftth_overlap" value="OVERLAP">
                <label class="form-check-label" for="check_ftth_overlap">FTTH - OVERLAP</label>
            </div>
            <div class="layer-item">
                <input class="check_tecnologia_ftth" type="checkbox" name="check_tecnologia_ftth" id="check_ftth_overlap_edificio" value="OVERLAP-EDIFICIO">
                <label class="form-check-label" for="check_ftth_overlap_edificio">FTTH - OVERLAP EDIFICIO</label>
            </div>
            <div class="layer-item">
                <input class="check_tecnologia_ftth" type="checkbox" name="check_tecnologia_ftth" id="check_ftth_residencias_oficina" value="RESIDENCIAS-OFICINA">
                <label class="form-check-label" for="check_ftth_residencias_oficina">FTTH - RESIDENCIAS / OFICINA</label>
            </div>
        </div>
    </div>
</div>
@include("includes.spinner_loader")
@include('includes.googleapis_js')
@endsection

@section('after_scripts')
<script>
const config = {
    base_url: "{{ asset('api-json') }}".replace("/api-json", "")
};
</script>
<script src="{{ asset('js/factibilidad_fija/cobertura_fija.js') }}?q={{ time() }}"></script>
<script>
$(document).ready(function () {
    let service = new CoberturaFijaController(config);
});
</script>
@endsection