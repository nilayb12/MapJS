// ── MapLibre GL JS + OpenFreeMap ──────────────────────────────────────────────
// No access token needed. OpenFreeMap serves free vector tiles with no API key,
// no registration, and no usage limits. Attribution is added automatically by
// MapLibre, so there is nothing to configure.
//
// OpenFreeMap styles (plain URLs, all keyless):
//   Liberty  → detailed streets (light)      Bright → light, colorful
//   Positron → minimal light                 Dark   → CartoDB Dark Matter fork
//   Fiord    → blue-toned dark (Nordic feel)
// The raw vector tile source (used for 3D buildings) is tiles.openfreemap.org/planet

const OFM_STYLES = {
    liberty: 'https://tiles.openfreemap.org/styles/liberty',
    bright: 'https://tiles.openfreemap.org/styles/bright',
    positron: 'https://tiles.openfreemap.org/styles/positron',
    dark: 'https://tiles.openfreemap.org/styles/dark',
    fiord: 'https://tiles.openfreemap.org/styles/fiord'
};

// Which styles are visually dark — used to sync the Bootstrap UI theme to the map.
const DARK_STYLES = new Set(['dark', 'fiord']);

// Persist the chosen map style separately from the Bootstrap theme key ('theme').
const getStoredMapStyle = () => localStorage.getItem('mapStyle');
const setStoredMapStyle = (k) => localStorage.setItem('mapStyle', k);

// Sync the Bootstrap UI (option panel, popups, tables) to match the map's darkness,
// unless the user has explicitly pinned a UI theme via the Bootstrap toggle.
function syncUiThemeToStyle(styleKey) {
    const pinned = localStorage.getItem('theme'); // set only when user clicks the toggle
    if (pinned === 'light' || pinned === 'dark') return; // respect explicit choice
    document.documentElement.setAttribute(
        'data-bs-theme', DARK_STYLES.has(styleKey) ? 'dark' : 'light'
    );
}

// Decide the initial style: a previously chosen map style wins; otherwise fall back
// to the OS colour-scheme preference (dark → Fiord, light → Liberty).
function initialStyleKey() {
    const saved = getStoredMapStyle();
    if (saved && OFM_STYLES[saved]) return saved;
    const prefersDark = window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches;
    return prefersDark ? 'fiord' : 'liberty';
}

const START_STYLE = initialStyleKey();

const map = new maplibregl.Map({
    container: 'map',
    style: OFM_STYLES[START_STYLE],
    center: [79.9338403201581, 23.1680847713300], // [lng, lat] Jabalpur — same order as Mapbox
    zoom: 4,
    attributionControl: true,
    boxZoom: true,
    doubleClickZoom: true,
    dragPan: true,
    dragRotate: true,      // MapLibre keeps rotation/pitch (unlike Leaflet)
    keyboard: true,
    scrollZoom: true,
    trackResize: true
});

document.addEventListener("DOMContentLoaded", () => {
    map.resize();
    // Reflect the resolved initial style in the dropdown + UI theme.
    const sel = document.getElementById('mapStyle');
    if (sel) sel.value = START_STYLE;
    syncUiThemeToStyle(START_STYLE);
});

// ── 3D buildings toggle helper ────────────────────────────────────────────────
// OpenFreeMap's 'building' source-layer carries OSM render_height data, letting us
// add a fill-extrusion layer for the "3D Objects" switch (replaces Mapbox Standard's
// show3dObjects config property, which has no MapLibre equivalent).
function add3dBuildings() {
    if (map.getLayer('3d-buildings')) return;
    if (!map.getSource('ofm-planet')) {
        map.addSource('ofm-planet', { type: 'vector', url: 'https://tiles.openfreemap.org/planet' });
    }
    // Insert beneath the first symbol (label) layer so labels stay on top.
    const layers = map.getStyle().layers;
    let labelLayerId;
    for (const l of layers) {
        if (l.type === 'symbol' && l.layout && l.layout['text-field']) { labelLayerId = l.id; break; }
    }
    map.addLayer({
        id: '3d-buildings',
        source: 'ofm-planet',
        'source-layer': 'building',
        type: 'fill-extrusion',
        minzoom: 14,
        filter: ['!=', ['get', 'hide_3d'], true],
        paint: {
            'fill-extrusion-color': ['interpolate', ['linear'], ['get', 'render_height'],
                0, 'lightgray', 200, 'royalblue', 400, 'lightblue'],
            'fill-extrusion-height': ['interpolate', ['linear'], ['zoom'],
                14, 0, 15.05, ['get', 'render_height']],
            'fill-extrusion-base': ['interpolate', ['linear'], ['zoom'],
                14, 0, 15.05, ['get', 'render_min_height']],
            'fill-extrusion-opacity': 0.6
        }
    }, labelLayerId);
}
function remove3dBuildings() {
    if (map.getLayer('3d-buildings')) map.removeLayer('3d-buildings');
}

// ── Style switcher ────────────────────────────────────────────────────────────
// Repurposes the existing #mapStyle <select>. Values are OpenFreeMap keys.
// Persists the choice and syncs the Bootstrap UI theme to the map's darkness.
$('#mapStyle').on('change', function () {
    const key = OFM_STYLES[this.value] ? this.value : 'liberty';
    setStoredMapStyle(key);
    syncUiThemeToStyle(key);
    map.setStyle(OFM_STYLES[key]);
});

// Re-apply user's label + 3D preferences whenever a new style finishes loading,
// because setStyle() replaces the whole layer set.
map.on('style.load', () => {
    applyLabelVisibility();
    if ($('#show3dObjects').is(':checked')) add3dBuildings();
});

// ── Label visibility toggles ──────────────────────────────────────────────────
// Mapbox Standard used setConfigProperty('basemap', 'showPlaceLabels', …). OpenFreeMap
// (OpenMapTiles schema) has no single config knob, so we toggle the matching style
// layers' visibility instead. The checkbox ids map to OpenMapTiles source-layers.
const LABEL_SOURCELAYERS = {
    showPlaceLabels: ['place'],
    showPointOfInterestLabels: ['poi'],
    showRoadLabels: ['transportation_name'],
    showTransitLabels: ['transportation_name'] // OFM has no separate transit label layer
};

function setSourceLayerVisibility(sourceLayers, visible) {
    const vis = visible ? 'visible' : 'none';
    for (const l of map.getStyle().layers) {
        if (l['source-layer'] && sourceLayers.includes(l['source-layer']) &&
            l.layout && 'text-field' in (l.layout || {})) {
            map.setLayoutProperty(l.id, 'visibility', vis);
        }
    }
}

function applyLabelVisibility() {
    document.querySelectorAll('#mapLabels input[type="checkbox"]').forEach((cb) => {
        if (cb.id === 'show3dObjects') return;
        const sls = LABEL_SOURCELAYERS[cb.id];
        if (sls) setSourceLayerVisibility(sls, cb.checked);
    });
}

document.querySelectorAll('#mapLabels input[type="checkbox"]').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        if (this.id === 'show3dObjects') {
            this.checked ? add3dBuildings() : remove3dBuildings();
        } else {
            const sls = LABEL_SOURCELAYERS[this.id];
            if (sls) setSourceLayerVisibility(sls, this.checked);
        }
    });
});

// ── Live pointer coordinates + zoom readout ───────────────────────────────────
// Note: in MapLibre GL v5, map.on() does NOT return the map, so calls can't be
// chained (map.on(...).on(...) throws "on is not a function"). Keep them separate.
map.on('mousemove', (e) => {
    $('#longVal').html(roundNum(e.lngLat.lng, 5));
    $('#latVal').html(roundNum(e.lngLat.lat, 5));
});
map.on('zoom', () => {
    $('#zoomLvl').html(roundNum(map.getZoom(), 2));
});

// ── Coordinate-pair geocoder (keeps your lat/long parsing) ────────────────────
// Matches a decimal-degrees pair, same regex as the original coordinatesGeocoder.
function parseCoordinates(query) {
    const matches = query.match(
        /^[ ]*(?:Lat: )?(-?\d+\.?\d*)[, ]+(?:Long: )?(-?\d+\.?\d*)[ ]*$/i
    );
    if (!matches) return null;

    const feature = (lng, lat) => ({
        type: 'Feature',
        geometry: { type: 'Point', coordinates: [lng, lat] },
        place_name: 'Long: ' + lng + ' Lat: ' + lat,
        place_type: ['coordinate'],
        center: [lng, lat],
        properties: {}
    });

    const c1 = Number(matches[1]), c2 = Number(matches[2]);
    const out = [];
    if (c1 < -90 || c1 > 90) out.push(feature(c1, c2));          // looks like lng, lat
    if (c2 < -90 || c2 > 90) out.push(feature(c2, c1));          // looks like lat, lng
    if (out.length === 0) { out.push(feature(c1, c2)); out.push(feature(c2, c1)); }
    return out;
}

// Geocoder API adapter: coordinate pairs handled locally; place names go to
// Nominatim (OpenStreetMap's free geocoder — mind its usage policy / rate limits).
const geocoderApi = {
    forwardGeocode: async (config) => {
        const q = config.query;
        const coords = parseCoordinates(q);
        if (coords) return { features: coords };
        try {
            const url = 'https://nominatim.openstreetmap.org/search?format=geojson&countrycodes=in&limit=5&q=' +
                encodeURIComponent(q);
            const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
            const geojson = await res.json();
            const features = (geojson.features || []).map((f) => ({
                type: 'Feature',
                geometry: f.geometry,
                place_name: f.properties.display_name,
                center: f.geometry.coordinates,
                properties: f.properties
            }));
            return { features };
        } catch (e) {
            console.error('Geocode failed:', e);
            return { features: [] };
        }
    }
};

// ── Controls ──────────────────────────────────────────────────────────────────
// Built-in controls are added FIRST and unconditionally, so that if the optional
// geocoder plugin ever fails to load or throws while constructing, it can't take
// the rest of the controls down with it (the original bug: one throw here aborted
// the whole script, so nothing after it — including the custom controls — ran).
// Geocoder is optional and guarded so a plugin load failure can't break the other
// controls. It's added to top-right FIRST so it stacks ABOVE the geolocate/nav
// stack (same-corner controls added earlier sit closer to the corner edge; for
// top-right that means higher up). The guard means that even if construction throws,
// the built-in controls below still get added.
if (typeof MaplibreGeocoder !== 'undefined') {
    map.addControl(new MaplibreGeocoder(geocoderApi, {
        maplibregl: maplibregl,
        zoom: 15,
        placeholder: '🇮🇳 Lat/Long Pair, Place',
        reverseGeocode: true
    }), 'top-right');
} else {
    console.warn('MaplibreGeocoder plugin not loaded — geocoder control skipped.');
}

// Built-in controls (default to top-right, stacking below the geocoder).
map.addControl(new maplibregl.GeolocateControl({
    positionOptions: { enableHighAccuracy: true },
    trackUserLocation: true,
    showUserHeading: true,
    showAccuracyCircle: true,
    showUserLocation: true
}));
map.addControl(new maplibregl.NavigationControl({
    showCompass: true,
    showZoom: true,
    visualizePitch: true
}));
map.addControl(new maplibregl.FullscreenControl());
map.addControl(new maplibregl.ScaleControl());

// ── Custom controls (unchanged from Mapbox — same onAdd/onRemove API) ─────────
class zoomLvl {
    onAdd(map) {
        this._map = map;
        this._container = document.createElement('div');
        this._container.className = 'maplibregl-ctrl';
        this._container.innerHTML = '<h5 data-bs-toggle="tooltip" title="Zoom Level"><span class="badge bg-black rubik-font" id="zoomLvl">4</h5>';
        return this._container;
    }
    onRemove() {
        this._container.parentNode.removeChild(this._container);
        this._map = undefined;
    }
}
class lngLatVal {
    onAdd(map) {
        this._map = map;
        this._container = document.createElement('div');
        this._container.className = 'maplibregl-ctrl';
        this._container.innerHTML =
            '<table class="table table-sm table-bordered table-striped table-hover caption-top table-dark rubik-font">' +
            '<caption class="bg-white bg-opacity-50"><i class="bi bi-mouse3-fill me-1"></i>Pointer Coordinates</caption>' +
            '<tbody><tr><td class="w-25 text-center">Long</td><td id="longVal"></td></tr>' +
            '<tr><td class="text-center">Lat</td><td id="latVal"></td></tr>' +
            '</tbody></table>';
        return this._container;
    }
    onRemove() {
        this._container.parentNode.removeChild(this._container);
        this._map = undefined;
    }
}
map.addControl(new zoomLvl(), 'top-right');
map.addControl(new lngLatVal(), 'bottom-right');

$(document).ready(function () {
    $('.maplibregl-ctrl-geocoder').addClass('rounded-pill');
    $('.maplibregl-ctrl-geolocate').parent().addClass('rounded-pill');
});

// ── Default "NHQ" marker (same API, same [lng,lat] order) ─────────────────────
new maplibregl.Marker({
    color: 'blue',
    draggable: false
}).setLngLat([73.014641, 19.126813]
).setPopup(new maplibregl.Popup({
    closeButton: true,
    closeOnClick: true,
    closeOnMove: false,
    maxWidth: '180px'
}).setHTML('<div class="card"><div class="card-header"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#0a2885"></circle>' +
    '<path d="M17.587 14.559c-.883 0-1.49-.648-1.49-1.574 0-.912.62-1.56 1.49-1.56s1.491.648 1.491 1.573c0 .897-.634 1.56-1.49 1.56zm.03-5.152c-2.265 0-3.772 1.437-3.772 3.576 0 2.195 1.451 3.604 3.729 3.604 2.264 0 3.755-1.409 3.755-3.59 0-2.153-1.475-3.59-3.713-3.59zM11.78 6.272c-.856 0-1.395.483-1.395 1.243 0 .774.552 1.257 1.435 1.257.857 0 1.395-.483 1.395-1.257 0-.773-.552-1.243-1.435-1.243zm.152 3.204h-.277c-.675 0-1.187.317-1.187 1.285v4.42c0 .98.496 1.284 1.216 1.284h.275c.677 0 1.16-.33 1.16-1.285v-4.419c0-.995-.47-1.285-1.187-1.285zM8.316 7.392h-.4c-.76 0-1.174.43-1.174 1.285v4.13c0 1.063-.36 1.436-1.2 1.436-.662 0-1.201-.29-1.63-.816C3.87 13.373 3 13.786 3 14.81c0 1.104 1.035 1.781 2.955 1.781 2.334 0 3.563-1.173 3.563-3.742V8.675c0-.856-.413-1.283-1.202-1.283z" fill="#fff">' +
    '</path></svg></div><h5 class="card-footer"><span style="color: #FF671F;">NHQ</span> <span style="color: #06038D;">TC</span> - <span style="color: #046A38;">22</span></h5></div>')
).addTo(map);
