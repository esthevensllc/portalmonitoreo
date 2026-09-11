@extends(backpack_view('blank'))

@section('header')
<input type="hidden" value="{{$tracingID}}" id="tracingID">
<input type="hidden" value="{{$menuID}}" id="menuID">
<input type="hidden" name="" id="fildValStr" value="">
<input type="hidden" name="" id="typedesc" value="null">
<input type="hidden" name="" id="mapurl" value="<?=$mapurl?>">
@endsection

@section('after_styles')
<style>
    /* Set the size of the div element that contains the map */
    #map {
        height: 850px;
        /* The height is 400 pixels */
        width: 100%;
        /* The width is the width of the web page */
    }

	.forms-map{
        top: 70px;
		left: 10px;
		color: white;
		font-weight: bold;
		z-index: 401;
		background: white;
		padding: 0.5em;
		border-radius: 0.2em;
	}

	.gm-style .gm-style-iw-c .gm-ui-hover-effect{
		border: none!important;
	}

	.form-check-label {
    	color: black;
	}

	#dataMapGraph {
		position: absolute;
		right: 55px;
		background: #FFF;
		border-radius: 20px;
		margin-top: 180px;
		opacity: 0.7;
		border: solid 1px #ccc;
	}

	#dataMapGraph .closeTab{
		margin: 5px 15px;
		position: absolute;
		z-index: 1;
	}

	.slc-changeVwMap{
		color: #1b2a4e!important;
		background-color: #d39e00!important;
		border-color: #c69500!important;
	}
	#dataMapInd{
		font-size: 0.8rem;
		margin: 160px 10px;
		max-width: 400px;
	}
	#dataMapInd .close{
		padding: 0 10px!important;
	}
	#legend{
		top: 180px!important;
	}
	.table td, .table th {
    	padding: 0.2rem!important;
	}
</style>
@endsection

@section('content')
<div id="dataMapInd" style="display: none" class="card border-secondary mb-3">dataMapInd</div>
<form style="margin-bottom:5px;">
  	<div class="form-row">
		<div class="col-auto">
	
		<input type="button" class="btn btn-filtrar btn-danger" value="Buscar">
		</div>
		<div class="col anioInterf">

		<select class="form-control slc-anioSemInterf">
					<option selected="" value="0-0">---Año y semana--</option>
					<?php foreach ($anioSemList as $key => $item): ?>
						<option value="<?=$item['ANIO']?>-<?=$item['SEMANA']?>"><?=$item['ANIO']?>-<?=$item['SEMANA']?></option>
					<?php endforeach ?>
				</select>
		</div>

		<div class="col banda">

		<select class="form-control slc-banda">
			<option selected="" value="0">---Banda--</option>
					<option value="450 MHz">450 MHz</option>
					<option value="700 MHz">700 MHz</option>
					<option value="850 MHz">850 MHz</option>
					<option value="2.6 GHz">2.6 GHz</option>
					<option value="3.5 GHz">3.5 GHz</option>
					<option value="1900 MHz">1900 MHz</option>
		</select>
		</div>
		<div class="col ubgDpto">

		<select class="form-control slc-ubgDpto">
					<option selected="" value="0">---Departamento--</option>
					<?php foreach ($dptoList as $key => $item): ?>
						<option value="<?=$item['CODE']?>"><?=$item['DEPARTAMENTO']?></option>
					<?php endforeach ?>
				</select>
		</div>
		<div class="col ubgProv" id="colProv" style="display: none;">

		<select class="form-control slc-ubgProv field-hidden">
					<option selected="" value="0">---Provincia---</option>
				</select>
		</div>
		<div class="col ubgDist" id="colDist" style="display: none;">

		<select class="form-control slc-ubgDist field-hidden">
					<option selected="" value="0">---Distrito---</option>
				</select>
		</div>
		<div class="col OVERSHOOTER">

		<select class="form-control slc-OVERSHOOTER ">
					<option selected="" value="-1" >---Overshooter---</option>
					<option value="0">SI</option>
					<option value="1">NO</option>
				</select>
		</div>
		<div class="col PROBLEMA_ASOCIADO">

		<select class="form-control slc-PROBLEMA_ASOCIADO ">
					<option selected="" value="-1" >---Problema Asociado---</option>
					<option value="6329">PROBLEMA TRANSPORTE</option>
					<option value="6330">FALTA CAPACIDAD</option>
					<option value="6331">FALTA OPTIMIZAR</option>
					<option value="6332">PROBLEMA OPERATIVO</option>
				</select>
		</div>
		<div class="col freqband">

		<select class="form-control slc-freqband ">
					<option selected="" value="-1" >---Freqband---</option>
					<option value="6897">450 MHz</option>
					<option value="6898">700 MHz</option>
					<option value="6899">1900 MHz</option>
					<option value="6900">2.6 GHz</option>
					<option value="6901">3.5 GHz</option>
				</select>
		</div>
		<div class="col carrier">

		<select class="form-control slc-carrier ">
					<option selected="" value="-1" >---Carrier---</option>
					<option value="6903">C1</option>
					<option value="6904">C2</option>
					<option value="6905">C3</option>
					<option value="6906">C4</option>
					<option value="6907">C5</option>
					<option value="6908">C6</option>
					<option value="6909">C7</option>
					<option value="6910">C11</option>
					<option value="6911">C12</option>
					<option value="6912">C21</option>
				</select>
		</div>
		<div class="col priority" style="display:none;">

		<select class="form-control slc-priority field-hidden">
					<option selected="" value="-1">---Prioridad---</option>				
					<option value="0">0</option>				
					<option value="1">1</option>				
					<option value="2">2</option>				
				</select>
		</div>
		<div class="col scene"  style="display:none;">

		<select class="form-control slc-scene field-hidden">
					<option selected="" value="-1" >---Escenario Capacidad---</option>
					<option value="6969">1.CAPACIDAD OK</option>
					<option value="6970">2.CAPACIDAD TEMPORAL Y/O SUBUTILIZADO</option>
					<option value="6971">3.OPTIMIZACION</option>
					<option value="6972">4.CAPACIDAD INSUFICIENTE</option>
					<option value="7088">5.CAPACIDAD INSUFICIENTE – REINCIDENTE ( CRITICO >= 5SEM)</option>
				</select>
		</div>
		<div class="col capacity"  style="display:none;">

		<select class="form-control slc-capacity field-hidden">
					<option selected="" value="-1" >---Critico Capacidad---</option>
					<option value="6973">Si</option>
				</select>
		</div>
		<div class="col changeVwMap">

		<select class="form-control slc-changeVwMap btn-sm btn-warning">
					<!-- <option selected="" value="1">Timing Advance</option> -->
					<option value="2">Interferencia</option>
					<!-- <option value="3">Capacidad Sector</option>
					<option value="4">Capacidad Banda</option>
					<option value="5">Capacidad TDD</option>
					<option value="6">Interferencia Penales</option>
					<option value="7">Interferencia Semanal</option> -->
				</select>
		</div>

		<div class="col btnExportData">
			<button id="btn-download" class="btn btn-danger btnExportData2" data-type="1" type="button" >Exportar</button>
		</div>

		<div class="col-auto btnPlantilla" style="display:none;">
			<button id="btn-plantilla" class="btn btn-danger btnPlantilla" data-type="1" type="button" onclick="window.open('/portalmonitoreov2/files/plantilla_interferencia_penales.xlsx')">Plantilla</button>
		</div>

		<div id="colExport" class="col btnImportData" style="display:none;">
			<div class="input-group">
				<form action="{{ asset('importData') }}" enctype="multipart/form-data" method="post" class="btn btnImportData" id="formLoadTemplate" style="display:none;padding: 0;margin: 2px 0 0 0;">
					<label class="btn btn-danger">
						<input type="hidden" name="tracID" value="1"> 
						<input type="hidden" name="userID" value="1"> 
						<input type="hidden" name="profID" value="1"> 
						<input type="hidden" name="baseurl" value="/portalmonitoreov2/">
						<input class="btn-importData" type="file" value="Importar" name="fileImport" accept=".xls" style="display:none">
						<i class="icon icon-upload" style="font-size: 1.2em;"></i>
						Importar
					</label>
				</form>
			</div>
		</div>
  	</div>
	<div class="col" style="display:none;">
		<label for="slc-crit"></label>
		<select class="form-control slc-site field-hidden" multiple>
			<option selected="" value="0">---</option>
		</select>
	</div>
	@if ($tracingID == 3 && $menuID == 6251)
	<div class="d-flex position-relative">
        <div class="float-start position-absolute forms-map" style="display:none">
			<div class="form-row">
				<div class="col-auto">
					<div class="form-check interf_capaPolyCheck">
						<input class="form-check-input capaPolyCheck" type="checkbox" data-capa="hfc" value="planos_hfc" id="planos_hfc" checked>
						<label class="form-check-label" for="flexCheckDefault">
							PLANOS HFC
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="float-start position-absolute" style="top: 20px; left: 200px; z-index: 1;">
			<form class="d-flex" id="form_buscador">
				<input type="text" class="form-control form-control-sm" name="value" placeholder="Cellname">
				<button class="btn btn-secondary btn-sm">Buscar</button>
			</form>
		</div>
	</div>
	@endif
</form>
<!--The div element for the map -->
<div id="map"></div>
@endsection

@section('after_scripts')
<script type="text/javascript" src="{{asset('libs/jsts/jsts.min.js')}}"></script>
<script>
	var dataMap;
	var mapApi;
	var geometryFactory = new jsts.geom.GeometryFactory();
	// Lista de intersecciones
	var drawingIntscList = [];
	//lista de marcadores de sitios encontrados en el filtro
	var markerFilterList = [];
	//Lista total de marcadores encontrados en el filtro
	var markersPoint = [];
	//Lista total de polygonos encontrados en el filtro
	var listPolygone = [];
	//Lista total de polylines encontrados en el filtro
	var listPolyline = [];
	//lista de sectores encontrados en el filtro
	var listPolyBase = [];
	//lista de texto de procentaje encontrados en el filtro
	var listMarkPorc =[];
	//Lista total de intersecciones mostrados
	var listingIntscView = [];
	//Lista de polygonos mostrados
	var listPolygoneView = [];
	//Lista de polyline mostrados
	var listPolylineView = [];
	//Lista de texto de porcentaje mostrados
	var listMarkPrctView = [];
	//Lista de campos con interferencia
	var listDataIntfc = [];
	//Lista de distancias en kilometros
	var listRad = [0.078,0.234,0.546,1.014,1.95,3.51,6.63,14.43,30.03,53.43,76.83,85];
	// lista de nombres de campos en orden ascendente en distancias.
	var listRadName = ["0M_78M","78M_234M","234M_546M","546M_1014M","1014M_1950M",
	"1950M_3510M","3510M_6630M","6630M_14450M","14450M_30030M","30030M_53430M",
	"53430M_76830M","M_76830M"]
	// Lista de colores a mostrar de mayor a menor importancia.
	var colorPct = ["#78281F","#943126","#B03A2E","#CD5C5C","#E74C3C","#F08080",
	"#FF5733","#FA8072","#E9967A","#FFA07A","#F5B7B1","#FADBD8"];
	// var callback = function (feature) {
	//   mapApi.data.remove(feature);
	// };
	var listMap=[];
	var polygon_boundary = [];
	const gradient = ["rgba(255,243,51,0)",
		//"rgba(255,243,51,1)",
		"rgba(255,159,51,1)", 
		"rgba(255,159,51,1)",        
		"rgba(255,159,51,1)",

		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)",
		"rgba(255,0,0,1)"
	];
	$(document).ready(function () {
		$(".banda").hide();
		$(".anioInterf").hide();
		$(".semInterf").hide();
		// getListIntfc();
		$(".slc-ubgDpto").change(function () {
			changeDpto();
			// addData();
		})

		$(".slc-ubgProv").change(function () {
			changeProv();
			// addData();
		})
		// $(".slc-ubgDist,.slc-PROBLEMA_ASOCIADO,.slc-OVERSHOOTER,.slc-freqband,.slc-carrier",).change(function () {
		// 	addData();
		// });
		$(".slc-site").change(function () {
			filterSite();
		});

		$(".btn-filtrar").click(function () {
			clearPolygone();
			addData();
		});

		$(".btnExportData2").click(function () {
			exportData($(this).attr("data-type"));
		});

		changeVwMap();

		$("input.btn-importData").change(function () {
			$(this).parent().parent().submit();
		});

		$(".capaPolyCheck").on( 'change', function() {
			if( $(this).is(':checked') ) {
				// Hacer algo si el checkbox ha sido seleccionado
				$("#spinnerData").show();
				loadCapa($(this).val(),"geoJson","TODO");
				//planosDorsales();
			} else {
				// Hacer algo si el checkbox ha sido deseleccionado
				mapApi.data.forEach(function (feature) {
					if(feature.getProperty('capa') == 'hfc'){
						mapApi.data.remove(feature);
					}
				});
				//clearMarkersSites('dorsales');
			}
      	});

		$("#form_buscador").on("submit", function(e){
			e.preventDefault();
			let formData = new FormData(e.target);
			let result = markerFilterList.filter(marker => {
				return (marker.data_cellname??"").toLowerCase().includes(formData.get("value").toLowerCase());
			});
			if(result.length > 0){
				let latLng = result[0].getPosition();
				mapApi.setCenter(latLng);
				mapApi.setZoom(12);
			}
		});
	});

	$(window).on('load', function() {
		initMap();
	});

	function loadCapa(capa,tipo,filtro){
      $.ajax({
          url: "{{ route('api.mapData') }}",
          type: 'GET',
          //dataType: 'json',
          data: "id="+filtro+"&type="+capa,
          beforeSend:function () {
            $("#spinnerData").show();
          },
          success: function(response) {
			data = response.data;
			if(tipo=="geoJson" && (filtro == data.opcion || filtro == "TODO")){ 
				mapApi.data.addGeoJson(data);

				mapApi.data.setStyle(function(feature){
					var id = feature.getProperty('capa');
					if(id === 'hfc'){
						return{
							fillColor: "blue",
							strokeOpacity: 0.5,
							fillOpacity: 0.2,
							zIndex: -1
						};
					}
				});

				var infoWindow = new google.maps.InfoWindow();
				
				var indoWindowAbierto = null;

				mapApi.data.addListener('click', function(event){
					var feature  = event.feature;
					if(feature.getProperty('capa') === 'hfc'){
						var detalle = feature.getProperty('nombre');

						infoWindow.setContent(detalle);
						infoWindow.setPosition(event.latLng);
						infoWindow.open(mapApi);

						if(indoWindowAbierto  !== null){
							//indoWindowAbierto.close();
						}

						//indoWindowAbierto = infoWindow;
					}
				});
			}
          },
          complete: function () {
            $("#spinnerData").hide();
          },
          error: function(data){
            new Noty({text: "Ocurrió un error al cargar la información", type: "warning", timeout: 2000}).show();
            $("#spinnerData").hide();
            console.log(data);
          }
      });
    }

			function changeVwMap() {
				$(".slc-changeVwMap").change(function () {
					clearPolygone();
					clearListVw();
					while(listMap.length){listMap.pop().setMap(null);}
					$(".btnImportData").hide();
					$("#legend,.priority").hide();
					$(".freqband,.carrier").show();
					$(".ubgDpto").show();
					$(".banda").hide();
					$(".anioInterf").hide();
					$(".semInterf").hide();
					$(".btnPlantilla").hide();
					$(".forms-map").hide();
					mapApi.data.forEach(function (feature) {
						if(feature.getProperty('capa') == 'hfc'){
							mapApi.data.remove(feature);
						}
					});
					if($(this).val()==1){
						$(".btn-filtrar").show();
						$(".btnExportData").show();
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").show();
						$(".scene,.capacity").hide();
          				$("slc-.scene,slc-.capacity").val("-1");
					}else if($(this).val()==3){
						$(".btn-filtrar").show();
						$(".btnExportData").show();
						$(".scene,.capacity").show();
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").hide();
						$(".freqband,.carrier").hide();
						$("#legend").show();
					}else if($(this).val()==4){
						$(".btn-filtrar").show();
						$(".btnExportData").show();
						$(".scene,.capacity").show();
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").hide();
						$(".carrier").hide();
						$("#legend").show();
					}else if($(this).val()==5){
						$(".btn-filtrar").show();
						$(".btnExportData").show();
						$(".scene,.capacity").show();
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").hide();
						$(".freqband,.carrier").hide();
						$("#legend").show();					
					}else if($(this).val()==6){
						$(".slc-ubgDpto").val('0');
						$(".ubgDpto").hide();	
						$(".ubgProv").hide();	
						$(".ubgDist").hide();	
						$(".btnExportData").hide();
						$(".scene,.capacity").hide();
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").hide();
						$(".freqband,.carrier").hide();
						$("#legend").hide();
						$(".btnPlantilla").show();
						$(".btnImportData").show();				
					}else if($(this).val()==7){
						//$(".btn-filtrar").hide();
						$(".btnExportData").hide();
						$(".scene,.capacity").hide();
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").hide();
						$(".freqband,.carrier").hide();
						$("#legend").hide();
						$(".banda").show();
						$(".anioInterf").show();
						$(".semInterf").show();
					}else{						
						$(".btn-filtrar").show();
						$(".btnExportData").show();
						$(".priority").show();
						$(".scene,.capacity").hide().val("-1");
						$(".OVERSHOOTER,.PROBLEMA_ASOCIADO").hide();
						@if ($tracingID == 3 && $menuID == 6251)
						loadCapa('planos_hfc','geoJson','TODO');
						$(".forms-map").show();
						@endif						
					}
					$('.slc-OVERSHOOTER,.slc-scene,.slc-capacity,.slc-PROBLEMA_ASOCIADO,.slc-freqband,.slc-carrier,.slc-priority').val("-1");
				});
			}
			function getListIntfc() {
				var tracingID=$("#tracingID").val();
				$.ajax({
					url:"{{ asset('getMapCoverageInfo') }}/"+tracingID,
					dataType: 'json',
					type:'POST',
					success:function (resContent) {
						listDataIntfc=resContent;
					}
				});
			}
			function closeMenuSel() {
				$(".search-choice-close:not(.closedElmnt)").click(function () {
					var siteName = $(this).parent().find('span').text();
					let listData = arrayColumn(listPolygone,'nameDataG');
					let listPrct = arrayColumn(listMarkPorc,'nameDataG');
					let listLine = arrayColumn(listPolyline,'nameDataG');
					let keysSearch = [],prctSearch = [],lineSearch = [];
					for (var dataKey=0;dataKey<listData.length;dataKey++){
						if(siteName.includes(listData[dataKey]))
							keysSearch.push(dataKey);
					}
					$.each(keysSearch,function (x,y) {
						if(listPolygone[y].getVisible())
							listPolygone[y].setVisible(false);                      
					});
					for (var dataKeyPrct=0;dataKeyPrct<listPrct.length;dataKeyPrct++){
						if(siteName.includes(listPrct[dataKeyPrct]))
							prctSearch.push(dataKeyPrct);
					}
					$.each(prctSearch,function (x,y) {
						if(listMarkPorc[y].getVisible())
							listMarkPorc[y].setVisible(false);                      
					});
					for (var dataKeyLine=0;dataKeyLine<listLine.length;dataKeyLine++){
						if(siteName.includes(listLine[dataKeyLine]))
							lineSearch.push(dataKeyLine);
					}
					$.each(lineSearch,function (x,y) {
						if(listPolyline[y].getVisible())
							listPolyline[y].setVisible(false);                      
					});
					if ($("#dataMapInd").attr("data-sn").substr(0,siteName.length)==siteName){
						$("#dataMapInd").hide();
					}
				});
				$.each($("a.search-choice-close:not(.closedElmnt)"),function (x,y) {
					$(y).addClass("closedElmnt");
				})
			}
			function initMap() {
				mapApi = new google.maps.Map(document.getElementById('map'), {
					center: new google.maps.LatLng(-10.0431805,-74.0282364),
					zoom: 5.8,
					// mapTypeID: google.maps.MapTypeId.ROADMAP,
					minZoom: 4,
					options:{gestureHandling: 'greedy'}
				});
				// addData();
				viewMap();
				//addControl();
			}
			function viewMap() {
				setTimeout('showMap()',1000);
			}
			function showMap() {
				$(".widget-content.sectVw #map").css({"overflow-x":"hidden","position":""});
				// setTimeout($(".sectTabsContent #map div").css({"overflow-x":"hidden","overflow-y":"hidden"}),5000);
			}
			function addControl() {
				var legend = document.getElementById("legend");
				mapApi.control[google.maps.ControlPosition.RIGHT_TOP].push(legend);
			}

			function exportData(typeExport) {
				var tracingID=$("#tracingID").val();
				var menuID=$("#menuID").val();
				var url = "{{ url('acceso_5g_movil_cobertura/getDataDownCoverage') }}/";
				var params=$.map(paramData(),function (val,key) {return val;});
				params = params.join("/")+"/"+typeExport;
          		window.location = url+params;
			}

			function paramData() {
				return {
					dpto:btoa($('select.slc-ubgDpto option:selected').text()),
					prov:btoa($('select.slc-ubgProv option:selected').text()),
					dist:btoa($('select.slc-ubgDist option:selected').text()),
					OVERSHOOTER:btoa($(".slc-OVERSHOOTER option:selected").text()),
					PROBLEMA_ASOCIADO:btoa($(".slc-PROBLEMA_ASOCIADO option:selected").text()),
					scene:btoa($(".slc-scene option:selected").text()),
					capacity:btoa($(".slc-capacity option:selected").text()),
					freqband:btoa($(".slc-freqband option:selected").text()),
					carrier:btoa($(".slc-carrier option:selected").text()),
					priority:btoa($(".slc-priority option:selected").text()),
					changeVwMap:$(".slc-changeVwMap").val(),
				}
			}

			function addData() {
				var est = "";
				var url = $("#mapurl").val();
				clearPolygone();
				clearListVw();
				var infoWIndows = new google.maps.InfoWindow;
				var changeVwMap = $(".slc-changeVwMap").val();
				while(listMap.length){listMap.pop().setMap(null);}
				$.ajax({
					type:'GET',
					url:"{{ url('acceso_5g_movil_cobertura/21/6251/getDataMapCoverage') }}",
					data:{
						tracingID:$("#tracingID").val(),
						menuID:$("#menuID").val(),
						dpto:$('select.slc-ubgDpto option:selected').text(),
						prov:$('select.slc-ubgProv option:selected').text(),
						dist:$('select.slc-ubgDist option:selected').text(),
						OVERSHOOTER:$(".slc-OVERSHOOTER option:selected").text(),
						PROBLEMA_ASOCIADO:$(".slc-PROBLEMA_ASOCIADO option:selected").text(),
						scene:$(".slc-scene option:selected").text(),
						capacity:$(".slc-capacity option:selected").text(),
						freqband:$(".slc-freqband option:selected").text(),
						carrier:$(".slc-carrier option:selected").text(),
						priority:($(".slc-priority option:selected").text()),
						changeVwMap:changeVwMap
					},
					dataType: "json",
					success: function(itmList){

						var row = 0;
						let indexData=0;
						if(["2","3","4","5",2,3,4,5].includes(changeVwMap)){							

							var seltxt = "<option value=''>---</option>";
							$.each(itmList.siteNameList,function (x,y) {
								seltxt += "<option value='"+y+"'>"+y+"</option>";
							});
							$(".slc-site").chosen("destroy");
							$(".slc-site").html(seltxt);
							$(".slc-site").chosen();

							if(itmList.DATACONTENT.length>0)
							$.each(itmList.DATACONTENT, function(itemkey, xElmnt) {   
								row++;
								var textHTML = "data";
								let latPoint = xElmnt["latitud"];
								let lonPoint = xElmnt["longitud"];
								let AZIMUTH = parseFloat(xElmnt["azimuth"].replace(",","."));
								let LONGITUD = parseFloat(xElmnt["longitud"].replace(",","."));
								let LATITUD = parseFloat(xElmnt["latitud"].replace(",","."));
								let point = null;
								if(latPoint!=null&&lonPoint!=null){
									point = new google.maps.LatLng(latPoint.replace(',','.'),lonPoint.replace(',','.'));
								}

								var infowindowcontent = document.createElement('div');
								var strong = document.createElement('strong');

								strong.textContent = "UNKNOWN";

								infowindowcontent.appendChild(strong);
								infowindowcontent.appendChild(document.createElement('br'));
								var text = document.createElement('table');

								text.innerHTML = textHTML;
								infowindowcontent.appendChild(text);

								// var iconPosition=iconVerde;
								if(point!=null&&(["3","4","5"].includes(changeVwMap)||!["-1","","null",null].includes(xElmnt["PRIORIDAD"]))) {

									let latBase_0 = LATITUD+(.10*Math.cos((3.141592653/180)*(AZIMUTH+30)))/111.325;
									let longBase_0 = LONGITUD+(.10*Math.sin((3.141592653/180)*(AZIMUTH+30)))/((Math.cos((3.141592653/180)*LATITUD)*40076)/360);
									let latBase_1 = LATITUD+(.10*Math.cos((3.141592653/180)*(AZIMUTH-30)))/111.325;
									let longBase_1 = LONGITUD+(.10*Math.sin((3.141592653/180)*(AZIMUTH-30)))/((Math.cos((3.141592653/180)*LATITUD)*40076)/360);
									let point0 = new google.maps.LatLng(latBase_0,longBase_0);
									let point1 = new google.maps.LatLng(latBase_1,longBase_1);

									let polLatLngbase = [
										point,
										point0,
										point1,
										point,
									];
									
									strockColorBase="#000000";
									fillColorBase="";
									if(["3","4","5"].includes(changeVwMap)){
										switch(xElmnt["ESCENARIO_V2"]){
											case '1.CAPACIDAD OK':
												fillColorBase="#0000ff";break;
											case '2.CAPACIDAD TEMPORAL Y/O SUBUTILIZADO':
												fillColorBase="#00ff00";break;
											case '3.OPTIMIZACION':
												fillColorBase="#FFFF00";break;
											case '4.CAPACIDAD INSUFICIENTE':
												fillColorBase="#ff0000";break;
												// let nSemCritCap=0;
												// try{
												// 	nSemCritCap=parseFloat(xElmnt["N_SEM_CRIT_CAPACIDAD"]);
												// }catch{
												// 	nSemCritCap=0;
												// }
												// if(nSemCritCap>=5)
												// {
												// 	fillColorBase="#800000";
												// }
												break;
											case '5.CAPACIDAD INSUFICIENTE – REINCIDENTE ( CRITICO >= 5SEM)':
												fillColorBase="#800000";break;
										}
										strockColorBase=fillColorBase;
									}else if (["0",0].includes(xElmnt["PRIORIDAD"])) {
										fillColorBase="#FF0000";
									} else if (["1",1].includes(xElmnt["PRIORIDAD"])) {
										fillColorBase="#ffa500";
									}else{
										fillColorBase="#FFFF00";
									}
									
									let fieldUniq = xElmnt["cellname"];
									let fieldGeneral = xElmnt["site_name"];
									if(["3","4","5"].includes(changeVwMap)){
										fieldUniq = xElmnt["sector_name"];
										fieldGeneral = xElmnt["enodob_name"];
									}
									let polygonebase = new google.maps.Polygon({
										nameDataG: fieldGeneral,
										nameData:fieldUniq,
										path: polLatLngbase,
										fillColor:fillColorBase,
										fillOpacity:0.7,
										strokeColor:strockColorBase,
										strokeWeight:1,
										strokeOpacity:2,
										map:mapApi,
										zindex:indexData+row+1000000,
									});
									
									polygonebase.addListener('click',function(){
										// let cellname = fieldUniq;
										console.log(fieldUniq);
										if(["1","2"].includes(changeVwMap))
											fnAddFncMapGraph(xElmnt,fieldUniq,1);
										viewDataMap(xElmnt);
										// listingIntscView = intscSearch;
									});
									
									listPolyBase.push(polygonebase);
									let totalRadItm = 0;
									let pointsPrct = [];
								}
							});
							if(itmList.SITELIST.length>0&&changeVwMap=="2")
							$.each(itmList.SITELIST, function(itemkey, xElmnt) {
								var pointCenter = new google.maps.LatLng(xElmnt["latitud"].replace(",","."),
								xElmnt["longitud"].replace(",","."));
								var linkIcon = "{{ asset('images/antena_rojo2.png') }}";
								if(xElmnt["prioridad"]=='-1')
									linkIcon = "{{ asset('images/antena_azul.png') }}";
								let markerPrc = new google.maps.Marker({
									nameDataG: xElmnt["site_name"],
									data_cellname: xElmnt["cellname"],
									map:mapApi,
									icon:{url:linkIcon,
										scaledSize: new google.maps.Size(30,30),
									},
									position:pointCenter,
									title:xElmnt["site_name"] + " - " + xElmnt["site_address"],
								});
								markerFilterList.push(markerPrc);
							});
						}else{

							if((["1",1].includes(changeVwMap))){

								var seltxt = "<option value=''>---</option>";
								$.each(itmList.siteNameList,function (x,y) {
									seltxt += "<option value='"+y+"'>"+y+"</option>";
								});
								$(".slc-site").chosen("destroy");
								$(".slc-site").html(seltxt);
								$(".slc-site").chosen();

								if(itmList.DATACONTENT.length>0)
								$.each(itmList.DATACONTENT, function(itemkey, xElmnt) {   
								row++;
								var dataPrct={
									"TA_IDX0_0M_78M":
									parseFloat((xElmnt["RATIO_TA_IDX0"]??'0').replace(",",".")),
									"TA_IDX0_78M_234M":
									parseFloat((xElmnt["RATIO_TA_IDX1"]??'0').replace(",",".")),
									"TA_IDX0_234M_546M":
									parseFloat((xElmnt["RATIO_TA_IDX2"]??'0').replace(",",".")),
									"TA_IDX0_546M_1014M":
									parseFloat((xElmnt["RATIO_TA_IDX3"]??'0').replace(",",".")),
									"TA_IDX0_1014M_1950M":
									parseFloat((xElmnt["RATIO_TA_IDX4"]??'0').replace(",",".")),
									"TA_IDX0_1950M_3510M":
									parseFloat((xElmnt["RATIO_TA_IDX5"]??'0').replace(",",".")),
									"TA_IDX0_3510M_6630M":
									parseFloat((xElmnt["RATIO_TA_IDX6"]??'0').replace(",",".")),
									"TA_IDX0_6630M_14450M":
									parseFloat((xElmnt["RATIO_TA_IDX7"]??'0').replace(",",".")),
									"TA_IDX0_14450M_30030M":
									parseFloat((xElmnt["RATIO_TA_IDX8"]??'0').replace(",",".")),
									"TA_IDX0_30030M_53430M":
									parseFloat((xElmnt["RATIO_TA_IDX9"]??'0').replace(",",".")),
									"TA_IDX0_53430M_76830M":
									parseFloat((xElmnt["RATIO_TA_IDX1"]??'0').replace(",",".")),
									"TA_IDX0_M_76830M":
									parseFloat((xElmnt["RATIO_TA_IDX1"]??'0').replace(",","."))
								}
								var listSortDataPct = sortObjectEntries(dataPrct);
								// countTotal++;
								// sitesName.push(xElmnt["SITE"]);
								var textHTML = "data";
								let latPoint = xElmnt["latitud"];
								let lonPoint = xElmnt["longitud"];
								let AZIMUTH = parseFloat(xElmnt["azimuth"].replace(",","."));
								let LONGITUD = parseFloat(xElmnt["longitud"].replace(",","."));
								let LATITUD = parseFloat(xElmnt["latitud"].replace(",","."));
								let point = null;
								if(latPoint!=null&&lonPoint!=null){
									point = new google.maps.LatLng(latPoint.replace(',','.'),lonPoint.replace(',','.'));
								}

								var infowindowcontent = document.createElement('div');
								var strong = document.createElement('strong');

								strong.textContent = "UNKNOWN";

								infowindowcontent.appendChild(strong);
								infowindowcontent.appendChild(document.createElement('br'));
								var text = document.createElement('table');

								text.innerHTML = textHTML;
								infowindowcontent.appendChild(text);

								// var iconPosition=iconVerde;
								if(point!=null){

									let latBase_0 = LATITUD+(.030*Math.cos((3.141592653/180)*(AZIMUTH+30)))/111.325;
									let longBase_0 = LONGITUD+(.030*Math.sin((3.141592653/180)*(AZIMUTH+30)))/((Math.cos((3.141592653/180)*LATITUD)*40076)/360);
									let latBase_1 = LATITUD+(.030*Math.cos((3.141592653/180)*(AZIMUTH-30)))/111.325;
									let longBase_1 = LONGITUD+(.030*Math.sin((3.141592653/180)*(AZIMUTH-30)))/((Math.cos((3.141592653/180)*LATITUD)*40076)/360);


									let point0 = new google.maps.LatLng(latBase_0,longBase_0);
									let point1 = new google.maps.LatLng(latBase_1,longBase_1);

									let polLatLngbase = [
									point,
									point0,
									point1,
									point,
									];
									let polygonebase = new google.maps.Polygon({
									nameDataG: xElmnt["site_name"],
									nameData: xElmnt["sector_name"],
									path: polLatLngbase,
									fillColor:"#FFFF00",
									fillOpacity:0.7,
									strokeColor:"#000000",
									strokeWeight:2,
									strokeOpacity:1,
									map:mapApi,
									zindex:indexData+row+1000000,
									});
									polygonebase.addListener('click',function(){
									let sectorName = xElmnt["sector_name"];
									fnAddFncInterface(xElmnt,sectorName,1);
									// listingIntscView = intscSearch;
									});
									listPolyBase.push(polygonebase);
									let totalRadItm = 0;
									let pointsPrct = [];
									for(var radItm=0;radItm<listRad.length;radItm++){
									indexData++;
									var itmTAName = "TA_IDX0_"+listRadName[radItm];
									var itmRadName = "RATIO_TA_IDX"+radItm;
									var elmtRad = xElmnt[itmRadName]??"0";
									var color = colorPct[listSortDataPct.indexOf(itmTAName)];
									var fillOpacity = 0.6;
									var elementRad = parseFloat("0"+elmtRad.replace(",",".")).toFixed(8)*100;
									var elementRadTmp = elementRad;
									if(elementRadTmp==parseFloat('0')){
										for(var itmName=radItm;itmName<listRad.length;itmName++){
										elementRadTmp = xElmnt["RATIO_TA_IDX"+itmName]??"0";
										elementRadTmp = parseFloat("0"+elementRadTmp.replace(",",".")).toFixed(8)*100;
										if(elementRadTmp!=parseFloat('0'))
											break;
										}
									}
									if(elementRad==parseFloat('0')&&elementRadTmp==parseFloat('0')){
										break;
									}
									if(elementRad>=50){
										color="#FF0000";
									}else if(elementRad>20){
										color="#FF8000";
									}else if(elementRad>10){
										color="#00FF00";
									}else if(elementRad>1){
										color="#00FF00";
										fillOpacity=0.4;
									}else{
										color="#979A9A";
										fillOpacity=0.1;
									}
									let latIndx0 = LATITUD+(listRad[radItm]*Math.cos((Math.PI/180)*(AZIMUTH+30)))/111.325;
									let longIndx0 = (listRad[radItm]*Math.sin((Math.PI/180)*(AZIMUTH+30)));
									longIndx0 = longIndx0/((Math.cos((Math.PI/180)*LATITUD)*40076)/360)+LONGITUD;
									let latIndx1 = LATITUD+(listRad[radItm]*Math.cos((Math.PI/180)*(AZIMUTH-30)))/111.325;
									let longIndx1 = (listRad[radItm]*Math.sin((Math.PI/180)*(AZIMUTH-30)));
									longIndx1 = longIndx1/((Math.cos((Math.PI/180)*LATITUD)*40076)/360)+LONGITUD;
									let polLatLng = [
										new google.maps.LatLng(latIndx0,longIndx0),
										new google.maps.LatLng(latIndx1,longIndx1),
										point1,
										point0,
									];
									let point3=point0;
									let point4=point1;
									point0 = new google.maps.LatLng(latIndx0,longIndx0);
									point1 = new google.maps.LatLng(latIndx1,longIndx1);
									totalRadItm+=elementRad;
									if(totalRadItm>=80 && pointsPrct.length==0){
										pointsPrct = [
										point,
										point0,
										point1,
										point,
										];
										let polyline = new google.maps.Polyline({
										nameDataG: xElmnt["site_name"],
										nameData: xElmnt["sector_name"],
										path: pointsPrct,
										strokeColor: "#FF0000",
										strokeOpacity: 0.9,
										strokeWeight:3,
										map:mapApi,
										visible:false,
										geodesic:true,
										});
										listPolyline.push(polyline);
									}
									let opacityCov = 0.8-(0.060*(radItm+1));
									let polygone = new google.maps.Polygon({
										nameDataG: xElmnt["site_name"],
										nameData: xElmnt["sector_name"],
										level: radItm,
										levelPrct:elementRad,
										// title:(elementRad.toFixed(4)+"%"),
										path: polLatLng,
										fillColor: color,
										fillOpacity: fillOpacity,
										strokeColor: "#000000",
										strokeOpacity: 0.5,
										strokeWeight:1,
										map:mapApi,
										zindex:-indexData,
										visible:false,
										geodesic:true,
									});
									var infowindowcontent = document.createElement('div');
									var strong = document.createElement('strong');
									strong.textContent = "Test";
									infowindowcontent.appendChild(strong);
									var bounds = new google.maps.LatLngBounds();
									$.each(polLatLng,function (x,y) {
										bounds.extend(y);
									});
									let markerPrc = new google.maps.Marker({
										nameDataG: xElmnt["site_name"],
										nameData: xElmnt["sector_name"],
										level:radItm,
										map:mapApi,
										icon:"Itemprct",
										position:bounds.getCenter(),
										label:(elementRad.toFixed(4)==0?0:elementRad.toFixed(2))+"%",
										visible:false,
									});
									listMarkPorc.push(markerPrc);
									// polygone.addListener('click',function(event){
									//   infoWIndows.setContent(infowindowcontent);
									//   infoWIndows.setPosition(bounds.getCenter());
									//   infoWIndows.open(mapApi);
									// });
									listPolygone.push(polygone);
									}
									if (!markersPoint.includes(xElmnt["site_name"])) {
									markersPoint.push(xElmnt["site_name"]);
									let marker = new google.maps.Marker({
										map:mapApi,
										position:point,
										title: xElmnt["site_name"],
									});
									marker.addListener('click',function(event){
										// mapApi.setCenter(point);
										// mapApi.setZoom(18.5);
										let siteName = xElmnt["site_name"];
										fnAddFncInterface(xElmnt,siteName,2);
										// infoWIndows.setContent(infowindowcontent);
										// infoWIndows.open(mapApi,marker,infowindowcontent);
									});
									markerFilterList.push(marker);
									}
								}
								});

							}else{
								if((["6",6].includes(changeVwMap))){
									$.ajax({
									url:"{{ route('api.getMapPenales') }}",
									dataType: 'json',
									method: 'GET',
									data:{dpto:$(".slc-ubgDpto").val(),
										prov:$(".slc-ubgProv").val(),
										dist:$(".slc-ubgDist").val()},
									success:function (result) {
										var totalMSISDM=0;
										points = [];
										$.each(result,function (x,dataItm) {
										points.push({location:new google.maps.LatLng(dataItm.lat.replace(",","."),
											dataItm.lon.replace(",",".")),weight:dataItm.weight});
										totalMSISDM+=parseInt(1);
										// totalMSISDM+=parseInt(dataItm.dataItmMSISDM);
										});

										heatmap = new google.maps.visualization.HeatmapLayer({
										data:points,
										map:mapApi,
										radius:35,
										opacity:0.8,
										gradient:gradient,
										maxIntensity:.8,
										});
										listMap.push(heatmap);
									}
									// beforeSend:function () {
									// }
									});
								}
								if((["7",7].includes(changeVwMap))){
									anioSem = $(".slc-anioSemInterf").val().split("-");
									$.ajax({
										url:"{{ route('api.getMapTest7') }}",
										dataType: 'json',
										method: 'GET',
										data:{
											anio:anioSem[0],
											sem:anioSem[1],
											banda:$(".slc-banda").val(),
											dpto:$(".slc-ubgDpto").val(),
											prov:$(".slc-ubgProv").val(),
											dist:$(".slc-ubgDist").val()
										},
										success:function (result) {
											var totalMSISDM=0;
											points = [];
											$.each(result,function (x,dataItm) {
											points.push({location:new google.maps.LatLng(dataItm.lat.replace(",","."),
												dataItm.lon.replace(",",".")),weight:(dataItm.weight)*1});
											totalMSISDM+=parseInt(1);
											// totalMSISDM+=parseInt(dataItm.dataItmMSISDM);
											});

											heatmap = new google.maps.visualization.HeatmapLayer({
												data:points,
												map:mapApi,
												radius:25,
												opacity:0.8,
												gradient:gradient,
												maxIntensity:.8,
											});
											listMap.push(heatmap);
										}
										// beforeSend:function () {
										// }
									});
								}
							}
						}
						return false;            
					}
				});
			}

			function fnAddFncMapGraph(xElmnt,filtName,type){
				var tracingID=$("#tracingID").val();
				$.ajax({
					url:"{{ url('acceso_5g_movil_cobertura/21/6251/graphSurface') }}",
					data:{
						// cellname:xElmnt["id_celda"],
						cellname:xElmnt["cellname"],
						tracingID:tracingID
					},
					method: 'GET',
					dataType: 'html',
					success:function (resFilt) {
						var closeTab = $("#dataClose").html();
						$("#dataMapGraph").html('<button type="button" class="close closeTab" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+resFilt);
						$("#dataMapGraph").show();
						$("#dataMapGraph .closeTab").click(function () {
							$("#dataMapGraph").hide();
						});
					}
				});
			}
			function fnAddFncInterface(xElmnt,filtName,type){
				namePar = 'nameData';
				if(type==2)
					namePar = 'nameDataG';
				let listData = arrayColumn(listPolygone,namePar);
				let listPrct = arrayColumn(listMarkPorc,namePar);
				let listLine = arrayColumn(listPolyline,namePar);
				// let listIntsc = arrayColumn(drawingIntscList,'iniNameData')
				// listIntsc=listIntsc.concat(arrayColumn(drawingIntscList,'lastNameData'));
				let keysSearch = [],prctSearch = [],lineSearch=[],intscSearch=[];
				for (var dataKey=0;dataKey<listData.length;dataKey++){
					if(listData[dataKey]==filtName)
						keysSearch.push(dataKey);
				}
				for (var dataKeyPrct=0;dataKeyPrct<listPrct.length;dataKeyPrct++){
					if(listPrct[dataKeyPrct]==filtName)
						prctSearch.push(dataKeyPrct);
				}
				for (var dataKeyLine=0;dataKeyLine<listLine.length;dataKeyLine++){
					if(listLine[dataKeyLine]==filtName)
						lineSearch.push(dataKeyLine);
				}
				// for (var drawKeyIntsc=0;drawKeyIntsc<listIntsc.length;drawKeyIntsc++){
				//   if(listIntsc[drawKeyIntsc]==filtName)
				//     intscSearch.push(drawKeyIntsc);
				// }
				// clearPolygone();
				$.each(keysSearch,function (x,y) {
					if(listPolygone[y].getVisible()){
						listPolygone[y].setVisible(false);
						if ($("#dataMapInd").attr("data-sn")==filtName&&type==1){
							$("#dataMapInd").hide();
						}
					}
					else{
						listPolygone[y].setVisible(true);
						if(type==1)
							viewDataMap(xElmnt);
					}
				});
				$.each(prctSearch,function (x,y) {
					if(listMarkPorc[y].getVisible())
						listMarkPorc[y].setVisible(false);
					else
						listMarkPorc[y].setVisible(true);                      
				});
				$.each(lineSearch,function (x,y) {
					if(listPolyline[y].getVisible())
						listPolyline[y].setVisible(false);
					else
						listPolyline[y].setVisible(true);                      
				});
				// $.each(intscSearch,function (x,y) {
				//   if(drawingIntscList[y].getVisible())
				//     drawingIntscList[y].setVisible(false);
				//   else
				//     drawingIntscList[y].setVisible(true);                      
				// });
				listPolygoneView = listPolygoneView.concat(keysSearch).unique();
				listMarkPrctView = listMarkPrctView.concat(prctSearch).unique();
				listPolylineView = listPolylineView.concat(lineSearch).unique();
			}

			function filtIntersections(){
				var data = {};
				for(var i=0;i<listPolygone.length;i++){
					var polIni = listPolygone[i];
					data.iniNameDataG = polIni.nameDataG;
					data.iniNameData = polIni.nameData;
					data.iniLevel = polIni.level;
					data.iniLevelPrct = polIni.levelPrct;
					polIni = createJstsPolygon(polIni);
					for(var j=0;j<listPolygone.length;j++){
						if (i==j)
							continue;
						var polLast = listPolygone[j];
						data.lastNameDataG = polLast.nameDataG;
						data.lastNameData = polLast.nameData;
						data.lastLevel = polLast.level;
						data.lastLevelPrct = polLast.levelPrct;
						data.lastLevelPrct = polLast.levelPrct;
						data.lastLevelPrct = polLast.levelPrct;
						polLast = createJstsPolygon(polLast);
						var intersectionPolys = polIni.intersection(polLast);
						if (intersectionPolys.getCoordinates().length>2)
							drawIntersectionArea(mapApi,intersectionPolys,data);
						// break;
					}
					// break;
				}
			}

			function filterSite(){
				var selSites = $(".slc-site").val();
				// clearPolygone();
				let listData = arrayColumn(listPolygone,'nameDataG');
				let listPrct = arrayColumn(listMarkPorc,'nameDataG');
				let listLine = arrayColumn(listPolyline,'nameDataG');
				
				// let listIntsc = arrayColumn(drawingIntscList,'iniNameDataG').concat(arrayColumn(drawingIntscList,'lastNameDataG'));
				let keysSearch = [];
				let prctSearch = [];
				let lineSearch = [];
				// let intscSearch = [];
				if(selSites!=null&&selSites.length>0){
					for (var dataKey=0;dataKey<listData.length;dataKey++){
						if(selSites.includes(listData[dataKey]))
							keysSearch.push(dataKey);
					}
					$.each(keysSearch,function (x,y) {
						if(!listPolygone[y].getVisible())
							listPolygone[y].setVisible(true);                      
					});
					for (var dataKeyPrct=0;dataKeyPrct<listPrct.length;dataKeyPrct++){
						if(selSites.includes(listPrct[dataKeyPrct]))
							prctSearch.push(dataKeyPrct);
					}
					$.each(prctSearch,function (x,y) {
						if(!listMarkPorc[y].getVisible())
							listMarkPorc[y].setVisible(true);                      
					});
					for (var dataKeyLine=0;dataKeyLine<listLine.length;dataKeyLine++){
						if(selSites.includes(listLine[dataKeyLine]))
							lineSearch.push(dataKeyLine);
					}
					$.each(lineSearch,function (x,y) {
						if(!listPolyline[y].getVisible())
							listPolyline[y].setVisible(true);                      
					});
					listPolygoneView = listPolygoneView.concat(keysSearch).unique();
					listMarkPrctView = listMarkPrctView.concat(prctSearch).unique();
					listPolylineView = listPolylineView.concat(lineSearch).unique();
					// listingIntscView = intscSearch;
				}
				closeMenuSel();
			}

			function VerGraph() {
				const _id_tracing = document.querySelector("#tracingID").value;
				const _capa_id = document.querySelector("#selectCapa").value;
				const _value = document.querySelector("#fildValStr").value;

				openWin(base_url+"getGraph/"+tracingID+"/"+slcMap+"/"+fildVal,580,1100);
				submenuGraph();
				returnMenu();
			}

			function viewDataMap(dt) {
					var tblhtml = "<table class='table'>";
					var btn = "";
					var params = "";
					var filts = [];
					var changeVwMap=$(".slc-changeVwMap").val();
					if(["3","4","5"].includes(changeVwMap)){
						data = {
							tracingID:$("#tracingID").val(),
							menuID:$("#menuID").val(),
							cellname:dt["sector_name"],
							changeVwMap:changeVwMap,
							siteName:dt["enodob_name"],
							}	
					}else{
						data = {
							tracingID:$("#tracingID").val(),
							menuID:$("#menuID").val(),
							cellname:dt["cellname"],
							changeVwMap:changeVwMap,
							}
					}
					$.ajax({
						url:"{{ url('acceso_5g_movil_cobertura/21/6251/getDataMapCoverageCellName') }}",
						data:data,
						dataType: 'json',
						type:'GET',
						success:function (result) {
							$.each(result.Content,function (x,y) {
								$.each(y,function (arg,val) {
									var itmStr=arg;
									if(typeof result.fldList==='object'&&Object.keys(result.fldList).length>0){
										if(result.fldList.hasOwnProperty(arg)){
											itmStr=result.fldList[arg];
											tblhtml += "<tr><th><b>"+itmStr+"</b></th><td>"+(val==null?'':val)+"</td></tr>";
										}
									}else
										tblhtml += "<tr><th><b>"+itmStr+"</b></th><td>"+(val==null?'':val)+"</td></tr>";
								})
							})
							tblhtml += "</table>";
							tblhtml += "</table>";
							var closeTab = $("#dataClose").html();
							$("#dataMapInd").html('<button type="button" class="close closeTab" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+tblhtml);
							$("#dataMapInd").attr("data-sn",dt["cellname"]);
							$("#dataMapInd").show();
							$("#dataMapInd .closeTab").click(function () {
								$("#dataMapInd").hide();
								// clearPolygone();
							});
							$("#spinnerData").hide();
							$("#dataMap").html(tblhtml);
						}
					});
				
			}

			function changeDpto() {
				var CodeUbgSlc = $(".slc-ubgDpto").val();
				if(CodeUbgSlc!='0'){
					$(".ubgProv").show();
					$.ajax({
						url:"{{ route('api.getDataUbgProvByDptoCode') }}",
						type:'GET',
						data:"CodeUbgSlc="+CodeUbgSlc,
						dataType: 'json',
						success:function (resFilt) {
							console.log(resFilt);
							var list = "<option selected value='0'>---Provincia---</option>"
							$.each(resFilt,function (x,y) {
								list +="<option value='"+y.CODE+"'>"+y.PROVINCIA+"</option>";              
							})
							$(".slc-ubgProv").html(list);
						}
					});
				}else{
					$(".ubgProv").hide();
					$(".ubgDist").hide();
					$(".slc-ubgProv").html("<option selected value='0'>---Provincia---</option>");
					$(".slc-ubgDist").html("<option selected value='0'>---Distrito---</option>");
				}
			}

			function changeProv() {
				var CodeUbgSlc = $(".slc-ubgProv").val();
				//var filtTypeMap = $(".slc-mapGran").val();
				if(CodeUbgSlc!='0'){
					$(".ubgDist").show();
					$.ajax({
						url:"{{ route('api.getDataUbgDistByProvCode') }}",
						type:'GET',
						data:"CodeUbgSlc="+CodeUbgSlc,
						dataType: 'json',
						success:function (resFilt) {
							var list = "<option selected value='0'>---Distrito---</option>"
							$.each(resFilt,function (x,y) {
								list +="<option value='"+y.CODE+"'>"+y.DISTRITO+"</option>";              
							})
							$(".slc-ubgDist").html(list);
						}
					});
				}else{
					$(".ubgDist").hide();
					$(".slc-ubgDist").html("<option selected value='0'>---Distrito---</option>");
				}
			}

			function clearPolygone() {
				$("#dataMapGraph").hide();
				$.each(listPolygoneView,function (x,y) {
					listPolygone[y].setVisible(false);
				});
				$.each(listMarkPrctView,function (x,y) {
					listMarkPorc[y].setVisible(false);
				});
				$.each(listPolylineView,function (x,y) {
					listPolyline[y].setVisible(false);
				});
				listPolygoneView=[],listMarkPrctView=[],listPolylineView=[];
				$("#dataMapInd").hide();
				// $.each(listingIntscView,function (x,y) {
				//   drawingIntscList[y].setVisible(false);
				// });
			}
			function clearListVw() {
				while(markerFilterList.length){markerFilterList.pop().setMap(null);}
				while(listPolygone.length){listPolygone.pop().setMap(null);}
				while(listPolyBase.length){listPolyBase.pop().setMap(null);}
				while(markersPoint.length){markersPoint.pop();}
				while(listMarkPorc.length){listMarkPorc.pop();}
				while(listPolyline.length){listPolyline.pop();}
			}

			function sortObjectEntries(obj) {
				return Object.entries(obj).sort((a,b)=>b[1]-a[1]).map(el=>el[0]);
			}

			function drawIntersectionArea(map, polygon,detail) {
				var coords = polygon.getCoordinates().map(function (coord) {
					return {lat:coord.x, lng:coord.y};
				});
				var intersectionArea = new google.maps.Polygon({
					paths: coords,
					strokeColor: "#000000",
					strokeOpacity: 0.5,
					strokeWeight:1,
					fillColor: "#FF0000",
					fillOpacity: 0.8,
					map:map,
					visible:false,
					iniNameDataG:detail.iniNameDataG,
					iniNameData:detail.iniNameData,
					iniLevel:detail.iniLevel,
					iniLevelPrct:detail.iniLevelPrct,
					lastNameDataG:detail.lastNameDataG,
					lastNameData:detail.lastNameData,
					lastLevel:detail.lastLevel,
					lastLevelPrct:detail.lastLevelPrct,
				});
				drawingIntscList.push(intersectionArea);
			}

			function createJstsPolygon(polygon) {
				var path = polygon.getPath();
				var coordinates = path.getArray().map(function name(coord) {
					return new jsts.geom.Coordinate(coord.lat(),coord.lng());
				})
				coordinates.push(coordinates[0]);
				var shell = geometryFactory.createLinearRing(coordinates);
				return geometryFactory.createPolygon(shell);
			}

			function arrayColumn(array, columnName) {
				if (array.length > 0)
					return array.map(function (value, index) {
						return value[columnName];
					});
				else
					return [];
			}

			Array.prototype.unique = function () {
				var a = this.concat();
				for (var i = 0; i < a.length; i++) {
					for (var j = i + 1; j < a.length; j++) {
						if (a[i] === a[j])
							a.splice(j--, 1);
					}
				}
				return a;
			}

			Date.prototype.addHours = function (h) {
				this.setTime(this.getTime() + (h * 60 * 60 * 1000));
				return this;
			}
		</script>
		<div id="legend" class="card border-secondary mb-5" style="display:none">
			<div class="card-header" style="font-weight: bold;">Leyenda</div>
			<table class="table data"><tbody>
				<tr><td><label style="background-color:#0000FF;">&nbsp;</label></td><td>1.CAPACIDAD OK</td></tr>
				<tr><td><label style="background-color:#00FF00;">&nbsp;</label></td><td>2.CAPACIDAD TEMPORAL Y/O SUBUTILIZADO</td></tr>
				<tr><td><label style="background-color:#FFF000;">&nbsp;</label></td><td>3.OPTIMIZACION</td></tr>
				<tr><td><label style="background-color:#FF0000;">&nbsp;</label></td><td>4.CAPACIDAD INSUFICIENTE</td></tr>
				<tr><td><label style="background-color:#800000;">&nbsp;</label></td><td>5.CAPACIDAD INSUFICIENTE – REINCIDENTE ( CRITICO >= 5SEM)</td></tr>
			</tbody></table>
		</div>
		</div>
		<div id="dataClose" style="display: none; z-index: 1;"><span class="closeTab"><b>x</b></span></div>
		<div id="dataMap" style="display: none"></div>
		<div id="dataMapGraph" style="display: none">dataMapGraph</div>
<!-- <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script> -->
<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&callback=initMap&libraries=&v=weekly" async></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCA4qFt-RRmlltEMiFhNowpPaLxekroPgI&libraries=visualization&v=weekly" async></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="{{asset('css/chosen.css')}}">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{asset('js/chosen.jquery.js')}}"></script>
<script type="text/javascript" src="{{asset('libs/moment/moment.min.js')}}"></script>
@endsection