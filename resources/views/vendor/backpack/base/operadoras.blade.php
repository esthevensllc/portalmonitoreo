@extends(backpack_view('blank'))

@section('content')
<div class="mb-2">
    <form id="frm_search">
        <input id="tcdg_btn_search" type="submit" class="btn btn-danger btn-sm" value="Buscar">

        <select id="tcdg_trimestre" name="trimestre" class="btn-sm btn-primary">
            <option selected="" value="">---Trimestre---</option>
        </select>

        <select id="tcdg_department" name="departamento" class="btn-sm btn-primary field-hidden">
            <option selected="" value="">---Departamento--</option>
            @foreach ($departamentos as $row)
                <option value="{{ $row['DEPARTAMENTO'] }}" data-id="{{ $row['CODE'] }}">{{ $row['DEPARTAMENTO'] }}</option>
            @endforeach
        </select>

        <select id="tcdg_provincia" name="provincia" class="btn-sm btn-primary field-hidden">
            <option selected="" value="">---Provincia---</option>
        </select>

        <select id="tcdg_distrito" name="distrito" class="btn-sm btn-primary field-hidden">
            <option selected="" value="">---Distrito---</option>
        </select>
	    <button class="btn-sm btn-primary btnExportData" type="button">Exportar</button>
    </form>
</div>

<div class="row" id="data_content">

</div>
@endsection

@section('after_scripts')
<!--
<script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-exporting.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/highcharts/highcharts-export-data.js') }}"></script>
-->
<script type="text/javascript" src="{{ asset('libs/highcharts/highcharts.js') }}"></script>
<script>
    var categoryImgs = {
        'CLARO': '<img style="width:30px;" src="http://172.17.27.157/portalmonitoreo/assets/images/claro.png"><img>&nbsp;',
        'BITEL': '<img style="width:30px;" src="http://172.17.27.157/portalmonitoreo/assets/images/bitelLogo.png"><img>&nbsp;',
        'MOVISTAR': '<img style="width:30px;" src="http://172.17.27.157/portalmonitoreo/assets/images/movistarLogo.png"><img>&nbsp;',
        'ENTEL': '<img style="width:30px;" src="http://172.17.27.157/portalmonitoreo/assets/images/entelLogo.png"><img>&nbsp;'
    };
    Highcharts.setOptions({
        lang: {
            thousandsSep: ','
        }
    });
    let colorsByOperadora = @json($colorsByOperadora);

    async function renderData(){
        let slc_trimestre = document.querySelector("#tcdg_trimestre");
        let content = document.querySelector("#data_content");


        try{
            const formData = new FormData(document.getElementById("frm_search"));
            const string_params = new URLSearchParams(formData).toString();

            let response = await fetch("{{ asset('api/operadoras').'/'.$id_tracing }}"+"?"+string_params);
            let data = await response.json();

            let html_trimestres = data.trimestres.map(r => `<option value="${r.code}">${r.trimestre}</option>`).join('');
            let trimestre_selected = slc_trimestre.value;
            slc_trimestre.innerHTML = '<option selected="" value="">---Trimestre---</option>'+html_trimestres;
            slc_trimestre.value = trimestre_selected;


            content.innerHTML = '';

            let graphData = [];

            data.dataToView.forEach((config, index) => {
                let html_head = '<tr>'+Object.keys(config.columnNames)
                .map(fname => `<th>${config.columnNames[fname].label}</th>`).join('')
                +'</tr>';

                const html_body = config.data.map(r => {
                    return '<tr>'+Object.keys(config.columnNames)
                    .map(fname => `<td>${r[fname]}</td>`).join('')
                    +'</tr>';
                }).join('');

                const table_html = `<table class="table table-sm tblMonit mb-0" style="width:100%;">
                <thead class="table-danger">${html_head}</thead>
                <tbody>${html_body}</tbody>
                </table>`;

                content.innerHTML = `${content.innerHTML}<div class="col-12">
                    <div class="card p-2">
                        <div class="row">
                            <div class="col-md-6">${table_html}</div>
                            <div class="col-md-6">
                                <div id="container_graph_${index}" style="width:100%;"></div>
                            </div>
                        <div>
                    <div>
                </div>`;

                /*
                
                */
                let data_graph = [];
                let field_by_index = {};
                let columns = [];
                config.data.forEach(r => {
                    Object.keys(r)
                    .forEach(field => {
                        if(field.includes("cobertura") || field.includes("habitantes")){
                            if(field_by_index[field] === undefined){
                                field_by_index[field] = data_graph.length;
                                data_graph.push({yAxis: data_graph.length, name: config.columnNames[field].label, data: []});
                            }
                            const index_of_field = field_by_index[field];
                            const value = parseFloat(r[field]);
                            const color = colorsByOperadora[r.operadora];
                            data_graph[index_of_field].data.push({y: value, color: color});
                            //data_graph[index_of_field].data.push(value);
                        }
                    });

                    columns.push(r.operadora);
                });
                console.log(data_graph);
                //let graph_config = {title: config.title, columns: columns, data: data_graph};
                graphData.push({title: config.title, columns: columns, data: data_graph});
                //renderGraph(`${index}`, {...graph_config});
            });

            graphData.forEach((config, index) => {
                let yAxis = config.data.map(r => ({
                    title: {text: r.name}
                }));

                console.log(yAxis);
                console.log(config);
                
                Highcharts.chart(`container_graph_${index}`, {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: config.title
                    },
                    subtitle: {
                        text: ''
                    },
                    colors: [
                        '#000',
                        '#000'
                    ],
                    xAxis: {
                        categories: config.columns,
                        crosshair: true,
                        labels: {
                            formatter: function() {
                                return '<div class="myToolTip" title="Hello ' +
                                    this.value + '">' + categoryImgs[this.value] + '</div>';
                            },
                            useHTML: true
                        },
                    },
                    //yAxis: yAxis,
                    yAxis: [{
                        title: {
                            text: config.data[0].name
                        }
                    }, { // Secondary yAxis
                        title: {
                            text: config.data[1].name
                        },
                        opposite: true,
                        crosshair: true
                    }],
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: config.data
                });
            });
        }catch(e){
            console.log(e);
        }
    }

    document.getElementById("frm_search")
    .addEventListener("submit", function(e){
        e.preventDefault();
        renderData();
    }, false);

    $("#tcdg_department").change(function(){
        const value = $(this).val();
        if(value !== ""){
            const ubigeo = $(this).find(`option[value=${value}]`).attr('data-id');
            $.ajax({
                url: "{{ asset('fija-coverage/departamentos') }}/"+ubigeo+"/provincias",
                dataType: 'json',
                success:function (response) {
                    let html = "<option selected value=''>---Provincia---</option>";
                    html += response.map(r => `<option value='${r.PROVINCIA}' data-id='${r.CODE}'>${r.PROVINCIA}</option>`).join('');
                    $("#tcdg_provincia").html(html);
                }
            });
        }else{
            $("#tcdg_provincia").html("<option selected value=''>---Provincia---</option>");
            $("#tcdg_distrito").html("<option selected value=''>---Distrito---</option>");
        }
    });

    document.getElementById("tcdg_provincia")
    .addEventListener("change", async function(e){
        try{
            const value = e.target.value;
            if(value !== ""){
                const ubigeo = document.querySelector(`#tcdg_provincia option[value=${value}]`).attributes['data-id'].value;
                let response = await fetch("{{ asset('fija-coverage/departamentos/provincias') }}/"+ubigeo+"/distritos");
                let data = await response.json();

                let html = "<option selected value=''>---Distrito---</option>";
                html += data.map(r => `<option value='${r.DISTRITO}' data-id='${r.CODE}'>${r.DISTRITO}</option>`).join('');
                document.getElementById("tcdg_distrito").innerHTML = html;
            }else{
                $("#tcdg_distrito").html("<option selected value=''>---Distrito---</option>");
            }
        } catch (e){
            alert(e);
        }
    }, false);

    document.querySelector(".btnExportData")
    .addEventListener("click", async function(e){
        try{
            const formData = new FormData(document.getElementById("frm_search"));
            const string_params = new URLSearchParams(formData).toString();

            location.href=("{{ asset('operadoras').'/'.$id_tracing }}/export"+"?"+string_params);
        }catch(e){

        }
    }, false);

    $(document).ready(async function(){
        await renderData();
        let _tcdg_trimestre = document.querySelector("#tcdg_trimestre");
        
        if(_tcdg_trimestre.childNodes.length > 1){
            let default_value = _tcdg_trimestre.childNodes[1].value;
            _tcdg_trimestre.value = default_value;
        }
        renderData();
    });
</script>
@endsection
