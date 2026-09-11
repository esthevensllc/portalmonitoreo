class MapController{
    constructor(config){
        this.config = config;
        this.store = {
            // marker: undefined,
            // edificiosMarker: [],
            infoWindow: undefined,
            // edificios: {
            //     hfc: [],
            //     ftth: [],
            //     overlap: [],
            // },
            // edificiosMarker: [],
            // fats: [],
            // searchFatsCache: {},
            // searchFat: {
            //     originMarker: undefined,
            //     destinoMarker: undefined,
            //     features: undefined
            // },
            layerFeatures: {},
            // layerLabels: {},
            // options: {
            //     viewLayerLabels: false
            // }
            kpiCharts: {}
        };
        this.map = undefined;
        this.initMap();
        this._createHeatLayer();
        this._createGraphs();
        this.attachHandlers();
        this.onReady();
    }

    initMap(){
        let self = this;
        this.map = L.map('map').setView([-12.046035898836875, -77.02870893069844], 11);
        L.tileLayer(`${this.config.openstreetmap_url}/{z}/{x}/{y}.png`, {
            maxZoom: 15,
            attribution: '&copy; Claro'
        }).addTo(this.map);

        let customControl = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: (map) => {
                let legend_layers = document.getElementById('layers_options_main');
                legend_layers.style.display = '';
                return legend_layers;
            }
        });

        let control = new customControl();
        this.map.addControl(control);

        this.map.createPane('layer_planos');
        this.map.getPane('layer_planos').style.zIndex = 399;
    }

    _createHeatLayer(){
        let self = this;
        
        // this.hexLayer.data([[-77.066, -11.956], [-78.540, -9.120], [-78.674, -6.553]]);
    }

    _createGraphs(){
        this.config.kpiList.forEach(row => {
            let options = {title: row.label, series: [
                {name: 'Claro', data: []},
                {name: 'Movistar', data: []},
                {name: 'Entel', data: []},
                {name: 'Bitel', data: []},
            ]};
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
            this.store.kpiCharts[row.id] = new ApexCharts(document.querySelector(`#graph-${row.id}`), chart_config);
            this.store.kpiCharts[row.id].render();
        });
    }

    attachHandlers(){
        let self = this;
        let kpiMonthsSelector = document.querySelector('#kpi_months');
        let kpiOperatorSelector = document.querySelector('#kpi_operator');
        let kpiLayerSelector = document.querySelector('#kpi_layers');

        document.querySelector('#check_layer_planos')
        .addEventListener('change', function(e){
            if (self.store.layerFeatures.planos) {
                self.store.layerFeatures.planos.remove();
                self.store.layerFeatures.planos = undefined;
            }
            if (e.target.checked) {
                $("#spinnerData").show();
                fetch(`${self.config.base_url}/map/map_19_6748.json?time=${(new Date()).getTime()}`, {
                    headers: {'Accept': 'application/json'}
                })
                .then(response => response.json())
                .then(response => {
                    $("#spinnerData").hide();
                    self.store.layerFeatures.planos = L.geoJSON(response, {
                        pane: 'layer_planos',
                        style: (element) => {
                            let fillColor = '';
                            if (element.properties.tecnologia === 'HFC') {
                                fillColor = '#E70906';
                            } else if (element.properties.tecnologia === 'FTTH') {
                                fillColor = '#3498db';
                            }
                            return {
                                fillOpacity: 0.6,
                                weight: 1,
                                fillColor: fillColor,
                                color: '#000000',
                                zIndex: 1
                            }
                        },
                        onEachFeature: (feature, layer) => {
                            // layer.bringToBack();
                            layer.on('click', async function(event){
                                event.feature = feature;
                                $("#spinnerData").show();
                                await self.showPolygonInfo(event);
                                $("#spinnerData").hide();
                            });
                        }
                    }).addTo(self.map);
                });
            }
        });

        let renderLayerHandler = function(){
            let kpiName = kpiLayerSelector.value;
            fetch(`${self.config.base_url}/ookla-map/kpis/${kpiMonthsSelector.value}/${kpiOperatorSelector.value}/${kpiLayerSelector.value}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(response => {
                let kpiLayerSelector = document.querySelector('#kpi_layers');
                if (self.hexLayer) {
                    self.hexLayer.remove();
                    self.hexLayer = undefined;
                }
                if(response.data){
                    const kpiList = response.data.map(row => kpiLayerSelector.value === 'score' ? Number(row[kpiName])*100 : Number(row[kpiName]));

                    const colorScale = d3.scaleSequential(d3.interpolateYlOrRd)
                    .domain([Math.min(...kpiList), Math.max(...kpiList)]);

                    console.log("layer min: "+Math.min(...kpiList)*100);
                    console.log("layer max: "+Math.max(...kpiList)*100);

                    let kpiConfig = {
                        'score': {
                            colorDomain: [0, 30, 40, 50, 60, 80, 100],
                            colorRange: ['#950F0F','#FF0000', '#FF8700', '#FFD300', '#0aff33ff', '#1d7c35ff'],
                        },
                        'val_download_kbps': {
                            colorDomain: [5, 10, 15, 30, 50, 100],
                            colorRange: ['#950F0F','#FF0000', '#FF8700', '#FFD300', '#0aff33ff', '#1d7c35ff'],
                        },
                        'val_upload_kbps': {
                            colorDomain: [2.5, 5, 7.5, 15, 25, 50],
                            colorRange: ['#950F0F','#FF0000', '#FF8700', '#FFD300', '#0aff33ff', '#1d7c35ff'],
                        },
                        'val_download_latency_iqm_ms': {
                            colorDomain: [250, 500, 1000, 1500, 2000, 4000],
                            colorRange: ['#1d7c35ff', '#0aff33ff',  '#FFD300', '#FF8700', '#FF0000', '#950F0F'],
                        },
                        'val_upload_latency_iqm_ms': {
                            colorDomain: [250, 500, 1000, 1500, 2000, 4000],
                            colorRange: ['#1d7c35ff', '#0aff33ff',  '#FFD300', '#FF8700', '#FF0000', '#950F0F'],
                        },
                        'num_devices': {
                            colorDomain: [1, 5, 10],
                            colorRange: ['#FFD300', '#0aff33ff', '#1d7c35ff'],
                        },
                    };

                    console.log(kpiConfig[kpiLayerSelector.value]?.colorDomain);
                    console.log(kpiConfig[kpiLayerSelector.value]?.colorRange);

                    self.hexLayer = L.hexbinLayer({
                        radius: 12, // tamaño del hexágono en píxeles
                        // opacity: 0.6,
                        opacity: 0.7,
                        // colorScaleExtent: [1, undefined], // rango de colores automático
                        // colorRange: ['black', 'red', 'orange', 'yellow', 'green', '#008FFB'],
                        // colorDomain: [0, 10, 20, 30],
                        colorDomain: kpiConfig[kpiLayerSelector.value]?.colorDomain,
                        colorRange: kpiConfig[kpiLayerSelector.value]?.colorRange,
                        // ?? ['#950F0F','#FF0000', '#FF8700', '#FFD300', '#0aff33ff', '#147DF5'],
                        // , '#580AFF'
                        duration: 200, // animación
                        colorScale: colorScale
                    })
                    // .colorScale(colorScale)
                    .colorValue(function (d) {
                        // console.log(d.x, d.y);
                        const avg = d3.mean(d, p => p['o'][2]);
                        if (kpiLayerSelector.value === 'num_devices') {
                            return d3.sum(d, p => p['o'][2]);
                        }
                        return avg;
                    })
                    .addTo(self.map);
                    
                    self.hexLayer.dispatch()
                    .on('click', function(d, i) {
                        const latlng = self.map.layerPointToLatLng(L.point(d.x, d.y));
                        console.log(latlng);
                        if (self.store.infoWindow) {
                            self.store.infoWindow.remove();
                            self.store.infoWindow = undefined;
                        }
                        let mean = d3.mean(d, p => p['o'][2])
                        let sum = d3.sum(d, p => p['o'][2]);
                        let max = d3.max(d, p => p['o'][2]);
                        let min = d3.min(d, p => p['o'][2]);
                        let htmlTable = d.map(row => `<tr>
                            <td>${row['o'][0]}</td>
                            <td>${row['o'][1]}</td>
                            <td>${row['o'][2]}</td>
                        </tr>`).join('');

                        setTimeout(() => {
                            self.store.infoWindow = L.popup({width: 200})
                            .setContent(`
                                Promedio: ${Number(mean.toFixed(2))}<br>
                                Cantidad: ${d.length}<br>
                                <div class="table-responsive" style="max-height: 200px;">
                                    <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Longitud</th>
                                            <th>Latitud</th>
                                            <th>Kpi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    ${htmlTable}
                                    </tbody>
                                </div>
                            </table>`)
                            .setLatLng(latlng)
                            .openOn(self.map);
                        }, 100);

                        console.log(self.store.infoWindow);

                        console.log({
                            mean: d3.mean(d, p => p['o'][2]),
                            sum: d3.sum(d, p => p['o'][2]),
                            data: d.filter(p => typeof p === 'object').map(p => p['o']),
                            event: d
                        });
                    });

                    
                    // const scaleIntensidad = d3.scaleLinear()
                    // .domain([Math.min(...intensidadList), Math.max(...intensidadList)])
                    // .range([1, 100]);

                    // const scaleSumIntensidad = d3.scaleLinear()
                    // .domain([Math.min(...intensidadList), d3.sum()])
                    // .range([1, 100]);

                    // let min = Math.min(...intensidadList);
                    // let max = Math.max(...intensidadList);

                    let layerData = response.data.map(row => [
                        Number(row.longitude),
                        Number(row.latitude),
                        // scaleIntensidad(Number(row[kpiName])),
                        // Number(row[kpiName]),
                        kpiLayerSelector.value === 'score' ? Number(row[kpiName])*100 : Number(row[kpiName])
                    ]);
                    self.hexLayer.data(layerData);
                    // self.store.scaleKpiValue = scaleIntensidad;
                    // self.store.scaleSumKpiValue = scaleIntensidad;
                } else {
                    alert("No se pudo cargar correctamente el resultado");
                }
            })
            .catch(error => {
                alert(error);
            });
        }

        kpiMonthsSelector.addEventListener('change', renderLayerHandler);
        kpiOperatorSelector.addEventListener('change', renderLayerHandler);
        kpiLayerSelector.addEventListener('change', renderLayerHandler);
    }

    async showPolygonInfo(event){
        let self = this;
        const feature = event.feature;
        if(self.infoWindow !== undefined){
            self.infoWindow.remove();
        }
        // let layer = feature.properties.capa;
        let contentString = "";

        contentString = `<table class="table table-condensed table-sm">
        <thead><tr class="active"></tr>
        </thead>
        <tbody>
        <tr><td>Plano</td><td class="font-weight-bold">${feature.properties.NOMBRE??''}</td></tr>
        <tr><td>Tecnologia</td><td>${feature.properties.tecnologia??''}</td></tr>
        <tr><td>Distrito</td><td>${feature.properties.distrito??''}</td></tr>
        <tr><td>Provincia</td><td>${feature.properties.provincia??''}</td></tr>
        <tr><td>Departamento</td><td>${feature.properties.departamento??''}</td></tr>
        </tbody></table>`;

        if(contentString !== ""){
            self.infoWindow = L.popup()
            .setContent(contentString)
            .setLatLng(event.latlng)
            .openOn(self.map);
        }
    }

    onReady(){
        let self = this;
        /*
        fetch(`${this.config.base_url}/ookla-map/kpis/months`, {
            headers: {'Accept': 'application/json'}
        })
        .then(response => response.json())
        .then(response => {
            document.querySelector('#kpi_months').innerHTML = response.data.map(row => `<option>${row.mes}</option>`).join('');
            document.querySelector('#kpi_layers').dispatchEvent(new Event('change'));
        });
        */

        document.querySelector('#kpi_layers').dispatchEvent(new Event('change'));

        fetch(`${this.config.base_url}/ookla-map/kpis/last-months`, {
            headers: {'Accept': 'application/json'}
        })
        .then(response => response.json())
        .then(response => {
            let kpiByMonth = {};
            response.data.forEach(row => {
                let operator = row[response.fields.operator];
                if (kpiByMonth[operator]) {
                    kpiByMonth[operator].push(row);
                } else {
                    kpiByMonth[operator] = [row];
                }
            });
            response.data = [];
            let colorsByOperator = {'Claro': '#FF4560', 'Entel': '#008FFB', 'Bitel': '#FEB019', 'Movistar': '#00E396'};
            
            self.config.kpiList.forEach(kpi => {
                let colors = [];
                let series = Object.keys(kpiByMonth).map(operator => {
                    if (colorsByOperator[operator]) {
                        colors.push(colorsByOperator[operator]);
                    }
                    let data = kpiByMonth[operator].map(row => {
                        let value = row[response.fields[kpi.id]];
                        return {
                            x: new Date(row[response.fields.mes]).getTime(),
                            y: value !== null ? Number(value) : null,
                        };
                    });
                    return {name: operator, data: data}
                });
                self.store.kpiCharts[kpi.id].updateSeries(series);
                self.store.kpiCharts[kpi.id].updateOptions({colors: colors});
            });
        });
    }
}