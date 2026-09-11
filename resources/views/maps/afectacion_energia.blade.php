@extends(backpack_view('blank'))

@section('after_styles')
@include("includes.spinner_loader_css")
<style>
#map {
    height: 90vh;
    width: 100%;
    /*position: relative;*/
    /*margin-left: -15px;*/
}
.panel-data{
    position: absolute;
    top: 80px;
    left: 20px;
}
.panel-options{
    width: 300px;
}
.panel-options .card-body{
    height: calc(100vh - 300px);
    overflow-y: auto;
}
</style>
@endsection

@section('content')

<div class="mb-2 head-main">
    <button class="btn btn-danger btn-sm d-inline-block btn-toogle-filters" data-toogle="false">
        <i class="la la-filter mb-0"></i>
    </button>
</div>

<div class="d-flex mb-2" style="gap: 10px;">

<div class="panel-options" style="display: none;">
    <div class="panel-options-content d-flex card h-100">
        <div class="card-body p-3">
            <form id="filter_form">
            <div style="flex-grow: 1;">
                <h6 class="font-weight-bold">Filtros</h6>
                <div class="form-group">
                    <label for="filter_region" class="font-sm">Region</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="region" id="filter_region">
                        <option value="">Region</option>
                        <?php foreach ($regiones as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_provincia" class="font-sm">Provincia</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="provincia" id="filter_provincia">
                        <option value="">Seleccione</option>
                        <?php foreach ($provincias as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_distrito" class="font-sm">Distrito</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="distrito" id="filter_distrito">
                        <option value="">Seleccione</option>
                        <?php foreach ($distritos as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_grupo_afectacion" class="font-sm">Grupo % de Afectacion</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="grupo_afectacion" id="filter_grupo_afectacion">
                        <option value="">Seleccione</option>
                        <?php foreach ($gruposAfectacion as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_grupo_sitios" class="font-sm">Grupo Sitios</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="grupo_sitios" id="filter_grupo_sitios">
                        <option value="">Seleccione</option>
                        <?php foreach ($gruposSitios as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_grupo_autonomia" class="font-sm">Grupo Autonomia</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="grupo_autonomia" id="filter_grupo_autonomia">
                        <option value="">Seleccione</option>
                        <?php foreach ($gruposAutonomia as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_sem_recurrentes" class="font-sm">Cant. Sem. Recurrentes</label>
                    <input type="number" class="form-control form-control-sm slc-filter mb-2" name="sem_recurrentes" id="filter_sem_recurrentes" value="4" min="0">
                </div>

                <div class="form-group">
                    <label for="filter_caido_ult_mes" class="font-sm">Caido Ult. Mes</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="caido_ult_mes" id="filter_caido_ult_mes">
                        <option value="">Seleccione</option>
                        <?php foreach ($caidosUltMes as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter_caido_ult_4meses" class="font-sm">Caido Ult. 4 Meses</label>
                    <select class="form-control form-control-sm slc-filter mb-2" name="caido_ult_4meses" id="filter_caido_ult_4meses">
                        <option value="">Seleccione</option>
                        <?php foreach ($caidosUlt4Mes as $row): ?>
                            <option value="{{ $row->label }}">{{ $row->label }}</option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
                   
            <button class="btn btn-sm btn-danger btn-block" type="submit">
                <i class="la la-eye"></i>
                Buscar
            </button>
            </form>
        </div>
    </div>
</div>

<div
class="panel-map"
style="width: 100%; height: 87vh; border: 0; border-radius: 5px; position: relative; overflow: hidden;"
>
    <div id="map"></div>
    <div class="panel-data card" style="display: none;">
        <div class="card-body">
            <div class="d-flex panel-data-header" style="justify-content: space-between;">
                <h5 class="font-weight-bold distrito-title"></h5>
                <i class="la h5 mb-0 la-close" style="cursor: pointer;"></i>
            </div>

            <div class="btn-group btn-group-data mb-2" role="group">
                <button type="button" class="btn btn-secondary btn-sm" data-target="panel-data-resumen">Resumen</button>
                <button type="button" class="btn btn-secondary btn-sm" data-target="panel-data-detalle">Detalle</button>
            </div>

            <div class="d-flex" style="gap: 10px; flex-direction: column; min-width: 500px;">
                <div class="panel-data-resumen">
                    <table class="distrito-detalle-1 mb-3" style="font-size: 10px;">
                        <tbody></tbody>
                    </table>
                    <table class="table table-sm table-bordered distrito-grupo-horas" style="max-width: 400px; font-size: 10px;">
                        <thead>
                            <tr class="bg-danger">
                                <th>Grupo Horas</th>
                                <th>G. importante</th>
                                <th>G. Regular</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="panel-data-detalle">
                    <table class="distrito-detalle-2 mb-3"  style="font-size: 10px;">
                        <tbody></tbody>
                    </table>

                    <table class="table table-sm table-bordered distrito-sitios" style="max-width: 700px; font-size: 10px;">
                        <thead>
                            <tr class="bg-danger">
                                <th style="min-width: 200px;">Sitio</th>
                                <th>Autonomia</th>
                                <th>Cant. Sem. Recurrentes</th>
                                <th>% de Afectacion</th>
                                <th>Fallas Energ. Acum (Hr)</th>
                                <th>Caida Serv. Acum (Hr)</th>
                                <th>Horas por cubrir (P90)</th>
                                <th>Horas por cubir (max)</th>
                                <th>Cant. Baterias Falt.</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@include("includes.spinner_loader")
@include('includes.googleapis_js')
@endsection

@section('after_scripts')
<script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<script>
const config = {
    base_url: "{{ asset('api-json') }}".replace("/api-json", "")
};
</script>
<script src="{{ asset('js/maps/map_utils.js') }}?q={{ time() }}"></script>
<script src="{{ asset('js/maps/afectacion_energia.js') }}?q={{ time() }}"></script>
<script>
$(document).ready(function () {
    config.provincias = @json($provincias);
    config.distritos = @json($distritos);
    let service = new AfectacionEnergiaController(config);
});
</script>
@endsection