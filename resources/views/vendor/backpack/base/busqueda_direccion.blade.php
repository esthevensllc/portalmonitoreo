@extends(backpack_view('blank'))

@section('after_styles')
<style>
    .modal-title{
        margin-left: 120px;
    }
    .result-content{
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .result-section{
        font-size: 13px;
        border: 1px solid #5db969;
        border-radius: 5px;
        background: #ebffdd;
        color: #5db969;
        padding: 4px;
        margin-bottom: 2px;
    }
    .result-item{
        margin-bottom: 10px;
        font-size: 13px;
        font-weight: 700;
    }
    .result-row{
        display: flex;
        justify-content: center;
    }
    .result-row-right{
        display: flex;
        justify-content: flex-start;
    }
    .result-row-field{
        padding-right: 5px;
        margin: 2px;
        color: #555555;
    }
    .result-row-value{
        margin: 2px;
        color: #cb2626;
    }

    .bg-success {
        background-color: #29c761 !important;
    }
    .modal-content{
        border-radius: 10px;
    }
    .modal-header{
        border-radius: 10px 10px 0 0;
    }
    .modal-backdrop{
        background-color: rgba(0, 0, 0, 0.75);
    }
    .modal-backdrop.show {
        opacity: 1;
    }

    .modal-separator{
        height: 2px;
        background: #838383;
        width: 50px;
        border-radius: 1px;
    }
</style>
@endsection

@section('header')
<div class="modal fade" id="coberturaModal" tabindex="-1" role="dialog" aria-labelledby="coberturaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: unset;">
                <img src="" alt="Status Icon" style="position: absolute; width: 90px; top: 0; left: 42%; z-index: 1;">
            <div class="modal-content" style="margin-top: 30px;">
                <div class="modal-header py-1">
                    <div>
                        <h5 class="modal-title"></h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="result-content"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
</div>
@include("vendor.backpack.base.widgets.spinner_loader")
@endsection

@section('content')
<iframe
id="iframe_map"
src="{{$mapUrl}}"
style="width: 100%; height: 87vh; border: 0; border-radius: 5px;"
>
</iframe>
<script>
var f = function (event) {
    //console.log(event);
    if (event.data.action === "sendGeoResult") {
        if (event.data.content.trim() !== "") {
            let json = JSON.parse(event.data.content);
            consultarCobertura(json);
        }
    }
};
window.addEventListener("message", f, false);

//let frame = document.getElementById("iframe_map");
let store = {
    servicio: {},
};
// $("#iframe_map")[0].contentWindow.window.getElementById
function consultarCobertura(json)
{
    let icon = {
        error: "{{ asset('images/check-error.png') }}",
        success: "{{ asset('images/check-ok.png') }}",
    };
    //$("#coberturaModal .result-content").html("Cargando ...");
    $("#spinnerData").show();
    $("#coberturaModal").modal("hide");
    let api = `{{ asset('buscar-direccion/30/ConsultarCoberturaWS') }}?cuentaUser={{$username}}&application=PMONITOREO&tipo=FIJA&longitud=${json.longitud}&latitud=${json.latitud}`;
    fetch(api, {headers: {
        "Accept": "application/json"
    }})
    .then(resp => resp.json())
    .then(response => {
        if(response.message){
            throw Error(response.message);
        }
        let servicios = response.filter(row => row.COBERTURA === "SI");

        let servicioFtthVertical = servicios.filter(row => row.SERVICIO === "FTTH VERTICAL");
        let servicioFtthHorizontal = servicios.filter(row => row.SERVICIO === "FTTH HORIZONTAL");
        let servicioHfcVertical = servicios.filter(row => row.SERVICIO === "HFC VERTICAL");
        let servicioHfcHorizontal = servicios.filter(row => row.SERVICIO === "HFC HORIZONTAL");
        let servicioHfc = servicios.filter(row => row.SERVICIO === "HFC");
        let servicioVertical = servicios.filter(row => row.SERVICIO === "VERTICAL");
        
        let servicio = {};
        if(servicioFtthVertical.length > 0){
            servicio = servicioFtthVertical[0];
        }else if(servicioFtthHorizontal.length > 0){
            servicio = servicioFtthHorizontal[0];
        }else if(servicioHfcVertical.length > 0){
            servicio = servicioHfcVertical[0];
        }else if(servicioHfcHorizontal.length > 0){
            servicio = servicioHfcHorizontal[0];
        }else if(servicioHfc.length > 0){
            servicio = servicioHfc[0];
        }
        store.servicio = servicio;

        json.dep_prov_dist = `${json.departamento} / ${json.provincia} / ${json.distrito}`;
        json.mz_tipoVivienda = json.manzana ?? json.tipoVivienda;
        json = {...json, ...servicio.PARAMETROS};
        let fields = {
            tipoVia: {label: "AV/CALLE/JR"},
            nombreVia: {label: "NOMBRE DE LA VIA"},
            numeroVia: {label: "NÚMERO"},
            mz_tipoVivienda: {label: "MZ/BLOQ/EDIF"},
            manzana: {label: "MZ"},
            lote: {label: "LOTE"},
            tipoInterior: {label: "TIPO INTERIOR"},
            numInterior: {label: "NUM INTERIOR"},
            urbanizacion: {label: "URBANIZACIÓN"},
            tipoVivienda: {label: "TIPO DOMICILIO"},
            referencia: {label: "REFERENCIA PRINCIPAL"},
            c: {label: "", class: "p-1"},
            dep_prov_dist: {label: "DEP/PROV/DIST DEL CLIENTE"},
            d: {label: "", class: "p-1"},
            longitud: {label: "COORDENADAS X"},
            latitud: {label: "COORDENADAS Y"},
        };
        let htmlBody = Object.keys(fields).map(field => `<tr>
            <td>${fields[field].label}</td>
            <td class="text-center ${fields[field].class??''}">${json[field]??''}</td>
        </tr>`).join("");

        if(Object.keys(servicio).length === 0){
            $("#coberturaModal img").attr("src", icon.error);
            $("#coberturaModal .modal-header").removeClass("bg-error").removeClass("bg-success").addClass("bg-error");
            //$("#coberturaModal .modal-title").html("Sin Coobertura");
            $("#coberturaModal .result-content").html(`
            <h5 class="font-weight-bold m-3 pt-2 text-center text-error">SIN COBERTURA</h5>
            <div class="modal-separator mb-3"></div>
            <p class="text-center">No se econtraron datos para la busqueda</p>

            <h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
        
            <table class="w-100" style="font-weight: 600;">
            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
            ${htmlBody}
            </tbody></table>
            `);
            $("#coberturaModal").modal("show");
            $("#spinnerData").hide();
            return;
        }

        if(servicio.PARAMETROS['Categoría'] == 'Zona Peligrosa'){
            $("#coberturaModal img").attr("src", icon.error);
            $("#coberturaModal .modal-header").removeClass("bg-error").removeClass("bg-success").addClass("bg-error");
            //$("#coberturaModal .modal-title").html("Sin Coobertura");
            $("#coberturaModal .result-content").html(`
            <h5 class="font-weight-bold m-3 pt-2 text-center text-error">SIN COBERTURA</h5>
            <div class="modal-separator mb-3"></div>
            <p class="text-center">No se econtraron datos para la busqueda</p>

            <h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
        
            <table class="w-100" style="font-weight: 600;">
            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
            ${htmlBody}
            </tbody></table>
            `);
            $("#coberturaModal").modal("show");
            $("#spinnerData").hide();
            return;
        }

        let edificiosHtml = "";
        if(servicioVertical.length > 0){
            edificiosHtml = servicioVertical[0].PARAMETROS.map(row => {
                return `<option data-row='${JSON.stringify(row)}'>${row.Direccion}</option>`;
            }).join("");
        }

        edificiosHtml = `<select class="form-control form-control-sm select-edificios">
            <option>Seleccione</option>
            ${edificiosHtml}
        <select>`;
        servicio.PARAMETROS.edificios = edificiosHtml;

        let servicioFields = {
            "Plano": {label: "PLANO", class: "servicio-plano"},
            "Tecnología": {label: "TECNOLOGÍA"},
            "Provincia para Venta": {label: "PROVINCIA PARA VENTA"},
            "Distrito para Venta": {label: "DISTRITO PARA VENTA", class: "servicio-distrito"},
            //"edificios": {label: "EDIFICIOS"},
            "Clasificación de Velocidad Máxima": {label: "CLASIFICACIÓN DE VELOCIDAD MÁXIMA"},
            "Velocidad Máxima": {label: "VELOCIDAD MÁXIMA"},
        };
        if(servicioVertical.length > 0){
            if(servicioVertical[0].PARAMETROS.length > 0){
                servicioFields["edificios"] = {label: "EDIFICIOS"};
            }
        }
        let htmlServicioBody = Object.keys(servicioFields)
        .map(field => `<tr>
            <td>${servicioFields[field].label}</td>
            <td class="text-center ${servicioFields[field].class??''}">${servicio.PARAMETROS[field]??''}</td>
        </tr>`).join("");

        let htmlCategoria = '';

        if(servicio.PARAMETROS['Categoría'] == 'Fraude'){
            htmlCategoria = '<h5 class="font-weight-bold mb-0 text-danger">Requiere Aprobación de Gerencia</h5><br>';
        }

        let html = `
        <h5 class="font-weight-bold m-3 pt-2 text-center text-success">CON COBERTURA</h5>

        <div class="modal-separator mb-3"></div>
        
        <table class="mb-3 w-100" style="color: #0070C0; font-weight: 600;">
        <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
        ${htmlServicioBody}
        </tbody></table>        
        ${htmlCategoria}
        <h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
        
        <table class="w-100" style="font-weight: 600;">
        <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
        ${htmlBody}
        </tbody></table>`;

        $("#coberturaModal img").attr("src", icon.success);
        $("#coberturaModal .modal-header").removeClass("bg-error").removeClass("bg-success").addClass("bg-success");
        //$("#coberturaModal .modal-title").html("Con Cobertura");
        $("#coberturaModal .result-content").html(html);
        $("#coberturaModal").modal("show");
        $(".select-edificios").on("change", onChangeEdificioHandler);
        $("#spinnerData").hide();
    })
    .catch(err => {
        $("#coberturaModal img").attr("src", icon.error);
        $("#coberturaModal .modal-header").removeClass("bg-error").removeClass("bg-success").addClass("bg-error");
        //$("#coberturaModal .modal-title").html("Error al hacer la busqueda");
        $("#coberturaModal .result-content").html(`
        <h5 class="font-weight-bold m-3 pt-2 text-center text-error">OCURRION UN ERROR</h5>
        <div class="modal-separator mb-3"></div>
        <p class="text-center">${err}</p>
        `);
        $("#coberturaModal").modal("show");
        $("#spinnerData").hide();
    });
}

function onChangeEdificioHandler(e){
    let data = $(".select-edificios option:selected").attr("data-row");
    if(data){
        data = JSON.parse(data);
        $(".servicio-plano").text(data.Plano);
        $(".servicio-distrito").text(data["Distrito para Venta"]);
    }else{
        $(".servicio-plano").text(store.servicio.Plano);
        $(".servicio-distrito").text(store.servicio["Distrito para Venta"]);
    }
}
</script>
@endsection