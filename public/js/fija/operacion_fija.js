
class MapWrapper
{
    constructor(map){
        this.map = map;
        this.store = {
            layers: {}
        };
    }

    addLayer(layerName, source, style){
        let self = this;

        return new Promise((resolve, reject) => {
            self.removeLayer(layerName);
            // let features = self.map.data.addGeoJson(geojson);
            self.map.data.loadGeoJson(source, {}, function(features){
                self.store.layers[layerName+"_features"] = features;
    
                features.forEach(feature => {
                    self.map.data.overrideStyle(feature, style);
    
                    feature.setProperty("capa", layerName);
    
                    // Añade el listener de clic para mostrar el popup
                    // google.maps.event.addListener(self.map.data, 'click', function(event) {
                    //     if (event.feature === feature) {
                    //         self.showPolygonInfo(event);
                    //     }
                    // });
                });
                
                resolve(features);
            });
        });
    }

    removeLayer(layerName){
        let self = this;
        if(self.store.layers[layerName+"_features"] !== undefined){
            self.store.layers[layerName+"_features"].forEach(feature => {
                self.map.data.remove(feature);
            });
        }
    }
    
}

class GraphModal{
    constructor(el_selector){
        this.el_selector = el_selector;
        this.attachHandlers();
    }

    attachHandlers(){
        let el_selector = this.el_selector;
        $(el_selector+' .lgnd-close').on('click', function(){
            $(el_selector).hide();
        });
        $(el_selector).draggable({handle: ".lgnd-header"});
    }
    
    show(){
        $(this.el_selector).show();
    }
    hide(){
        $(this.el_selector).hide();
    }

    setTitle(title){
        $(this.el_selector+' .title').text(title);
    }
}

class PanelData extends GraphModal{
    constructor(el_selector){
        super(el_selector);
        this.data = {};
        this.updateData = {};
        this.mode = "read";
        this.selector = document.querySelector(el_selector);
        //this.selector.attributes["data-mode"].value = this.mode;
        // this.el_selector = el_selector;
        // this.attachHandlers();
        this.defaultData = {
            id: null,
            plano: null,
            tipo_nodo: null,
            tipo_fuente: null,
            ubicado_en: null,
            tiene_baterias: null,
            anio_fab: null,
            tipo_respaldo: null,
            amp: null,
            candado: null,
            barra: null,
            seguro_h: null,
            seguro_baterias: null,
            fecha_manto: null,
            anio_manto: null,
            mes_manto: null,
            referido: null,
            region: null,
            distrito: null,
            segmento_urbano: null,
            gestion_campo: null,
            nivel_de_seguridad: null,
            comentario: null,
            latitud: null,
            longitud: null
        }
    }

    attachHandlers(){
        super.attachHandlers();
        let self = this;
        
        let btnConfirmCreate = document.querySelector(".panel-data-actions .btn-confirm-create");
        let btnCancelCreate = document.querySelector(".panel-data-actions .btn-cancel-create");

        let btnEdit = document.querySelector(".panel-data-actions .btn-edit");
        let btnConfirmUpdate = document.querySelector(".panel-data-actions .btn-confirm-edit");

        let btnDelete = document.querySelector(".panel-data-actions .btn-delete");
        let btnConfirmDelete = document.querySelector(".panel-data-actions .btn-confirm-delete");
        let btnConfirmCancel = document.querySelector(".panel-data-actions .btn-confirm-cancel");

        let btnUploadImg = document.querySelector(".panel-data-actions .btn-upload-img");
        btnConfirmCreate.addEventListener("click", function(e){
            let event = new CustomEvent("confirm-create", {
                detail: {...self.defaultData, ...self.updateData},
                bubbles: true,
                composed: true
            });
            self.selector.dispatchEvent(event);
        });
        btnCancelCreate.addEventListener("click", function(e){
            self.setMode("read");
            self.hide();
            self.render();
            let event = new CustomEvent("cancel-create", {
                detail: {},
                bubbles: true,
                composed: true
            });
            self.selector.dispatchEvent(event);
        });

        btnEdit.addEventListener("click", function(e){
            if(self.mode === "read"){
                self.setMode("update");
                self.updateData = {...self.data};
            }else{
                self.setMode("read");
            }
            self.render();
        });
        btnConfirmUpdate.addEventListener("click", function(e){
            let event = new CustomEvent("confirm-update", {
                detail: {...self.updateData},
                bubbles: true,
                composed: true
            });
            self.selector.dispatchEvent(event);
        });

        btnDelete.addEventListener("click", function(e){
            self.setMode("delete");
            self.render();
        });
        btnConfirmDelete.addEventListener("click", function(e){
            let event = new CustomEvent("confirm-delete", {
                detail: {...self.data},
                bubbles: true,
                composed: true
            });
            self.selector.dispatchEvent(event);
        });
        btnConfirmCancel.addEventListener("click", function(e){
            self.setMode("read");
            self.render();
        });

        btnUploadImg.addEventListener("click", function(e){
            let event = new CustomEvent("upload-image", {
                detail: {...self.data},
                bubbles: true,
                composed: true
            });
            self.selector.dispatchEvent(event);
        });
    }

    setData(data){
        this.data = data;
        this.updateData = {...data};
    }

    setMode(mode){
        this.mode = mode;
    }

    render(){
        let self = this;
        let html = ``;
        if(this.mode === "read" || this.mode === "delete"){
            html = `${self.getHtmlImgSlide(this.data)}
            <table class="tbl tblMonit mb-0" style="min-width: 290px;">
            <thead><tr class="active"></tr>
            </thead><tbody>
            <tr><td>Plano</td><td>${this.data.plano??''}</td></tr>
            <tr><td>Tipo Nodo</td><td>${this.data.tipo_nodo??''}</td></tr>
            <tr><td>Tipo Fuente</td><td>${this.data.tipo_fuente??''}</td></tr>
            <tr><td>Ubicado En</td><td>${this.data.ubicado_en??''}</td></tr>
            <tr><td>Tiene Baterias</td><td>${this.data.tiene_baterias??''}</td></tr>
            <tr><td>Año Fab</td><td>${this.data.anio_fab??''}</td></tr>
            <tr><td>Tipo Respaldo</td><td>${this.data.tipo_respaldo??''}</td></tr>
            <tr><td>Amp</td><td>${this.data.amp??''}</td></tr>
            <tr><td>Candado</td><td>${this.data.candado??''}</td></tr>
            <tr><td>Barra</td><td>${this.data.barra??''}</td></tr>
            <tr><td>Seguro H</td><td>${this.data.seguro_h??''}</td></tr>
            <tr><td>Seguro Baterias</td><td>${this.data.seguro_baterias??''}</td></tr>
            <tr><td>Fecha Manto</td><td>${this.data.fecha_manto??''}</td></tr>
            <tr><td>Año Manto</td><td>${this.data.anio_manto??''}</td></tr>
            <tr><td>Mes Manto</td><td>${this.data.mes_manto??''}</td></tr>
            <tr><td>Referido</td><td>${this.data.referido??''}</td></tr>
            <tr><td>Region</td><td>${this.data.region??''}</td></tr>
            <tr><td>Distrito</td><td>${this.data.distrito??''}</td></tr>
            <tr><td>Segmento Urbano</td><td>${this.data.segmento_urbano??''}</td></tr>
            <tr><td>Gestión Campo</td><td>${this.data.gestion_campo??''}</td></tr>
            <tr><td>Nivel de Seguridad</td><td>${this.data.nivel_de_seguridad??''}</td></tr>
            <tr><td>Comentario</td><td>${this.data.comentario??''}</td></tr>
            <tr><td>Latitud</td><td>${this.data.latitud??''}</td></tr>
            <tr><td>Longitud</td><td>${this.data.longitud??''}</td></tr>
            </tbody></table>`;
        }else if(this.mode === "update" || this.mode === "create"){
            html = `<table class="tbl tblMonit mb-0" style="min-width: 290px;">
            <thead><tr class="active"></tr>
            </thead><tbody>
            <tr><td>Plano</td><td><input type="text" name="plano" value="${this.updateData.plano??''}" autocomplete="off"></td></tr>
            <tr><td>Tipo Nodo</td><td><input type="text" name="tipo_nodo" value="${this.updateData.tipo_nodo??''}" autocomplete="off"> </td></tr>
            <tr><td>Tipo Fuente</td><td><input type="text" name="tipo_fuente" value="${this.updateData.tipo_fuente??''}" autocomplete="off"> </td></tr>
            <tr><td>Ubicado En</td><td><input type="text" name="ubicado_en" value="${this.updateData.ubicado_en??''}" autocomplete="off"> </td></tr>
            <tr><td>Tiene Baterias</td><td><input type="text" name="tiene_baterias" value="${this.updateData.tiene_baterias??''}" autocomplete="off"> </td></tr>
            <tr><td>Año Fab</td><td><input type="number" name="anio_fab" value="${this.updateData.anio_fab??''}" autocomplete="off"> </td></tr>
            <tr><td>Tipo Respaldo</td><td><input type="text" name="tipo_respaldo" value="${this.updateData.tipo_respaldo??''}" autocomplete="off"> </td></tr>
            <tr><td>Amp</td><td><input type="text" name="amp" value="${this.updateData.amp??''}" autocomplete="off"> </td></tr>
            <tr><td>Candado</td><td><input type="text" name="candado" value="${this.updateData.candado??''}" autocomplete="off"> </td></tr>
            <tr><td>Barra</td><td><input type="text" name="barra" value="${this.updateData.barra??''}" autocomplete="off"> </td></tr>
            <tr><td>Seguro H</td><td><input type="text" name="seguro_h" value="${this.updateData.seguro_h??''}" autocomplete="off"> </td></tr>
            <tr><td>Seguro Baterias</td><td><input type="text" name="seguro_baterias" value="${this.updateData.seguro_baterias??''}" autocomplete="off"> </td></tr>
            <tr><td>Fecha Manto</td><td><input type="date" name="fecha_manto" value="${this.updateData.fecha_manto??''}" autocomplete="off"> </td></tr>
            <tr><td>Año Manto</td><td><input type="number" name="anio_manto" value="${this.updateData.anio_manto??''}" autocomplete="off"> </td></tr>
            <tr><td>Mes Manto</td><td><input type="text" name="mes_manto" value="${this.updateData.mes_manto??''}" autocomplete="off"> </td></tr>
            <tr><td>Referido</td><td><input type="text" name="referido" value="${this.updateData.referido??''}" autocomplete="off"> </td></tr>
            <tr><td>Region</td><td><input type="text" name="region" value="${this.updateData.region??''}" autocomplete="off"> </td></tr>
            <tr><td>Distrito</td><td><input type="text" name="distrito" value="${this.updateData.distrito??''}" autocomplete="off"> </td></tr>
            <tr><td>Segmento Urbano</td><td><input type="text" name="segmento_urbano" value="${this.updateData.segmento_urbano??''}" autocomplete="off"> </td></tr>
            <tr><td>Gestión Campo</td><td><input type="text" name="gestion_campo" value="${this.updateData.gestion_campo??''}" autocomplete="off"> </td></tr>
            <tr><td>Nivel de Seguridad</td><td><input type="text" name="nivel_de_seguridad" value="${this.updateData.nivel_de_seguridad??''}" autocomplete="off"> </td></tr>
            <tr><td>Comentario</td><td><input type="text" name="comentario" value="${this.updateData.comentario??''}" autocomplete="off"> </td></tr>
            <tr><td>Latitud</td><td><input type="text" name="latitud" value="${this.updateData.latitud??''}" autocomplete="off"> </td></tr>
            <tr><td>Longitud</td><td><input type="text" name="longitud" value="${this.updateData.longitud??''}" autocomplete="off"> </td></tr>
            </tbody></table>`;
        }
        document.querySelector(".panel-data-body").innerHTML = html;

        let primaryActions = document.querySelector(`${this.el_selector} .primary-actions`);
        let confirmActions = document.querySelector(`${this.el_selector} .confirm-actions`);
        let confirmCreateActions = document.querySelector(`${this.el_selector} .confirm-create-actions`);

        if(this.mode === "read"){
            primaryActions.style.display = '';
            confirmActions.style.display = 'none';
            confirmCreateActions.style.display = 'none';
        }else if(this.mode === "create"){
            primaryActions.style.display = 'none';
            confirmActions.style.display = 'none';
            confirmCreateActions.style.display = '';
        }else if(this.mode === "update"){
            primaryActions.style.display = 'none';
            confirmActions.style.display = '';
            confirmCreateActions.style.display = 'none';
            document.querySelector(`${this.el_selector} .confirm-actions .btn-confirm-delete`).style.display = 'none';
            document.querySelector(`${this.el_selector} .confirm-actions .btn-confirm-edit`).style.display = '';
        }else if(this.mode === "delete"){
            primaryActions.style.display = 'none';
            confirmActions.style.display = '';
            confirmCreateActions.style.display = 'none';
            document.querySelector(`${this.el_selector} .confirm-actions .btn-confirm-delete`).style.display = '';
            document.querySelector(`${this.el_selector} .confirm-actions .btn-confirm-edit`).style.display = 'none';
        }

        // events
        document.querySelectorAll(`${this.el_selector} input`)
        .forEach(element => {
            element.addEventListener("input", self.inputFieldHandler.bind(self));
        });
    }

    getHtmlImgSlide(data){
        let liHtml = (data.images??[]).map((img, index) => `<li data-target="#carouselExampleIndicators" data-slide-to="${index}" ${index === 0 ? 'class="active"' : ""}></li>`).join("");
        let carouselHtml = (data.images??[]).map((imgUrl, index) => `<div class="carousel-item ${index===0 ? "active" : ""}">
            <img class="d-block w-100" src="${imgUrl}" alt="Imagen ${index+1}">
        </div>`).join("");

        return `<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="false" style="background: #353535;">
            <ol class="carousel-indicators">
                ${liHtml}
            </ol>
            <div class="carousel-inner">
                ${carouselHtml}
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>`;
    }

    inputFieldHandler(e){
        this.updateData[e.target.name] = e.target.value;
    }

    addEventListener(event, handler){
        this.selector.addEventListener(event, handler);
    }
}

class CreatorButton{
    constructor(strSelector, map){
        this.strSelector = strSelector;
        this.active = false;
        this.map = map;
        this.marker = undefined;
        this.selector = document.querySelector(strSelector);
        this.attachHandlers();
    }

    attachHandlers(){
        let self = this;
        this.selector.addEventListener("click", function(){
            self.active = !self.active;
            if(self.active){
                if(self.marker){
                    self.marker.setMap(null);
                    self.marker = undefined;
                }
            }
            self.renderButton();
        });

        self.map.addListener("click", function(event){
            if(self.active){
                if(self.marker){
                    self.marker.setMap(null);
                    self.marker = undefined;
                }
                self.marker = new google.maps.marker.AdvancedMarkerElement({
                    map: self.map,
                    position: event.latLng,
                });
                self.active = false;
                self.renderButton();
                
                let customEvent = new CustomEvent("click-map", {
                    detail: {
                        latitud: Number(event.latLng.lat().toFixed(6)),
                        longitud: Number(event.latLng.lng().toFixed(6))
                    },
                    bubbles: true,
                    composed: true
                });
                self.selector.dispatchEvent(customEvent);
            }
        });
    }

    addEventListener(event, handler){
        this.selector.addEventListener(event, handler);
    }

    renderButton(){
        let self = this;
        self.selector.classList.remove("btn-primary");
        self.selector.classList.remove("btn-secondary");
        if(self.active){
            self.selector.classList.add("btn-primary");
        }else{
            self.selector.classList.add("btn-secondary");
        }
    }

    clear(){
        this.active = false;
        this.renderButton();
        if(this.marker){
            this.marker.setMap(null);
            this.marker = undefined;
        }
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

class PolylineCreatorButton{
    constructor(strSelector, map){
        this.strSelector = strSelector;
        this.active = false;
        this.map = map;
        this.markerStart = undefined;
        this.markerEnd = undefined;
        this.polyline = undefined;
        this.coords = [];
        this.selector = document.querySelector(strSelector);
        this.attachHandlers();
    }

    attachHandlers(){
        let self = this;
        this.selector.addEventListener("click", function(){
            self.active = !self.active;
            if(self.active){
                self.clear();
                self.active = true;
            }
            self.renderButton();
        });

        self.map.addListener("click", function(event){
            if(self.active && self.coords.length > 0){
                let point = {
                    lat: Number(event.latLng.lat().toFixed(6)),
                    lng: Number(event.latLng.lng().toFixed(6))
                };

                self.addClickPolyline(point);
                
                let customEvent = new CustomEvent("click-map", {
                    detail: {
                        latitud: Number(event.latLng.lat().toFixed(6)),
                        longitud: Number(event.latLng.lng().toFixed(6))
                    },
                    bubbles: true,
                    composed: true
                });
                self.selector.dispatchEvent(customEvent);
            }
        });
    }

    addClickPolyline(point){
        this.coords.push(point);
        if(this.polyline){
            this.polyline.setMap(null);
            this.polyline = undefined;
        }
        this.polyline = new google.maps.Polyline({
            map: this.map,
            path: this.coords,
            geodesic: true,
            strokeColor: "#FF0000",
            strokeOpacity: 1,
            strokeWeight: 2
        });
    }

    addEventListener(event, handler){
        this.selector.addEventListener(event, handler);
    }

    clickMarkerListener(marker){
        if(this.markerStart === undefined){
            this.markerStart = marker;
        }else{
            if(Number(this.markerStart.content.data.id) !== Number(marker.content.data.id)){
                this.markerEnd = marker;
            }else{
                return;
            }
        }

        let point = {lat: marker.position.lat, lng: marker.position.lng};

        this.addClickPolyline(point);

        if(this.markerStart !== undefined && this.markerEnd !== undefined){
            let customEvent = new CustomEvent("create-polyline", {
                detail: {
                    coords: this.coords
                },
                bubbles: true,
                composed: true
            });
            this.selector.dispatchEvent(customEvent);
            this.clear();
        }
    }

    renderButton(){
        let self = this;
        self.selector.classList.remove("btn-primary");
        self.selector.classList.remove("btn-secondary");
        if(self.active){
            self.selector.classList.add("btn-primary");
        }else{
            self.selector.classList.add("btn-secondary");
        }
    }

    clear(){
        this.active = false;
        this.renderButton();
        if(this.polyline){
            this.polyline.setMap(null);
            this.polyline = undefined;
        }
        this.markerStart = undefined;
        this.markerEnd = undefined;
        this.coords = [];
    }
}

class OperacionFijaViewModel{
    constructor(){
        this.store = {
            markers: [],
            infoWindow: undefined,
            fuentesFija: [],
            polylines: [],
            addPolyline: {
                toogle: false,
                polylineCoords: [],
                polyline: undefined
            }
        };
        this.map = this.initMap();
        this.mapWrapper = new MapWrapper(this.map);
        this.panelOptionsView = new GraphModal(".panel-options");
        this.panelDataView = new PanelData(".panel-data");
        this.creatorButton = new CreatorButton(".btn-create", this.map);
        this.polylineCreatorButton = new PolylineCreatorButton(".btn-create-polyline", this.map);
        this.markerSuggestions = new SuggestionsComponent({selector: "#suggestions_marker", target: "#input_search"});
        this.attachHandlers();
        this.onReady()
    }

    initMap(){
        let map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-12.046035898836875, -77.02870893069844),
            zoom: 13,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1,
            mapId: "MAPA_OPERACION_FIJA"
        });
        google.maps.event.addListenerOnce(map,'idle', function(){
            // let legend = document.getElementById('legend');
            // map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
            // legend.style.display = 'none';
            
            let legend_layers = document.getElementById('legend_layers');
            
            map.controls[google.maps.ControlPosition.LEFT_TOP].push(legend_layers);
            setTimeout(function(){
                legend_layers.style.display = '';
            }, 500);

            //let legend_data = document.querySelector('.panel-data');
            //map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend_data);
        });
        let self = this;
        let mapBoundsChangedTimer;
        map.addListener("bounds_changed", function(){
            if(mapBoundsChangedTimer){
                clearTimeout(mapBoundsChangedTimer);
            }
            if(map.getZoom() >= 15){
                mapBoundsChangedTimer = setTimeout(function(){
                    let bounds = map.getBounds();
                    let markersHide = [];
                    let markersShow = self.store.markers.filter(marker => {
                        let contains = bounds.contains(marker.position);
                        if(!contains){
                            markersHide.push(marker);
                        }
                        return contains;
                    });
                    markersHide.forEach(m => {
                        let label = m.content.children[1];
                        if(label.style.display !== 'none'){
                            label.style.display = 'none';
                        }
                    });
                    markersShow.forEach(marker => {
                        let label = marker.content.children[1];
                        if(label.style.display === 'none'){
                            label.style.display = '';
                        }
                    });
                }, 500);
            }else{
                /*store.labelNumCompetencia.markers.forEach(marker => {
                    if(marker.map !== null){
                        marker.setMap(null);
                    }
                });*/
            }
        });
        return map;
    }

    onReady(){
        let self = this;
        /*this.mapWrapper.addLayer("planos", `${config.base_url}/map/map_19_6748.json`)
        .then(features => {
            features.forEach(feature => {
                let tecnologia = feature.getProperty("tecnologia");
                let style = {fillOpacity: 0.6, strokeWeight: 1};
                if(tecnologia === "HFC"){
                    style["fillColor"] = "#3498db";
                }else if(tecnologia === "FTTH"){
                    style["fillColor"] = "#e74c3c";
                }
                self.map.data.overrideStyle(feature, style);
            });
        });*/
        this.addMarkers();
        this.renderPolylines();
    }

    attachHandlers(){
        let self = this;

        document.querySelector(".btn-toogle-layers")
        .addEventListener("click", function(e){
            let toogle = $(".btn-toogle-layers").attr("data-toogle") === "true";
            toogle = !toogle;
            $(".btn-toogle-layers").attr("data-toogle", toogle);
            if(toogle){
                self.panelOptionsView.show();
            }else{
                self.panelOptionsView.hide();
            }
        });

        document.querySelector("#input_search")
        .addEventListener("keyup", function(e){
            let value = e.target.value.trim().toLocaleLowerCase();
            let results = self.store.markers.filter(m => value !== "" && (m.content.data.plano??"").toLocaleLowerCase().includes(value))
            .filter((m, index) => index < 5)
            .map(m => ({id: m.content.data.id, label: m.content.data.plano}));
            self.markerSuggestions.setList(results);
            self.markerSuggestions.show();
        });

        self.markerSuggestions.addEventListener("change", function(e){
            // console.log(e.detail);
            let marker = self.store.markers.filter(m => Number(m.content.data.id) === Number(e.detail.value))[0];
            if(marker !== undefined){
                self.map.setCenter(marker.position);
                self.map.setZoom(18);
                self.showInfo({id: e.detail.value});
            }else{
                alert("No se pudo encontrar el marcador");
            }
        });

        self.creatorButton.addEventListener("click-map", function(event){
            self.panelDataView.show();
            self.panelDataView.setTitle("Agregar Fuente");
            self.panelDataView.setData(event.detail);
            self.panelDataView.setMode("create");
            self.panelDataView.render();
        });

        self.panelDataView.addEventListener("confirm-create", function(event){
            let _csrf = document.querySelector("meta[name=csrf-token]").content;
            fetch(`${config.base_url}/api/operacion-fija`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": _csrf
                },
                body: JSON.stringify(event.detail)
            })
            .then(response => response.json())
            .then(response => {
                let notifyConfig = {layout: 'topRight'};
                if(response.passes){
                    self.panelDataView.setTitle(response.data.plano);
                    self.panelDataView.setData(response.data);
                    self.panelDataView.setMode("read");
                    self.panelDataView.render();
                    self.creatorButton.clear();

                    let marker = self.addMarker(response.data);
                    self.store.markers.push(marker);

                    notifyConfig = {
                        type: 'success',
                        layout: 'topRight',
                        text: "Se agregó correctamente"
                    };
                    self.renderTipoRespaldo();
                } else if(response.message){
                    notifyConfig = {
                        type: 'error',
                        layout: 'topRight',
                        text: response.message
                    };
                }else{
                    let htmlErrors = Object.keys(response.errors??{}).map(field => `${field}: ${response.errors[field]}`).join("<br>");
                    notifyConfig = {
                        type: 'error',
                        layout: 'topRight',
                        text: `<strong>Errores de validación</strong><br>${htmlErrors}`
                    };
                }
                new Noty(notifyConfig).show();
            });
        })

        self.panelDataView.addEventListener("cancel-create", function(){
            self.creatorButton.clear();
        });

        self.panelDataView.addEventListener("confirm-update", function(event){
            let _csrf = document.querySelector("meta[name=csrf-token]").content;
            fetch(`${config.base_url}/api/operacion-fija`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": _csrf
                },
                body: JSON.stringify({...event.detail, _csrf})
            })
            .then(response => response.json())
            .then(response => {
                let notifyConfig = {layout: 'topRight'};

                if(response.passes){
                    self.panelDataView.setTitle(event.detail.plano);
                    self.panelDataView.setData(event.detail);
                    self.panelDataView.setMode("read");
                    self.panelDataView.render();

                    let indexFinded = undefined;
                    for (let index = 0; index < self.store.markers.length; index++) {
                        const marker = self.store.markers[index];
                        if(parseInt(marker.content.data.id) === parseInt(event.detail.id)){
                            indexFinded = index;
                            break;
                        }
                    }
                    if(indexFinded !== undefined){
                        self.store.markers[indexFinded].setMap(null);
                        self.store.markers[indexFinded] = self.addMarker(event.detail);
                    }

                    notifyConfig = {
                        type: 'success',
                        layout: 'topRight',
                        text: "Se actualizó correctamente"
                    };

                    self.renderTipoRespaldo();
                } else if(response.message){
                    notifyConfig = {
                        type: 'error',
                        layout: 'topRight',
                        text: response.message
                    };
                }else{
                    let htmlErrors = Object.keys(response.errors??{}).map(field => `${field}: ${response.errors[field]}`).join("<br>");
                    notifyConfig = {
                        type: 'error',
                        layout: 'topRight',
                        text: `<strong>Errores de validación</strong><br>${htmlErrors}`
                    };
                }
                new Noty(notifyConfig).show();
            });
        });

        self.panelDataView.addEventListener("confirm-delete", function(event){
            let _csrf = document.querySelector("meta[name=csrf-token]").content;
            fetch(`${config.base_url}/api/operacion-fija/${event.detail.id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": _csrf
                }
            })
            .then(response => response.json())
            .then(response => {
                let notifyConfig = {};
                if(response.message === undefined){
                    self.store.markers = self.store.markers.filter(marker => {
                        if(parseInt(marker.content.data.id) === parseInt(event.detail.id)){
                            marker.setMap(null);
                            return false;
                        }
                        return true;
                    });
                    
                    self.panelDataView.hide();
                    self.panelDataView.setMode("read");
                    self.panelDataView.render();

                    notifyConfig = {
                        type: 'success',
                        layout: 'topRight',
                        text: "Se eliminó correctamente"
                    };
                    self.renderTipoRespaldo();
                }else{
                    notifyConfig = {
                        type: 'error',
                        layout: 'topRight',
                        text: response.message
                    };
                }
                new Noty(notifyConfig).show();
            });
        });

        self.panelDataView.addEventListener("upload-image", function(event){
            $("#detalleTablaModal").modal("show");
            document.querySelector("#file_imagenes").value = '';
            document.querySelector("#tipo_carga_imgen_add").checked = true;
        });


        document.querySelector("#upload_images_form")
        .addEventListener("submit", function(e){
            e.preventDefault();
            let formData = new FormData(e.target);
            formData.set("id", self.panelDataView.data.id);
            let _csrf = document.querySelector("meta[name=csrf-token]").content;
            fetch(`${config.base_url}/api/operacion-fija/images`, {
                method: "POST",
                headers: {
                    // "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": _csrf
                },
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if(response.message === undefined){
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: `Se cargo correctamente`
                    }).show();
                    $("#detalleTablaModal").modal("hide");
                    self.showInfo({id: self.panelDataView.data.id});
                }else{
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: `${response.message}`
                    }).show();
                }
            });
        });

        self.polylineCreatorButton.addEventListener("create-polyline", function(event){
            let accept = confirm("Esta seguro que desea cargar la linea?");
            if(accept){
                let _csrf = document.querySelector("meta[name=csrf-token]").content;
                fetch(`${config.base_url}/operacion-fija/enlaces`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": _csrf
                    },
                    body: JSON.stringify({
                        path: JSON.stringify(event.detail.coords)
                    })
                })
                .then(resp => resp.json())
                .then(data => {
                    self.renderPolylines();
                });
            }
        });
    }

    renderPolylines(){
        let self = this;
        fetch(`${config.base_url}/operacion-fija/enlaces`)
        .then(resp => resp.json())
        .then(data => {
            self.store.polylines.forEach(polyline => {
                polyline.setMap(null);
            });
            self.store.polylines = data.map(row => {
                let polyline = new google.maps.Polyline({
                    map: self.map,
                    path: JSON.parse(row.path),
                    geodesic: true,
                    strokeColor: "#000000",
                    strokeOpacity: 1,
                    strokeWeight: 2
                });

                polyline.addListener("click", function (event) {
                    if(self.store.infoWindow !== undefined){
                        self.store.infoWindow.close();
                    }
                    let path = JSON.parse(row.path);
                    let origenString = {};
                    let destinoString = {};
                    if(path[0]){
                        origenString = path[0];
                    }
                    if(path[path.length-1]){
                        destinoString = path[path.length-1];
                    }

                    let contentString = `<table class="tbl tblMonit mb-2">
                    <thead><tr class="active"></tr>
                    </thead><tbody>
                    <tr><td>Longitud Origen</td><td>${origenString.lng ?? ''}</td></tr>
                    <tr><td>Latitud Origen</td><td>${origenString.lat ?? ''}</td></tr>
                    <tr><td>Longitud Destino</td><td>${destinoString.lng ?? ''}</td></tr>
                    <tr><td>Latitud Destino</td><td>${destinoString.lat ?? ''}</td></tr>
                    </tbody></table>
                    <button class="btn btn-outline-danger btn-sm btn-delete-polyline">Eliminar</button>`;
                    self.store.infoWindow = new google.maps.InfoWindow();
                    self.store.infoWindow.setContent(contentString);
                    self.store.infoWindow.setPosition(event.latLng);
                    self.store.infoWindow.open(self.map);

                    setTimeout(() => {
                        document.querySelector('.btn-delete-polyline')
                        .addEventListener('click', function(){
                            self.deletePolylineHandler(row.id);
                        });
                    }, 100);
                });
                return polyline;
            });
        });
    }

    deletePolylineHandler(id){
        let self = this;
        if(confirm("Esta seguro que desea eliminar la linea?")){
            if(self.store.infoWindow !== undefined){
                self.store.infoWindow.close();
            }
            
            let _csrf = document.querySelector("meta[name=csrf-token]").content;
            fetch(`${config.base_url}/operacion-fija/enlaces/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": _csrf
                }
            })
            .then(async(response) => {
                if(response.ok){
                    self.renderPolylines();
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: `Se elimino correctamente`
                    }).show();
                }else{
                    let json = await response.json();
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: json.message
                    }).show();
                }
            });
        }
    }

    async addMarkers(){
        let self = this;
        fetch(`${config.base_url}/operacion-fija/fuentes-hfc`)
        .then(resp => resp.json())
        .then(data => {
            self.store.fuentesFija = data;
            self.store.markers.forEach(marker => {
                marker.setMap(null);
            });
            self.store.markers = data.map(row => {
                let icon;
                if(row.tipo_respaldo === "BATERIA LITIO"){
                    icon = `${config.base_url}/images/green-dot.png`;
                }else if(row.tipo_respaldo === "BATERIAS PLOMO"){
                    icon = `${config.base_url}/images/yellow-dot.png`;
                }else if((row.tipo_respaldo??'').includes("MULTISWITCH")){
                    icon = `${config.base_url}/images/purple-dot.png`;
                }else if((row.tipo_respaldo??'') === ""){
                    icon = `${config.base_url}/images/blue-dot.png`;
                }else{
                    icon = `${config.base_url}/images/red-dot.png`;
                }
                let img = document.createElement("div");
                img.classList.add("custom-marker-content");
                img.innerHTML = `
                    <img src="${icon}">
                    <label class="font-weight-bold" style="display: none;">${row.plano}</label>
                `;
                img.data = row;
                let marker = new google.maps.marker.AdvancedMarkerElement({
                    map: self.map,
                    position: {lat: parseFloat(row.latitud), lng: parseFloat(row.longitud)},
                    title: row.plano,
                    // data: row,
                    content: icon === undefined ? icon : img
                    // size: new google.maps.Size(10, 15),
                    // anchor: new google.maps.Point(0, 15)
                });
                marker.addListener("click", function(){
                    if(self.polylineCreatorButton.active){
                        self.polylineCreatorButton.clickMarkerListener(marker);
                    }else{
                        self.showInfo(row);
                    }
                });
                return marker;
            });

            self.renderTipoRespaldo();
        })
    }

    addMarker(row){
        let self = this;
        let icon;
        if(row.tipo_respaldo === "BATERIA LITIO"){
            icon = `${config.base_url}/images/green-dot.png`;
        }else if(row.tipo_respaldo === "BATERIAS PLOMO"){
            icon = `${config.base_url}/images/yellow-dot.png`;
        }else if((row.tipo_respaldo??'').includes("MULTISWITCH")){
            icon = `${config.base_url}/images/purple-dot.png`;
        }else if((row.tipo_respaldo??'') === ""){
            icon = `${config.base_url}/images/blue-dot.png`;
        }else{
            icon = `${config.base_url}/images/red-dot.png`;
        }
        let img = document.createElement("div");
        img.classList.add("custom-marker-content");
        img.innerHTML = `
            <img src="${icon}">
            <label class="font-weight-bold" style="display: none;">${row.plano}</label>
        `;
        img.data = row;
        let marker = new google.maps.marker.AdvancedMarkerElement({
            map: self.map,
            position: new google.maps.LatLng(parseFloat(row.latitud), parseFloat(row.longitud)),
            content: icon === undefined ? icon : img,
            // size: new google.maps.Size(10, 15),
            // anchor: new google.maps.Point(0, 15)
        });
        marker.addListener("click", function(){
            self.showInfo(row);
        });
        return marker;
    }

    showInfo(data){
        let self = this;
        // self.panelDataView.hide();
        fetch(`${config.base_url}/api/operacion-fija/${data.id}`, {
            method: "GET",
            headers: {
                "Accept": "application/json",
                // "X-CSRF-TOKEN": _csrf
            },
        })
        .then(response => response.json())
        .then(response => {
            if(response.message){
                new Noty({
                    type: 'error',
                    layout: 'topRight',
                    text: `${response.message}`
                }).show();
            }else{
                self.panelDataView.show();
                self.panelDataView.setTitle(response.data.plano);
                self.panelDataView.setData(response.data);
                self.panelDataView.setMode("read");
                self.panelDataView.render();
            }
        });
        
        // if(self.store.infoWindow !== undefined){
        //     self.store.infoWindow.close();
        // }
        /*let content = document.querySelector(".panel-data-body");
        content.innerHTML = `<table class="tbl tblMonit mb-0" style="min-width: 290px;">
        <thead><tr class="active"></tr>
        </thead><tbody>
        <tr><td>Plano</td><td>${data.plano??''}</td></tr>
        <tr><td>Tipo Nodo</td><td>${data.tipo_nodo??''}</td></tr>
        <tr><td>Tipo Fuente</td><td>${data.tipo_fuente??''}</td></tr>
        <tr><td>Ubicado En</td><td>${data.ubicado_en??''}</td></tr>
        <tr><td>Tiene Baterias</td><td>${data.tiene_baterias??''}</td></tr>
        <tr><td>Año Fab</td><td>${data.anio_fab??''}</td></tr>
        <tr><td>Tipo Respaldo</td><td>${data.tipo_respaldo??''}</td></tr>
        <tr><td>Amp</td><td>${data.amp??''}</td></tr>
        <tr><td>Candado</td><td>${data.candado??''}</td></tr>
        <tr><td>Barra</td><td>${data.barra??''}</td></tr>
        <tr><td>Seguro H</td><td>${data.seguro_h??''}</td></tr>
        <tr><td>Seguro Baterias</td><td>${data.seguro_baterias??''}</td></tr>
        <tr><td>Fecha Manto</td><td>${data.fecha_manto??''}</td></tr>
        <tr><td>Año Manto</td><td>${data.anio_manto??''}</td></tr>
        <tr><td>Mes Manto</td><td>${data.mes_manto??''}</td></tr>
        <tr><td>Referido</td><td>${data.referido??''}</td></tr>
        <tr><td>Region</td><td>${data.region??''}</td></tr>
        <tr><td>Distrito</td><td>${data.distrito??''}</td></tr>
        <tr><td>Segmento Urbano</td><td>${data.segmento_urbano??''}</td></tr>
        <tr><td>Gestión Campo</td><td>${data.gestion_campo??''}</td></tr>
        <tr><td>Nivel de Seguridad</td><td>${data.nivel_de_seguridad??''}</td></tr>
        <tr><td>Comentario</td><td>${data.comentario??''}</td></tr>
        <tr><td>Latitud</td><td>${data.latitud??''}</td></tr>
        <tr><td>Longitud</td><td>${data.longitud??''}</td></tr>
        </tbody></table>`;*/
    
        // self.store.infoWindow.open(self.map, marker);
    }

    renderTipoRespaldo(){
        let self = this;
        let tiposRespaldo = {};
        this.store.markers.forEach(marker => {
            let tipo = marker.content.data.tipo_respaldo;
            if((tipo??'').includes("MULTISWITCH")){
                tipo = "MULTISWITCH";
            }
            tiposRespaldo[tipo] = 1;
        });
        function getTipoRespaldoColor(tipo_respaldo){
            console.log(tipo_respaldo);
            if(tipo_respaldo === "BATERIA LITIO"){
                return "#00E64D";
            }else if(tipo_respaldo === "BATERIAS PLOMO"){
                return "#FDF569";
            }else if((tipo_respaldo??'').includes("MULTISWITCH")){
                return "#8E67FD";
            }else if((tipo_respaldo??'') === "" || tipo_respaldo === 'null'){
                return "#6991FD";
            }else{
                return "#FD7567";
            }
        }
        let html = Object.keys(tiposRespaldo).map((value, index) => `<div class="layer-item">
            <input class="check_tipo_respaldo" type="radio" name="check_tipo_respaldo" id="check_tipo_${index}" value="${value}">
            <label class="form-check-label" for="check_tipo_${index}">
            <div class="item-color" style="background: ${getTipoRespaldoColor(value)};"></div>
            ${value}
            </label>
        </div>`)
        .join("");
        document.querySelector(".radio_tipos_respaldo_main").innerHTML = `<div class="layer-item">
            <input class="check_tipo_respaldo" type="radio" name="check_tipo_respaldo" id="check_tipo_todos" value="__todos__" checked>
            <label class="form-check-label" for="check_tipo_todos">Todos</label>
        </div>${html}`;
        document.querySelectorAll(".check_tipo_respaldo")
        .forEach(element => {
            element.addEventListener("change", function(e){
                let value = e.target.value;
                self.store.markers.forEach(marker => {
                    if(value === "__todos__" || (marker.content.data.tipo_respaldo??'').includes(value) || `${marker.content.data.tipo_respaldo}` === value){
                        if(marker.map === null){
                            marker.setMap(self.map);
                        }
                    }else{
                        if(marker.map !== null){
                            marker.setMap(null);
                        }
                    }
                });
            });
        });

        self.store.markers.forEach(marker => {
            if(marker.map === null){
                marker.setMap(self.map);
            }
        });
    }

}

class OperacionFijaDashboardViewModel{
    constructor(){
        this.store = {
            addPolyline: {
                toogle: false,
                polylineCoords: [],
                polyline: undefined
            },
            charts: {},
        };
        this.attachHandlers();
        this.onReady();
    }

    onReady(){
        let self = this;
        fetch(`${config.base_url}/operacion-fija/dashboard/tipos-respaldo-by-region`)
        .then(response => response.json())
        .then(data => {
            let tiposRespaldo = data[0] ?? {};

            let htmlBody = data.map(row => {
                let htmlRow = Object.keys(tiposRespaldo).map(field => `<td>${row[field]}</td>`).join("");
                return `<tr>${htmlRow}</tr>`;
            }).join("");

            let htmlHead = "<tr>"+Object.keys(tiposRespaldo).map(field => `<th>${field.toUpperCase()}</th>`).join("")+"</tr>";

            console.log(htmlHead);
            console.log(htmlBody);

            document.querySelector('#table-region thead').innerHTML = htmlHead;
            document.querySelector('#table-region tbody').innerHTML = htmlBody;

            tiposRespaldo = Object.keys(tiposRespaldo).filter(tipo => tipo !== 'region');
            let series = tiposRespaldo.map(tipoRespaldo => {
                return {
                    name: tipoRespaldo,
                    data: data.map(row => {
                        return {
                            x: row.region,
                            y: row[tipoRespaldo] !== undefined ? Number(row[tipoRespaldo]) : null
                        };
                    })
                };
            });
            // self.renderGraph('graph-region', {title: 'Respaldo de energía por región', series: series, ''});
            let options = {
                title: {
                    text: 'Tipo de respaldo por región',
                    align: 'center'
                },
                series: series,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: data.map(row => row.region),
                },
                yaxis: {
                    title: {
                        text: 'Cantidad'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    /*y: {
                        formatter: function (val) {
                            return "$ " + val + " thousands"
                        }
                    }*/
                }
            };

            if(this.store.charts['graph-region']){
                this.store.charts['graph-region'].destroy();
            }
            this.store.charts['graph-region'] = new ApexCharts(document.querySelector(`#graph-region`), options);
            this.store.charts['graph-region'].render();
        });

        fetch(`${config.base_url}/operacion-fija/dashboard/tipos-respaldo-by-month`)
        .then(response => response.json())
        .then(data => {
            let tiposRespaldo = {};
            data.forEach(row => {
                tiposRespaldo[row.tipo_respaldo] = null;
            });

            let resultByMonth = {};
            data.forEach(row => {
                if(resultByMonth[row.fecha] === undefined){
                    resultByMonth[row.fecha] = {fecha: row.fecha, ...tiposRespaldo}
                }
                resultByMonth[row.fecha][row.tipo_respaldo] = row.counter;
            });
            resultByMonth = Object.keys(resultByMonth).map(key => resultByMonth[key]);

            tiposRespaldo = Object.keys(tiposRespaldo);
            let series = tiposRespaldo.map(tipoRespaldo => {
                return {
                    name: tipoRespaldo,
                    data: resultByMonth.map(row => {
                        const date = new Date(row.fecha.replace(' ', 'T')+'Z');
                        return {
                            x: date.getTime(),
                            y: row[tipoRespaldo] ? Number(row[tipoRespaldo]) : null
                        };
                    })
                };
            });
            self.renderGraph('graph-month', {title: 'Tipo de respaldo por mes', series: series});
        });
    }

    renderGraph(el_container, options){
        let chart_config = {
            series: options.series,
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    type: "x",
                    enabled: true,
                    autoScaleYaxis: true
                },
                toolbar: {
                    export: {
                        csv: {
                            filename: options.title,
                            dateFormatter: function(timestamp) {
                                return (new Date(timestamp)).toISOString().replace("T", " ").replace(".000Z", "");
                            }
                        }
                        
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                size: 0
            },
            stroke:{
                width: 2,
            },
            title: {
                text: options.title,
                align: 'center'
            },
            grid: {
                row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
                },
            },
            xaxis: {
                type: "datetime",
                //tickAmount: 6,
                labels: {
                    datetimeFormatter: {
                        year: "yyyy",
                        month: "yyyy-MM",
                        day: "yyyy-MM",
                        hour: "yyyy-MM",
                    },
                }
            },
            tooltip: {
                x: {
                    format: "yyyy-MM"
                }
            }
        };
        if(this.store.charts[el_container]){
            this.store.charts[el_container].destroy();
        }
        this.store.charts[el_container] = new ApexCharts(document.querySelector(`#${el_container}`), chart_config);
        this.store.charts[el_container].render();
    }

    attachHandlers(){
        let self = this;
    }
}
let service;
async function initMap(){
    service = new OperacionFijaViewModel();
}