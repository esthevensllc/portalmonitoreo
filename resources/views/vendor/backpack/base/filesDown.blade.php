@extends(backpack_view('blank'))

@section('after_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('packages/jquery-ui-dist/jquery-ui.min.css') }}">
<style type="text/css">
	.reportesLink{

	}
	.linkReport:over{
		cursor: pointer;
	}
	.ui-datepicker-calendar{
		display: none;
	}
	.linkrefer{
		display: none;
	}
</style>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
	<label>Fecha </label>
	<input type="text" name="dateMonthWeek" id="dateMonthWeek" readonly="">
	</div>
</div>
<div class="container">
<div class="row reportesLink">
	<table class="tblReport table table-sm">
	<thead>
		<tr><td>Reportes</td><td></td></tr>
	</thead>
	<tbody>
		<tr><td>Reporte 01</td>
		<td>Reporte de uso diario - Internet</td>
		<td>
			<span data-process="01" target="_blank" class="btn btn-sm btn-danger linkProcess">Procesar</span>
			<span data-href="/01/01.- Reporte de uso diario - Internet.[FILTER].zip" 
			target="_blank"class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 02</td>
		<td>Reporte de uso mensual - Internet</td>
		<td><span data-href="{{ asset('filesdown/report/02/') }}/" target="_blank"
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 04</td>
		<td>Estadísticas de las veinte (20) páginas Web más visitadas</td>
		<td><span data-href="{{ asset('filesdown/report/04/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 05</td>
		<td>Reporte de pico de volumen de trafico - Internet</td>
		<td><span data-href="{{ asset('filesdown/report/05/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 06</td>
		<td>Reporte de Cantidad de sesiones Internet</td>
		<td><span data-href="{{ asset('filesdown/report/06/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 07</td>
		<td>Reporte de uso diario - Intranet</td>
		<td>
			<span data-process="07" target="_blank" class="btn btn-sm btn-danger linkProcess">Procesar</span>
			<span data-href="/07/07.- Reporte de uso diario - Intranet.[FILTER].zip" target="_blank"class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 08</td>
		<td>Reporte de uso mensual - Intranet</td>
		<td><span data-href="{{ asset('filesdown/report/08/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 10</td>
		<td>Reporte de pico de volumen de trafico - Intranet</td>
		<td><span data-href="{{ asset('filesdown/report/10/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 11</td>
		<td>Reporte de interrupción del acceso a Internet</td>
		<td>
        @if($user->cargo != '1')
            @if($user->cargo != '22')
                <span data-href="{{ asset('filesdown/export/exp-interrupciones-cpe-excel-inc') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
            @else
                <span data-href="{{ asset('filesdown/export/exp-interrupciones-cpe-excel') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
            @endif
        @else
            <span data-href="{{ asset('filesdown/export/exp-interrupciones-cpe-excel') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span>
			<span data-href="{{ asset('filesdown/export/exp-interrupciones-cpe-excel-inc') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
        @endif
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 12</td>
		<td>Reporte de interrupción del acceso a Intranet</td>
		<td>
		@if($user->cargo != '1')
			@if($user->cargo != '22')
				<span data-href="{{ asset('filesdown/export/exp-intranet-excel-inc') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			@else
				<span data-href="{{ asset('filesdown/export/exp-intranet-excel') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			@endif
		@else
			<span data-href="{{ asset('filesdown/export/exp-intranet-excel') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span>
			<span data-href="{{ asset('filesdown/export/exp-intranet-excel-inc') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		@endif
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 13</td>
		<td>Reporte mensual de interrupción del Subsistema de seguimiento</td>
		<td>
		@if($user->cargo != '1')
			@if($user->cargo != '22')
				<span data-href="{{ asset('filesdown/export/exp-int-sub-sistema-excel-inc') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			@else
				<span data-href="{{ asset('filesdown/export/exp-int-sub-sistema-excel') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			@endif
		@else
			<span data-href="{{ asset('filesdown/export/exp-int-sub-sistema-excel') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span>
			<span data-href="{{ asset('filesdown/export/exp-int-sub-sistema-excel-inc') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		@endif
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 14</td>
		<td>Reporte mensual de interrupciones POP</td>
		<td>
		@if($user->cargo != '1')
			@if($user->cargo != '22')
				<span data-href="{{ asset('filesdown/export/exp-int-pop-excel-inc') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			@else
				<span data-href="{{ asset('filesdown/export/exp-int-pop-excel') }}/" target="_blank" 
				class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			@endif
		@else
			<span data-href="{{ asset('filesdown/export/exp-int-pop-excel') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span>
			<span data-href="{{ asset('filesdown/export/exp-int-pop-excel-inc') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		@endif			
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 15 1</td>
		<td>TIA Incidencias</td>
		<td><span data-href="{{ asset('filesdown/report/15_1/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 15 2</td>
		<td>TIA Indicador</td>
		<td><span data-href="{{ asset('filesdown/report/15_2/') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 16 1</td>
		<td>Reporte TOE diario</td>
		<td><span data-process="16_1" target="_blank" class="btn btn-sm btn-danger linkProcess">Procesar</span>
			<span data-href="/16_1/16.- Reporte toe diario.[FILTER].zip" 
			target="_blank"class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 16 2</td>
		<td>Reporte TOE mensual</td>
		<td><span data-href="{{ asset('filesdown/report/16_2') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 17</td>
		<td>Indicador Latencia</td>
		<td><span data-href="{{ asset('filesdown/report/17') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 18</td>
		<td>Indicador Perdida de Paquete</td>
		<td><span data-href="{{ asset('filesdown/report/18') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 19 1</td>
		<td>Indicador Velocidad Subida</td>
		<td><span data-href="{{ asset('filesdown/report/19_1') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 19 2</td>
		<td>Indicador Velocidad Bajada</td>
		<td><span data-href="{{ asset('filesdown/report/19_2') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Reporte 20</td>
		<td>Indicador BER</td>
		<td><span data-href="{{ asset('filesdown/report/20') }}/" target="_blank" 
			class="btn btn-sm btn-danger linkReport">Descargar</span></td>
		<td><span class="linkrefer">Transferir</span></td>
		</tr>
		<tr><td>Graficos Pronatel</td>
		<td>Graficos</td>
			<td>
				<span data-process="cpe_graf" target="_blank"class="btn btn-sm btn-danger linkProcess">Procesar</span>
				<span data-href="/graphs/Graficas trafico mensual internet_[FILTER].zip" target="_blank"class="btn btn-sm btn-danger linkReport">Descargar</span></td>
			</tr>
		</tr>
	</tbody></table>
</div></div>
@endsection

@section('after_scripts')
<script type="text/javascript" src="{{ asset('packages/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<script>
$('#dateMonthWeek').datepicker({
    monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Sepiembre","Octubre","Noviembre","Diciembre"],
    monthNamesShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
    changeMonth:true,
    changeYear:true,
    showButtonPanel:true,
    dateFormat:'yy-mm',
    minDate:new Date(2021,6,1),
    maxDate:"-1m -3d",
    beforeShow: function(e){
        let value = e.target?e.target.value:"";
        if(value !== ""){
            $("#dateMonthWeek").datepicker("setDate", new Date(value+"-01"));
        }   
    },
    onClose:function(dateText,inst) {
        let month_by_index = {0: "01", 1: "02", 2: "03", 3: "04", 4: "05", 5: "06", 6: "07", 7: "08", 8: "09", 9: "10", 10: "11", 11: "12"};
        $('#dateMonthWeek').val(inst.drawYear+"-"+month_by_index[inst.drawMonth]);
        console.log(dateText,inst);
    }
});

$(".linkReport").click(function () {
    var paramDate = $("#dateMonthWeek").val();
    var linkRep  = $(this).attr("data-href");
    if(linkRep.indexOf("[FILTER]")<0){
        linkRep+=paramDate;
        window.open(linkRep);
    }else{
        if(linkRep.startsWith("/")){
            path = 1;
        }else{
            path = 0;
        }
        linkRep=linkRep.replace("[FILTER]",paramDate.replace("-",""));
        $.ajax({
            url: "{{ asset('filesdown/validFileExist') }}",
            data:{"fileLink":linkRep,"path":path},
            method:'post',
            type:'json',
            dataType:'json',
            success:function (result) {
                if(result.exist==1){
                    if(path == 1){
                        window.open("{{ asset('files') }}"+linkRep);
                    }else{
                        window.open(linkRep);
                    }						
                }else{
                    alert("El archivo aun no esta cargado.");
                }
            }
        });
    }
    //window.open("http://localhost:82/"+$(this).attr("data-href")+$("#monthList").val());
});

$(".linkProcess").click(function () {
    var proceso = $(this).attr("data-process");
    var fecha = $("#dateMonthWeek").val();

    $.ajax({
        url: "{{ asset('filesdown/validProcess') }}",
        data:{"proceso":proceso, "fecha":fecha},
        method:'get',
        type:'json',
        dataType:'json',
        success:function (result) {	
            console.log(result.data[0].status);
            if(result.data[0].status == '2'){
                alert("El proceso ya fue cargado para la fecha seleccionada.");
            }else{
                if(result.data[0].status == '1'){
                    alert("El proceso se encuntra cargando para la fecha seleccionada.");
                }else{
                    cargarProceso(proceso,fecha);	
                }
            }					
        }
    });
});

function cargarProceso(proceso,fecha){
    $.ajax({
        url: "{{ asset('filesdown/loadProcess') }}",
        data:{"proceso":proceso,"fecha":fecha},
        method:'post',
        type:'json',
        dataType:'json',
        success:function (result) {	
            console.log(result.data);
        }
    });
}

$("#dateMonthWeek").datepicker("setDate","-1m -3d");
</script>
@endsection