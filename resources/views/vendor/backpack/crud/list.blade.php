@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
$crud->entity_name_plural => url($crud->route),
trans('backpack::crud.list') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
<div id="spinnerData" class="modal" data-backdrop="static" data-keyboard="false">
    <div class="cssload-loader-inner">
        <div class="cssload-cssload-loader-line-wrap-wrap">
            <div class="cssload-loader-line-wrap"></div>
        </div>
        <div class="cssload-cssload-loader-line-wrap-wrap">
            <div class="cssload-loader-line-wrap"></div>
        </div>
        <div class="cssload-cssload-loader-line-wrap-wrap">
            <div class="cssload-loader-line-wrap"></div>
        </div>
        <div class="cssload-cssload-loader-line-wrap-wrap">
            <div class="cssload-loader-line-wrap"></div>
        </div>
        <div class="cssload-cssload-loader-line-wrap-wrap">
            <div class="cssload-loader-line-wrap"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="DescModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titleGraph"></h3>

      </div>
      <div class="modal-body">
        <canvas id="myChart" width="900" height="350"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"> Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Modal edit sitio-->
<div class="modal fade" id="sitio_edit_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Editar Sitio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="sitio_edit_form">
      <div class="modal-body">
          @csrf
          <input type="hidden" id="sitioId" name="sitioId">
          <div class="form-group">
              <label for="grupo_motivo">Grupo Motivo</label>
              <select class="form-control" id="selGrupoMotivo" name="selGrupoMotivo">
              <option value=''>Seleccione un motivo</option>
              @php
                $motivos = DB::select('SELECT id,nombre FROM PRG_SITIOS_OBSERVADOS_MOTIVO WHERE estado=1');
                foreach ($motivos as $motivo) {
                    echo "<option value='".$motivo->id."'>".$motivo->nombre."</option>";
                }
              @endphp
              </select>
          </div>
          <div class="form-group">
              <label for="detalle_motivo">Detalle Motivo</label>
              <input type="text" class="form-control" id="txtDetalleMotivo" name="txtDetalleMotivo" aria-describedby="txtDetalleMotivo">
          </div>
          <div class="form-group">
              <label for="accion_solucion">Acción Solución</label>
              <input type="text" class="form-control" id="txtAccionSolucion" name="txtAccionSolucion" aria-describedby="txtAccionSolucion">
          </div>
          <div class="form-group">
            <label for="fecha_solucion">Fecha Solución</label>
            <div class="input-group date" data-provide="datepicker">            
              <input type="text" class="form-control" id="dateFechaSolucion" name="dateFechaSolucion">
              <div class="input-group-addon">
                  <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- ver registros de log -->
<!-- The Modal -->
<div class="modal" id="verRegistrosModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Hora</th>
          <th>Total</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
  </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!--Agregar periodo CV -->
<!-- The Modal -->
<div id="addPeriodoModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Periodo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addPeriodoForm">
      <div class="modal-body">
          @csrf
        <select class="form-control" id="selPeriodoCV" name="selPeriodoCV">
              @php
                $lastPeriodo = DB::table('prg_cv_periodos')->where('estado',1)->orderBy('id','desc')->get('periodo')->first();
                $periodoExplode = explode("_",$lastPeriodo->periodo);
                $año1 = intval($periodoExplode[1]) + 1;
                $año2 = intval($periodoExplode[1]) + 2;
                if($periodoExplode[2] == "01"){
                  echo "<option value='".$periodoExplode[1]."_02'>".$periodoExplode[1]."-02</option>";
                  echo "<option value='".$año1."_01'>".$año1."-01</option>";
                  echo "<option value='".$año1."_02'>".$año1."-02</option>";
                }else{
                  echo "<option value='".$año1."_01'>".$año1."-01</option>";
                  echo "<option value='".$año1."_02'>".$año1."-02</option>";
                  echo "<option value='".$año2."_01'>".$año2."-01</option>";
                }
              @endphp
        </select>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Agregar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal edit cv-->
<div class="modal fade" id="cv_edit_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Editar CV</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="cv_edit_form">
        <div class="modal-body">
            @csrf
            <input type="hidden" id="cvId" name="cvId">
            @php
              $periodos = DB::table('prg_cv_periodos')->where('estado',1)->orderBy('id','desc')->get('periodo');
            @endphp
            <div class="row">
            @foreach($periodos as $periodo)
              <div class="col">
                  <label for="grupo_periodo">{{$periodo->periodo}}</label>
                  <input type="text" class="form-control" id="txtPeriodo" name="txt{{$periodo->periodo}}" aria-describedby="txt{{$periodo->periodo}}">
              </div>
            @endforeach
            </div>
            <div class="form-group">
              <label for="fecha_solucion">Fecha Solución</label>
              <div class="input-group date" data-provide="datepicker">            
                <input type="text" class="form-control" id="dateFechaSolucion" name="dateFechaSolucion">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal" id="dataModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary btn-export-detalle">Exportar</button> -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- Default box -->
<div class="row widget-content sectVw sectTable">

  <!-- THE ACTUAL CONTENT -->
  <div class="{{ $crud->getListContentClass() }}">
    @if ( $crud->buttons()->where('stack', 'top')->count() || $crud->exportButtons())
    <div class="row mb-0">
      <div class="col-sm-6">


        <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">
          <div id="datatable_button_stack" class="float-left text-left hidden-xs"></div>
          @include('crud::inc.button_stack', ['stack' => 'top'])

        </div>

      </div>
      <div class="col-sm-6">
        <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
      </div>
    </div>
    @else
    <style>
    .dataTables_filter{
      display: none;
    }
    #datatable_info_stack{
      display: none!important;
    }
    </style>
    @endif

    {{-- Backpack List Filters --}}
    @if ($crud->filtersEnabled())
    @include('crud::inc.filters_navbar')
    @endif

    <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2" cellspacing="0">
      <thead>
        <tr>
          {{-- Table columns --}}
          @foreach ($crud->columns() as $column)
          <th data-orderable="{{ var_export($column['orderable'], true) }}" data-priority="{{ $column['priority'] }}" {{--

                        data-visible-in-table => if developer forced field in table with 'visibleInTable => true'
                        data-visible => regular visibility of the field
                        data-can-be-visible-in-table => prevents the column to be loaded into the table (export-only)
                        data-visible-in-modal => if column apears on responsive modal
                        data-visible-in-export => if this field is exportable
                        data-force-export => force export even if field are hidden

                    --}} {{-- If it is an export field only, we are done. --}} @if(isset($column['exportOnlyField']) && $column['exportOnlyField']===true) data-visible="false" data-visible-in-table="false" data-can-be-visible-in-table="false" data-visible-in-modal="false" data-visible-in-export="true" data-force-export="true" @else data-visible-in-table="{{var_export($column['visibleInTable'] ?? false)}}" data-visible="{{var_export($column['visibleInTable'] ?? true)}}" data-can-be-visible-in-table="true" data-visible-in-modal="{{var_export($column['visibleInModal'] ?? true)}}" @if(isset($column['visibleInExport'])) @if($column['visibleInExport']===false) data-visible-in-export="false" data-force-export="false" @else data-visible-in-export="true" data-force-export="true" @endif @else data-visible-in-export="true" data-force-export="false" @endif @endif>
            {!! $column['label'] !!}
          </th>
          @endforeach

          @if ( $crud->buttons()->where('stack', 'line')->count() )
          <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}" data-visible-in-export="false">{{ trans('backpack::crud.actions') }}</th>
          @endif
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          {{-- Table columns --}}
          @foreach ($crud->columns() as $column)
          <th>{!! $column['label'] !!}</th>
          @endforeach

          @if ( $crud->buttons()->where('stack', 'line')->count() )
          <th>{{ trans('backpack::crud.actions') }}</th>
          @endif
        </tr>
      </tfoot>
    </table>

    @if ( $crud->buttons()->where('stack', 'bottom')->count() )
    <div id="bottom_buttons" class="d-print-none text-center text-sm-left">
      @include('crud::inc.button_stack', ['stack' => 'bottom'])
    </div>
    @endif

    <h2 style="display:none!important">      
      <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
    </h2>
  </div>
</div>

@endsection

@section('after_styles')
<!-- DATA TABLES -->
<link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.standalone.min.css"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.standalone.min.css')}}">
<!-- ColReorder -->
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.dataTables.min.css"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/colReorder.dataTables.min.css')}}">

{{-- <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap4-toggle.min.css')}}">

{{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css"> --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/chosen.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('packages/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('packages/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">

<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css').'?v='.config('backpack.base.cachebusting_string') }}">
<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css').'?v='.config('backpack.base.cachebusting_string') }}">

<style>
    .modal-dialog {
        max-width: 1000px;
    }
    .modal-content {
        width: auto;
        margin: 0 auto;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>

<!-- CRUD LIST CONTENT - crud_list_styles stack -->
@stack('crud_list_styles')
@endsection

@section('after_scripts')
<!-- <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> -->
<script type="text/javascript" src="{{asset('js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('js/chosen.jquery.js')}}"></script>
<script src="{{asset('libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js')}}"></script>
<!-- <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script> -->
<script type="text/javascript" src="{{asset('js/bootstrap4-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/moment/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/tags/tags.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/highcharts/highcharts.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/highcharts/modules/data.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/highcharts/modules/exporting.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/highcharts/modules/export-data.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/highcharts/modules/offline-exporting.js')}}"></script>
@include('crud::inc.datatables_logic')
<!-- ColReorder -->
<!-- <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script> -->
<script type="text/javascript" src="{{asset('js/dataTables.colReorder.min.js')}}"></script>
<script>
const BASE_URL = "{{ asset('') }}";
</script>
<script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
<script src="{{ asset('packages/backpack/crud/js/form.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
<script src="{{ asset('packages/backpack/crud/js/list.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
<script src="{{ asset('packages/backpack/crud/js/utils.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>

<!-- CRUD LIST CONTENT - crud_list_scripts stack -->
@stack('crud_list_scripts')
@endsection