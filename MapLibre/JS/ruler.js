// ── Measure / Ruler tool ──────────────────────────────────────────────────────
// Toggle-able distance measurer for MapLibre GL v5.
//   • toolbar button toggles measuring; only captures clicks while ACTIVE
//   • click to drop points; each consecutive pair becomes its own segment
//   • HOVER a segment to see a tooltip: its length + the coordinates of the two
//     points it connects (this replaces the old fixed readout panel)
//   • right-click / Esc / toggling off clears the measurement
//   • re-installs layers after a style switch (dark mode wipes added layers)
//   • line/point colours adapt to light vs dark basemaps
//
// Depends on the global `map` (from map.js) and Turf (`turf`).

(function () {
    'use strict';

    const SRC = 'measure-src';
    const LAYER_LINE = 'measure-line';       // visible dashed line
    const LAYER_HIT = 'measure-line-hit';    // wide invisible line for easy hovering
    const LAYER_PTS = 'measure-pts';
    const LAYER_HALO = 'measure-pts-halo';
    const LAYER_LABEL = 'measure-labels';    // A, B, C… letters on each point

    const SNAP_PX = 15; // click within this many pixels of a server pin snaps to it

    let active = false;
    let points = [];   // array of [lng, lat]
    let hoverPopup = null;

    const fmt = (n) => n.toLocaleString(undefined, { maximumFractionDigits: 2 });
    const fmtCoord = (c) => fmt(c[1]) + ', ' + fmt(c[0]); // "lat, lng"
    // Point index (1-based) → letter label. 1→A … 26→Z, then AA, AB… for safety.
    function letterFor(idx) {
        let n = idx - 1, s = '';
        do { s = String.fromCharCode(65 + (n % 26)) + s; n = Math.floor(n / 26) - 1; } while (n >= 0);
        return s;
    }

    // Build a FeatureCollection: one point per vertex, and one LineString PER
    // SEGMENT (not a single multi-point line) so each segment can carry its own
    // hover properties and be picked out individually by queryRenderedFeatures.
    function data() {
        const features = points.map((c, i) => ({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: c },
            properties: { idx: i + 1, label: letterFor(i + 1) }
        }));
        for (let i = 1; i < points.length; i++) {
            const a = points[i - 1], b = points[i];
            const seg = turf.length({
                type: 'Feature',
                geometry: { type: 'LineString', coordinates: [a, b] }
            });
            features.push({
                type: 'Feature',
                geometry: { type: 'LineString', coordinates: [a, b] },
                properties: {
                    idx: i,
                    km: seg,
                    aLng: a[0], aLat: a[1],
                    bLng: b[0], bLat: b[1]
                }
            });
        }
        return { type: 'FeatureCollection', features };
    }

    function palette() {
        const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        return dark
            ? { line: '#4dd0e1', point: '#ffffff', halo: '#00838f' }
            : { line: '#d81b60', point: '#ffffff', halo: '#880e4f' };
    }

    function installLayers() {
        if (!map.getSource(SRC)) {
            map.addSource(SRC, { type: 'geojson', data: data() });
        }
        const p = palette();
        // Wide, transparent line first — this is the generous hover/hit target.
        if (!map.getLayer(LAYER_HIT)) {
            map.addLayer({
                id: LAYER_HIT, type: 'line', source: SRC,
                filter: ['==', ['geometry-type'], 'LineString'],
                layout: { 'line-cap': 'round', 'line-join': 'round' },
                paint: { 'line-color': p.line, 'line-width': 14, 'line-opacity': 0 }
            });
        }
        if (!map.getLayer(LAYER_LINE)) {
            map.addLayer({
                id: LAYER_LINE, type: 'line', source: SRC,
                filter: ['==', ['geometry-type'], 'LineString'],
                layout: { 'line-cap': 'round', 'line-join': 'round' },
                paint: { 'line-color': p.line, 'line-width': 3, 'line-dasharray': [2, 1] }
            });
        }
        if (!map.getLayer(LAYER_HALO)) {
            map.addLayer({
                id: LAYER_HALO, type: 'circle', source: SRC,
                filter: ['==', ['geometry-type'], 'Point'],
                paint: { 'circle-radius': 10, 'circle-color': p.halo }
            });
        }
        if (!map.getLayer(LAYER_PTS)) {
            map.addLayer({
                id: LAYER_PTS, type: 'circle', source: SRC,
                filter: ['==', ['geometry-type'], 'Point'],
                paint: {
                    'circle-radius': 9, 'circle-color': p.halo,
                    'circle-stroke-color': p.point, 'circle-stroke-width': 1.5
                }
            });
        }
        // Letter labels (A, B, C…) drawn on top of the point discs.
        if (!map.getLayer(LAYER_LABEL)) {
            map.addLayer({
                id: LAYER_LABEL, type: 'symbol', source: SRC,
                filter: ['==', ['geometry-type'], 'Point'],
                layout: {
                    'text-field': ['get', 'label'],
                    'text-size': 12,
                    'text-font': ['Noto Sans Bold'],
                    'text-allow-overlap': true,
                    'text-ignore-placement': true
                },
                paint: { 'text-color': p.point }
            });
        }
        wireHover();
    }

    function removeLayers() {
        [LAYER_LABEL, LAYER_PTS, LAYER_HALO, LAYER_LINE, LAYER_HIT].forEach((id) => {
            if (map.getLayer(id)) map.removeLayer(id);
        });
        if (map.getSource(SRC)) map.removeSource(SRC);
    }

    function refresh() {
        const src = map.getSource(SRC);
        if (src) src.setData(data());
    }

    // ── Hover tooltip on segments ────────────────────────────────────────────
    let hoverWired = false;
    function wireHover() {
        if (hoverWired) return;
        hoverWired = true;

        map.on('mousemove', LAYER_HIT, (e) => {
            if (!active || !e.features || !e.features.length) return;
            const f = e.features[0];
            const pr = f.properties;
            map.getCanvas().style.cursor = 'pointer';

            const a = [+pr.aLng, +pr.aLat], b = [+pr.bLng, +pr.bLat];
            // Bearing (0–360° from north) gives the segment a direction readout.
            let bearing = turf.bearing(turf.point(a), turf.point(b));
            if (bearing < 0) bearing += 360;
            const compass = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N'][Math.round(bearing / 45)];

            const html =
                '<div class="card border-0 rubik-font" style="min-width:15rem;">' +
                  '<div class="card-header d-flex align-items-center justify-content-between py-1 px-2">' +
                    '<span class="fw-semibold"><i class="bi bi-rulers me-1"></i>Segment ' + pr.idx + '</span>' +
                    '<span class="badge rounded-pill bg-primary">' + fmt(+pr.km) + ' km</span>' +
                  '</div>' +
                  '<ul class="list-group list-group-flush small">' +
                    '<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">' +
                      '<span class="text-body-secondary"><i class="bi bi-compass me-1"></i>Bearing</span>' +
                      '<span class="font-monospace">' + fmt(bearing) + '° ' + compass + '</span>' +
                    '</li>' +
                    '<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">' +
                      '<span class="text-success"><i class="bi bi-geo-alt-fill me-1"></i>A</span>' +
                      '<span class="font-monospace">' + fmtCoord(a) + '</span>' +
                    '</li>' +
                    '<li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">' +
                      '<span class="text-danger"><i class="bi bi-geo-alt-fill me-1"></i>B</span>' +
                      '<span class="font-monospace">' + fmtCoord(b) + '</span>' +
                    '</li>' +
                  '</ul>' +
                '</div>';

            if (!hoverPopup) {
                hoverPopup = new maplibregl.Popup({
                    closeButton: false,
                    closeOnClick: false,
                    offset: 8,
                    className: 'measure-popup'
                });
            }
            hoverPopup.setLngLat(e.lngLat).setHTML(html).addTo(map);
        });

        map.on('mouseleave', LAYER_HIT, () => {
            map.getCanvas().style.cursor = active ? 'crosshair' : '';
            if (hoverPopup) { hoverPopup.remove(); }
        });
    }

    // ── Interaction ──────────────────────────────────────────────────────────
    // Return the coordinates to use for a click: the nearest server pin within
    // SNAP_PX screen pixels if there is one, otherwise the raw click location.
    function snapToPin(e) {
        const pins = window.serverPins;
        if (!Array.isArray(pins) || !pins.length) return [e.lngLat.lng, e.lngLat.lat];
        const cp = e.point; // click position in screen pixels
        let best = null, bestDist = SNAP_PX;
        for (const pin of pins) {
            const pp = map.project([pin.lng, pin.lat]); // pin position in screen pixels
            const d = Math.hypot(pp.x - cp.x, pp.y - cp.y);
            if (d <= bestDist) { bestDist = d; best = pin; }
        }
        return best ? [best.lng, best.lat] : [e.lngLat.lng, e.lngLat.lat];
    }

    function onClick(e) {
        if (!active) return;
        points.push(snapToPin(e)); // snap to a nearby server pin if one is close
        refresh();
    }

    function clearMeasure() {
        points = [];
        if (hoverPopup) { hoverPopup.remove(); }
        refresh();
    }

    function onContextMenu(e) {
        if (!active) return;
        e.preventDefault();
        clearMeasure();
    }

    function onKey(e) {
        if (active && e.key === 'Escape') clearMeasure();
    }

    function setActive(on) {
        active = on;
        const btn = document.getElementById('measure-btn');
        if (btn) btn.classList.toggle('active', on);
        map.getCanvas().style.cursor = on ? 'crosshair' : '';
        if (on) {
            installLayers();
            refresh();
        } else {
            clearMeasure();
            if (hoverPopup) { hoverPopup.remove(); }
        }
    }

    // Toolbar button as a MapLibre control.
    class MeasureControl {
        onAdd(m) {
            this._map = m;
            const c = document.createElement('div');
            c.className = 'maplibregl-ctrl maplibregl-ctrl-group';
            c.innerHTML =
                '<button id="measure-btn" type="button" title="Measure distance" ' +
                'aria-label="Measure distance"><i class="bi bi-rulers"></i></button>';
            c.querySelector('#measure-btn').addEventListener('click', () => setActive(!active));
            this._container = c;
            return c;
        }
        onRemove() {
            this._container.parentNode.removeChild(this._container);
            this._map = undefined;
        }
    }

    function init() {
        map.addControl(new MeasureControl(), 'bottom-right');
        map.on('click', onClick);
        map.on('contextmenu', onContextMenu);
        document.addEventListener('keydown', onKey);
        // A style switch (dark mode) wipes added layers + source — reinstall if active.
        map.on('style.load', () => {
            hoverWired = false; // listeners are per-layer; re-wire after re-add
            if (active) { installLayers(); refresh(); }
        });
    }

    if (map.loaded()) init();
    else map.on('load', init);
})();
