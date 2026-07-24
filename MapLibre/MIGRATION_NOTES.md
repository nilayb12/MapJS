# MapJS — Mapbox GL JS → MapLibre GL JS + OpenFreeMap

This is the ported version of the Circle Server Map. The map engine is now
**MapLibre GL JS 5.24.0** with **OpenFreeMap** vector tiles. No access token,
no API key, no billing.

## What changed

- **index.php** — swapped Mapbox JS/CSS + Mapbox Geocoder for MapLibre GL JS
  5.24.0 and `@maplibre/maplibre-gl-geocoder`. Everything else untouched.
- **JS/map.js** — `maplibregl.Map` with OpenFreeMap style URLs; style switcher
  repointed to `liberty` / `bright` / `positron`; light-preset logic removed
  (no OpenFreeMap equivalent); label toggles now use `setLayoutProperty` on
  OpenMapTiles label layers; `show3dObjects` builds a `fill-extrusion` layer
  from OpenFreeMap's `building` source-layer; MapboxGeocoder replaced by a
  MapLibre geocoder that keeps your lat/long-pair regex and falls back to
  Nominatim for place names. Custom controls, pointer readout, and the default
  NHQ marker are unchanged (same API).
- **modules/mapMarkers.php** — `maplibregl.Marker`/`Popup`; legend show/hide now
  keyed off a `data-srv` attribute set at marker creation (robust) instead of
  the old SVG child-index `fill` check.
- **modules/mapMarkers_noPing.php** — same prefix port.
- **modules/mapOptions.php** — style `<select>` values → OpenFreeMap keys; Light
  Preset block removed; Map Labels tooltip updated.
- **JS/ruler.js** — no change needed (engine-agnostic; uses `map` + Turf only).
- **style.css** — `.mapboxgl-*` → `.maplibregl-*`.

## Unchanged (backend/data layer)

dbConfig.php, search.php, ping.php, MySQL schema, IPv6 hex logic, script.js
polling/search, colorToggle.js, Bootstrap markup, Turf.

## Notes / things to tune

- **Pin the version.** Stay on `maplibre-gl@5.24.0`. v6 pre-releases drop WebGL1
  support and move to ESM — don't use `@latest` in production yet.
- **Label toggles** depend on OpenMapTiles source-layer names (`place`, `poi`,
  `transportation_name`). If you switch to a non-OpenMapTiles style, revisit
  `LABEL_SOURCELAYERS` in map.js.
- **Geocoder / Nominatim** has strict rate limits and a usage policy. For heavy
  use, self-host Nominatim or swap in Photon. Coordinate-pair search is local
  and unaffected.
- **Attribution** is added automatically by MapLibre for OpenFreeMap — nothing
  to do for web. (Only needed manually for print/video or non-MapLibre clients.)
- **3D buildings** appear from zoom 14+. The `show3dObjects` switch adds/removes
  the extrusion layer and is re-applied on every style change.

---

## Update: dark map, legend counts, measure tool

### Dark map (Dark + Fiord)
- Added two keyless OpenFreeMap dark styles to the `#mapStyle` dropdown: **Dark**
  (CartoDB Dark Matter fork) and **Fiord** (blue-toned).
- The Bootstrap UI (option panel, popups, tables) now follows the map's darkness
  automatically via `data-bs-theme` (`syncUiThemeToStyle` in map.js).
- The chosen style is remembered across reloads (`localStorage['mapStyle']`).
- On first visit with no saved choice, the map follows the OS colour scheme
  (`prefers-color-scheme: dark` → Fiord, otherwise Liberty).
- If you re-enable the Bootstrap theme toggle (colorToggle.js, currently commented
  out in index.php), an explicit user theme pin (`localStorage['theme']`) will
  override the auto-sync — that's intentional.

### Legend live counts
- mapMarkers.php now counts server-present vs server-absent cities **server-side**
  during the existing marker loop (no extra queries) and renders the totals as
  pill badges next to the two legend switches.

### Measure / ruler tool (rewritten)
- JS/ruler.js is a fresh MapLibre-v5 implementation, re-enabled in index.php.
- It's a **toggle** (ruler button, top-left). It only captures clicks while active,
  so it no longer hijacks the cursor or fights with panning/markers (the old bug).
- Click to drop points; a floating panel shows total + per-segment distance (km via
  Turf). Right-click or Esc clears; the button toggles off.
- Line/point colours adapt to light vs dark basemaps, and the layers are
  automatically reinstalled after a style switch (dark mode wipes added layers).

---

## Update: consolidation

- **Merged mapMarkers_noPing.php into mapMarkers.php.** The no-ping variant had
  drifted badly out of sync (missing legend counts, data-srv tags, serverPins
  registration, and still using the old fragile SVG-fill legend hack). It's now a
  single file driven by a `$ping` flag (defaults to true). For off-intranet
  deployments where ping doesn't work, set `$ping = false;` before including
  mapMarkers.php — this omits the per-server Status column and ping cells while
  keeping everything else identical. mapMarkers_noPing.php has been deleted.
- **Removed dead label-toggle markup** in mapOptions.php: the Mapbox-Standard-era
  `showRoadsAndTransit` and `showPedestrianRoads` switches (hidden with d-none and
  wired to nothing) are gone. map.js never referenced them.
