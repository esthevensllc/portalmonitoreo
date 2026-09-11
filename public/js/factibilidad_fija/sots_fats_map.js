class FatSearcher
{
    constructor(map){
        this.map = map;
        this.originMarker;
        this.features = [];
        this.fatsMarkers = [];
        this.infoWindow;
    }

    find(origin){
        let self = this;
        // let isActive = $(".btn-buscar-fats").attr("data-active") === 'true';
        $("#spinnerData").show();
        this.originMarker = new google.maps.Marker({
            map: this.map,
            position: origin,
        });
        $(".btn-buscar-fats").attr("data-active", false);
        $(".check_tecnologia").trigger("change");

        //fetch(`${base_url}api/busqueda-fats`)
        //fetch(`http://127.0.0.1:8000/api/busqueda-fats?longitud=${event.latLng.lng()}&latitud=${event.latLng.lat()}`)
        return fetch(`http://172.19.122.127:8001/api/busqueda-fats?longitud=${origin.lng}&latitud=${origin.lat}`)
        .then(resp => resp.json())
        .then(response => {
            if(response.passes === true){
                let geojson = {
                    "type": "FeatureCollection",
                    "features": response.data.polylines
                };
                let features = self.map.data.addGeoJson(geojson);
                features.forEach(feature => {
                    let style = {
                        strokeColor: "#3498db",
                        strokeOpacity: 1.0,
                        strokeWeight: 3,
                        zIndex: 2
                    };
                    self.map.data.overrideStyle(feature, style);
                    feature.setProperty("capa", "busqueda_fats");
                });
                self.features = features;

                // markers
                self.fatsMarkers = response.data.fats.map(row => {
                    return new google.maps.Marker({
                        map: self.map,
                        position: new google.maps.LatLng(parseFloat(row.latitud), parseFloat(row.longitud)),
                        icon: `${config.base_url}/images/trapecio.png`,
                    });
                });

                if(self.infoWindow){
                    self.infoWindow.close();
                }

                self.infoWindow = new google.maps.InfoWindow({
                    content: `<table class="table table-condensed table-sm">
                    <thead><tr class="active"></tr>
                    </thead>
                    <tbody>  
                    <tr><td>Origen latitud</td><td>${origin.lat}</td></tr>
                    <tr><td>Origlen longitud</td><td>${origin.lng}</td></tr>
                    <tr><td>Plano</td><td>${response.data.fats[0].plano}</td></tr>
                    <tr><td>Nombre Fat</td><td>${response.data.fats[0].fat}</td></tr>
                    <tr><td>Distancia</td><td>${features[0].getProperty('distance')}</td></tr>
                    </tbody>
                    </table>`
                });
                self.infoWindow.open(self.map, self.originMarker);
                self.originMarker.addListener("click", function(){
                    self.infoWindow.open(self.map, self.originMarker);
                });
            } else {
                alert(response.errors.message);
            }
            $("#spinnerData").hide();
        })
        .catch(error => {
            alert(error.message);
            $("#spinnerData").hide();
        });
    }

    clear(){
        if(this.originMarker){
            this.originMarker.setMap(null);
            this.originMarker = undefined;
        }
        this.features.forEach(feature => {
            map.data.remove(feature);
        });
        this.fatsMarkers.forEach(marker => {
            marker.setMap(null);
        });
    }
}

class ViewModel {
    constructor(){
        this.store = {};
        this.map = undefined;
        this.initMap();
        this.fatSearcher = new FatSearcher(this.map);
        this.ready();
        this.attachHandlers();
    }

    initMap(){
        this.map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(-10.0431805,-74.0282364),
            zoom: 5.8,
            mapTypeID: google.maps.MapTypeId.ROADMAP,
            minZoom:-1
        });
    }

    ready(){
        let url = new URL(document.location);
        let origin = {
            lat: parseFloat(url.searchParams.get('lat')),
            lng: parseFloat(url.searchParams.get('lng'))
        };
        if(isNaN(origin.lat) || isNaN(origin.lng)){
            alert(`La latitud: ${url.searchParams.get('lat')} y longitud: ${url.searchParams.get('lng')} no son validos`);
        }else{
            this.fatSearcher.find(origin);
            this.map.setCenter(origin);
            this.map.setZoom(18);
        }
    }

    attachHandlers(){

    }
}

let service = new ViewModel();