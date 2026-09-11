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
            if(typeof source !== "string"){
                let features = self.map.data.addGeoJson(source);

                self.store.layers[layerName+"_features"] = features;
                features.forEach(feature => {
                    self.map.data.overrideStyle(feature, style);
                    feature.setProperty("capa", layerName);
                });
                resolve(features);
            }else{
                // let features = self.map.data.addGeoJson(geojson);
                self.map.data.loadGeoJson(source, {}, function(features){
                    self.store.layers[layerName+"_features"] = features;
        
                    features.forEach(feature => {
                        self.map.data.overrideStyle(feature, style);
                        feature.setProperty("capa", layerName);
                    });
                    
                    resolve(features);
                });
            }
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

class CoberturaFijaController{
    constructor(config){
        this.config = config;
        this.store = {
            // marker: undefined,
            // edificiosMarker: [],
            infoWindow: undefined,
        };
        this.map = undefined;
        this.initMap();
        this.mapWrapper = new MapWrapper(this.map);
        // this.planos = [];
        // this.coberturaModal = new CoberturaModal({selector: "#coberturaModal"});
        this.attachHandlers();
    }

    initMap(){
        let self = this;
        let map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-12.046035898836875, -77.02870893069844),
            zoom: 15,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
        google.maps.event.addListenerOnce(map,'idle', function(){
            // let legend = document.getElementById('legend');
            // map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
            // legend.style.display = '';

            let legend_layers = document.getElementById('layers_main');
            map.controls[google.maps.ControlPosition.LEFT_TOP].push(legend_layers);
            //legend_layers.style.display = '';

            //let legend_data = document.querySelector('.panel-data');
            //map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend_data);
        });
        map.data.addListener('click', async function(event) {
            $("#spinnerData").show();
            await self.showPolygonInfo(event);
            $("#spinnerData").hide();
        });
        this.map = map;
    }

    async showPolygonInfo(event){
        let self = this;
        const feature = event.feature;
        if(self.infoWindow !== undefined){
            self.infoWindow.close();
        }
        let layer = feature.getProperty('capa');
        let contentString = "";

        if(layer.startsWith("FTTH")){
            let respone = await fetch(`${self.config.base_url}/cobertura-fija-ventas/31/cobertura-ftth/${feature.getProperty('id')}`);
            let json = await respone.json();
            if(json.passes){
                contentString = `<table class="table table-condensed table-sm">
                <thead><tr class="active"></tr>
                </thead>
                <tbody>
                <tr><td>ID</td><td>${json.data.id??''}</td></tr>
                <tr><td>NOMBRE</td><td>${json.data.nombre??''}</td></tr>
                <tr><td>NODO_OPTICO</td><td>${json.data.nodo_optico??''}</td></tr>
                <tr><td>IDENTIFICA_RED</td><td>${json.data.identifica_red??''}</td></tr>
                <tr><td>HHP</td><td>${json.data.hhp??''}</td></tr>
                <tr><td>ESTADO</td><td>${json.data.estado??''}</td></tr>
                <tr><td>CARACTERISTICA_CLARO</td><td>${json.data.caracteristica_claro??''}</td></tr>
                <tr><td>FECHA_CARGA</td><td>${json.data.fecha_carga??''}</td></tr>
                <tr><td>IDPAP</td><td>${json.data.idpap??''}</td></tr>
                <tr><td>IDPLANO</td><td>${json.data.idplano??''}</td></tr>
                <tr><td>CLASE_PROYECTO</td><td>${json.data.clase_proyecto??''}</td></tr>
                <tr><td>TECNOLOGIA</td><td>${json.data.tecnologia??''}</td></tr>
                <tr><td>CMTS_OLT</td><td>${json.data.cmts_olt??''}</td></tr>
                <tr><td>F_LIBERADO</td><td>${json.data.f_liberado??''}</td></tr>
                <tr><td>TIPO_MODIFI</td><td>${json.data.tipo_modifi??''}</td></tr>
                <tr><td>FLAG</td><td>${json.data.flag??''}</td></tr>
                <tr><td>SGA_DISTRITO</td><td>${json.data.sga_distrito??''}</td></tr>
                <tr><td>SGA_PROVINCIA</td><td>${json.data.sga_provincia??''}</td></tr>
                </tbody></table>`;
            }else{
                contentString = `<strong>${json.errors.message}</strong>`;
            }
        }else if(layer === 'hfc'){
            let respone = await fetch(`${self.config.base_url}/cobertura-fija-ventas/31/cobertura-hfc/${feature.getProperty('id')}`);
            let json = await respone.json();
            if(json.passes){
                contentString = `<table class="table table-condensed table-sm">
                <thead><tr class="active"></tr>
                </thead>
                <tbody>
                <tr><td>ID</td><td>${json.data.id??''}</td></tr>
                <tr><td>NOMBRE</td><td>${json.data.nombre??''}</td></tr>
                <tr><td>NODO_OPTICO</td><td>${json.data.nodo_optico??''}</td></tr>
                <tr><td>IDENTIFICA_RED</td><td>${json.data.identifica_red??''}</td></tr>
                <tr><td>HHP</td><td>${json.data.hhp??''}</td></tr>
                <tr><td>ESTADO</td><td>${json.data.estado??''}</td></tr>
                <tr><td>CARACTERISTICA_CLARO</td><td>${json.data.caracteristica_claro??''}</td></tr>
                <tr><td>FECHA_CARGA</td><td>${json.data.fecha_carga??''}</td></tr>
                <tr><td>IDPAP</td><td>${json.data.idpap??''}</td></tr>
                <tr><td>IDPLANO</td><td>${json.data.idplano??''}</td></tr>
                <tr><td>CLASE_PROYECTO</td><td>${json.data.clase_proyecto??''}</td></tr>
                <tr><td>TECNOLOGIA</td><td>${json.data.tecnologia??''}</td></tr>
                <tr><td>CMTS_OLT</td><td>${json.data.cmts_olt??''}</td></tr>
                <tr><td>F_LIBERADO</td><td>${json.data.f_liberado??''}</td></tr>
                <tr><td>TIPO_MODIFI</td><td>${json.data.tipo_modifi??''}</td></tr>
                <tr><td>FLAG</td><td>${json.data.flag??''}</td></tr>
                <tr><td>SGA_DISTRITO</td><td>${json.data.sga_distrito??''}</td></tr>
                <tr><td>SGA_PROVINCIA</td><td>${json.data.sga_provincia??''}</td></tr>
                </tbody></table>`;
            }else{
                contentString = `<br>${json.errors.message}`;
            }
        }

        if(contentString !== ""){
            self.infoWindow = new google.maps.InfoWindow();
            self.infoWindow.setContent(contentString);
            self.infoWindow.setPosition(event.latLng);
            self.infoWindow.open(self.map);
        }
    }

    attachHandlers(){
        let self = this;
        $("#btn_layers").on("click", function(){
            let isActive = !($(this).attr("data-toogle") === 'true');
            $(this).attr("data-toogle", isActive);
            if(isActive){
                $("#layers_options_main").show();
            }else{
                $("#layers_options_main").hide();
            }
        });

        document.querySelectorAll(".check_tecnologia_ftth")
        .forEach(element => {
            element.addEventListener("change", function(e) {
                let layer = e.target.value;
                if(e.target.checked){
                    $("#spinnerData").show();
                    fetch(`${self.config.base_url}/cobertura-fija-ventas/31/cobertura-ftth?tipo=${layer}`)
                    .then(response => response.json())
                    .then(response => {
                        let features = response.map(row => ({
                            type: "Feature",
                            properties: {id: row.id},
                            geometry: JSON.parse(row.polygon)
                        }));
                        let geojson = {
                            type: "FeatureCollection",
                            features: features
                        };
                        self.mapWrapper.addLayer(`FTTH.${layer}`, geojson, {fillOpacity: 0.6, strokeWeight: 1, fillColor: '#3498db'});
                        $("#spinnerData").hide();
                    });
                }else{
                    self.mapWrapper.removeLayer(`FTTH.${layer}`);
                }
            });
        });

        document.querySelector(".check_tecnologia_hfc")
        .addEventListener("change", function(e) {
            let layer = 'hfc';
            if(e.target.checked){
                $("#spinnerData").show();
                fetch(`${self.config.base_url}/cobertura-fija-ventas/31/cobertura-hfc`)
                .then(response => response.json())
                .then(response => {
                    let features = response.map(row => ({
                        type: "Feature",
                        properties: {id: row.id},
                        geometry: JSON.parse(row.polygon)
                    }));
                    let geojson = {
                        type: "FeatureCollection",
                        features: features
                    };
                    self.mapWrapper.addLayer(layer, geojson, {fillOpacity: 0.6, strokeWeight: 1, fillColor: '#e74c3c'});
                    $("#spinnerData").hide();
                });
            }else{
                self.mapWrapper.removeLayer(layer);
            }
        });
    }
}