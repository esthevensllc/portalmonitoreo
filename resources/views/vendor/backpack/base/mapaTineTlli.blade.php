@extends(backpack_view('blank'))

@php
$jspoli = "";
$jspunto = "";
$poliubidep = array();
$poliubiprov = array();
$poliubidist = array();
foreach($poligonos as $poligono){
  $jspoli = $jspoli."map.data.add({\ngeometry: new google.maps.Data.Polygon([\n[";
  if(in_array($poligono->ubigeo,$ubigeos_crit)){
    $color = '#cf243894';
  }else{
    $color = '#00FF00';
  }
  $poliubidep[] = substr($poligono->ubigeo,0,2);
  $poliubiprov[] = substr($poligono->ubigeo,0,4);
  $poliubidist[] = substr($poligono->ubigeo,0,6);
  $poliarray = explode("," , rtrim(ltrim($poligono->polygono)));
  foreach($poliarray as $poli){
      $coord = explode(" " , rtrim(ltrim($poli)));
      $jspoli = $jspoli."{ lat: ".$coord[1].", lng: ".$coord[0]."},\n";
  }
  $jspoli = $jspoli."]])\n,properties: {color:'".$color."', ubigeo:'".$poligono->ubigeo."'}});\n\n";
}
foreach($puntos as $punto){
  if($punto->tech == "lte"){
    if($punto->dl_kbps < 2048 || $punto->ul_kbps < 410 ){
      $jspunto = $jspunto."{\n position: new google.maps.LatLng(".$punto->latitude.", ".$punto->longitude."),\n type: 'mala_medicion',\n id: $punto->id,\n},\n";
    }else{
      $jspunto = $jspunto."{\n position: new google.maps.LatLng(".$punto->latitude.", ".$punto->longitude."),\n type: 'buena_medicion',\n id: $punto->id,\n},\n";
    }
  }else{
    if($punto->dl_kbps < 410 || $punto->ul_kbps < 82 ){
      $jspunto = $jspunto."{\n position: new google.maps.LatLng(".$punto->latitude.", ".$punto->longitude."),\n type: 'mala_medicion',\n id: $punto->id,\n},\n";
    }else{
      $jspunto = $jspunto."{\n position: new google.maps.LatLng(".$punto->latitude.", ".$punto->longitude."),\n type: 'buena_medicion',\n id: $punto->id,\n},\n";
    }
  }  
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
      <label for="selectMap">Mapa:</label>
      <select class="form-control" id="selectMap">
        <option value="cvm">CVM</option>
        <option value="drivetest">Drive Test</option>
      </select>
    </div>
    <div class="col">
      <label for="selectSem">Semestre:</label>
      <select class="form-control" id="selectSem">
        <option>Primer semestre</option>
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
    $(document).ready(function () {
        var poliubidep = <?php echo json_encode($poliubidep) ?>;
        var poliubidist = <?php echo json_encode($poliubidist) ?>;
        var poliubiprov = <?php echo json_encode($poliubiprov) ?>;
        var ubigeocrit = <?php echo json_encode($ubigeos_crit) ?>;
        var fillColorBk,opt,clear=0,map,icons,marker,oms,loadedsites4g=0,loadedsites3g=0;
        var url = "{{ asset('map/peru_regiones.json') }}";
        var ubidep=[],ubiprov= [],ubidist= [],ubiccpp= [],ccpp= [],polyccpp = [],markers = [],sites = [],feature = [],listPolyBase=[];
        var slcubg = "555";
        let infoWindow;

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

        const puntos = [
            { lat: -16.06777680759392, lng: -69.64464290255624},
            { lat: -16.06846646166173, lng: -69.64608959306909},
            { lat: -16.06884115100248, lng: -69.64590309628126},
            { lat: -16.0692321135785, lng: -69.64460896800097},
            { lat: -16.07651273331157, lng: -69.64171365287822},
            { lat: -16.07651302291294, lng: -69.64247995816271},
            { lat: -16.07418933110464, lng: -69.64421640081676},
            { lat: -16.07309905130411, lng: -69.64553284019819},
            { lat: -16.07284875427937, lng: -69.64621216394342},
            { lat: -16.07426835880333, lng: -69.64776700707134},
            { lat: -16.07332245489389, lng: -69.6490305545323},
            { lat: -16.07253295285861, lng: -69.65110746452832},
            { lat: -16.07455418206123, lng: -69.65285732918035},
            { lat: -16.0742164173148, lng: -69.65361114220941},
            { lat: -16.07545529479374, lng: -69.65428571751517},
            { lat: -16.07652507896721, lng: -69.65448961792696},
            { lat: -16.07722698157698, lng: -69.65477844454834},
            { lat: -16.07641860207783, lng: -69.65686228201656},
            { lat: -16.07659287026077, lng: -69.65705373900812},
            { lat: -16.07619100341501, lng: -69.65804622528911},
            { lat: -16.07606997871542, lng: -69.65800087532874},
            { lat: -16.07555244841993, lng: -69.65837209579526},
            { lat: -16.07403732665371, lng: -69.66075141499887},
            { lat: -16.07463213797274, lng: -69.66108999481945},
            { lat: -16.07388843611028, lng: -69.66296641970671},
            { lat: -16.07288463519017, lng: -69.66294700131311},
            { lat: -16.07192658733301, lng: -69.66452874395618},
            { lat: -16.07492709637542, lng: -69.66582203443967},
            { lat: -16.07782057779751, lng: -69.66329221061976},
            { lat: -16.07852906781638, lng: -69.66188598371726},
            { lat: -16.07829692074582, lng: -69.66177445434572},
            { lat: -16.07985885125456, lng: -69.65832953105478},
            { lat: -16.08060308503746, lng: -69.65804357424715},
            { lat: -16.0808881482232, lng: -69.65736328780231},
            { lat: -16.08050134038204, lng: -69.65719247872742},
            { lat: -16.08199015409924, lng: -69.65393538440064},
            { lat: -16.08166026503888, lng: -69.65315335225317},
            { lat: -16.08095331203814, lng: -69.65268675071803},
            { lat: -16.08191033097937, lng: -69.65185635389309},
            { lat: -16.08459705882317, lng: -69.65320824570939},
            { lat: -16.0856517130099, lng: -69.65249975796496},
            { lat: -16.08767422076096, lng: -69.65349318455297},
            { lat: -16.09158291491097, lng: -69.65458349139993},
            { lat: -16.09268145583179, lng: -69.65454493424409},
            { lat: -16.09379812795143, lng: -69.64782089762618},
            { lat: -16.09264596538164, lng: -69.64667670582672},
            { lat: -16.09374158705423, lng: -69.64435049537093},
            { lat: -16.0946474927543, lng: -69.64493250517833},
            { lat: -16.09723106332994, lng: -69.64554671030569},
            { lat: -16.09846621327446, lng: -69.64547697748594},
            { lat: -16.09862397601438, lng: -69.64482932409342},
            { lat: -16.09383797389786, lng: -69.64254406788591},
            { lat: -16.09236052859698, lng: -69.64096359520704},
            { lat: -16.09186153476866, lng: -69.63876169571583},
            { lat: -16.09325354369743, lng: -69.63692267346129},
            { lat: -16.09202274189136, lng: -69.63534746556395},
            { lat: -16.09062621569849, lng: -69.63398273093708},
            { lat: -16.09053070048085, lng: -69.63284607436049},
            { lat: -16.08892629909163, lng: -69.6322645287506},
            { lat: -16.08808370754585, lng: -69.6316666873508},
            { lat: -16.08539282249879, lng: -69.63127908453036},
            { lat: -16.08347288464847, lng: -69.63196438399349},
            { lat: -16.08320290950968, lng: -69.63391795919289},
            { lat: -16.08193083865182, lng: -69.63314404324262},
            { lat: -16.08134211695543, lng: -69.63406438861654},
            { lat: -16.07827685249897, lng: -69.63156529969858},
            { lat: -16.0785224613322, lng: -69.63060822682817},
            { lat: -16.07496889210064, lng: -69.62784819542203},
            { lat: -16.07434283027396, lng: -69.62836413986226},
            { lat: -16.06967687692644, lng: -69.6258609760235},
            { lat: -16.0671815310806, lng: -69.62808726690797},
            { lat: -16.06638407446734, lng: -69.62739898118696},
            { lat: -16.06552673912494, lng: -69.62845358542882},
            { lat: -16.06624997208318, lng: -69.63095249242141},
            { lat: -16.06867344349156, lng: -69.6330704674091},
            { lat: -16.06890872577418, lng: -69.63283519333535},
            { lat: -16.06933320641676, lng: -69.633305686212},
            { lat: -16.06908870036424, lng: -69.63360817819225},
            { lat: -16.06973773086791, lng: -69.63448813450799},
            { lat: -16.06966958613012, lng: -69.63457087841076},
            { lat: -16.06997916447931, lng: -69.6348754094213},
            { lat: -16.07039187351883, lng: -69.63452078684234},
            { lat: -16.07064174047138, lng: -69.63428043239911},
            { lat: -16.07086512401167, lng: -69.63442226043414},
            { lat: -16.07075155107807, lng: -69.63458774728674},
            { lat: -16.07102415883906, lng: -69.63485171388247},
            { lat: -16.07054714838801, lng: -69.63554124501196},
            { lat: -16.07116123838393, lng: -69.63615149709277},
            { lat: -16.07148925366391, lng: -69.63581445146347},
            { lat: -16.07169922924103, lng: -69.6359733567328},
            { lat: -16.07134930405655, lng: -69.63653416528938},
            { lat: -16.07189650955479, lng: -69.6368692432998},
            { lat: -16.07151606199855, lng: -69.63772465587419},
            { lat: -16.0713092739333, lng: -69.63770479965252},
            { lat: -16.07103569038481, lng: -69.63810541073212},
            { lat: -16.06825069110664, lng: -69.64125710329218},
            { lat: -16.06953619125352, lng: -69.64380650750024}
        ];

        

        addMarker(lima, map, 'azul');

        let color = 'azul';
        puntos.forEach(item => {
            addMarker(item, map, color, {
                color: color
            });
            color = color === 'naranja'? 'azul' : 'naranja';
        });

        map.data.addListener('click', function(event) {
            console.log('event');
            //param = typeof event.feature.i ==='undefined' ? event.feature.j : event.feature.i;
            //viewDataMap(event);
        });

        $("#closedata").click(function() {
            $(".card.mb-3").hide();
            deleteMarkers();
        });

        map.data.loadGeoJson(url,{},function(features){
            //$("#spinnerData").hide();
        });
        
        //fill color of departaments
        map.data.setStyle((feature) => {
            const size = feature.getProperty("name_dep").length;
            if(1<size && size <= 5){
                return {
                    fillColor: 'green',
                    strokeWeight: 1,
                };
            }else if(5<size && size < 8){
                return {
                    fillColor: 'red',
                    strokeWeight: 1,
                };
            }else if(8<size){
                return /** @type {google.maps.Data.StyleOptions} */ {
                    fillColor: 'blue',
                    strokeWeight: 1,
                };
            }else{
                return {fillColor: 'white', strokeWeight: 1};
            }
        });
        //map.data.revertStyle();
        
        /*

map.data.setStyle({
            fillColor: 'green',
            strokeWeight: 1
        });

        map.data.loadGeoJson("http://172.17.27.157/portalmonitoreov2/assets/map/peru_regiones.json",{},function(features){
            //$("#spinnerData").hide();
        });
        map.data.loadGeoJson("https://storage.googleapis.com/mapsdevsite/json/google.json");
        
        */

            
            /*
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

            const features = []
            {{-- const features = [{!! '$jspunto' !!}] --}}

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

            map.data.loadGeoJson(url,{},function(features){
                //$("#spinnerData").hide();
            });
            */

            /*
            if(!clear){
            }

            {!! '$jspoli' !!}

            map.data.addListener('click', function(event) {
            param = typeof event.feature.i ==='undefined' ? event.feature.j : event.feature.i;
            viewDataMap(param);
            });

            fillColorByUbgInit();

            // Create markers.
            for (let i = 0; i < features.length; i++) {
            addMarkerCVM(features[i],icons[features[i].type].icon);
            }

            filterMarkers('','0')
            */

            // Add a marker clusterer to manage the markers.
            /* new MarkerClusterer(map, markers, {
            imagePath:
                "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
            }); */

        }

    function addMarker(location, map, color = 'default', data = {}) {
        const iconURL = "{{asset('')}}/images/"+color+".png";
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        let marker = undefined;
        if(color === 'default'){
            marker = new google.maps.Marker({
                position: location,
                label: "",
                map: map
            });
        }else{
            marker = new google.maps.Marker({
                position: location,
                label: "",
                map: map,
                icon: iconURL,
                customData: data
            });
            google.maps.event.addListener(marker, 'spider_click', function(e) { 
                handleClickMaker();
            });
            google.maps.event.addListener(marker, "click", function(event){
                console.log(data);
                handleClickMaker();
            });
            /*
            */
        }
        markers.push(marker);
    }

    function deleteMarkers() {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }

    function handleClickMaker(){
        $.ajax({
            url: "{{ route('api.mapData') }}",
            type: 'GET',
            dataType: 'json',
            data: "param=71167&type=point",
            beforeSend:function () {
                $("#spinnerData").show();
            },
            success: function(data) {
                data = {data: {}}
                $("#spinnerData").show();
                $('#title').text("IMSI: CUSTOM INFORMATION");
                $('#tabledatamap > tbody').html(`
                    <tr><th>IDCLIENT</th><td>'+data.data.IDCLIENT+'</td></tr>
                    <tr><th>IDCLIENT</th><td>'+data.data.IDCLIENT+'</td></tr>
                `);
                        
                $('.card.mb-3').show();
                $("#spinnerData").hide();
            },
            complete: function () {
              $("#spinnerData").hide();
            },
            error: function(data){
              console.log(data);
            }
        });
    }
        
        
    function __fillColorByUbg(code,typeFilt=-1,crit="0") {  
      var newColor;
      var typeMap,typemapB;
      map.data.setStyle((feature)=>{
        var fillColorBk ="#fff";
        if(slcubg=="555"){
          rowEx = typeof feature.i.name_dep === "undefined" ? "00" : feature.i.name_dep;
          typeMap = ubidep.includes(feature.i.cod_dep);
          typemapB = poliubidep.includes(feature.i.cod_dep);
        }
        if(slcubg=="556"){
          if(typeFilt == 1){
            rowEx = typeof feature.i.NOMBDEP === "undefined" ? feature.i.FIRST_NOMB : feature.i.NOMBDEP;
          }
          if(typeFilt == 2){
            rowEx = typeof feature.i.NOMBPROV === "undefined" ? "00" : feature.i.NOMBPROV;
          }
          typeMap = ubiprov.includes(feature.i.FIRST_IDPR);
          typemapB = poliubiprov.includes(feature.i.FIRST_IDPR);
        }
        if(slcubg=="557"){
          if(typeFilt == 1){
            rowEx = typeof feature.i.NOMBDEP === "undefined" ? feature.i.FIRST_NOMB : feature.i.NOMBDEP;
            //console.log(rowEx);
          }
          if(typeFilt == 2){
            rowEx = typeof feature.i.NOMBPROV === "undefined" ? "00" : feature.i.NOMBPROV;
          }
          if(typeFilt == 3){
            rowEx = typeof feature.i.NOMBDIST === "undefined" ? "00" : feature.i.NOMBDIST;
          }
          typeMap = ubidist.includes(feature.i.IDDIST);
          typemapB = poliubidist.includes(feature.i.IDDIST);
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

    function __fillColorByUbgInit() {
      map.data.setStyle((feature)=>{ 
            //console.log(feature);
            rowEx = typeof feature.i.cod_dep === "undefined" ? "00" : feature.i.cod_dep;
            if(poliubidep.includes(rowEx) && slcubg=="555"){
              if(ubidep.includes(rowEx)){
                fillColorBk ="red";
              }else{
                fillColorBk ="green";
              }
              fillOpacityBK = 0.5;
              zIndexBK = 1
            }else{
              rowEx = typeof feature.i.FIRST_IDPR === "undefined" ? "00" : feature.i.FIRST_IDPR;
              if(poliubiprov.includes(rowEx) && slcubg=="556"){
                if(ubiprov.includes(rowEx)){
                  fillColorBk ="red";
                }else{
                  fillColorBk ="green";
                }
                fillOpacityBK = 0.5;
                zIndexBK = 1
              }else{
                rowEx = typeof feature.i.IDDIST === "undefined" ? "00" : feature.i.IDDIST;
                if(poliubidist.includes(rowEx) && slcubg=="557"){
                  if(ubidist.includes(rowEx)){
                    fillColorBk ="red";
                  }else{
                    fillColorBk ="green";
                  }
                  fillOpacityBK = 0.5;
                  zIndexBK = 1
                }else{
                  if(typeof feature.i.color === "undefined"){
                    fillColorBk ="#e8e8e8";
                    fillOpacityBK = 0.5;
                    zIndexBK = 1
                  }else{
                    fillColorBk = feature.i.color;
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
        initMap();
    });

</script>
<!-- <script src="https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js"></script> -->
<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&callback=initMap&libraries=&v=weekly" async></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script> --}}
<script src="{{asset('libs/oms/oms.min.js')}}"></script>
@endsection