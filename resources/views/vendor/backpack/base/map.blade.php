@extends(backpack_view('blank'))

@section('header')
<input type="hidden" value="{{$tracingID}}" id="tracingID">
<input type="hidden" value="{{$menuID}}" id="menuID">
<input type="hidden" name="" id="fildValStr" value="">
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
<div id="dataMapInd" class="card border-secondary mb-3" style="font-size: 0.8rem;margin: 120px 40px;">dataMapInd</div>
<div class="card border-secondary mb-4 d-none">
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
  <div class="card-header" style="font-weight: bold;">Leyenda</div>
  <div id="legend" class="card-body">
    <table class="table">
      <tbody>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('after_styles')
<style>
  html, body{
    height: 100%;
    background: #ebedf0;
  }
  /* Set the size of the div element that contains the map */
  #map {
      height: 100vh;
      /* The height is 400 pixels */
      width: 100%;
      /* The width is the width of the web page */
      border-radius: 10px;
      z-index: 0;
  }
</style>
@endsection

@section('content')
<form>
  <div class="form-row">
    <div class="col">
      <select class="form-control slc-mapGroup">
      @foreach ($data['menuGroup'] as $menuGItem)
          <option value="{{$menuGItem['TYPE_NAME']}}" @if($data['menuGroupKey'] == $menuGItem['TYPE_NAME']) selected @endif data-val="">{{$menuGItem["MENU_NAME"]}}</option>        
      @endforeach
      </select>
    </div>
    <div class="col">
      <select class="form-control slc-mapGroupUnit">
        <option value="" class='optIni'>---</option>
      </select>
    </div>
    <div class="col">
      <select class="form-control slc-mapGran" id="selectCapa">
          <option value="555" data-val="{{ asset('map/peru_regiones.json') }}">Departamento</option>        
          <option value="556" data-val="{{ asset('map/peru_provincias.json') }}">Provincia</option>        
          <option value="557" data-val="{{ asset('map/peru_distritos.json') }}">Distrito</option>
      </select>
    </div>
    <div class="col">
      <select class="form-control slc-mapKpiType">
      @foreach ($data['menuFiltMap'] as $key => $item)
        <option value="{{$item['ID_TYPE']}}" data-val="{{$item['TYPE_NAME']}}">{{$item['TYPE_DESCRIPTION']}}</option>
      @endforeach
      </select>
    </div>
    <div class="col">
      <select class="form-control" id="selectDep">
        <option value="0">Seleccione departamento</option>
        @foreach ($departamentos as $departamento)
        <option value="{{$departamento->code}}">{{$departamento->departamento}}</option>
        @endforeach
      </select>
    </div>
    <div class="col" id="colProv" style="display: none;">
      <select class="form-control" id="selectProv" disabled>
        <option value="0">Seleccione Provincia</option>
      </select>
    </div>
    <div class="col" id="colDist" style="display: none;">
      <select class="form-control" id="selectDist" disabled>
        <option value="0">Seleccione Distrito</option>
      </select>
    </div>
    <div class="col">
      <select class="form-control slc-crit">
        <option selected="" value="0">---Total---</option>
        <option value="1">Zonas Criticas</option>
      </select>
    </div>
    <div class="col">
        <div class="input-group">
          <button id="btn-download" class="btn btn-danger" type="button" >Descargar</button>
        </div>
  </div>
</form>
  </br>
  </br>
<!--The div element for the map -->
<div id="map"></div>
@endsection

@section('after_scripts')
<script type="text/javascript" src="{{asset('packages/backpack/crud/js/list.js')}}"></script>
<script>

    var url = "{{ asset('map/peru_regiones.json') }}";
    var slcubg = "555";
    var dataMap;
    var map;
    var filtDataMap = [];
    var totalCodes = [];
    var criticZones = [];
    var colorlist = ["#9A1818","#FF0000"];
    var isInFilt = 0;
    var codeName = "";
    var codeValSTR = "";
    var callback = function (feature) {
      map.data.remove(feature);
    };


    $(document).ready(function () {

      $("#spinnerData").show();

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
        clearUbg();
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
          fillColorByUbg(CodeUbgDist,3,$(".slc-crit").val());
        }else{
          fillColorByUbg(CodeUbgProv,2,$(".slc-crit").val());
        }
      });

      $("#closedata").click(function() {
        $(".card.mb-3").hide();
      });

      dataMap = $("#dataMap").dialog({
          position: { my: "center", at: "center top" },
          minWidth: 650,
          autoOpen: false,
          modal: true,
          title: "Detalles",
          buttons: [
            {
              text: "Cerrar",
              click: function() {$(this).dialog( "close" )},
              icon: "ui-icon-gear"
            }
          ]
        });

        $(".slc-mapGroup").change(function(){
          changeGroupType();
          fillMapColor();
          clearUbg();
        });

        $(".slc-mapGroupUnit").change(function(){
          fillMapColor();
          clearUbg();
        });

        $(".slc-mapKpiType").change(function () {
          fillMapColor();
          clearUbg();
        });

        $(".slc-crit").change(function () {
          //var filtTypeMap = $(".slc-mapGran").val();
          var CodeUbgSlc = $("#selectDist").val();
          var typeFilt = 3;
          if(CodeUbgSlc=='0'){
            CodeUbgSlc=$("#selectProv").val();
            typeFilt = 2;
          }
          if(CodeUbgSlc=='0'){
            CodeUbgSlc=$("#selectDep").val();
            typeFilt = 1;
          }
          if($(this).val()=="1"&&CodeUbgSlc=='0'){
            fillColorByUbg(CodeUbgSlc,-2,$(this).val());
          }else if($(this).val()=="1"&&CodeUbgSlc!='0'){
            fillColorByUbg(CodeUbgSlc,typeFilt,$(this).val());
          }else if($(this).val()=="0"&&CodeUbgSlc!='0'){
            fillColorByUbg(CodeUbgSlc,typeFilt,0);
          }else{
            fillColorByUbg('',0);
          }
        });
        changeGroupType();
        //downladData();

        $("#btn-download").click(function () {
          var tracingID=$("#tracingID").val();
          var menuID = $("#menuID").val();
          var kpiSelName = $("option:selected",'.slc-mapKpiType').attr("data-val");
          var typeMapSeg = $("#selectCapa").val();
          var typeKpiSel = $(".slc-mapKpiType").val();
          var mapGroupSel = $(".slc-mapGroup").val();
          var mapGroupUnit = $(".slc-mapGroupUnit").val();
          if(mapGroupUnit == ''){
            mapGroupUnit = 0;
          }
          var parms=typeMapSeg+"/"+tracingID+"/"+menuID+"/"+mapGroupSel+"/"+mapGroupUnit;
          window.location.href="{{ asset('poligonos/export') }}"+'/'+parms
          return false;
        });
    });

    $(window).on('load', function() {
      setTimeout(initMap(),500);
    });

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
              list +="<option value='"+y.code+"'>"+y.provincia+"</option>";              
            })
            $("#selectProv").html(list);
            $("#selectProv").removeAttr("disabled")
          },
          complete: function () {
              $("#spinnerData").hide();
          },
        });

        // if(filtTypeMap=="555"){
          fillColorByUbg(CodeUbgDep,1,$(".slc-crit").val());
        // }
      }else{
        //fillColorByUbgInit();
        $("#selectProv").html("<option selected value='0'>Seleccione Provincia</option>");
        $("#selectDist").html("<option selected value='0'>Seleccione Distrito</option>");
        fillColorByUbg('',0,$(".slc-crit").val());
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
              list +="<option value='"+y.code+"'>"+y.distrito+"</option>";              
            })
            $("#selectDist").html(list);
            $("#selectDist").removeAttr("disabled")
          },
          complete: function () {
              $("#spinnerData").hide();
          }
        });

        // if(filtTypeMap=="555"){
          fillColorByUbg(CodeUbgProv,2,$(".slc-crit").val());
        // }
      }else{
        //fillColorByUbg(CodeUbgDep,1);
        //$(".slc-ubgProv").html("<option selected value='0'>---Provincia---</option>");
        $("#selectDist").html("<option selected value='0'>Seleccione Distrito</option>");
        fillColorByUbg('',0,$(".slc-crit").val());
      }
    }

    function clearUbg() {
      $("#selectDep").val(0);
      $("#selectProv").val(0);
      $("#selectDist").val(0);
    }

    function changeMapUnit() {
      var selMap = $(".slc-mapGroup").val();
      switch(selMap){
        case '2571':$(".slc-mapGroupUnit .optIni").text("---DIA---");break;
        case '2572':$(".slc-mapGroupUnit .optIni").text("---SEMANA---");break;
        default:$(".slc-mapGroupUnit .optIni").text("---");break;
      }
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
            mapTypeID: google.maps.MapTypeId.ROADMAP,
        });

        addData();
        addControl();
        viewMap();

    }

    function fillColorByUbg(code,typeFilt=-1,crit="0") {
        var newColor;
        //console.log(code,typeFilt,crit);
        if(typeFilt==-1)
          map.data.setStyle((feature)=>{
            var fillColorBk ="#fff";
            var rowEx = {};
            feature.forEachProperty(function(val,key){ rowEx[key] = val; });
            rowEx = rowEx[codeName];
            if(rowEx==code){
              newColor=totalCodes.reduce((a,o)=>(o.code==rowEx && a.push(o.color),a),[]);
              data = {fillColor:newColor[0],
                    fillOpacity: 0.5,
                    strokeColor: '#464646',
                    strokeWeight: 1};
            }else{
              data = {visibility:"off",fillColor:"#000",strokeWeight:0.3};
            }
            return data;
          });
        if(typeFilt==-2)//Color de criticos
          map.data.setStyle((feature)=>{
            var fillColorBk ="#fff";
            var rowEx ={};
            feature.forEachProperty(function(val,key){ rowEx[key] = val; });
            rowEx = rowEx[codeName];
            newColor=totalCodes.reduce((a,o)=>(o.code==rowEx && 
              a.push(colorlist.includes(o.color)?o.color:"#000"),a),[]);
            if(newColor!="#000"){
              data = {fillColor:newColor[0],
                    fillOpacity: 0.5,
                    strokeColor: '#464646',
                    strokeWeight: 1};
            }else{
              data = {visibility:"off",fillColor:"#000",strokeWeight:0.3};
            }
            return data;
          });
        if(typeFilt==0)
          map.data.setStyle((feature)=>{
            var fillColorBk ="#fff";
            var rowEx ={};
            feature.forEachProperty(function(val,key){ rowEx[key] = val; });
            rowEx = rowEx[codeName];
            newColor=totalCodes.reduce((a,o)=>(o.code==rowEx && a.push(o.color),a),[]);
            data = {fillColor:newColor[0],
                    fillOpacity: 0.5,
                    strokeColor: '#464646',
                    strokeWeight: 1};
            return data;
          });
        if (typeFilt>0) {

          map.data.setStyle((feature)=>{
            var fillColorBk ="#fff";
            var rowEx ={};
            feature.forEachProperty(function(val,key){ rowEx[key] = val; });
            rowEx = rowEx[codeName];
            //console.log(feature);
            data = {visibility:"off",fillColor:"#000",strokeWeight:0.3};
            if((typeFilt==1?rowEx.substr(0,2):(typeFilt==2?rowEx.substr(0,4):rowEx))==code){
              newColor=totalCodes.reduce((a,o)=>(o.code==rowEx && a.push(o.color),a),[]);
              //console.log(newColor);
              if(crit=='0'||colorlist.includes(newColor[0])){
                data = {fillColor:newColor[0],
                    fillOpacity: 0.5,
                    strokeColor: '#464646',
                    strokeWeight: 1};
              }
            }
            return data;
          });
        }
        $("#dataMapInd").hide();
      }

    function editLegend() {
        // $("#legend").html();
        // console.log(filtDataMap);
        var htmlStr = "";
        var dataIntrvl = "";
        var first=0;
        $.each(filtDataMap,function (x,y) {
          if(x==0&&y.TYPE_DESCRIPTION.split('|')[1]!='')
            first=1;
          htmlStr += "<tr><td><label style=\"background-color:";
          htmlStr += y.TYPE_NAME+";\">&nbsp;</label></td><td>";
          htmlStr += y.TYPE_DESCRIPTION.replace('|',' KPI ')+"</td></tr>";
          dataIntrvl = y.TYPE_DESCRIPTION;
        })
        dataIntrvl = dataIntrvl.split("|");
        if(first==1&&htmlStr.length>1){
          htmlStr += "<tr><td><label style=\"background-color:green;\">&nbsp;";
          htmlStr += "</label></td><td>";
          htmlStr += dataIntrvl[1].replace('<','').replace('=','')+"< KPI</td></tr>";
        }
        $("#legend .table>tbody").html(htmlStr);
      }

      function viewMap() {
        setTimeout('showMap()',1000);
      }

      function changeGroupType(){
        var tracingID=$("#tracingID").val();
        var menuID = $("#menuID").val();
        var mapGroupSel = $(".slc-mapGroup").val();
        var typeMapSeg = $("#selectCapa").val();
        $.ajax({
          url:"{{ route('api.mapDataKpiUbgTypeDataList') }}",
          method:"POST",
          data:{
            typeMapSeg:typeMapSeg,
            tracingID:tracingID,
            menuID:menuID,
            mapGroupSel:mapGroupSel
          }, 
          dataType: 'json',
          beforeSend:function () {
            $("#spinnerData").show();
            totalCodes=[];
          },
          success: function(result) {
            var OptMenuSelGroup = "<option value='' class='optIni'>---</option>";
            $.each(result.dataTbl,function(x,y){
              OptMenuSelGroup+="<option value='"+y.GROUPTYPE+"'>"+y.GROUPTYPE+"</option>";
            });
            $(".slc-mapGroupUnit").html(OptMenuSelGroup);
          },
          complete: function () {
            $("#spinnerData").hide();
            changeMapUnit();
          }
        });
      }

      function showMap() {
        $(".widget-content.sectVw #map").css({"overflow-x":"hidden","position":""});
        // setTimeout($(".sectTabsContent #map div").css({"overflow-x":"hidden","overflow-y":"hidden"}),5000);
      }

      function addControl() {
        //var legend = document.getElementById("legend");
        //map.control[google.maps.ControlPosition.RIGHT_TOP].push(legend);

        const $checkbox = $(`
          <div style="
            background:#fff;
            padding:6px;
            margin-top:65px;  /* Desplazado hacia abajo */
            margin-left:-185px; /* Ajusta hacia la izquierda */
            border-radius:3px;
            box-shadow:0 2px 4px rgba(0,0,0,0.3);
            font-family:Roboto, Arial, sans-serif;
          ">
            <label style="margin:0;">
              <input type="checkbox" id="switchMarcadores"> Mostrar Falla Energía
            </label>
          </div>
        `);

        map.controls[google.maps.ControlPosition.TOP_LEFT].push($checkbox[0]);

        // Manejo del checkbox con jQuery
        $checkbox.find('#switchMarcadores').on('change', function() {
            const mostrar = $(this).is(':checked');
            window.markersFallaEnergia.forEach(marker => marker.setVisible(mostrar));
        });

      }

      function addData() {
        var est = "";
        // map.features.set(null);
        map.data.forEach(function (feature) {
          map.data.remove(feature);
        });
        map.data.loadGeoJson(url);
        map.data.addListener('click', function(event) {
          //console.log(object);
          var rowEx ={};
          event.feature.forEachProperty(function(val,key){ rowEx[key] = val; });
          param = rowEx;
          console.log("param", param);
          viewDataMap(param);
        });
        fillMapColor();
      }

      function fillMapColor() {
        var tracingID=$("#tracingID").val();
        var menuID = $("#menuID").val();
        var kpiSelName = $("option:selected",'.slc-mapKpiType').attr("data-val");
        var typeMapSeg = $("#selectCapa").val();
        var typeKpiSel = $(".slc-mapKpiType").val();
        var mapGroupSel = $(".slc-mapGroup").val();
        var mapGroupUnit = $(".slc-mapGroupUnit").val();

        // var params = "tracingID="+tracingID+"&menuID="+menuID+"&";
        $.ajax({
          url:"{{ route('api.mapDataGetDaByFatID') }}",
          type: "get",
          dataType: 'json',
          data:"typeKpiSel="+typeKpiSel,
          success:function (resFilt) {
            filtDataMap = resFilt;
            editLegend();
            $.ajax({
              url:"{{ route('api.mapDataKpiUbgType') }}",
              method:"POST",
              data:{
                typeMapSeg:typeMapSeg,
                tracingID:tracingID,
                menuID:menuID,
                mapGroupSel:mapGroupSel,
                mapGroupUnit:mapGroupUnit
              }, 
              dataType: 'json',
              beforeSend:function () {
                $("#spinnerData").show();
                totalCodes=[];
              },
              success: function(result) {
                var infoMap = result.dataTbl;
                var rowEx = 0;
                
                switch(typeMapSeg){
                  case "555":codeName="cod_dep";codeValSTR="name_dep";break;
                  case "556":codeName="FIRST_IDPR";codeValSTR="FIRST_NOMB,NOMBPROV,";break;
                  case "557":codeName="IDDIST";codeValSTR="NOMBPROV,NOMBDIST";break;
                }

                var rankVal = 0;

                map.data.setStyle((feature)=>{
                  var fillColorBk ="#fff";
                  var rowEx = {};
                  feature.forEachProperty(function(val,key){ rowEx[key] = val; });
                  rowEx = rowEx[codeName];
                  if(infoMap.hasOwnProperty(rowEx)&&infoMap[rowEx][kpiSelName]!=null){
                    rankVal=parseFloat(infoMap[rowEx][kpiSelName].replace(',',''));
                    fillColorBk=getColorByRank(rankVal);   
                    addMarkerP_FallaEnergia(feature, infoMap, codeName);
                  }

                  totalCodes.push({code:rowEx,color:fillColorBk});
   
                  return{
                    fillColor: fillColorBk,
                    fillOpacity: 0.5,
                    strokeColor: '#464646',
                    strokeWeight: 1
                  };

                });

                $("#dataMapInd").hide();
              },
              complete: function () {
                $("#spinnerData").hide();
              }
            });
          }
        });
      }

      // Define esto fuera de setStyle (antes de tu función fillMapcolor o al inicio)
      window.markersFallaEnergia = [];

      function addMarkerP_FallaEnergia(feature, infoMap, codeName){
          let rowEx = {};
          feature.forEachProperty(function(val, key){ rowEx[key] = val; });
          rowEx = rowEx[codeName];

          if (infoMap.hasOwnProperty(rowEx) && infoMap[rowEx]["P_FALLA_ENERGIA"] != null) {
              const bounds = new google.maps.LatLngBounds();
              feature.getGeometry().forEachLatLng(function(latlng){
                  bounds.extend(latlng);
              });
              const center = bounds.getCenter();

              const marker = new google.maps.Marker({
                  position: center,
                  map: map,
                  icon: { path: google.maps.SymbolPath.CIRCLE, scale: 0 },
                  label: {
                      text: infoMap[rowEx]["P_FALLA_ENERGIA"].toString(),
                      color: 'black',
                      fontSize: '12px',
                      fontWeight: 'bold'
                  },
                  visible: $('#switchMarcadores').is(':checked')
              });

              window.markersFallaEnergia.push(marker);
          }
      }

      function getColorByRank(rank){
        color='green';
        first=0;
        $.each(filtDataMap,function (x,y) {
          var filtData = y.TYPE_DESCRIPTION.split("|");
          if(x==0&&filtData[1]!='')
            first=1;
          if(color=='green'||first==0){
            if(filtData[1]!=''){
              if(filtData[1].search("<=")>=0){
                if(rank<=parseFloat(filtData[1].replace(',','.').replace('<=',''))){
                  color=y.TYPE_NAME;
                }
              }else if(filtData[1].search("<")>=0){
                if(rank<parseFloat(filtData[1].replace(',','.').replace('<',''))){
                  color=y.TYPE_NAME;
                }
              }else if(filtData[1].search("=")>=0){
                if(rank==parseFloat(filtData[1].replace(',','.').replace('=',''))){
                  color=y.TYPE_NAME;
                }
              }
            }else{
              if(filtData[0].search("<=")>=0){
                if(rank>=parseFloat(filtData[0].replace(',','.').replace('<=',''))){
                  color=y.TYPE_NAME;
                }
              }else if(filtData[0].search("<")>=0){
                if(rank>parseFloat(filtData[0].replace(',','.').replace('<',''))){
                  color=y.TYPE_NAME;
                }
              }else if(filtData[0].search("=")>=0){
                if(rank==parseFloat(filtData[0].replace(',','.').replace('=',''))){
                  color=y.TYPE_NAME;
                }
              }
            }
          }
        });
        return color;
      }

      function VerGraph() {
        var slcMap = $("#selectCapa").val();
        var tracingID = $("#tracingID").val();
        var fildVal = $("#fildValStr").val();

            /* if(result.session==0){
              location.href="http://172.17.27.157/portalmonitoreov2/";
            }else{ */
            
              openWin("{{ asset('api/getGraph') }}?tracingID="+tracingID+"&slcMap="+slcMap+"&fildVal="+fildVal+"&submenu=0&clearSubMenu=1",580,1100);
              submenuGraph();
              returnMenu();
            //}
      }

      function viewDataMap(dt) {
        // console.log(dt);
          var tblhtml = "";
          var btn = "";
          var params = "";
          var filts = [];
          var tracingID=$("#tracingID").val();

          // $.each(codeValSTR.split('|',function (x,y) {
            // filts.push($("#fildValStr").val(dt[y]));
          // });
          // $("#fildValStr").val(filts.json(''));
          var filtTypeMap = $("#selectCapa").val();
          var mapGroupUnit = $(".slc-mapGroupUnit").val();
          if(mapGroupUnit == ''){
            mapGroupUnit = 0;
          }
          var mapGroupSel = $(".slc-mapGroup").val();
          var dataFilter = [];
          console.log(dt);
          $.each(codeValSTR.split(","),function(x,y){
            dataFilter.push(dt[y]);
          });
          dataFilter = dataFilter.join("__");
          // dt[codeValSTR]
          $("#fildValStr").val(dataFilter);
          $.each(dt,function (x,y) {
            params += "&"+x+"="+y;
            tblhtml += "<tr><td><b>"+x+"</b></td><td>"+y+"</td></tr>";
          });
          params+="&tracingID="+tracingID+"&filtTypeMap="+filtTypeMap+"&mapGroupUnit="+mapGroupUnit;
          params+="&mapGroupSel="+mapGroupSel;
          btn += "<button onclick='VerGraph()'>Ver KPI</button>";
          $.ajax({
            url:"{{ route('api.dataMap') }}",
            method:"POST",
            data: params,
            dataType: 'json',
            beforeSend:function () {
              $("#spinnerData").show();
            },
            success: function(result) {
              var closeTab = $("#dataClose").html();
              $("#dataMapInd").html(btn+"<button type='button' class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+result.dataMap);
              $("#dataMapInd").show();
              $("#dataMapInd .close").click(function () {
                $("#dataMapInd").hide();
              });
            },
            complete: function () {
              $("#spinnerData").hide();
              $("#dataMap").html(tblhtml);
            }
          });
          // map.data.overrideStyle(event.feature, {fillColor: 'red'});
          // $("#dataMap").dialog("open");
        
      }

    function viewDataMap_(dt) {
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
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&callback=initMap&libraries=&v=weekly" async></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCA4qFt-RRmlltEMiFhNowpPaLxekroPgI&amp;libraries=drawing,geometry&amp;v=weekly" async=""></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script> -->
<script src="{{asset('libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js')}}"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<div id="dataMap" style="display: none"></div>
<div id="dataMapGraph" style="display: none">dataMapGraph</div>
@endsection