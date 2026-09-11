<!DOCTYPE html>
<html>
  <head>
    <title>Test Map</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>
        html,
        body,
        #map-canvas {
            height: 100%;
            margin: 0px;
            padding: 0px;
            top:0
        }
        .float-form{
            position: absolute;
            top: 12px;
            left: 205px;
            z-index: 999;
        }
        .float-form.create{
            left: 330px;
        } 
        .float-form.read{
            left: 465px;
        }            
        .float-form-submit{
            background: whitesmoke;
            font-size:12px;
            position: absolute;
            bottom: 30px;
            left: 15px;
            z-index: 999;
        }
        .float-form-submit > .form-group{
            min-height: 50px;
        }
        .card{
            background: #FFF;
            border-radius: 10px;
            z-index: 1;
        }
        .card .close{
            appearance: none;
            border: none;
            background: #f7f7f6;
            padding: 10px;
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #161c2d;
            text-shadow: 0 1px 0 #fff;
            opacity: .5;
        }
        .card.mb-3{
            position: absolute;
            border: none;
            top: 60px;
            right: 10px;
            max-height: 500px;
            overflow-y: scroll;
            display: none;
            opacity: .9;
        }
    </style>
  </head>
  <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2_aylDeV0Y-nijy4TWemxJ_QCcjLRYHA&libraries=drawing"></script>
    <div class="card border-secondary mb-3 col-4">
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
    <form class="float-form show">
        <div class="form-row align-items-center">
            <div class="col-auto" id="">
                <button id="btnExportPoligons" class="btn btn-danger" type="button">Ver Poligonos</button>
            </div>
        </div>
    </form>
    <form class="float-form create">
        <div class="form-row align-items-center">
            <div class="col-auto" id="">
                <button id="btnCreatePoligons" class="btn btn-danger" type="button">Crear Poligono</button>
            </div>
        </div>
    </form>
    <form class="float-form read">
        <div class="form-row align-items-center">
            <div class="col-auto" id="">
                <button id="btnReadMap" class="btn btn-danger" type="button">Mover Mapa</button>
            </div>
        </div>
    </form>
    <div id="map-canvas"></div>
    <form method="post" accept-charset="utf-8" id="map_form" class="row col-4 g-2 float-form-submit">
        <input type="hidden" name="poligono" value="" id="vertices" />
        <div class="form-group row m-0 mt-2">
            <label for="nombre" class="col-sm-5 col-form-label">Nombre de Polygono:</label>
            <div class="col-sm-7">
                <input type="text" class="form-control col-6" id="nombre" name="nombre" requrired>
            </div>
        </div>
        <div class="form-group row m-0">
            <label for="incidencia" class="col-sm-5 col-form-label">Tipo de Reporte:</label>
            <div class="col-sm-7">
                <select class="form-select col-6" aria-label="Default select example" name="tipo_incidencia" requrired>
                    <option value="ninguno" selected="">seleccione un tipo de reporte</option>
                    <option value="sin cobertura">sin cobertura</option>
                    <option value="alta latencia">alta latencia</option>
                    <option value="caida del servicio">caida del servicio</option>
                    <option value="baja velocidad">baja velocidad</option>
                    <option value="señal baja de cobertura">señal baja de cobertura</option>
                    <option value="problemas calidad llamadas">problemas calidad llamadas</option>
                    <option value="problemas calidad internet">problemas calidad internet</option>
                </select>
            </div>
        </div>
        <div class="form-group row m-0">
            <label for="noclientesmbre" class="col-sm-5 col-form-label">Número de clientes potenciales/afectados:</label>
            <div class="col-sm-7">
                <input type="text" class="form-control col-6" id="clientes" name="num_cli_potenciales">
            </div>
        </div>
        <div class="form-group row m-0">
            <label for="empresas" class="col-sm-5 col-form-label">Número de empresas potenciales/afectados:</label>
            <div class="col-sm-7">
                <input type="text" class="form-control col-6" id="empresas" name="num_emp_potenciales">
            </div>
        </div>
        <div class="form-group row m-0">
            <label for="departamentos" class="col-sm-5 col-form-label">Número de departamentos/edificios:</label>
            <div class="col-sm-7">
                <input type="text" class="form-control col-6" id="departamentos" name="num_dep_edificios"> 
            </div>
        </div>
        <div class="form-group row m-0">
            <label for="condominios" class="col-sm-5 col-form-label">Número de condominios:</label>
            <div class="col-sm-7">
                <input type="text" class="form-control col-6" id="condominios" name="num_condominios">
            </div>
        </div>        
        <div class="form-group row m-0">
            <label for="observacion" class="col-sm-5 col-form-label">Observaciones:</label>
            <div class="col-sm-7">
                <input  type="text" name="observacion" class="form-control col-6" id="observacion"></input>
            </div>
        </div>
        <div class="col-12" style="text-align:right">
            <button type="submit" id="update" class="btn btn-primary" style="margin:10px">Guardar</button>
        </div>
    </form>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    
    <script>        	
        var map; // Global declaration of the map
        var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
        var lat_longs = new Array();
        var markers = new Array();
        var drawingManager;
        var showPoly= 0;

        function initialize() {
            var newShape;
            var myLatlng = new google.maps.LatLng(-9.0431805, -78.0282364);
            var myOptions = {
                zoom: 5.8,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

            if(!showPoly){
                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingMode: google.maps.drawing.OverlayType.POLYGON,
                    drawingControl: false,
                    drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        //google.maps.drawing.OverlayType.MARKER,
                        //google.maps.drawing.OverlayType.CIRCLE,
                        google.maps.drawing.OverlayType.POLYGON,
                        //google.maps.drawing.OverlayType.POLYLINE,
                        //google.maps.drawing.OverlayType.RECTANGLE,
                    ],
                    },
                    polygonOptions: {
                        editable: true,
                        fillColor: '#dc3545',
                        strokeColor: '#dc3545'
                    }
                });
            }else{
                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingControl: false,
                    drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        //google.maps.drawing.OverlayType.MARKER,
                        //google.maps.drawing.OverlayType.CIRCLE,
                        google.maps.drawing.OverlayType.POLYGON,
                        //google.maps.drawing.OverlayType.POLYLINE,
                        //google.maps.drawing.OverlayType.RECTANGLE,
                    ],
                    },
                    polygonOptions: {
                        editable: true,
                        fillColor: '#dc3545',
                        strokeColor: '#dc3545'
                    }
                });
            }

            drawingManager.setMap(map);

            if(showPoly){
                map.data.loadGeoJson("http://172.17.27.157/portalregulatorio/api/mapa-poligonos",{},function(features){                
                });
                map.data.setStyle((feature)=>{                 
                    return{
                        fillColor: '#dc3545',
                        strokeColor: '#dc3545'
                    };
                });
                map.data.addListener('click', function(event) {                                        
                    param = typeof event.feature.i ==='undefined' ? event.feature.j : event.feature.i;
                    viewDataMap(param);
                });
            }

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                newShape = event.overlay;
                newShape.type = event.type;
            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                overlayClickListener(event.overlay);
                $('#vertices').val(event.overlay.getPath().getArray());
            });

            google.maps.event.addDomListener(document, 'keyup', function (e) {

                var code = (e.keyCode ? e.keyCode : e.which);

                if (code === 27) {

                    drawingManager.setDrawingMode(null);
                    newShape.setMap(null);
                    drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
                }
            });

            $('#btnCreatePoligons').click(function(e) {
                e.preventDefault();
                drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
            });

            $('#btnReadMap').click(function(e) {
                e.preventDefault();
                drawingManager.setDrawingMode(null);
            });
        }

        function overlayClickListener(overlay) {

            google.maps.event.addListener(overlay, "mouseup", function(event) {
                $('#vertices').val(overlay.getPath().getArray());
            });

        }

        google.maps.event.addDomListener(window, 'load', initialize);

        function viewDataMap(dt) {
            $('#title').text("Nombre de Poligono: "+dt.nombre);
            $('#tabledatamap > tbody:last-child > tr').remove();

            $('#tabledatamap > tbody:last-child').append('<tr><th>N° de clientes potenciales: </th><td>'+dt.num_cli_potenciales+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>N° de empresas potenciales: </th><td>'+dt.num_emp_potenciales+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>N° de departamentos/edificios: </th><td>'+dt.num_dep_edificios+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>N° de condominios: </th><td>'+dt.num_condominios+'</td></tr>'); 
            $('#tabledatamap > tbody:last-child').append('<tr><th>Tipo de incidencia</th><td>'+dt.tipo_incidencia+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>N° de clientes afectados</th><td>'+dt.num_cli_afectados+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>N° de empresas afectadas</th><td>'+dt.num_emp_afectados+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>Observación</th><td>'+dt.observacion+'</td></tr>');
            $('#tabledatamap > tbody:last-child').append('<tr><th>Fecha de creación</th><td>'+dt.created_at+'</td></tr>');

            $('.card.mb-3').show();
            
        }

        $(document).ready(function () {

            $('#btnExportPoligons').click(function(e) {
                e.preventDefault();
                showPoly = 1;
                initialize();
            });

            $("#update").click(function(e) {
                e.preventDefault();
                var dataString = $("#map_form").serialize();
                $.ajax({
                    type:'POST',
                    data:dataString,
                    url:'api/mapa-poligonos',
                    success:function(data) {
                        $(':input','#map_form')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .prop('checked', false)
                        .prop('selected', false);
                        showPoly = 0;
                        initialize();
                    }
                });
            });

            $("#closedata").click(function() {
                $(".card.mb-3").hide();
            });
        });
    </script>
  </body>
</html>