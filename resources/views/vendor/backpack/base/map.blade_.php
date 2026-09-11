@extends(backpack_view('blank'))

@php
$poliubidep = array();
$poliubiprov = array();
$poliubidist = array();

foreach($ubigeos as $ubigeo){
  $poliubidep[] = substr($ubigeo,0,2);
  $poliubiprov[] = substr($ubigeo,0,4);
  $poliubidist[] = substr($ubigeo,0,6);
}
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
<div class="card border-secondary mb-3">
  <button type="button" id="closedata" class="close" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <div class="card-header" id="title" style="font-weight: bold;"></div>
  <div class="card-body">
    <table class="table" id="tabledatamap">
    <tbody>
    </tbody>
  </table>
  </div>
</div>
<div class="card border-secondary mb-4">
  <div class="card-header" style="font-weight: bold;">Leyenda Poligonos</div>
  <div class="card-body">
    <table class="table">
      <tbody>
        <tr><td><label style="background-color:#FF0000;">&nbsp;</label></td><td>0 > KPI < 90</td></tr>
        <tr><td><label style="background-color:#e8e8e8;">&nbsp;</label></td><td>Sin mediciones</td></tr>
        <tr><td><label style="background-color:green;">&nbsp;</label></td><td>KPI >= 90</td></tr>
      </tbody>
    </table>
  </div>
</div>
<div class="card border-secondary mb-5">
  <div class="card-header" style="font-weight: bold;">Leyenda Puntos</div>
  <div class="card-body">
    <table class="table">
      <tbody>
        <tr>
          <td>Mal</td>
          <td>Umbral CVM</td>
          <td>Bien</td>
        </tr>
        <tr>
        <td><label style="background-color:#f5721a;">&nbsp;</label></td>
          <td>4G TH DL < 2</td>
        <td><label style="background-color:blue;">&nbsp;</label></td>
        </tr>
        <tr class="hideDrive">
          <td><label style="background-color:#f5721a;">&nbsp;</label></td>
          <td>4G TH UL < 0.4</td>
          <td><label style="background-color:blue;">&nbsp;</label></td>
        </tr>
        <tr>
          <td><label style="background-color:#f5721a;">&nbsp;</label></td>
          <td>3G TH DL < 0.4</td>
          <td><label style="background-color:blue;">&nbsp;</label></td>
        </tr>
        <tr class="hideDrive">
          <td><label style="background-color:#f5721a;">&nbsp;</label></td>
          <td>3G TH UL < 0.082</td>
          <td><label style="background-color:blue;">&nbsp;</label></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
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
</style>
@endsection

@section('content')
<form>
  <div class="form-row">
    <div class="col">
      <label for="selectSem">Semestre:</label>
      <select class="form-control" id="selectSem">
        <option value="0" @if(app('request')->input('semestre') == "0") selected @endif>Todos</option>
        <option value="1" @if(app('request')->input('semestre') == "1") selected @endif>Semestre Anterior</option>
        <option value="2" @if(app('request')->input('semestre') == "2") selected @endif>Semestre Actual</option>
      </select>
    </div>
    <div class="col">
      <label for="selectMap">Mapa:</label>
      <select class="form-control" id="selectMap">
        <option value="cvm">CVM</option>
        <option value="drivetest">Drive Test</option>
      </select>
    </div>
    <div class="col">
      <label for="selectCapa">Vista:</label>
      <select class="form-control" id="selectCapa">
          <option value="555" data-val="{{ asset('map/peru_regiones.json') }}">Departamento</option>        
          <option value="556" data-val="{{ asset('map/peru_provincias.json') }}">Provincia</option>        
          <option value="557" data-val="{{ asset('map/peru_distritos.json') }}">Distrito</option>
      </select>
    </div>
    <div class="col" id="searchUbigeo">
      <label for="ubigeo">Ubigeo:</label>
      <div class="input-group">
        <input type="text" id="ubigeo" name="ubigeo" class="form-control" placeholder="ubigeo">
        <div class="input-group-append">
          <button id="btnUbigeo" class="btn btn-secondary" type="button">
            <i class="las la-search"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="col">
      <label for="selectDep">Departamento:</label>
      <select class="form-control" id="selectDep">
        <option value="0">Seleccione departamento</option>
        @foreach ($departamentos as $departamento)
        <option value="{{$departamento->departamento}}">{{$departamento->departamento}}</option>
        @endforeach
      </select>
    </div>
    <div class="col" id="colProv" style="display: none;">
      <label for="selectProv">Provincia:</label>
      <select class="form-control" id="selectProv" disabled>
        <option value="0">Seleccione Provincia</option>
      </select>
    </div>
    <div class="col" id="colDist" style="display: none;">
      <label for="selectDist">Distrito:</label>
      <select class="form-control" id="selectDist" disabled>
        <option value="0">Seleccione Distrito</option>
      </select>
    </div>
    <div class="col" id="colKpi" >
      <label for="selectCcpp">KPI:</label>
      <select class="form-control" id="selectCcpp">
        <option value="0">KPI</option>
      </select>
    </div>
    <div class="col" id="colJob" style="display: none;">
      <label for="selectJob">JobName:</label>
      <select class="form-control" id="selectJob">
        <option value="0" selected>Seleccione JobName</option>
        @foreach ($jobs as $job)
        <option value="{{$job->job_name}}">{{$job->job_name}}</option>
        @endforeach
      </select>
    </div>
    <div class="col" id="searchIdclient" style="display: none;">
      <label for="idClient">IdClient:</label>
      <div class="input-group">
        <input type="text" id="idClient" name="idClient" class="form-control" placeholder="Id_Client">
        <div class="input-group-append">
          <button id="btnidClient" class="btn btn-secondary" type="button">
            <i class="las la-search"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="form-row align-items-center">
    <div class="col-auto" id="colExport">
      <label for="idClient">Exportar:</label>
      <div class="input-group">
        <button id="btnExportPoligons" class="btn btn-danger" type="button" onclick="window.location.href='poligonos/export'">Poligonos</button>
        <button id="btnExportPoints" class="btn btn-danger" type="button" onclick="window.location.href='puntos/export'">Puntos</button>
      </div>
    </div>
    <div class="col-auto" id="colSites">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="sitesMap4g">
        <label class="form-check-label" for="sitesMap">Ver sitios 4G</label>
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="sitesMap3g">
        <label class="form-check-label" for="sitesMap">Ver sitios 3G</label>
      </div>
    </div>
  </div>
</form>
<br>
<!--The div element for the map -->
<div id="map"></div>
@endsection

@section('after_scripts')
<script>
    var poliubidep = <?php echo json_encode($poliubidep) ?>;
    var poliubidist = <?php echo json_encode($poliubidist) ?>;
    var poliubiprov = <?php echo json_encode($poliubiprov) ?>;
    var ubigeocrit = <?php echo json_encode($ubigeos_crit) ?>;
    var poligonos = <?php echo json_encode($poligonos) ?>;
    var jspunto = <?php echo json_encode($puntos) ?>;
    var fillColorBk,opt,clear=0,map,icons,marker,oms,loadedsites4g=0,loadedsites3g=0;
    var url = "{{ asset('map/peru_regiones.json') }}";
    var ubidep=[],ubiprov= [],ubidist= [],ubiccpp= [],ccpp= [],polyccpp = [],markers = [],sites = [],feature = [],listPolyBase=[];
    var slcubg = "555";
    let infoWindow;

    $(document).ready(function () {

      $("#spinnerData").show();

      $.each( ubigeocrit, function( key, value ) {
        ubidep.push(value.substr(0,2));
        ubiprov.push(value.substr(0,4));
        ubidist.push(value.substr(0,6));
      });

      $("#selectCapa").change(function () {
        $("#spinnerData").show();
        url = $("option:selected",this).attr("data-val");
        slcubg = $(this).val();
        if(slcubg == "555"){
          $("#colDist").hide();
          $("#colProv").hide();
        }
        if(slcubg == "556"){
          $("#colDist").hide();
          $("#colProv").show();
        }
        if(slcubg == "557"){
          $("#colProv").show();
          $("#colDist").show();
        }
        //$("#mapaurl").val(url);
        initMap();
        //showSlcByFilterMap();
        //clearUbg();
      });

      $("#selectMap").change(function () {
        slcmap = $(this).val();
        param = $("#selectSem").val();
        $(".card.mb-3").hide();
        if(slcmap == 'drivetest'){
          loadDriveTest(param);
        }else{
          window.location.href= "http://172.17.27.157/portalregulatorio/mapa?semestre="+param;  
        }
      });

      $("#selectSem").change(function () {
        param = $(this).val();
        slcmap = $("#selectMap").val();
        if(slcmap == "drivetest"){
          loadDriveTest(param);
        }else{
          window.location.href= "http://172.17.27.157/portalregulatorio/mapa?semestre="+param;
        }
      });


      $("#selectJob").change(function () {
        filterMarkers($("#idClient").val(), $(this).val());
      });

      $("#btnidClient").on("click",function(){
        filterMarkers($("#idClient").val(), $("#selectJob").val());
      });

      $('#idClient').keydown(function(e){
          if(e.keyCode == 13)
          {
            e.preventDefault();
            filterMarkers($("#idClient").val(), $("#selectJob").val());
            return false;
          }
      });

      $("#btnUbigeo").on("click",function(){
        loadMapUbigeo();
      });

      $('#ubigeo').keydown(function(e){
          if(e.keyCode == 13)
          {
            e.preventDefault();
            loadMapUbigeo();
            return false;
          }
      });

      $("#selectDep").change(function () {
        changeDpto();
      });

      $("#selectProv").change(function () {
        changeProv();
      });

      $("#selectDist").change(function () {
        var CodeUbgDist = $(this).val();
        var CodeUbgProv = $("#selectProv").val();
        if(CodeUbgDist!='0'){
          fillColorByUbg(CodeUbgDist,3);
        }else{
          fillColorByUbg(CodeUbgProv,2);
        }
      });

      $("#closedata").click(function() {
        $(".card.mb-3").hide();
      });

      $("#sitesMap4g").on( 'change', function() {
        if( $(this).is(':checked') ) {
            // Hacer algo si el checkbox ha sido seleccionado
            if(loadedsites4g == 0){
              $.ajax({
              url: "{{ route('api.mapData') }}",
              type: 'GET',
              dataType: 'json',
              data: "type=sitesmap4g",
              beforeSend:function () {
                $("#spinnerData").show();
              },
              success: function(data) {      
                $.each(data.data,function (key, val ) {
                  var featur = [];
                  featur.position = new google.maps.LatLng(val.lat, val.lon);
                  featur.lat = parseFloat(val.lat);
                  featur.lon = parseFloat(val.lon);
                  featur.sector_name = val.sector_name;
                  featur.site_address = val.site_address;
                  featur.azimuth = parseFloat(val.azimuth);
                  featur.tech = "4g";
                  featur.height = val.height;
                  featur.electrical_tilt = val.electrical_tilt;
                  featur.mechanical_tilt = val.mechanical_tilt;
                  featur.hbw = val.hbw;
                  featur.ranking = val.ranking;
                  addMarkerSites(featur,icons['torre'].icon);
                  addMarkerSitesAngle(featur);
                });
                setMapOnSites(map,'4g')
              },
              complete: function () {
                $("#spinnerData").hide();
                loadedsites4g = 1;
              },
            });
          }else{
            setMapOnSites(map,'4g')
          }
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            clearMarkersSites('4g')
        }
    });

    $("#sitesMap3g").on( 'change', function() {
        if( $(this).is(':checked') ) {
            // Hacer algo si el checkbox ha sido seleccionado
            if(loadedsites3g == 0){
              $.ajax({
              url: "{{ route('api.mapData') }}",
              type: 'GET',
              dataType: 'json',
              data: "type=sitesmap3g",
              beforeSend:function () {
                $("#spinnerData").show();
              },
              success: function(data) {      
                $.each(data.data,function (key, val ) {
                  var featur = [];
                  featur.position = new google.maps.LatLng(val.lat, val.lon);
                  featur.lat = parseFloat(val.lat);
                  featur.lon = parseFloat(val.lon);
                  featur.sector_name = val.sector_name;
                  featur.site_address = val.site_address;
                  featur.azimuth = parseFloat(val.azimuth);
                  featur.tech = "3g";
                  featur.height = val.height;
                  featur.electrical_tilt = val.electrical_tilt;
                  featur.mechanical_tilt = val.mechanical_tilt;
                  featur.hbw = val.hbw;
                  featur.ranking = val.ranking;
                  addMarkerSites(featur,icons['torre'].icon);
                  addMarkerSitesAngle(featur);
                });
                setMapOnSites(map,'3g')
              },
              complete: function () {
                $("#spinnerData").hide();
                loadedsites3g = 1;
              },
            });
          }else{
            setMapOnSites(map,'3g')
          }
        } else {
            // Hacer algo si el checkbox ha sido deseleccionado
            clearMarkersSites('3g')
        }
    });

    });

    function loadDriveTest(param){
      $.ajax({
            url: "{{ route('api.mapData') }}",
            type: 'GET',
            dataType: 'json',
            data: "type=drivetest&semestre="+param,
            beforeSend:function () {
              $("#spinnerData").show();
            },
            success: function(data) {
              $("#colKpi").hide();    
              $(".hideDrive").hide();          
              deleteMarkers();
              $.each(data.data,function (key, val ) {
                feature = [];
                feature.position = new google.maps.LatLng(val.latitude, val.longitude);
                feature.id = val.id;
                feature.idclient = val.idclient;
                feature.job_name = val.job_name;
                if(val.tech_dl == "lte"){
                  if(val.dl < 2048){
                    medicion = "mala_medicion";
                  }else{
                    medicion = "buena_medicion";
                  }
                }else{
                  if(val.dl < 410){
                    medicion = "mala_medicion";
                  }else{
                    medicion = "buena_medicion";
                  }
                } 
                addMarkerDrive(feature,medicion);
              });
              filterMarkers('','0')
            },
            complete: function () {
              $("#colKpi").hide(); 
              $("#colJob").show(); 
              $("#searchIdclient").show();             
              $("#spinnerData").hide();
            },
          });
    }

    function loadMapUbigeo() {
      $.ajax({
          url: "{{ route('api.mapData') }}",
          type: 'GET',
          dataType: 'json',
          data: "type=loadMapUbigeo&param="+$("#ubigeo").val(),
          beforeSend:function () {
            $("#spinnerData").show();
          },
          success: function(data) { 
            val = data.data;
            console.log(val);
            if(val != 0 || val.length > 0){
              latit = parseFloat(val[0].latitude.replace(",", "."));
              longit = parseFloat(val[0].longitude.replace(",", "."));
              map.setCenter({lat:latit, lng:longit});
              map.setZoom(14);
            }else{
              Noty.overrideDefaults({
                layout: 'topRight',
                theme: 'backstrap',
                timeout: 2500,
                closeWith: ['click', 'button'],
              });
              new Noty({type: "warning", text: "No se encontro o no existe el ubigeo"}).show()
            }
          },
          complete: function () {
            $("#spinnerData").hide();
          },
        });      
    }

    function changeDpto() {
      var CodeUbgDep = $("#selectDep").val();
      //var filtTypeMap = $(".slc-mapGran").val();
      if(CodeUbgDep!='0'){
        $.ajax({
          url: "{{ route('api.mapData') }}",
          type: 'GET',
          dataType: 'json',
          data: "param="+CodeUbgDep+"&type=selectDep",
          beforeSend:function () {
            $("#spinnerData").show();
          },
          success: function(data) {
            var list = "<option selected value='0'>Seleccione Provincia</option>"
            $.each(data.data,function (x,y) {
              list +="<option value='"+y.provincia+"'>"+y.provincia+"</option>";              
            })
            $("#selectProv").html(list);
            $("#selectProv").removeAttr("disabled")
          },
          complete: function () {
              $("#spinnerData").hide();
          },
        });

        // if(filtTypeMap=="555"){
          fillColorByUbg(CodeUbgDep,1);
        // }
      }else{
        fillColorByUbgInit();
        $("#selectProv").html("<option selected value='0'>Seleccione Provincia</option>");
        $("#selectDist").html("<option selected value='0'>Seleccione Distrito</option>");
        //fillColorByUbg('',0,$(".slc-crit").val());
      }
    }

    function changeProv() {
      var CodeUbgDep = $("#selectDep").val();
      var CodeUbgProv = $("#selectProv").val();
      //var filtTypeMap = $(".slc-mapGran").val();
      if(CodeUbgProv!='0'){
        $.ajax({
          url: "{{ route('api.mapData') }}",
          type: 'GET',
          dataType: 'json',
          data: "param1="+CodeUbgDep+"&param2="+CodeUbgProv+"&type=selectProv",
          beforeSend:function () {
            $("#spinnerData").show();
          },
          success: function(data) {
            var list = "<option selected value='0'>Seleccione Distrito</option>"
            $.each(data.data,function (x,y) {
              list +="<option value='"+y.distrito+"'>"+y.distrito+"</option>";              
            })
            $("#selectDist").html(list);
            $("#selectDist").removeAttr("disabled")
          },
          complete: function () {
              $("#spinnerData").hide();
          }
        });

        // if(filtTypeMap=="555"){
          fillColorByUbg(CodeUbgProv,2);
        // }
      }else{
        fillColorByUbg(CodeUbgDep,1);
        //$(".slc-ubgProv").html("<option selected value='0'>---Provincia---</option>");
        $("#selectDist").html("<option selected value='0'>Seleccione Distrito</option>");
        //fillColorByUbg('',0,$(".slc-crit").val());
      }
    }

    function fillColorByUbg(code,typeFilt=-1,crit="0") {  
      var newColor;
      var typeMap,typemapB;
      map.data.setStyle((feature)=>{
        var fillColorBk ="#fff";
        //console.log(feature);
        param = typeof feature.i === "undefined" ? feature.h : feature.i;
        if(slcubg=="555"){
          rowEx = typeof param.name_dep === "undefined" ? "00" : param.name_dep;
          typeMap = ubidep.includes(param.cod_dep);
          typemapB = poliubidep.includes(param.cod_dep);
        }
        if(slcubg=="556"){
          if(typeFilt == 1){
            rowEx = typeof param.NOMBDEP === "undefined" ? param.FIRST_NOMB : param.NOMBDEP;
          }
          if(typeFilt == 2){
            rowEx = typeof param.NOMBPROV === "undefined" ? "00" : param.NOMBPROV;
          }
          typeMap = ubiprov.includes(param.FIRST_IDPR);
          typemapB = poliubiprov.includes(param.FIRST_IDPR);
        }
        if(slcubg=="557"){
          if(typeFilt == 1){
            rowEx = typeof param.NOMBDEP === "undefined" ? param.FIRST_NOMB : param.NOMBDEP;
            //console.log(rowEx);
          }
          if(typeFilt == 2){
            rowEx = typeof param.NOMBPROV === "undefined" ? "00" : param.NOMBPROV;
          }
          if(typeFilt == 3){
            rowEx = typeof param.NOMBDIST === "undefined" ? "00" : param.NOMBDIST;
          }
          typeMap = ubidist.includes(param.IDDIST);
          typemapB = poliubidist.includes(param.IDDIST);
        }
        if(code==rowEx){
          if(typeMap){
            fillColorBk ="red";
          }else{
            if(typemapB){
              fillColorBk ="green";
            }
          }
          fillOpacityBK = 0.5;
          zIndexBK = 1
          data = {
            fillColor: fillColorBk,
            fillOpacity: fillOpacityBK,
            strokeColor: '#464646',
            strokeWeight: 1,
            zIndex: zIndexBK
          }
        }else{
          data = {
            visibility:"off",
            fillColor:"#000",
            strokeWeight:0.3
          };
        }
        return data;
      });
    }

    function fillColorByUbgInit() {
      map.data.setStyle((feature)=>{ 
            //console.log(feature);
            param = typeof feature.i === "undefined" ? feature.h : feature.i;
            rowEx = typeof param.cod_dep === "undefined" ? "00" : param.cod_dep;
            if(poliubidep.includes(rowEx) && slcubg=="555"){
              if(ubidep.includes(rowEx)){
                fillColorBk ="red";
              }else{
                fillColorBk ="green";
              }
              fillOpacityBK = 0.5;
              zIndexBK = 1
            }else{
              rowEx = typeof param.FIRST_IDPR === "undefined" ? "00" : param.FIRST_IDPR;
              if(poliubiprov.includes(rowEx) && slcubg=="556"){
                if(ubiprov.includes(rowEx)){
                  fillColorBk ="red";
                }else{
                  fillColorBk ="green";
                }
                fillOpacityBK = 0.5;
                zIndexBK = 1
              }else{
                rowEx = typeof param.IDDIST === "undefined" ? "00" : param.IDDIST;
                if(poliubidist.includes(rowEx) && slcubg=="557"){
                  if(ubidist.includes(rowEx)){
                    fillColorBk ="red";
                  }else{
                    fillColorBk ="green";
                  }
                  fillOpacityBK = 0.5;
                  zIndexBK = 1
                }else{
                  if(typeof param.color === "undefined"){
                    fillColorBk ="#e8e8e8";
                    fillOpacityBK = 0.5;
                    zIndexBK = 1
                  }else{
                    fillColorBk = param.color;
                    fillOpacityBK = 0.7;
                    zIndexBK = 2;
                  }
                }
              }
            }
            return{
              fillColor: fillColorBk,
              fillOpacity: fillOpacityBK,
              strokeColor: '#464646',
              strokeWeight: 1,
              zIndex: zIndexBK
            };
          });
      }

    // Initialize and add the map
    function initMap() {

        // The location of lima
        const lima = {
            lat: -10.0431805,
            lng: -74.0282364
        };

        // The map, centered at lima
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5.8,
            center: lima,
            minZoom: 4,
        });

        
        oms = new OverlappingMarkerSpiderfier(map, {
          markersWontMove: true,
          markersWontHide: true,
          basicFormatEvents: true,
          nearbyDistance:1,
          keepSpiderfied:true
        });
          
        const iconBase = "images/";

        icons = {
          mala_medicion: {
            icon: iconBase + "naranja.png",
          },
          buena_medicion: {
            icon: iconBase + "azul.png",
          },
          torre: {
            icon: iconBase + "antena_negro.png",
          },
        };

        const features = jspunto;

        map.data.forEach(function (feature) {
          map.data.remove(feature);
        });

        if(opt=="CCPP"){
          $.each( polyccpp, function( key, value ) {
              //console.log(value);
              map.data.add({
                geometry: new google.maps.Data.Polygon([
                  value
                ]),
              });
          })
        }

        if(!clear){
          map.data.loadGeoJson(url,{},function(features){
            $("#spinnerData").hide();
          });
        }

        map.data.addGeoJson(
          poligonos
        );

        map.data.addListener('click', function(event) {
          //console.log(event);
          param = typeof event.feature.i ==='undefined' ? event.feature.h : event.feature.i;
          viewDataMap(param);
        });

        fillColorByUbgInit();

        // Create markers.
        for (let i = 0; i < features.length; i++) {
          addMarkerCVM(features[i],icons[features[i].type].icon);
        }

        filterMarkers('','0')

        // Add a marker clusterer to manage the markers.
        /* new MarkerClusterer(map, markers, {
          imagePath:
            "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
        }); */

    }

    // Adds a marker to the map and push to the array.
    function addMarkerDrive(feature,medicion) {
      const marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[medicion].icon,
            idclient: feature.idclient,
            job_name: feature.job_name
      });
      google.maps.event.addListener(marker, 'spider_click', function(e) { 
        viewDataPoint(feature.id);
      });
      markers.push(marker);
    }

    // Adds a marker to the map and push to the array.
    function addMarkerCVM(feature,icon) {

      const marker = new google.maps.Marker({
            position: new google.maps.LatLng(feature.latitude,feature.longitude),
            icon: icon
      });
      google.maps.event.addListener(marker, 'spider_click', function(e) { 
        viewDataPoint(feature.id);
      });
      markers.push(marker);
    }

        // Adds a marker to the map and push to the array.
    function addMarkerSites(feature,icon) {
      const marker = new google.maps.Marker({
        position: new google.maps.LatLng(feature.latitude,feature.longitude),
        icon: icon,
        sector_name: feature.sector_name,
        site_address: feature.site_address,
        tech: feature.tech
      });
      google.maps.event.addListener(marker, 'spider_click', function(e) { 
        
      });
      sites.push(marker);
    }   

    // Adds a marker to the map and push to the array.
    function addMarkerSitesAngle(f) {
      let latBase_0 = f.lat+(.20*Math.cos((3.141592653/180)*(f.azimuth+30)))/111.325;
      let longBase_0 = f.lon+(.20*Math.sin((3.141592653/180)*(f.azimuth+30)))/((Math.cos((3.141592653/180)*f.lat)*40076)/360);
      let latBase_1 = f.lat+(.20*Math.cos((3.141592653/180)*(f.azimuth-30)))/111.325;
      let longBase_1 = f.lon+(.20*Math.sin((3.141592653/180)*(f.azimuth-30)))/((Math.cos((3.141592653/180)*f.lat)*40076)/360);
      let point0 = new google.maps.LatLng(latBase_0,longBase_0);
      let point1 = new google.maps.LatLng(latBase_1,longBase_1);
      let fillColor;

      let polLatLngbase = [
      f.position,
      point0,
      point1,
      f.position,
      ];

      if(f.tech == "4g"){
        fillColor = "orange"
      }else{
        fillColor = "blue"
      }

      let polygonebase = new google.maps.Polygon({
        path: polLatLngbase,
        fillColor:fillColor,
        fillOpacity:0.7,
        strokeColor:"#000000",
        strokeWeight:2,
        strokeOpacity:1,
        zIndex:3,
        tech:f.tech,
        sector_name:f.sector_name,
        site_address:f.site_address,
        azimuth:f.azimuth,
        height:f.height,
        electrical_tilt:f.electrical_tilt,
        mechanical_tilt:f.mechanical_tilt,
        hbw:f.hbw,
        ranking:f.ranking,
      });

      infoWindow = new google.maps.InfoWindow();

      // Add a listener for the click event.
      polygonebase.addListener("click", showArrays);

      listPolyBase.push(polygonebase);
    }
    
    function showArrays(event) {
      let contentString =
        "<b>"+this.sector_name+"</b><br>" +
        "Site_address: "+this.site_address+"<br>"+
        "Azimuth: "+this.azimuth+"<br>"+
        "Tecnología: "+this.tech+"<br>"+
        "Height: "+this.height+"<br>"+
        "Electrical_tilt: "+this.electrical_tilt+"<br>"+
        "Mechanical_tilt: "+this.mechanical_tilt+"<br>"+
        "Hbw: "+this.hbw+"<br>"+
        "Ranking: "+this.ranking+"<br>";

      // Replace the info window's content and position.
      infoWindow.setContent(contentString);
      infoWindow.setPosition(event.latLng);
      infoWindow.open(map);
    }

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
      for (let i = 0; i < markers.length; i++) {
        //markers[i].setMap(map);
        oms.addMarker(markers[i])
      }
    }

    // Sets the map on all markers in the array.
    function setMapOnSites(map,tech) {
      for (let i = 0; i < sites.length; i++) {
        if(sites[i].tech == tech){
          sites[i].setMap(map);
        }
      }
      for (let i = 0; i < listPolyBase.length; i++) {
        if(listPolyBase[i].tech == tech){
          listPolyBase[i].setMap(map);
        }
      }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
      //setMapOnAll(null);
      oms.removeAllMarkers()
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkersSites(tech) {
      setMapOnSites(null,tech);
    }

    // Removes the markers from the map, but keeps them in the array.
    function filterMarkers(id,job) {
      $("#spinnerData").show();
      showMarkers();
      if(id != "" && job == "0"){
        clearMarkers();
        for (let i = 0; i < markers.length; i++) {
          if(markers[i].idclient == id){
            //console.log(markers[i].idclient);
            //markers[i].setMap(null);
            //oms.removeMarker(markers[i])
            oms.addMarker(markers[i]);
          }
        }
      }
      if(id != "" && job != "0"){
        clearMarkers();
        for (let i = 0; i < markers.length; i++) {
          if(markers[i].idclient == id || markers[i].job_name == job){
            //markers[i].setMap(null);
            oms.addMarker(markers[i])
          }
        }
      }
      if(id == "" && job != "0"){
        clearMarkers();
        for (let i = 0; i < markers.length; i++) {
          if(markers[i].job_name == job){
            //markers[i].setMap(null);
            oms.addMarker(markers[i])
          }
        }
      }
      $("#spinnerData").hide();
    }

    // Shows any markers currently in the array.
    function showMarkers() {
      setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
      clearMarkers();
      markers = [];
    }

    function viewDataMap(dt) {
      if(typeof dt.ubigeo != "undefined"){
        $.ajax({
            url: "{{ route('api.mapData') }}",
            type: 'GET',
            dataType: 'json',
            data: "param="+dt.ubigeo+"&type=map",
            beforeSend:function () {
            $("#spinnerData").show();
            },
            success: function(data) {
              $('#title').text("CCPP: "+data.data[0].ccpp);
              $('#tabledatamap > tbody:last-child > tr').remove();
              if(data.data[1]){
                $('#tabledatamap > tbody:last-child').append('<tr><th>Departamento</th><td colspan="2">'+data.data[0].departamento+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Provincia</th><td colspan="2">'+data.data[0].provincia+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Distrito</th><td colspan="2">'+data.data[0].distrito+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Ubigeo</th><td colspan="2">'+data.data[0].ubigeo+'</td></tr>'); 
                $('#tabledatamap > tbody:last-child').append('<tr><th>Tecnología</th><td>'+data.data[0].tech_dl+'</td><td>'+data.data[1].tech_dl+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>N° Muestras</th><td>'+data.data[0].num_muestras+'</td><td>'+data.data[1].num_muestras+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Muestras Down Umbral DL</th><td>'+data.data[0].mstras_down_umbral_dl+'</td><td>'+data.data[1].mstras_down_umbral_dl+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>CVM_DL</th><td>'+data.data[0].cvm_dl+'</td><td>'+data.data[1].cvm_dl+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>CVM_UL</th><td>'+data.data[0].cvm_ul+'</td><td>'+data.data[1].cvm_ul+'</td></tr>');
              }else{
                $('#tabledatamap > tbody:last-child').append('<tr><th>Departamento</th><td>'+data.data[0].departamento+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Provincia</th><td>'+data.data[0].provincia+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Distrito</th><td>'+data.data[0].distrito+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Ubigeo</th><td>'+data.data[0].ubigeo+'</td></tr>'); 
                $('#tabledatamap > tbody:last-child').append('<tr><th>Tecnología</th><td>'+data.data[0].tech_dl+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>N° Muestras</th><td>'+data.data[0].num_muestras+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>Muestras Down Umbral DL</th><td>'+data.data[0].mstras_down_umbral_dl+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>CVM_DL</th><td>'+data.data[0].cvm_dl+'</td></tr>');
                $('#tabledatamap > tbody:last-child').append('<tr><th>CVM_UL</th><td>'+data.data[0].cvm_ul+'</td></tr>');
              }
              $('.card.mb-3').show();
            },
            complete: function () {
              $("#spinnerData").hide();
            },
            error: function(data){
              console.log(data);
            }
        });
      }
    }

    function viewDataPoint(dt) {
      if(typeof dt != "undefined"){
        if($("#selectMap").val()=='cvm'){
          point="point";
        }else{
          if($("#selectMap").val()=='drivetest'){
            point="drivetestpoint";
          }
        }
        $.ajax({
            url: "{{ route('api.mapData') }}",
            type: 'GET',
            dataType: 'json',
            data: "param="+dt+"&type="+point,
            beforeSend:function () {
            $("#spinnerData").show();
            },
            success: function(data) {
              $('#title').text("IMSI: "+data.data.IMSI);
              $('#tabledatamap > tbody:last-child > tr').remove();
              $('#tabledatamap > tbody:last-child').append('<tr><th>IDCLIENT</th><td>'+data.data.IDCLIENT+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>IDSESSION</th><td>'+data.data.IDSESSION+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Fecha</th><td>'+data.data.TIMESTAMP+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Latitud</th><td>'+data.data.LATITUDE+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Longitud</th><td>'+data.data.LONGITUDE+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Packet sent</th><td>'+data.data.PACKETSENT+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Packet received</th><td>'+data.data.PACKETRECEIVED+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Latencia avg</th><td>'+data.data.LATENCYAVG+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Jitter</th><td>'+data.data.JITTER+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>DL_KBPS</th><td>'+data.data.THROUGHPUT_DL_KBPS+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>UL_KBPS</th><td>'+data.data.THROUGHPUT_UL_KBPS+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>Tecnología</th><td>'+data.data.TECH_DL+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>RAT4G_RSRQ</th><td>'+data.data.RAT4G_RSRQ+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>RAT4G_RSRP</th><td>'+data.data.RAT4G_RSRP+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>RAT3G_RSCP</th><td>'+data.data.RAT3G_RSCP+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>RAT3G_TXPOWER</th><td>'+data.data.RAT3G_TXPOWER+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>RAT2G_RXLEV</th><td>'+data.data.RAT2G_RXLEV+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>RAT2G_RXQUAL</th><td>'+data.data.RAT2G_RXQUAL+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>IDCELL_DL</th><td>'+data.data.IDCELL_DL+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>IDCELL_UL</th><td>'+data.data.IDCELL_UL+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>CELLNAME_DL</th><td>'+data.data.CELLNAME_DL+'</td></tr>');
              $('#tabledatamap > tbody:last-child').append('<tr><th>CELLNAME_UL</th><td>'+data.data.CELLNAME_UL+'</td></tr>');              
              $('.card.mb-3').show();
            },
            complete: function () {
              $("#spinnerData").hide();
            },
            error: function(data){
              console.log(data);
            }
        });
      }
    }
</script>
<!-- <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script> -->
<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&callback=initMap&libraries=&v=weekly" async></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script> -->
<script src="{{asset('libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js')}}"></script>
@endsection