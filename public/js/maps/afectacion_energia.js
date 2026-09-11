class AfectacionEnergiaController{
    constructor(config){
        this.config = config;
        this.store = {
            infoWindow: undefined,
            markers: []
        };
        this.map = undefined;
        this.map = this.initMap();
        this.mapWrapper = new MapWrapper(this.map);
        this.setUp();
        this.attachHandlers();
    }

    initMap(){
        let self = this;
        let map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-12.046035898836875, -77.02870893069844),
            zoom: 10,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
        google.maps.event.addListenerOnce(map,'idle', function(){
            // let legend = document.getElementById('legend');
            // map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
            // legend.style.display = '';

            // let legend_layers = document.getElementById('layers_main');
            // map.controls[google.maps.ControlPosition.LEFT_TOP].push(legend_layers);
            //legend_layers.style.display = '';

            //let legend_data = document.querySelector('.panel-data');
            //map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legend_data);
        });
        map.data.addListener('click', async function(event) {
            $("#spinnerData").show();
            await self.showPolygonInfo(event);
            $("#spinnerData").hide();
        });
        return map;
    }

    showPolygonInfo(event){
        // console.log(event);
        $("#spinnerData").show();
        let ubigeo = event.feature.getProperty("IDDIST");
        // ubigeo = '123';
        fetch(`${this.config.base_url}/afectacion_energia/ubigeos/${ubigeo}`)
        .then(resp => resp.json())
        .then(resp => {
            if(resp.data !== null){
                let data = resp.data;
                document.querySelector('.distrito-title').innerHTML = `${data.departamento} - ${data.provincia} - ${data.distrito}`;

                document.querySelector('.distrito-detalle-1 tbody').innerHTML = `
                <tr><td>% Cant. De sitios Caidos</td><td>${data.porc_cant_sitios_caidos}</td></tr>
                <tr><td>variacion ult 6 sem.</td><td>${data.porc_ult_seis_sem_sites_caidos}</td></tr>
                <tr><td>variacion con respecto al Año ant.</td><td>${data.porc_comp_anio_ant_sites_caidos}</td></tr>
                <tr><td>Cant. Sitios Caidos</td><td>${data.cant_sitios_caidos}</td></tr>`;

                document.querySelector('.distrito-detalle-2 tbody').innerHTML = `
                <tr><td>% afectacion de energ.</td><td>${data.porc_afecta_energ}</td></tr>
                <tr><td>vaiacion ult 6 sem.</td><td>${data.porc_ult_seis_sem_afecta_energ}</td></tr>
                <tr><td>variacion con respecto al Año ant.</td><td>${data.porc_comp_anio_ant_afecta_energ}</td></tr>
                `;

                document.querySelector('.distrito-grupo-horas tbody').innerHTML = data.grupo_horas.map(row => `<tr>
                    <td>${row.grupo_hora??''}</td>
                    <td>${row.cant_grupo_importante??''}</td>
                    <td>${row.cant_grupo_regular??''}</td>
                </tr>`).join('');

                document.querySelector('.distrito-sitios tbody').innerHTML = data.sitios.map(row => `<tr>
                    <td>${row.mbts??''}</td>
                    <td>${row.autonomia??''}</td>
                    <td>${row.cant_sem_recurrentes??''}</td>
                    <td>${row.porc_afectacion??''}</td>
                    <td>${row.fallas_energia_acum??''}</td>
                    <td>${Number(row.caida_serv_acum)??''}</td>
                    <td class="table-danger">${Number(row.horas_por_cubrir_p90)??''}</td>
                    <td>${Number(row.horas_por_cubrir_max)??''}</td>
                    <td class="table-danger">${row.cant_baterias_faltantes??''}</td>
                </tr>`).join('');
                document.querySelector('.panel-data').style.display = '';
                $(".btn-group-data button[data-target=panel-data-resumen]").trigger('click');
            } else {
                document.querySelector('.panel-data').style.display = 'none';
            }
            $("#spinnerData").hide();
        });
    }

    async setUp(){
        $("#spinnerData").show();
        document.querySelector('#filter_caido_ult_mes').value = 'Si';
        document.querySelector('#filter_caido_ult_4meses').value = 'Si';

        let promiseAddLayer = this.mapWrapper.addLayer('distritos', `${this.config.base_url}/map/peru_distritos.json`, {fillColor: '#27ae60', fillOpacity: 0.6, strokeWeight: 1});
        let promiseSitiosCaidos = this.getSitiosCaidos();
        let promiseSitios = this.renderSites();
        await promiseAddLayer;
        this.renderSitiosCaidos(await promiseSitiosCaidos);
        await promiseSitios;
        $("#spinnerData").hide();

        
        $('.panel-data-header .la-close').on('click', function(){
            $('.panel-data').hide();
        });
        $('.panel-data').draggable({handle: ".panel-data-header"});
    }

    async attachHandlers(){
        let self = this;
        $(".btn-group-data button").on('click', function(){
            $(".panel-data-resumen").hide();
            $(".panel-data-detalle").hide();
            $(".btn-group-data button").removeClass('active');
            let targetPanel = $(this).attr('data-target');
            $(`.${targetPanel}`).show();
            $("button[data-target="+targetPanel+"]").addClass('active');
        });

        let regionSelect = document.querySelector('#filter_region');
        let provinciaSelect = document.querySelector('#filter_provincia');
        let distritoSelect = document.querySelector('#filter_distrito');

        regionSelect.addEventListener('change', function(e){
            let htmlProvincias = self.config.provincias.filter(r => r.region === e.target.value)
                .map(r => `<option value='${r.id}'>${r.label}</option>`)
                .join('');

            provinciaSelect.innerHTML = `<option value=''>Seleccione</option>${htmlProvincias}`;
            distritoSelect.innerHTML = `<option value=''>Seleccione</option>`;
        });

        provinciaSelect.addEventListener('change', function(e){
            let htmlDistritos = self.config.distritos.filter(r => r.provincia === e.target.value && r.region === regionSelect.value)
                .map(r => `<option value='${r.id}'>${r.label}</option>`)
                .join('');

            distritoSelect.innerHTML = `<option value=''>Seleccione</option>${htmlDistritos}`;
        });

        document.querySelector('#filter_form')
        .addEventListener('submit', function(e){
            e.preventDefault();

            $("#spinnerData").show();
            self.renderSites()
            .then(_ => {
                $("#spinnerData").hide();
            });
        });

        $(".btn-toogle-filters").on('click', function(){
            let toogle = !($(this).attr('data-toogle') === 'true');
            if(toogle){
                $(".panel-options").show();
            } else {
                $(".panel-options").hide();
            }
            $(this).attr('data-toogle', toogle);
        });
    }

    async getSitiosCaidos(){
        return fetch(`${this.config.base_url}/afectacion_energia/sitios-caidos`)
        .then(resp => resp.json());
    }

    async renderSitiosCaidos(response){
        let self = this;
        let sitiosByUbigeo = {};
        let colorsByName = {
            'VERDE': '#27ae60',
            'ROJO_OSCURO': '#9B1619',
            'ROJO': '#DC3545',
            'NARANJA': '#F1661B',
            'AMARILLO': '#FFF000',
        };
        response//.filter(row => Number(row.cant_sitios_caidos) > 0)
        .forEach(row => {
            sitiosByUbigeo[row.ubigeo] = row;
        });

        // self.store.markers.forEach(marker => {
        //     marker.setMap(null);
        // });
        // self.store.markers = [];

        self.mapWrapper.store.layers['distritos_features']
        .forEach(feature => {
            let ubigeo = feature.getProperty("IDDIST");
            if(sitiosByUbigeo[ubigeo]){
                let row = sitiosByUbigeo[ubigeo];
                let markerLabel = sitiosByUbigeo[ubigeo].cant_sitios_caidos;
                let bounds = new google.maps.LatLngBounds();
                // console.log(feature);
                feature.getGeometry().forEachLatLng(function(path) {
                    bounds.extend(path);
                });
                let color = colorsByName[row.color_afectacion] ?? row.color_afectacion;
                color = color === null ? '#ecf0f1' : color;
                // console.log(bounds.getCenter());
                self.map.data.overrideStyle(feature, {fillColor: color, fillOpacity: 0.6, strokeWeight: 1});
                /*let marker = new google.maps.Marker({
                    map: self.map,
                    position: bounds.getCenter(),
                    label: {text: markerLabel, color: 'white'},
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: 'red',
                        fillOpacity: 1,
                        scale: 12,
                        strokeWeight: 0
                    }
                });
                self.store.markers.push(marker);*/
            }   
        });
    }

    async renderSites(){
        let self = this;
        const formData = new FormData(document.querySelector('#filter_form'));

        const params = new URLSearchParams();
        formData.forEach((value, key) => {
            params.append(key, value);
        });
        let strParams = params.toString();

        return fetch(`${this.config.base_url}/afectacion_energia/sitios?${strParams}`, {
            "headers": {"Accept": "application/json"}
        })
        .then(response => response.json())
        .then(response => {
            self.store.markers.forEach(marker => {
                marker.setMap(null);
            });
            self.store.markers = [];

            self.store.markers = response.map(row => {
                let marker = new google.maps.Marker({
                    map: self.map,
                    // data: row,
                    position: {lat: Number(row.latitud), lng: Number(row.longitud)},
                    /*label: {text: markerLabel, color: 'white'},
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: 'red',
                        fillOpacity: 1,
                        scale: 12,
                        strokeWeight: 0
                    }*/
                });
                marker.addListener('click', () => {
                    if(self.store.infoWindow !== undefined){
                        self.store.infoWindow.close();
                    }

                    self.store.infoWindow = new google.maps.InfoWindow({
                        content: `<table class="table table-condensed table-sm mb-0">
                        <thead><tr class="active"></tr>
                        </thead>
                        <tbody>
                        <tr><td>MBTS</td><td>${row.mbts}</td></tr>
                        <tr><td>Region</td><td>${row.region}</td></tr>
                        <tr><td>Ubicación</td><td>${row.departamento} - ${row.provincia} - ${row.distrito}</td></tr>
                        <tr><td>Grupo Sitios</td><td>${row.grupo_sitios??''}</td></tr>
                        <tr><td>Tiempo de Desplazamiento</td><td>${row.tiempo_desplazamiento??''}</td></tr>
                        <tr><td>Tiene Prob. Ambiental</td><td>${row.tiene_prob_ambiental??''}</td></tr>
                        <tr><td>Cant. Sitios Dependientes</td><td>${row.cant_sitios_dependientes??''}</td></tr>
                        <tr><td>Grupo Autonomia</td><td>${row.grupo_autonomia??''}</td></tr>
                        <tr><td>Grupo % de Afectacion</td><td>${row.grupo_porc_afectacion??''}</td></tr>
                        <tr><td>Trafico GB (BH)</td><td>${row.trafico_bh_gb??''}</td></tr>
                        <tr><td>Usuarios Fija</td><td>${row.usuarios_fija??''}</td></tr>
                        <tr><td>Cant. Sem. Con Caida Serv.</td><td>${row.cant_semanas_cc_ult_4m??''}</td></tr>
                        <tr><td>% de Afectacion</td><td>${row.porc_afectacion??''}</td></tr>
                        <tr><td>Fallas de Energ. Acum. (Hr)</td><td>${row.tiempo_fe_hr_acum??''}</td></tr>
                        <tr><td>Caida Serv. Acum. (Hr)</td><td>${row.tiempo_caida_sitio_hr_acum??''}</td></tr>
                        <tr><td>Autonomia</td><td>${row.autonomia !== null ? Number(row.autonomia) : ''}</td></tr>
                        <tr><td>Falla Energ. Por Cubrir (P90)</td><td>${row.hr_fe_por_cubrir_p90??''}</td></tr>
                        <tr><td>Falla Energ. Max. Por Cubrir</td><td>${row.hr_fe_por_cubrir_max??''}</td></tr>
                        <tr><td>Cant. Baterias Propuestas (P90)</td><td>${row.cant_bat_propuesta??''}</td></tr>
                        <tr><td>Caido Ulti. 4 Meses</td><td>${row.caido_ult_4meses??''}</td></tr>
                        <tr><td>Caido Ulti. Mes</td><td>${row.caido_ult_mes??''}</td></tr>
                        </tbody>
                        </table>`
                    });
                    self.store.infoWindow.open(self.map, marker);
                })
                return marker;
                // self.store.markers.push(marker);
            });
        });
    }
}