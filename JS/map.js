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

document.getElementById('mapStyle').addEventListener('change', function () {
    map.setStyle(this.value);
})

map.on('style.load', () => {
    map.setConfigProperty('basemap', 'lightPreset', 'dusk');
    map.setConfigProperty('basemap', 'showPlaceLabels', true);
    map.setConfigProperty('basemap', 'showPointOfInterestLabels', true);
    map.setConfigProperty('basemap', 'showRoadLabels', true);
    map.setConfigProperty('basemap', 'showTransitLabels', true);

});

document.getElementById('lightPreset').addEventListener('change', function () {
    map.setConfigProperty('basemap', 'lightPreset', this.value);
});

document.querySelectorAll('#mapLabels input[type="checkbox"]').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        map.setConfigProperty('basemap', this.id, this.checked);
    });
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
            place_name: 'Long: ' + lng + ' Lat: ' + lat,
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
    maxWidth: '180px'
}).setHTML('<div class="card"><div class="card-header"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#0a2885"></circle>' +
    '<path d="M17.587 14.559c-.883 0-1.49-.648-1.49-1.574 0-.912.62-1.56 1.49-1.56s1.491.648 1.491 1.573c0 .897-.634 1.56-1.49 1.56zm.03-5.152c-2.265 0-3.772 1.437-3.772 3.576 0 2.195 1.451 3.604 3.729 3.604 2.264 0 3.755-1.409 3.755-3.59 0-2.153-1.475-3.59-3.713-3.59zM11.78 6.272c-.856 0-1.395.483-1.395 1.243 0 .774.552 1.257 1.435 1.257.857 0 1.395-.483 1.395-1.257 0-.773-.552-1.243-1.435-1.243zm.152 3.204h-.277c-.675 0-1.187.317-1.187 1.285v4.42c0 .98.496 1.284 1.216 1.284h.275c.677 0 1.16-.33 1.16-1.285v-4.419c0-.995-.47-1.285-1.187-1.285zM8.316 7.392h-.4c-.76 0-1.174.43-1.174 1.285v4.13c0 1.063-.36 1.436-1.2 1.436-.662 0-1.201-.29-1.63-.816C3.87 13.373 3 13.786 3 14.81c0 1.104 1.035 1.781 2.955 1.781 2.334 0 3.563-1.173 3.563-3.742V8.675c0-.856-.413-1.283-1.202-1.283z" fill="#fff">' +
    '</path></svg></div><h5 class="card-footer"><span style="color: #FF671F;">NHQ</span> <span style="color: #06038D;">TC</span> - <span style="color: #046A38;">22</span></h5></div>')
).addTo(map);