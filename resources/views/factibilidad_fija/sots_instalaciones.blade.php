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
          {{-- @include('crud::inc.button_stack', ['stack' => 'top']) --}}

        </div>

      </div>
      <div class="col-sm-6">
        <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none" style="display: none !important;"></div>
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
    #datatable_search_stack{
      display: none !important;
    }
    .dash-item{
        display: flex;
        max-width: 200px;
        justify-content: space-between;
    }
    </style>
    @endif

    {{-- Backpack List Filters --}}
    {{-- @if ($crud->filtersEnabled()) --}}
    @if (false)
    @include('crud::inc.filters_navbar')
    @endif

    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <button type="button" class="btn btn-secondary btn-sm btn-filters" data-target=".options-content">
                <span class="la la-filter"></span>
            </button>
            @include('crud::inc.button_stack', ['stack' => 'top'])
            <div class="card mb-2 mt-2 p-2">
                <div class="dash-item field-sots">
                    <p class="dash-item-label">SOTS</p><p class="dash-item-value">-</p>
                </div>
                <div class="dash-item field-planos_fat">
                    <p class="dash-item-label">Planos con Fat</p><p class="dash-item-value">-</p>
                </div>
                <div class="dash-item field-planos">
                    <p class="dash-item-label">Solo Planos</p><p class="dash-item-value">-</p>
                </div>
                <div class="dash-item field-sinplanos">
                    <p class="dash-item-label">Sin Planos</p><p class="dash-item-value">-</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            
        </div>
    </div>

    <div class="main-table">
        <div class="options-content" style="display: none;">
            <div class="card p-2 h-100">
                <h5 class="">Filtros y Opciones</h5>
                <p class="mb-1 text-danger text-center">ÚLTIMA SEMANA</p>
                {{-- <p class="mb-1">{{ json_encode($crud->get("custom.fechas")) }}</p> --}}
                <div class="list-group mb-2 text-center" id="date_filter" data-field="fecha_generacion_sot" data-operator="date_in" data-interrup="true">
                    <button class="list-group-item list-group-item-action p-1" data-value='' data-range="true" data-operator="date_between">
                        Rango Personalizado
                    </button>
                </div>
                <div class="list-group mb-2 date-range-filter"  style="display: none;">
                    <input type="date" name="fecha_ini" class="form-control form-control-sm">
                    <input type="date" name="fecha_fin" class="form-control form-control-sm">
                </div>
                <p class="mb-1 text-danger text-center">ESTADO SOTS</p>
                <div class="list-group mb-2 text-center" id="estado_filter2" data-field="estado" data-operator="like">
                </div>

                <div class="list-group mb-2 text-center" id="estado_filter" data-field="estado" data-operator="like">
                    <button class="list-group-item p-1" data-value=''>Todos</button>
                    @foreach ($crud->get("custom.estados") as $row)
                        <button
                            class="list-group-item list-group-item-action p-1 {{$row->value === 'En Ejecución' ? 'active':''}}"
                            data-value="{{$row->value}}"
                            @if($row->value === "Sin Estado") data-operator="is_null" @endif>{{$row->label}}</button>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- <div class="table-responsive"> --}}
            {{-- <div class="table-responsive--"> --}}
                @include("backpack::base.layouts.list_table")
            {{-- </div> --}}
        {{-- </div> --}}
    </div>


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

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{asset('css/chosen.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('packages/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('packages/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">

<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css').'?v='.config('backpack.base.cachebusting_string') }}">
<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/form.css').'?v='.config('backpack.base.cachebusting_string') }}">
<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/list.css').'?v='.config('backpack.base.cachebusting_string') }}">



<!-- CRUD LIST CONTENT - crud_list_styles stack -->
@stack('crud_list_styles')
<style>
    .dash-item{
        padding: 0px;
        display: flex;
        justify-content: space-between;
    }
    .dash-item-label{
        font-size: 1rem;
        font-weight: bold;
        margin: 0px;
    }
    .dash-item-value{
        margin: 0px;
        font-size: 1rem;
    }

    .main-table{
        display: flex;
        gap: 10px;
    }
    .options-content{
        min-width: 200px;
    }
    #crudTable_wrapper{
        overflow: hidden;
    }
    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background-color: var(--danger);
        border-color: var(--danger);
    }
</style>
@endsection

@section('after_scripts')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{asset('js/chosen.jquery.js')}}"></script>
<script src="{{asset('libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
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
<script>
const BASE_URL = "{{ asset('') }}";
</script>
<script src="{{ asset('packages/backpack/crud/js/crud.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
<script src="{{ asset('packages/backpack/crud/js/form.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
<script src="{{ asset('packages/backpack/crud/js/list.js').'?v='.config('backpack.base.cachebusting_string') }}"></script>
<script src="{{ asset('libs/URI.js/1.18.2/URI.min.js') }}"></script>

<!-- CRUD LIST CONTENT - crud_list_scripts stack -->
@stack('crud_list_scripts')
<script>
/*
class FilterList extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });

        // Crea los elementos internos
        const wrapper = document.createElement('div');

        // Anexa los elementos al shadow DOM
        this.shadowRoot.append(wrapper);
    }

    connectedCallback() {
        this.render();
    }

    // Renderiza la lista de elementos
    render() {
        const wrapper = this.shadowRoot.querySelector('div');
        //wrapper.innerHTML = '';
        const items = JSON.parse(this.getAttribute('items') || '[]');

        wrapper.innerHTML = items.map(row => {
            return `<button
            class="list-group-item list-group-item-action p-1 ${row.value === 'En Ejecución' ? 'active':''}"
            data-value="${row.value}"
            ${row.value === "Sin Estado" ? 'data-operator="is_null"': ''}
            >${row.label}</button>`;
        }).join("")

        wrapper.children.forEach(itemElement => {
            itemElement.addEventListener('click', () => this.handleClick({
                value: itemElement.attributes["data-value"].value,
                label: itemElement.textContent
            }));
        });
    }

    handleClick(item, index) {
        const event = new CustomEvent('item-click', {
        detail: { item, index },
        bubbles: true,
        composed: true
        });
        this.dispatchEvent(event);
    }

    static get observedAttributes() {
        return ['items'];
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (name === 'items') {
            this.render();
        }
    }
}

customElements.define('filter-list', FilterList);*/

class FilterList{
    constructor(config){
        this.selector = config.selector;
        this.list = config.list??[];
        this.value;
        this.render();
    }

    render(){
        let self = this;
        this.value = this.list[0].value;
        let html = this.list.map((row, index) => {
            return `<button
            class="list-group-item list-group-item-action p-1 ${index === 0 ? 'active':''}"
            data-value="${row.value}"
            ${row.operator ? 'data-operator="'+row.operator+'"': ''}
            >${row.label}</button>`;
        }).join("")
        $(this.selector).html(html);

        let buttons = document.querySelector(this.selector).children;

        buttons.forEach(itemElement => {
            itemElement.addEventListener('click', () => {
                buttons.forEach(el => el.classList.remove("active"));
                itemElement.classList.add("active");
                self.handleClick({
                    value: itemElement.attributes["data-value"].value,
                    label: itemElement.textContent,
                    operator: $(itemElement).attr("data-operator") ?? $(self.selector).attr("data-operator")
                });
            });
        });
    }

    setList(list){
        this.list = list;
        this.render();
    }

    setValue(value){
        this.value = value;
        let buttons = document.querySelector(this.selector).children;
        buttons.forEach(el => el.classList.remove("active"));
        buttons.forEach(itemElement => {
            if(itemElement.attributes["data-value"].value === value){
                itemElement.classList.add("active");
            }
        });
    }

    handleClick(item) {
        const event = new CustomEvent('item-click', {
            detail: item,
            bubbles: true,
            composed: true
        });
        document.querySelector(this.selector).dispatchEvent(event);
    }

    addEventListener(event, handler){
        document.querySelector(this.selector).addEventListener(event, handler);
    }
}

let estados = [
    {value: "", label: "Todos"}
]
let fechas = @json($crud->get("custom.fechas"));
fechas.push({value: "range-filter", label: "Rango Personalizado"});
let datesFilter = new FilterList({selector: "#date_filter", list: fechas});
let statusFilters = new FilterList({selector: "#estado_filter", list: estados});

datesFilter.addEventListener("item-click", async function(e){
    console.log(e.detail);
    if(e.detail.value !== "range-filter"){
        let filter = e.detail;
        $(".date-range-filter").hide();

        await renderEstados([{field: "fecha_generacion_sot", operator: filter.operator, value: filter.value}]);
        let estado = statusFilters.value;
        let estadoOperator = "like";
        let current_url = addOrUpdateFilters([
            {field: "fecha_generacion_sot", operator: filter.operator, value: filter.value},
            {field: "estado", operator: estadoOperator, value: estado}
        ]);
        renderSummary(current_url);
    }else{
        $(".date-range-filter").show();
        $(".date-range-filter *[name=fecha_ini]").trigger("change");
    }
});

statusFilters.addEventListener("item-click", function(e){
    console.log(e.detail);
    let filter = e.detail;
    let current_url = addOrUpdateFilters([{field: "estado", operator: filter.operator, value: filter.value}]);
    renderSummary(current_url);
});

/*$(".list-group button").on("click", function(e){
    $(this).parent().find("button").removeClass("active");
    $(e.target).addClass("active");
    if($(e.target).attr("data-range") === "true"){
        $(".date-range-filter").show();
    }else{
        $(".date-range-filter").hide();
        // crud.table.ajax.reload();
    }
});*/

$(".btn-filters").on("click", function(e){
    let isActive = !($(this).attr("data-isactive") === "true");
    let target = $(this).attr("data-target");
    $(this).attr("data-isactive", isActive);
    if(isActive){
        $(target).show();
    }else{
        $(target).hide();
    }
});

$(".date-range-filter input").on("change", async function(e){
    let strFecha1 = $(".date-range-filter *[name=fecha_ini]").val();
    let strFecha2 = $(".date-range-filter *[name=fecha_fin]").val();
    if(strFecha1 && strFecha2){
        // addOrUpdateFilters([{field: "fecha_generacion_sot", operator: "date_between", value: `${strFecha1},${strFecha2}`}]);
        $("#date_filter button[data-range=true]").attr("data-value", `${strFecha1},${strFecha2}`);

        await renderEstados([{field: "fecha_generacion_sot", operator: "date_between", value: `${strFecha1},${strFecha2}`}]);
        let estado = statusFilters.value;
        let current_url = addOrUpdateFilters([
            {field: "fecha_generacion_sot", operator: "date_between", value: `${strFecha1},${strFecha2}`},
            {field: "estado", operator: "like", value: estado}
        ]);
        renderSummary(current_url);
    }
});

function addOrUpdateUriParameter(uri, parameter, value) {
    var new_url = normalizeAmpersand(uri);

    new_url = URI(new_url).normalizeQuery();

    // this param is only needed in datatables persistent url redirector
    // not when applying filters so we remove it.
    if (new_url.hasQuery('persistent-table')) {
        new_url.removeQuery('persistent-table');
    }

    if (new_url.hasQuery(parameter)) {
        new_url.removeQuery(parameter);
    }

    if (value !== '' && value != null) {
        new_url = new_url.addQuery(parameter, value);
    }

    //$('#remove_filters_button').removeClass('invisible');

    return new_url.toString();

}

function normalizeAmpersand(string) {
return string.replace(/&amp;/g, "&").replace(/amp%3B/g, "");
}

function addOrUpdateFilters(filters){
    // let filters = [];
    let ajax_table = $('#crudTable').DataTable();
    let current_url = ajax_table.ajax.url();
    // let new_url = "";
    filters.forEach(filter => {
        //var operator = $(this).parent().attr("data-operator");
        var c_value = filter.value;
        
        //if (operator || c_value) {
        if ((c_value !== '' && c_value !== undefined)) {
            var range = {
                'operator': filter.operator,
                'value': c_value
            };
            var value = JSON.stringify(range);
        } else {
            //this change to empty string,because addOrUpdateUriParameter method just judgment string
            var value = '';
        }

        /*if(value === '' && $(this).parent().attr("data-interrup") === "true"){
            return;
        }*/

        console.log({current_url, field: filter.field, value});

        // behaviour for ajax table
        current_url = addOrUpdateUriParameter(current_url, filter.field, value);
        //renderSummary(current_url);

        // replace the datatables ajax url with current_url and reload it
        current_url = normalizeAmpersand(current_url.toString());
    });

    ajax_table.ajax.url(current_url).load((e) => {
        if ($('.sectionGroupGraph').css('display') == 'none'){
            console.log(0);
            optionGraph(0);
        }else{
            console.log(1);
            optionGraph(1);
        }
    });

    // add filter to URL
    crud.updateUrl(current_url);
    return current_url;
}

async function renderEstados(filters){
    let mappedFilters = {};
    let strFilters = filters.map(row => {
        return `${row.field}=`+JSON.stringify(row);
    }).join("");
    await fetch(`{{ asset('sots/30/estados') }}?${strFilters??''}`)
    .then(response => response.json())
    .then(response => {
        let hasEnEjecucion = response.some(row => row.value === 'En Ejecución');
        let todos = [{value: "", label: "Todos"}];
        statusFilters.setList([...todos, ...response.map(row => ({...row, label: `${row.label} (${row.counter})`}))]);
        if(hasEnEjecucion){
            statusFilters.setValue('En Ejecución');
        }
    })
    .catch(error => {
    });
}
/*
$('#date_filter button').on('click', async function(e) {
    //var operator = $("li[filter-key=idProceso] input:radio[name=id_proceso_operator]:checked").val();
    var field = $(this).parent().attr("data-field");
    var operator = $(this).attr("data-operator") ?? $(this).parent().attr("data-operator");
    var value = $(this).attr("data-value");

    if(value === '' && $(this).parent().attr("data-interrup") === "true"){
        return;
    }

    await renderEstados();
    let estado = statusFilters.value;
    let estadoOperator = "equals";
    addOrUpdateFilters([
        {field, operator, value},
        {field: "estado", operator: estadoOperator, value: estado}
    ]);
});*/

/*$('.list-group button').on('click', function(e) {
    //var operator = $("li[filter-key=idProceso] input:radio[name=id_proceso_operator]:checked").val();
    var field = $(this).parent().attr("data-field");
    var operator = $(this).attr("data-operator") ?? $(this).parent().attr("data-operator");
    var value = $(this).attr("data-value");

    if(value === '' && $(this).parent().attr("data-interrup") === "true"){
        return;
    }

    addOrUpdateFilters([field, operator, value]);
});*/

async function renderSummary(uri){
    $('.list-group button').removeClass("disabled").addClass("disabled");
    let parts = uri.split("?");
    fetch(`{{ asset('sots/30/summary') }}?${parts[1]??''}`)
    .then(response => response.json())
    .then(response => {
        $(".field-sots .dash-item-value").text(response.sots);
        $(".field-planos_fat .dash-item-value").text(response.planos_fat);
        $(".field-planos .dash-item-value").text(response.planos);
        $(".field-sinplanos .dash-item-value").text(response.sin_planos);
        $('.list-group button').removeClass("disabled");
    })
    .catch(error => {
        $(".field-sots .dash-item-value").text("-");
        $(".field-planos_fat .dash-item-value").text("-");
        $(".field-planos .dash-item-value").text("-");
        $(".field-sinplanos .dash-item-value").text("-");
        $('.list-group button').removeClass("disabled");
    });
}

async function onInit(){
    let ajax_table = $('#crudTable').DataTable();
    //var current_url = ajax_table.ajax.url();
    let current_url = window.location.href.replace(window.location.search, "")+"/search";
    
    let defaultDate = $($("#date_filter button")[0]).attr("data-value");

    await renderEstados([{field: "fecha_generacion_sot", operator: 'date_in', value: datesFilter.value}]);

    current_url = addOrUpdateUriParameter(current_url, "fecha_generacion_sot", JSON.stringify({'operator': 'date_in', 'value': datesFilter.value}));
    current_url = addOrUpdateUriParameter(current_url, "estado", JSON.stringify({'operator': 'like', 'value': statusFilters.value}));
    ajax_table.ajax.url(current_url).load();
    renderSummary(current_url);
}

let timer;
timer = setInterval(function(){
    if(crud.table){
        clearInterval(timer);
        onInit();
    }
}, 100);
</script>
@endsection