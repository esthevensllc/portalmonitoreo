
const selectors = {
    getCoberturaServicioPrincipal: (servicios) => {
        let serviciosCobertura = servicios.filter(row => row.COBERTURA === "SI");

        let servicioFtthVertical = serviciosCobertura.filter(row => row.SERVICIO === "FTTH VERTICAL");
        let servicioFtthHorizontal = serviciosCobertura.filter(row => row.SERVICIO === "FTTH HORIZONTAL");
        let servicioHfcVertical = serviciosCobertura.filter(row => row.SERVICIO === "HFC VERTICAL");
        let servicioHfcHorizontal = serviciosCobertura.filter(row => row.SERVICIO === "HFC HORIZONTAL");
        let servicioHfc = serviciosCobertura.filter(row => row.SERVICIO === "HFC");

        let servicio = undefined;
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
        return servicio;
    },
    getCoberturaServicioVertical: (servicios) => {
        let serviciosCobertura = servicios.filter(row => row.COBERTURA === "SI");
        return serviciosCobertura.filter(row => row.SERVICIO === "VERTICAL")[0];
    },
    getTableBodyFromObject: (object, fields) => {
        return Object.keys(fields).map(field => `<tr>
            <td>${fields[field].label}</td>
            <td class="text-center ${fields[field].class??''}">${object[field]??''}</td>
        </tr>`).join("");
    }
};

class CoberturaModal{
    constructor(options){
        this.selector = options.selector;
        this.icon = {
            error: `${config.base_url}/images/check-error.png`,
            success: `${config.base_url}/images/check-ok.png`,
        };
    }

    hide(){
        $(this.selector).hide("show");
    }

    showSuccess(options){
        $(`${this.selector} img`).attr("src", this.icon.success);
        $(`${this.selector} .modal-header`).removeClass("bg-error").removeClass("bg-success").addClass("bg-success");
        //$("${this.selector} .modal-title").html("Sin Coobertura");
        $(`${this.selector} .result-content`).html(`
        <h5 class="font-weight-bold m-3 pt-2 text-center text-success">${options.title}</h5>
        <div class="modal-separator mb-3"></div>
        ${options.description ? '<p class="text-center">'+options.description+'</p>' : ''}

        ${options.html??''}
        `);
        $(this.selector).modal("show");
    }

    showError(options){
        $(`${this.selector} img`).attr("src", this.icon.error);
        $(`${this.selector} .modal-header`).removeClass("bg-error").removeClass("bg-success").addClass("bg-error");
        $(`${this.selector} .result-content`).html(`
        <h5 class="font-weight-bold m-3 pt-2 text-center text-error">${options.title}</h5>
        <div class="modal-separator mb-3"></div>
        ${options.description ? '<p class="text-center">'+options.description+'</p>' : ''}

        ${options.html??''}
        `);
        $(this.selector).modal("show");
    }
}

class CoordenadasViewModel {
    constructor(){
        this.store = {
            marker: undefined,
            infoWindow: undefined,
            edificiosMarker: []
        };
        this.map = undefined;
        this.initMap();
        this.coberturaModal = new CoberturaModal({selector: "#coberturaModal"});
        // this.ready();
        this.attachHandlers();
    }

    initMap(){
        this.map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-12.046035898836875, -77.02870893069844),
            zoom: 15,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
    }

    attachHandlers(){
        let self = this;
        let btnSearch = document.querySelector("#btn_search");

        /*document.querySelector("#input_lat_lon")
        .addEventListener("input", function(e){
            let input = e.target;
            input.value = input.value.replace(/[^0-9\.,\- ]/g, '');
        });*/

        document.querySelector('#input_lat_lon')
        .addEventListener('input', function(event){
            const input = event.target;
            const value = input.value;
            const validPattern = /^-?\d{0,2}(\.\d{0,15})?(,\s?-?\d{0,2}(\.\d{0,15})?)?$/;

            if (!validPattern.test(value)) {
                input.value = input.getAttribute('data-old-value') || '';
            } else {
                input.setAttribute('data-old-value', value);
            }
        });

        document.querySelector("#btn_search")
        .addEventListener("click", async function(e){
            let btnConfirmar = document.querySelector("#btn_confirmar");
            let value = document.querySelector("#input_lat_lon").value;
            let point = value.trim("").split(",").map(coord => parseFloat(coord));
            if(point.length >= 2){
                if(isNaN(point[0]) || isNaN(point[1])){
                    alert("La coordenada no es valida");
                    btnConfirmar.disabled = true;
                    return;
                }
                if(point[0] > 0){
                    alert("La latitud debe ser un número negativo");
                    btnConfirmar.disabled = true;
                    return;
                }
                if(point[1] > 0){
                    alert("La longitud debe ser un número negativo");
                    btnConfirmar.disabled = true;
                    return;
                }
            }else{
                alert("Ingresar latitud, longitud");
                btnConfirmar.disabled = true;
                return;
            }
            btnSearch.disabled = true;
            btnConfirmar.disabled = true;
            $("#spinnerData").show();
            point = {
                lat: point[0],
                lng: point[1]
            };
            self.map.setCenter(point);
            if(self.map.getZoom() < 18){
                self.map.setZoom(18);
            }

            if(self.store.marker){
                self.store.marker.setMap(null);
                self.store.marker = undefined;
            }

            let response = await fetch("https://servicios.analytics.pe/ClaroWidgetAPIRestGeoCodeAddress/api/Widget/ReverseGeocoding", {
                "method": "POST",
                body: JSON.stringify({
                    CantMaxResp: 1,
                    Latitud: point.lat,
                    Longitud: point.lng,
                    codigoUnico: 143901645
                }),
                headers: {"Content-Type": "application/json"}
            });
            let json = await response.json();
            if(json.success === false){
                alert(json.message);
                btnSearch.disabled = false;
                btnConfirmar.disabled = false;
                $("#spinnerData").hide();
                return;
            }
            let direccionSearch = json.Data[0];

            let direccion = {};
            if(direccionSearch){
                direccion = direccionSearch;
                let ubigeo = direccion.descripcionUbigeo.split("-");
                direccion.distrito = (ubigeo[2]??"").trim();
                direccion.provincia = (ubigeo[1]??"").trim();
                direccion.departamento = (ubigeo[0]??"").trim();
            }
            self.store.marker = new google.maps.Marker({
                map: self.map,
                position: point,
                data: direccion
            });

            if(self.store.infoWindow){
                self.store.infoWindow.close();
            }

            let fields = {
                TipoVia: {label: "AV/CALLE/JR"},
                NomVia: {label: "NOMBRE DE LA VIA"},
                NumeroPuerta1: {label: "NRO DE VIA"},
                TipoVivienda: {label: "MZ/BLOQ/EDIF"},
                Manzana: {label: "MZ"},
                Lote: {label: "LOTE"},
                NomUrb: {label: "URBANIZACIÓN"},
                distrito: {label: "DISTRITO"},
                provincia: {label: "PROVINCIA"},
                departamento: {label: "DEPARTAMENTO"},
                Longitud: {label: "LONGITUD"},
                Latitud: {label: "LATITUD"},
            };

            let htmlBody = selectors.getTableBodyFromObject(direccion, fields);
            self.store.infoWindow = new google.maps.InfoWindow({
                content: `<table class="table table-condensed table-sm">
                <thead><tr class="active"></tr></thead><tbody>
                ${htmlBody}
                </tbody></table>`
            });
            self.store.infoWindow.open(self.map, self.store.marker);
            self.store.marker.addListener("click", function(){
                if(self.store.infoWindow){
                    self.store.infoWindow.close();
                }
                let htmlBody = selectors.getTableBodyFromObject(direccion, fields);
                self.store.infoWindow = new google.maps.InfoWindow({
                    content: `<table class="table table-condensed table-sm">
                    <thead><tr class="active"></tr></thead><tbody>
                    ${htmlBody}
                    </tbody></table>`
                });
                self.store.infoWindow.open(self.map, self.store.marker);
            });

            self.store.edificiosMarker.forEach(marker => {
                marker.setMap(null);;
            });
            self.store.edificiosMarker = [];

            let api = `${config.base_url}/buscar-direccion/30/ConsultarCoberturaWS?application=PMONITOREO&tipo=FIJA&longitud=${point.lng}&latitud=${point.lat}`;
            fetch(api)
            .then(resp => resp.json())
            .then(response => {
                let servicio = selectors.getCoberturaServicioPrincipal(response);
                if(servicio){
                    fetch(`${config.base_url}/buscar-casa-plano/planos/${servicio.PARAMETROS["Plano"]}`)
                    .then(resp => resp.json())
                    .then(planos => {
                        let plano = planos[0];
                        let icon = {
                            // url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png", // URL de la imagen del marcador
                            url: `${config.base_url}/images/edificio.png`,
                            scaledSize: new google.maps.Size(40, 40),
                        };
                        if(plano){
                            self.store.edificiosMarker = plano.JSON_EDIFICIOS.map(row => {
                                return new google.maps.Marker({
                                    map: self.map,
                                    icon: icon,
                                    position: {
                                        lat: parseFloat(row.Latitud),
                                        lng: parseFloat(row.Longitud)
                                    }
                                });
                            });
                        }
                    });
                }
            });
            btnSearch.disabled = false;
            btnConfirmar.disabled = false;
            $("#spinnerData").hide();
        });

        document.querySelector("#btn_confirmar")
        .addEventListener("click", function(e){
            $("#spinnerData").show();
            let latitud = self.store.marker.position.lat();
            let longitud = self.store.marker.position.lng();

            let api = `${config.base_url}/buscar-direccion/30/ConsultarCoberturaWS?application=PMONITOREO&tipo=FIJA&longitud=${longitud}&latitud=${latitud}`;
            fetch(api, {headers: {
                "Accept": "application/json"
            }})
            .then(resp => resp.json())
            .then(response => {
                if(response.message){
                    throw Error(response.message);
                }
                let servicio = selectors.getCoberturaServicioPrincipal(response);
                let htmlBody = selectors.getTableBodyFromObject(self.store.marker.data, {
                    TipoVia: {label: "AV/CALLE/JR"},
                    NomVia: {label: "NOMBRE DE LA VIA"},
                    NumeroPuerta1: {label: "NRO DE VIA"},
                    TipoVivienda: {label: "MZ/BLOQ/EDIF"},
                    Manzana: {label: "MZ"},
                    Lote: {label: "LOTE"},
                    NomUrb: {label: "URBANIZACIÓN"},
                    distrito: {label: "DISTRITO"},
                    provincia: {label: "PROVINCIA"},
                    departamento: {label: "DEPARTAMENTO"},
                    // d: {label: "", class: "p-1"},
                    Longitud: {label: "LONGITUD"},
                    Latitud: {label: "LATITUD"}
                });
                if(servicio !== undefined){
                    if(servicio.PARAMETROS.Categoría != 'Zona Peligrosa'){
                        let servicioVertical = selectors.getCoberturaServicioVertical(response);

                        let servicioFields = {
                            "Plano": {label: "PLANO", class: "servicio-plano"},
                            "Tecnología": {label: "TECNOLOGÍA"},
                            "Provincia para Venta": {label: "PROVINCIA PARA VENTA"},
                            "Distrito para Venta": {label: "DISTRITO PARA VENTA", class: "servicio-distrito"},
                            // "edificios": {label: "EDIFICIOS"},
                            "Clasificación de Velocidad Máxima": {label: "CLASIFICACIÓN DE VELOCIDAD MÁXIMA"},
                            "Velocidad Máxima": {label: "VELOCIDAD MÁXIMA"},
                        };

                        let edificiosHtml = "";
                        if(servicioVertical !== undefined){
                            edificiosHtml = servicioVertical.PARAMETROS.map(row => {
                                return `<option data-row='${JSON.stringify(row)}'>${row.Direccion}</option>`;
                            }).join("");
                            if(servicioVertical.PARAMETROS.length > 0){
                                servicioFields["edificios"] = {label: "EDIFICIOS"};
                            }
                        }
                        edificiosHtml = `<select class="form-control form-control-sm select-edificios">
                            <option>Seleccione</option>
                            ${edificiosHtml}
                        <select>`;
                        servicio.PARAMETROS.edificios = edificiosHtml;

                        let htmlCategoria = '';

                        if(servicio.PARAMETROS.Categoría == 'Fraude'){
                            htmlCategoria = '<h5 class="font-weight-bold mb-0 text-danger">Requiere Aprobación de Gerencia</h5><br>';
                        }
                        
                        let htmlServicioBody = selectors.getTableBodyFromObject(servicio.PARAMETROS, servicioFields);
                        self.coberturaModal.showSuccess({
                            title: "CON COBERTURA",
                            html: `<table class="mb-3 w-100" style="color: #0070C0; font-weight: 600;">
                            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                            ${htmlServicioBody}
                            </tbody></table>
                            ${htmlCategoria}
                            <h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
                            
                            <table class="w-100" style="font-weight: 600;">
                            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                            ${htmlBody}
                            </tbody></table>`
                        });

                        if(document.querySelector(".select-edificios")){
                            document.querySelector(".select-edificios").addEventListener("change", function(e){
                                let edificioSelected = servicioVertical.PARAMETROS.filter(row => row.Direccion === e.target.value)[0];
                                if(edificioSelected){
                                    $(".servicio-plano").text(edificioSelected.Plano);
                                    $(".servicio-distrito").text(edificioSelected["Distrito para Venta"]);
                                }else{
                                    $(".servicio-plano").text(servicio.PARAMETROS.Plano);
                                    $(".servicio-distrito").text(servicio.PARAMETROS["Distrito para Venta"]);
                                }
                            });
                        }
                    } else {
                        self.coberturaModal.showError({
                            title: "SIN COBERTURA",
                            description: "No se encontraron datos para la busqueda",
                            html: `<h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
                            <table class="w-100" style="font-weight: 600;">
                            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                            ${htmlBody}
                            </tbody></table>`
                        });
                    }
                } else {
                    self.coberturaModal.showError({
                        title: "SIN COBERTURA",
                        description: "No se encontraron datos para la busqueda",
                        html: `<h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
                        <table class="w-100" style="font-weight: 600;">
                        <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                        ${htmlBody}
                        </tbody></table>`
                    });
                }
                $("#spinnerData").hide();
            })
            .catch(err => {
                $("#spinnerData").hide();
                self.coberturaModal.showError({
                    title: "OCURRION UN ERROR",
                    description: err.message,
                });
            });
        });

        document.querySelector("#btn_limpiar")
        .addEventListener("click", function(e){
            document.querySelector("#input_lat_lon").value = "";
            let btnConfirmar = document.querySelector("#btn_confirmar");
            btnConfirmar.disabled = true;
            if(self.store.marker){
                self.store.marker.setMap(null);
                self.store.marker = undefined;
            }
            if(self.store.infoWindow){
                self.store.infoWindow.close();
                self.store.infoWindow = undefined;
            }
        });
    }
}

class SuggestionsComponent {
    constructor(options){
        this.selector = options.selector;
        this.target = options.target;
        this.attachHandlers();
        this.hide();
        this.value = "";
        this.valueToken = "";
    }

    attachHandlers(){
        let self = this;
        let mainElement = document.querySelector(this.selector);
        let targetElement = document.querySelector(this.target);
        /*targetElement.addEventListener("focusin", function(e){
            self.show();
        });*/
        let focusoutInterval;
        let counter = 0;
        targetElement.addEventListener("focusout", function(e){
            if(focusoutInterval){
                clearInterval(focusoutInterval);
                counter = 0;
            }
            focusoutInterval = setInterval(() => {
                if(self.valueToken !== "" || counter >= 4){
                    clearInterval(focusoutInterval);
                    self.valueToken = "";
                    self.hide();
                }else{
                    counter = counter + 1;
                }
            }, 50);
        });

        mainElement.addEventListener("click", function(e){
            if(e.target.classList.contains("suggestions-row")){
                self.hide();
                self.value = e.target.attributes["data-value"].value;
                self.label = e.target.textContent;
                self.valueToken = (new Date()).getTime();
                targetElement.value = self.label;
                let event = new CustomEvent("change", {
                    detail: {value: self.value, label: self.label}
                });
                mainElement.dispatchEvent(event);
            }
        });
    }

    setList(values){
        let html = values.map(row => `<div class="suggestions-row" data-value="${row.id}">${row.label}</div>`).join("");
        document.querySelector(this.selector).innerHTML = html;
    }
    
    addEventListener(event, handler){
        let self = this;
        document.querySelector(this.selector)
        .addEventListener(event, handler);
    }

    show(){
        document.querySelector(this.selector).style.display = '';
    }

    hide(){
        document.querySelector(this.selector).style.display = 'none';
    }

    getValue(){
        return this.value;
    }

    setValue(value){
        this.value = value;
        // document.querySelector(this.target).value = value;
    }
}

class EdificiosViewModel{
    constructor(){
        this.store = {
            marker: undefined,
            infoWindow: undefined,
        };
        this.map = undefined;
        this.ubigeos = config.ubigeos;
        this.edificios = [];
        this.edificiosPlano = [];
        this.initMap();
        this.coberturaModal = new CoberturaModal({selector: "#coberturaModal"});
        this.ubigeoSuggestions = new SuggestionsComponent({selector: "#suggestions_ubigeo", target: "#input_ubigeo"});
        this.edificioSuggestions = new SuggestionsComponent({selector: "#suggestions_edificio", target: "#input_edificio"});
        this.attachHandlers();
    }

    initMap(){
        this.map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-12.046035898836875, -77.02870893069844),
            zoom: 15,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
    }

    attachHandlers(){
        let self = this;
        let ubigeoInput = document.querySelector("#input_ubigeo");
        let edificioInput = document.querySelector("#input_edificio");
        let btnConfirmar = document.querySelector("#btn_confirmar");

        document.querySelector("#input_ubigeo")
        .addEventListener("focusin", function(e){
            self.ubigeoSuggestions.show();
        });
        document.querySelector("#input_ubigeo")
        .addEventListener("input", function(e){
            let input = e.target;
            input.value = input.value.replace(/[^a-zA-Z0-9ñÑ_\- ]/g, '').toUpperCase();
        });
        let ubigeoTimeout;
        document.querySelector("#input_ubigeo")
        .addEventListener("keyup", function(e){
            if(ubigeoTimeout){
                clearTimeout(ubigeoTimeout);
            }
            ubigeoTimeout = setTimeout(() => {
                let value = e.target.value.toLowerCase().trim();
                let results = self.ubigeos.filter(row => value !== "" && row.UBIGEO_DESC.toLowerCase().includes(value))
                .map(row => ({id: row.UBIGEO_INEI, label: row.UBIGEO_DESC}))
                .filter((_, index) => index < 5);
                self.ubigeoSuggestions.setList(results);
                self.ubigeoSuggestions.setValue("");
                self.ubigeoSuggestions.show();

                self.edificioSuggestions.setList([]);
                self.edificioSuggestions.setValue("");
                edificioInput.value = "";
                btnConfirmar.disabled = true;
            }, 250);
        });

        self.ubigeoSuggestions.addEventListener("change", function(e){
            let ubigeo = e.detail.value;
            fetch(`${config.base_url}/buscar-edificios/by-ubigeo/${ubigeo}`)
            .then(response => response.json())
            .then(response => {
                self.ubigeoSuggestions.setList([]);
                self.edificios = response.map((row, index) => ({...row, _id: index+1}));
            });
        });

        document.querySelector("#input_edificio")
        .addEventListener("focusin", function(e){
            self.edificioSuggestions.show();
        });
        document.querySelector("#input_edificio")
        .addEventListener("input", function(e){
            let input = e.target;
            input.value = input.value.replace(/[^a-zA-Z0-9ñÑ_\-\. ]/g, '').toUpperCase();
        });
        let edificioTimeout;
        document.querySelector("#input_edificio")
        .addEventListener("keyup", function(e){
            if(edificioTimeout){
                clearTimeout(edificioTimeout);
            }
            edificioTimeout = setTimeout(() => {
                if(self.ubigeoSuggestions.getValue() !== ""){
                    let value = e.target.value.toLowerCase().trim();
                    let results = self.edificios.filter(row => value !== "" && `${row.DIRECCION}`.toLowerCase().includes(value))
                    .map(row => ({
                        id: row._id,
                        label: `${row.DIRECCION}`
                    }));
                    // .filter((_, index) => index < 5);
                    self.edificioSuggestions.setList(results);
                    self.edificioSuggestions.setValue("");
                    self.edificioSuggestions.show();
                    btnConfirmar.disabled = true;
                }
            }, 250);
        });

        self.edificioSuggestions.addEventListener("change", function(e){
            // let id = e.detail.value;
            self.edificioSuggestions.setList([]);
        });

        document.querySelector("#btn_search")
        .addEventListener("click", function(e){
            let id = self.edificioSuggestions.getValue();
            let edificio = self.edificios.filter(row => `${row._id}` === `${id}`)[0];
            if(edificio === undefined){
                btnConfirmar.disabled = true;
                alert("Seleccione un edificio antes de buscar");
                return;
            } else {
                btnConfirmar.disabled = false;
                let point = {lat: parseFloat(edificio.LATITUD), lng: parseFloat(edificio.LONGITUD)};
                self.map.setCenter(point);
                if(self.map.getZoom() < 18){
                    self.map.setZoom(18);
                }

                if(self.store.marker){
                    self.store.marker.setMap(null);
                    self.store.marker = undefined;
                }

                let ubigeoParts = ubigeoInput.value.split("-");
                edificio.distrito = (ubigeoParts[0]??"").trim();
                edificio.provincia = (ubigeoParts[1]??"").trim();
                edificio.departamento = (ubigeoParts[2]??"").trim();

                self.store.marker = new google.maps.Marker({
                    map: self.map,
                    position: point,
                    data: edificio
                });

                if(self.store.infoWindow){
                    self.store.infoWindow.close();
                }
                let content = `
                        <table class="table table-condensed table-sm">
                        <thead><tr class="active"></tr></thead>
                        <tbody>  
                        <tr><td>PLANO</td><td>${edificio.PLANO}</td></tr>
                        <tr><td>EDIFICIO</td><td>${edificio.EDIFICIO ?? ''}</td></tr>
                        <tr><td>NOMBRE_EDIFICIO</td><td>${edificio.NOMBRE_EDIFICIO ?? ''}</td></tr>
                        <tr><td>DIRECCION</td><td>${edificio.DIRECCION ?? ''}</td></tr>
                        <tr><td>LONGITUD</td><td>${edificio.LONGITUD ?? ''}</td></tr>
                        <tr><td>LATITUD</td><td>${edificio.LATITUD ?? ''}</td></tr>
                        <tr><td>REGION</td><td>${edificio.REGION ?? ''}</td></tr>
                        <tr><td>TECNOLOGIA</td><td>${edificio.TECNOLOGIA ?? ''}</td></tr>
                        <tr><td>N_PISOS</td><td>${edificio.N_PISOS ?? ''}</td></tr>
                        <tr><td>N_DPTOS</td><td>${edificio.N_DPTOS ?? ''}</td></tr>
                        <tr><td>DISTRITO</td><td>${edificio.distrito ?? ''}</td></tr>
                        <tr><td>PROVINCIA</td><td>${edificio.provincia ?? ''}</td></tr>
                        <tr><td>DEPARTAMENTO</td><td>${edificio.departamento ?? ''}</td></tr>
                        <tr><td>CLASIFICACIÓN DE VELOCIDAD MÁXIMA</td><td>${edificio.CLASIF_VELOCIDAD_MAX ?? ''}</td></tr>
                        <tr><td>VELOCIDAD MÁXIMA</td><td>${edificio.VELOCIDDAD_MAX ?? ''}</td></tr>`;

                if (edificio.CATEGORIA !== null && edificio.CATEGORIA !== undefined) {
                    content += `<tr><td>CATEGORÍA</td><td>${edificio.CATEGORIA}</td></tr>`;
                }

                content += `</tbody></table>`;

                self.store.infoWindow = new google.maps.InfoWindow({
                    content: content
                });

                self.store.infoWindow.open(self.map, self.store.marker);
                self.store.marker.addListener("click", function(){
                    self.store.infoWindow.open(self.map, self.store.marker);
                });
            }
        });

        document.querySelector("#btn_confirmar")
        .addEventListener("click", function(e){
            $("#spinnerData").show();
            let latitud = self.store.marker.position.lat();
            let longitud = self.store.marker.position.lng();
            let edificio = self.store.marker.data;

            let api = `${config.base_url}/buscar-direccion/30/ConsultarCoberturaWS?application=PMONITOREO&tipo=FIJA&longitud=${longitud}&latitud=${latitud}`;
            fetch(api, {headers: {
                "Accept": "application/json"
            }})
            .then(resp => resp.json())
            .then(response => {
                if(response.message){
                    throw Error(response.message);
                }
                let servicio = selectors.getCoberturaServicioPrincipal(response);
                let servicioVertical = selectors.getCoberturaServicioVertical(response);
                if(servicio === undefined){
                    if(servicioVertical !== undefined){
                        let verticalEdificio = servicioVertical.PARAMETROS.filter(row => row.Direccion === edificio.DIRECCION)[0];
                        if(verticalEdificio !== undefined){
                            servicio = {PARAMETROS: verticalEdificio};
                        }
                    }
                }
                if(servicio === undefined){
                    servicio = {PARAMETROS: {
                        "Plano": edificio.PLANO,
                        "Tecnología": edificio.TECNOLOGIA,
                        "Provincia para Venta": edificio.provincia,
                        "Distrito para Venta": edificio.distrito,
                        "Clasificación de Velocidad Máxima": edificio.CLASIF_VELOCIDAD_MAX,
                        "Velocidad Máxima": edificio.VELOCIDDAD_MAX,
                    }};
                }
                if(servicio !== undefined){
                    if(servicio.PARAMETROS.Categoría != 'Zona Peligrosa'){
                        // let servicioVertical = selectors.getCoberturaServicioVertical(response);
                        self.edificiosPlano = servicioVertical.PARAMETROS;

                        let servicioFields = {
                            "Plano": {label: "PLANO", class: "servicio-plano"},
                            "Tecnología": {label: "TECNOLOGÍA"},
                            "Provincia para Venta": {label: "PROVINCIA PARA VENTA"},
                            "Distrito para Venta": {label: "DISTRITO PARA VENTA", class: "servicio-distrito"},
                            // "edificios": {label: "EDIFICIOS"},
                            "Clasificación de Velocidad Máxima": {label: "CLASIFICACIÓN DE VELOCIDAD MÁXIMA"},
                            "Velocidad Máxima": {label: "VELOCIDAD MÁXIMA"},
                        }

                        let edificiosHtml = "";
                        if(servicioVertical !== undefined){
                            edificiosHtml = servicioVertical.PARAMETROS.map(row => {
                                return `<option data-row='${JSON.stringify(row)}'>${row.Direccion}</option>`;
                            }).join("");
                            if(servicioVertical.PARAMETROS.length > 0){
                                servicioFields["edificios"] = {label: "EDIFICIOS"};
                            }
                        }
                        edificiosHtml = `<select class="form-control form-control-sm select-edificios">
                            <option>Seleccione</option>
                            ${edificiosHtml}
                        <select>`;
                        servicio.PARAMETROS.edificios = edificiosHtml;
                        
                        let htmlServicioBody = selectors.getTableBodyFromObject(servicio.PARAMETROS, servicioFields);
                        let htmlBody = selectors.getTableBodyFromObject(self.store.marker.data, {
                            "PLANO": {"label": "PLANO"},
                            "EDIFICIO": {"label": "EDIFICIO"},
                            "NOMBRE_EDIFICIO": {"label": "NOMBRE_EDIFICIO"},
                            "DIRECCION": {"label": "DIRECCION"},
                            "LONGITUD": {"label": "LONGITUD"},
                            "LATITUD": {"label": "LATITUD"},
                            "REGION": {"label": "REGION"},
                            "TECNOLOGIA": {"label": "TECNOLOGIA"},
                            "N_PISOS": {"label": "N_PISOS"},
                            "N_DPTOS": {"label": "N_DPTOS"},
                            "distrito": {"label": "DISTRITO"},
                            "provincia": {"label": "PROVINCIA"},
                            "departamento": {"label": "DEPARTAMENTO"},
                        });

                        let htmlCategoria = '';

                        if(servicio.PARAMETROS.Categoría == 'Fraude'){
                            htmlCategoria = '<h5 class="font-weight-bold mb-0 text-danger">Requiere Aprobación de Gerencia</h5><br>';
                        }

                        self.coberturaModal.showSuccess({
                            title: "CON COBERTURA",
                            html: `<table class="mb-3 w-100" style="color: #0070C0; font-weight: 600;">
                            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                            ${htmlServicioBody}
                            </tbody></table>
                            ${htmlCategoria}
                            <h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
                            
                            <table class="w-100" style="font-weight: 600;">
                            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                            ${htmlBody}
                            </tbody></table>`
                        });
                        if(document.querySelector(".select-edificios")){
                            document.querySelector(".select-edificios").addEventListener("change", function(e){
                                let edificioSelected = self.edificiosPlano.filter(row => row.Direccion === e.target.value)[0];
                                if(edificioSelected){
                                    $(".servicio-plano").text(edificioSelected.Plano);
                                    $(".servicio-distrito").text(edificioSelected["Distrito para Venta"]);
                                }else{
                                    $(".servicio-plano").text(servicio.PARAMETROS.Plano);
                                    $(".servicio-distrito").text(servicio.PARAMETROS["Distrito para Venta"]);
                                }
                            });
                            document.querySelector(".select-edificios").value = edificio.DIRECCION;
                            document.querySelector(".select-edificios").dispatchEvent(new Event("change"));
                        }
                    } else {
                        let htmlBody = selectors.getTableBodyFromObject(self.store.marker.data, {
                            "PLANO": {"label": "PLANO"},
                            "EDIFICIO": {"label": "EDIFICIO"},
                            "NOMBRE_EDIFICIO": {"label": "NOMBRE_EDIFICIO"},
                            "DIRECCION": {"label": "DIRECCION"},
                            "LONGITUD": {"label": "LONGITUD"},
                            "LATITUD": {"label": "LATITUD"},
                            "REGION": {"label": "REGION"},
                            "TECNOLOGIA": {"label": "TECNOLOGIA"},
                            "N_PISOS": {"label": "N_PISOS"},
                            "N_DPTOS": {"label": "N_DPTOS"},
                            "distrito": {"label": "DISTRITO"},
                            "provincia": {"label": "PROVINCIA"},
                            "departamento": {"label": "DEPARTAMENTO"},
                        });
                        self.coberturaModal.showError({
                            title: "SIN COBERTURA",
                            description: "No se encontraron datos para la busqueda",
                            html: `<h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
                            <table class="w-100" style="font-weight: 600;">
                            <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                            ${htmlBody}
                            </tbody></table>`
                        });
                    }
                } else {
                    let htmlBody = selectors.getTableBodyFromObject(self.store.marker.data, {
                        "PLANO": {"label": "PLANO"},
                        "EDIFICIO": {"label": "EDIFICIO"},
                        "NOMBRE_EDIFICIO": {"label": "NOMBRE_EDIFICIO"},
                        "DIRECCION": {"label": "DIRECCION"},
                        "LONGITUD": {"label": "LONGITUD"},
                        "LATITUD": {"label": "LATITUD"},
                        "REGION": {"label": "REGION"},
                        "TECNOLOGIA": {"label": "TECNOLOGIA"},
                        "N_PISOS": {"label": "N_PISOS"},
                        "N_DPTOS": {"label": "N_DPTOS"},
                        "distrito": {"label": "DISTRITO"},
                        "provincia": {"label": "PROVINCIA"},
                        "departamento": {"label": "DEPARTAMENTO"},
                    });
                    self.coberturaModal.showError({
                        title: "SIN COBERTURA",
                        description: "No se encontraron datos para la busqueda",
                        html: `<h6 class="font-weight-bold mb-0">DATOS DE DIRECCIÓN DEL CLIENTE</h6>
                        <table class="w-100" style="font-weight: 600;">
                        <thead><tr><th style="width: 220px;"></th><th></th></tr></thead><tbody>
                        ${htmlBody}
                        </tbody></table>`
                    });
                }
                $("#spinnerData").hide();
            })
            .catch(err => {
                $("#spinnerData").hide();
                self.coberturaModal.showError({
                    title: "OCURRION UN ERROR",
                    description: err.message,
                });
            });
        });

        document.querySelector("#btn_limpiar")
        .addEventListener("click", function(e){
            ubigeoInput.value = "";
            edificioInput.value = "";
            let btnConfirmar = document.querySelector("#btn_confirmar");
            btnConfirmar.disabled = true;
            if(self.store.marker){
                self.store.marker.setMap(null);
                self.store.marker = undefined;
            }
            if(self.store.infoWindow){
                self.store.infoWindow.close();
                self.store.infoWindow = undefined;
            }

            self.ubigeoSuggestions.setList([]);
            self.ubigeoSuggestions.setValue("");

            self.edificioSuggestions.setList([]);
            self.edificioSuggestions.setValue("");
        });
    }
}

class PLanosViewModel {
    constructor(){
        this.store = {
            marker: undefined,
            edificiosMarker: [],
            infoWindow: undefined,
        };
        this.map = undefined;
        this.initMap();
        this.planos = [];
        this.coberturaModal = new CoberturaModal({selector: "#coberturaModal"});
        this.attachHandlers();
    }

    initMap(){
        this.map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-12.046035898836875, -77.02870893069844),
            zoom: 15,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
    }

    attachHandlers(){
        let self = this;

        let inputPlano = document.querySelector("#input_plano");
        let selectPlanos = document.querySelector("#select_planos");
        let btnSearch = document.querySelector("#btn_search");
        let btnConfirmar = document.querySelector("#btn_confirmar");

        inputPlano.addEventListener("input", function(e){
            let input = e.target;
            input.value = input.value.replace(/[^a-zA-Z0-9ñÑ_\-]/g, '').toUpperCase();
        });

        btnSearch.addEventListener("click", function(e){
            $("#spinnerData").show();
            selectPlanos.disabled = true;
            btnSearch.disabled = true;
            let plano = document.querySelector("#input_plano").value;
            // console.log("plano", plano);
            fetch(`${config.base_url}/buscar-casa-plano/planos/${plano}`)
            .then(response => response.json())
            .then(planos => {
                self.planos = planos;
                let html = planos.map(row => `<option>${row.PLANO}</option>`).join("");
                selectPlanos.innerHTML = `<option value="">Seleccione</option>${html}`;
                selectPlanos.disabled = false;
                btnSearch.disabled = false;
                $("#spinnerData").hide();
            }).catch(error => {
                selectPlanos.disabled = false;
                btnSearch.disabled = false;
                $("#spinnerData").hide();
            });
        });

        document.querySelector("#select_planos")
        .addEventListener("change", function(e){
            let plano = self.planos.filter(row => row.PLANO === e.target.value)[0];
            if(self.store.marker){
                self.store.marker.setMap(null);
                self.store.marker = undefined;
            }
            self.store.edificiosMarker.forEach(marker => {
                marker.setMap(null);
            });
            self.store.edificiosMarker = [];

            let point = {lat: parseFloat(plano.CENTROIDE_Y), lng: parseFloat(plano.CENTROIDE_X)};
            self.map.setCenter(point);
            if(self.map.getZoom() < 18){
                self.map.setZoom(18);
            }

            self.store.marker = new google.maps.Marker({
                map: self.map,
                position: point,
                data: plano
            });
            if(self.store.infoWindow){
                self.store.infoWindow.close();
            }

            let content = `
                <table class="table table-condensed table-sm">
                <thead><tr class="active"></tr>
                </thead>
                <tbody>  
                <tr><td>Plano</td><td class="font-weight-bold">${plano.PLANO}</td></tr>
                <tr><td>Latitud</td><td>${plano.CENTROIDE_Y}</td></tr>
                <tr><td>Longitud</td><td>${plano.CENTROIDE_X}</td></tr>
                <tr><td>Clasificación de Velocidad Máxima</td><td>${plano.CLASIF_VELOCIDAD_MAX}</td></tr>
                <tr><td>Velocidad Máxima</td><td>${plano.VELOCIDDAD_MAX}</td></tr>`;

            if (plano.CATEGORIA !== null && plano.CATEGORIA !== undefined) {
                content += `<tr><td>Categoría</td><td>${plano.CATEGORIA}</td></tr>`;
            }

            console.log(plano.CATEGORIA);

            content += `</tbody></table>`;

            self.store.infoWindow = new google.maps.InfoWindow({
                content: content
            });

            self.store.infoWindow.open(self.map, self.store.marker);
            self.store.marker.addListener("click", function(){
                if(self.store.infoWindow){
                    self.store.infoWindow.close();
                }
                self.store.infoWindow = new google.maps.InfoWindow({
                    content: `<table class="table table-condensed table-sm">
                    <thead><tr class="active"></tr>
                    </thead>
                    <tbody>  
                    <tr><td>Plano</td><td class="font-weight-bold">${plano.PLANO}</td></tr>
                    <tr><td>Latitud</td><td>${plano.CENTROIDE_Y}</td></tr>
                    <tr><td>Longitud</td><td>${plano.CENTROIDE_X}</td></tr>
                    <tr><td>Clasificación de Velocidad Máxima</td><td>${plano.CLASIF_VELOCIDAD_MAX}</td></tr>
                    <tr><td>Velocidad Máxima</td><td>${plano.VELOCIDDAD_MAX}</td></tr>
                    </tbody>
                    </table>`
                });
                self.store.infoWindow.open(self.map, self.store.marker);
            });

            let icon = {
                // url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png", // URL de la imagen del marcador
                url: `${config.base_url}/images/edificio.png`, // URL de la imagen del marcador
                scaledSize: new google.maps.Size(40, 40), // Tamaño opcional del marcador
            };
            self.store.edificiosMarker = plano.JSON_EDIFICIOS.map(row => {
                return new google.maps.Marker({
                    map: self.map,
                    icon: icon,
                    data: row,
                    position: {
                        lat: parseFloat(row.Latitud),
                        lng: parseFloat(row.Longitud)
                    }
                });
            });
            self.store.edificiosMarker.forEach(marker => {
                marker.addListener("click", function(e){
                    // console.log(marker.data);
                    if(self.store.infoWindow){
                        self.store.infoWindow.close();
                    }
                    self.store.infoWindow = new google.maps.InfoWindow({
                        content: `<table class="table table-condensed table-sm">
                        <thead><tr class="active"></tr>
                        </thead>
                        <tbody>  
                        <tr><td>Plano</td><td>${marker.data.Plano}</td></tr>
                        <tr><td>Dirección</td><td>${marker.data['Dirección']??''}</td></tr>
                        <tr><td>Tecnologia</td><td>${marker.data.Tecnologia??''}</td></tr>
                        <tr><td>Distrito</td><td>${marker.data.Distrito??''}</td></tr>
                        <tr><td>Departamento</td><td>${marker.data.Departamento??''}</td></tr>
                        <tr><td>Región</td><td>${marker.data.Región??''}</td></tr>
                        <tr><td>Nombre</td><td>${marker.data.Nombre??''}</td></tr>
                        <tr><td>Edifico</td><td>${marker.data.Edifico??''}</td></tr>
                        <tr><td>N° Pisos</td><td>${marker.data['N° Pisos']??''}</td></tr>
                        <tr><td>N° Departamentos</td><td>${marker.data['N° Departamentos']??''}</td></tr>
                        <tr><td>Latitud</td><td>${marker.data.Latitud??''}</td></tr>
                        <tr><td>Longitud</td><td>${marker.data['Longitud']??''}</td></tr>
                        </tbody>
                        </table>`
                    });
                    self.store.infoWindow.open(self.map, marker);
                });
            });
        });

        document.querySelector("#btn_limpiar")
        .addEventListener("click", function(e){
            document.querySelector("#input_plano").value = "";
            selectPlanos.value = "";
            selectPlanos.innerHTML = `<option value="">Seleccione</option>`;
            
            if(self.store.marker){
                self.store.marker.setMap(null);
                self.store.marker = undefined;
            }
            if(self.store.infoWindow){
                self.store.infoWindow.close();
                self.store.infoWindow = undefined;
            }

            self.store.edificiosMarker.forEach(marker => {
                marker.setMap(null);
            });
            self.store.edificiosMarker = [];
        });
    }
}