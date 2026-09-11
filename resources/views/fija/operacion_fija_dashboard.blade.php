@extends(backpack_view('blank'))

@section('content')
<a href="{{ asset('operacion-fija/'.$tracingID) }}" class="btn btn-outline-primary btn-sm mb-2">
    <i class="la la-external-link-alt"></i>
    Ver mapa
</a>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id="graph-region"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="font-weight-bold">Tabla de Tipos de Respaldo por región</h6>
                <table id="table-region" class="table table-sm">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div id="graph-month"></div>
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
<script>
$(function(){
    let service = new OperacionFijaDashboardViewModel();
}());
</script>
@endsection