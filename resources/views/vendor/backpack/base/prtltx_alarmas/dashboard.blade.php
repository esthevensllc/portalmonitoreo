@extends(backpack_view('blank'))

@section('content')
@include(backpack_view('widgets.spinner_loader'))
<h4 style="text-align:center">SLA DISPONIBILIDAD PRONATEL TX</h4>
<form id="filter-form-mes">
    <div class="row mb-3">
        <div class="col-lg-3">
            <select class="form-control" name="mes_ini">
                @foreach($diasMes as $mes)
                    <option value="{{$mes->mes}}">{{$mes->mes}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <select class="form-control" name="mes_fin">
                @foreach($diasMes as $mes)
                    <option value="{{$mes->mes}}">{{$mes->mes}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <button class="btn btn-secondary tbn-sm">Buscar</button>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                TABLA DE DISPONIBLIDAD
            </div>
            <div class="card-body">
                <table class="table table-sm" id="tbl-disponibilidad">
                    <thead class="table-danger">
                        <tr>
                            <th>Mes</th>
                            <th>Suma de % disponiblidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                Grafica de disponiblidad
            </div>
            <div class="card-body">
                <canvas id="grafica-disponiblidad" style="display: block; width: 560px; height: 300px;" width="560px" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">Disponibilidad por tipo de nodo</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="tbl-disp-mes-tiponodo">
                        <thead class="table-danger">
                            <tr>
                                <th>Tipo Nodo</th>
                                <th>Suma de % disponiblidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">Detalle disponibilidad por tipo de nodo</div>
            <div class="card-body">
                <div class="d-inline-block mr-3">
                    <label for="select-tiponodo" class="mr-2">Tipo nodo</label>
                    <select id="select-tiponodo">
                        <option value="__all__">Todos</option>
                        @foreach($dispGroupedByMesAndTipoNodo as $tipo_nodo => $value)
                            <option value="{{$tipo_nodo}}">{{$tipo_nodo}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-inline-block">
                    <label for="">Ne name</label>
                    <input type="text" id="filter-ne_name">
                </div>
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm" id="tbl-det-disp-mes-tiponodo">
                        <thead class="table-danger">
                            <tr><th>Ne name</th></tr>
                        </thead>
                        <tbody>
                            <tr class="table-secondary"><td>Total general</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('after_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
<script type="text/javascript">

class FilterItem {
    constructor(el, name){
        this._el = el;
        this._name = name;
        this._filterForm = null;
        this.onChange.bind(this);
        this.value.bind(this);
        this._el.addEventListener('change', this.onChange.bind(this));
    }

    setFilterForm(filterForm){
        this._filterForm = filterForm;
    }

    onChange(){
        //console.log('filtering');
        //console.log(this);
    }

    value(){
        return this._el.value;
    }
    name(){
        return this._name;
    }
}

class FilterForm{
    constructor(el, filters){
        filters.forEach(element => {
            element.setFilterForm(this);
        });
        this._filters = filters;
        this.getValues.bind(this);
    }

    getValues(){
        const values = [];
        this._filters.forEach(filter => {
            values[filter.name()] = {
                name: filter.name(),
                value: filter.value()
            };
        });
        return values;
    }
}


jQuery(document).ready(function($) {
    let dispGroupedByMes = @json($dispGroupedByMes);
    let dispGroupedByMesAndTipoNodo = @json($dispGroupedByMesAndTipoNodo);
    let dispGroupedByMesAndTipoNodoAndNeName = @json($dispGroupedByMesAndTipoNodoAndNeName);
    let dispEsperadaByTipoNodo = @json($dispEsperadaByTipoNodo);

    var ctx = document.getElementById('grafica-disponiblidad');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dispGroupedByMes.map(row => row.anio_mes),
            datasets: [{
                label: 'Suma de % disponiblidad',
                data: dispGroupedByMes.map(row => row.disponibilidad),
                backgroundColor: 'rgba(77, 189, 116, 0.4)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    const updateChart = () => {
        myChart.data.labels = dispGroupedByMes.map(row => row.anio_mes);
        myChart.data.datasets[0].data = dispGroupedByMes.map(row => row.disponibilidad);
        myChart.update();
    }

    const renderDispGroupedByMes = () => {
        //dispGroupedByMes
        const html = dispGroupedByMes.map(row => `
            <tr>
                <td>${row.anio_mes}</td>
                <td>${row.disponibilidad}%</td>
            </tr>
        `).reduce((p, c) => p+c, '');
        $('#tbl-disponibilidad tbody').html(html);
    }

    const renderDispGroupedByMesAndTipoNodo = () => {
        //dispGroupedByMesAndTipoNodo - dispGroupedByMes - dispEsperadaByTipoNodo
        let theads = dispGroupedByMes.map(row => `<th>${row.anio_mes}</th>`).reduce((p, c) => p+c, '');
        $('#tbl-disp-mes-tiponodo thead tr').html(`<th>Tipo Nodo</th> ${theads} <th>Meta</th> `);

        let html_tbody = '';
        for (const tipo_nodo in dispGroupedByMesAndTipoNodo) {
            const row = dispGroupedByMesAndTipoNodo[tipo_nodo];
            const dispEsperada = dispEsperadaByTipoNodo[tipo_nodo] ? dispEsperadaByTipoNodo[tipo_nodo].disponibilidad : undefined;

            const dispByMes = dispGroupedByMes.map(row2 => {
                const dispFinded = row[row2.anio_mes];
                if(dispEsperada){
                    const isDispEsperada = dispFinded >= dispEsperada;
                    return `<td class="${isDispEsperada?'text-success':'text-danger'}"> ${dispFinded ? dispFinded+'%' :''}</td>`;
                }
                return `<td>${dispFinded ? dispFinded+'%' :''}</td>`;
            }).reduce((p, c) => p+c, '');
                
            html_tbody += `<tr>
                <td>${tipo_nodo}</td>
                ${dispByMes}
                <td class="font-weight-bold">${dispEsperada? dispEsperada+'%' :''}</td>
            </tr>`;
        }

        let html_disp_totales = dispGroupedByMes.map(row => `<td>${row.disponibilidad}%</td>`).reduce((p, c) => p+c, '');
        html_disp_totales = `<tr class="table-secondary font-weight-bold"><td>Total General</td>${html_disp_totales}<td></td><tr>`;

        $('#tbl-disp-mes-tiponodo tbody').html(html_tbody+html_disp_totales);
    }

    const renderDetalleDispByTipoNodo = (values) => {
        //dispGroupedByMesAndTipoNodoAndNeName - dispGroupedByMesAndTipoNodo - dispGroupedByMes 
        const tiponodo = values.tipo_nodo.value;
        let detalleDispTipoNodoFinded = dispGroupedByMesAndTipoNodoAndNeName[tiponodo];
        const totales = dispGroupedByMesAndTipoNodo[tiponodo];
        if(tiponodo === '__all__'){
            detalleDispTipoNodoFinded = [];
            for (const tipo_nodo in dispGroupedByMesAndTipoNodoAndNeName) {
                detalleDispTipoNodoFinded = [...detalleDispTipoNodoFinded, ...dispGroupedByMesAndTipoNodoAndNeName[tipo_nodo]];
            }
        }
        if(detalleDispTipoNodoFinded){
            detalleDispTipoNodoFinded = detalleDispTipoNodoFinded.filter(row => {
                return row.ne_name.toLowerCase().includes(values.ne_name.value.toLowerCase());
            })
        }

        let theads = dispGroupedByMes.map(row => `<th>${row.anio_mes}</th>`).reduce((p, c) => p+c, '');
        $('#tbl-det-disp-mes-tiponodo thead tr').html(`<th>Ne name</th> ${theads} <th>Total general</th>`);

        let html_tbody = '';
        if(detalleDispTipoNodoFinded){

            html_tbody = detalleDispTipoNodoFinded.map(row => {
                const hymldispByMes = dispGroupedByMes.map(row2 => `<td> ${row[row2.anio_mes]}% </td>`).reduce((p, c) => p+c, '');
                return `<tr><td>${row.ne_name}</td> ${hymldispByMes} <td>${row.promedio_disp}%</td> </tr>`;
            });
        }

        let html_disp_totales = '';
        if(totales){
            html_disp_totales = dispGroupedByMes.map(row => `<td>${totales[row.anio_mes]}%</td>`).reduce((p, c) => p+c, '');
        }else if(tiponodo === '__all__'){
            html_disp_totales = dispGroupedByMes.map(row => `<td>${row.disponibilidad}</td>`).reduce((p, c) => p+c, '')
        }else{
            html_disp_totales = dispGroupedByMes.map(row => `<td></td>`).reduce((p, c) => p+c, '')
        }
        html_disp_totales = `<tr class="table-secondary font-weight-bold">
            <td>Total General</td>
            ${html_disp_totales}
            <td>${totales ? totales.promedio_disp+'%' : ''}</td>
        <tr>`;

        $('#tbl-det-disp-mes-tiponodo tbody').html(html_tbody+html_disp_totales);
    }

    $('#filter-form-mes').on('submit', function(e){
        e.preventDefault();
        $("#spinnerData").show();

        const mes_ini = $("#filter-form-mes select[name='mes_ini']").val();
        const mes_fin = $("#filter-form-mes select[name='mes_fin']").val();
        fetch("{{asset('api/prtltx-dashboard')}}"+`?mes_ini=${mes_ini}&mes_fin=${mes_fin}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            dispGroupedByMes = data.dispGroupedByMes;
            dispGroupedByMesAndTipoNodo = data.dispGroupedByMesAndTipoNodo;
            dispGroupedByMesAndTipoNodoAndNeName = data.dispGroupedByMesAndTipoNodoAndNeName;
            dispEsperadaByTipoNodo = data.dispEsperadaByTipoNodo;
            renderDispGroupedByMes();
            renderDispGroupedByMesAndTipoNodo();
            renderDetalleDispByTipoNodo(filterForm.getValues());
            updateChart();
            $("#spinnerData").hide();
        });
    })


    //$('#select-tiponodo').on('change', renderDetalleDispByTipoNodo);
    class DefaultFilter extends FilterItem{
        onChange(e){
            renderDetalleDispByTipoNodo(this._filterForm.getValues());
        }
    }

    //inicion
    renderDispGroupedByMes();
    renderDispGroupedByMesAndTipoNodo();
    const selectFilter = new DefaultFilter(document.getElementById('select-tiponodo'), 'tipo_nodo');
    const nenameFilter = new DefaultFilter(document.getElementById('filter-ne_name'), 'ne_name');
    const filterForm = new FilterForm(null, [selectFilter, nenameFilter]);
    renderDetalleDispByTipoNodo(filterForm.getValues());
    $("#filter-form-mes select[name='mes_ini'] option").each(function(){
        if ("{{$mesIni}}" == $(this).val()) {
            $("#filter-form-mes select[name='mes_ini']").val($(this).attr('value'));
            $("#filter-form-mes select[name='mes_ini']").change();
        }
    });
    $("#filter-form-mes select[name='mes_fin'] option").each(function(){
        if ("{{$mesFin}}" == $(this).val()) {
            $("#filter-form-mes select[name='mes_fin']").val($(this).attr('value'));
            $("#filter-form-mes select[name='mes_fin']").change();
        }
    });
});
    // selectFilter.onChange(() => {
    //     console.log('filtering');
    //     console.log(this);
    // })
</script>
@endsection