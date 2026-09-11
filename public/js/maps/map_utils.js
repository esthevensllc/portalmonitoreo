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