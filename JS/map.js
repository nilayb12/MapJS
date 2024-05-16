mapboxgl.accessToken = 'pk.eyJ1IjoibmlsYXlyaWwiLCJhIjoiY2x3NmhieTZqMW9sYTJqcGQ3Y2o2Mmd0eCJ9.8cNx9NZ2B0gEMRZVkvuXUg';
// DON'T. TAMPER. WITH. THE. KEY.
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/standard',
    center: [73.014641, 19.126813], // [lng, lat]
    zoom: 6,
    cooperativeGestures: true,
    attributionControl: true,
    boxZoom: true,
    doubleClickZoom: true,
    dragPan: true,
    dragRotate: true,
    interactive: true,
    keyboard: true,
    logoPosition: 'bottom-left',
    scrollZoom: true,
    trackResize: true
});

map.on('style.load', () => {
    map.setConfigProperty('basemap', 'lightPreset', 'dusk');
    map.setConfigProperty('basemap', 'showPlaceLabels', true);
    map.setConfigProperty('basemap', 'showRoadLabels', true);
    map.setConfigProperty('basemap', 'showPointOfInterestLabels', true);
    map.setConfigProperty('basemap', 'showTransitLabels', true);

});

map.on('mousemove', (e) => {
    document.getElementById('mouseCoord').innerHTML =
        // `e.point` is the x, y coordinates of the `mousemove` event
        // relative to the top-left corner of the map.
        // JSON.stringify(e.point) +
        // '<br />' +
        // `e.lngLat` is the longitude, latitude geographical position of the event.
        JSON.stringify(e.lngLat.wrap());
});

const coordinatesGeocoder = function (query) {
    // Match anything which looks like
    // decimal degrees coordinate pair.
    const matches = query.match(
        /^[ ]*(?:Lat: )?(-?\d+\.?\d*)[, ]+(?:Long: )?(-?\d+\.?\d*)[ ]*$/i
    );
    if (!matches) {
        return null;
    }

    function coordinateFeature(lng, lat) {
        return {
            center: [lng, lat],
            geometry: {
                type: 'Point',
                coordinates: [lng, lat]
            },
            place_name: 'Lat: ' + lat + ' Long: ' + lng,
            place_type: ['coordinate'],
            properties: {},
            type: 'Feature'
        };
    }

    const coord1 = Number(matches[1]);
    const coord2 = Number(matches[2]);
    const geocodes = [];

    if (coord1 < -90 || coord1 > 90) {
        // lng, lat
        geocodes.push(coordinateFeature(coord1, coord2));
    }

    if (coord2 < -90 || coord2 > 90) {
        // lat, lng
        geocodes.push(coordinateFeature(coord2, coord1));
    }

    if (geocodes.length === 0) {
        // either lng, lat or lat, lng
        geocodes.push(coordinateFeature(coord1, coord2));
        geocodes.push(coordinateFeature(coord2, coord1));
    }

    return geocodes;
};

map.addControl(new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    countries: 'in',
    localGeocoder: coordinatesGeocoder,
    zoom: 4,
    placeholder: '🇮🇳 Lat/Long Pair, Place',
    mapboxgl: mapboxgl,
    reverseGeocode: true
})
).addControl(new mapboxgl.GeolocateControl({
    positionOptions: {
        enableHighAccuracy: true
    },
    trackUserLocation: true,
    showUserHeading: true,
    showAccuracyCircle: true,
    showUserLocation: true
})
).addControl(new mapboxgl.NavigationControl({
    showCompass: true,
    showZoom: true
})
).addControl(new mapboxgl.FullscreenControl()
).addControl(new mapboxgl.ScaleControl()
);

// Set marker options.
new mapboxgl.Marker({
    color: 'blue',
    draggable: false
}).setLngLat([73.014641, 19.126813]
).setPopup(new mapboxgl.Popup({
    closeButton: true,
    closeOnClick: true,
    closeOnMove: false,
    maxWidth: '300px'
}).setHTML('<h5 class="card card-body d-block"><span style="color: #FF671F;">TC</span><span style="color: #06038D;">-</span><span style="color: #046A38;">22</span></h5>')
).addTo(map);