<div class="card position-absolute top-0 start-0 mt-1 ms-1 z-1" id="mapOptions">
    <div class="card-header">
        <h6 class="card-title">Map Style</h6>
        <select class="form-select" id="mapStyle">
            <option value="mapbox://styles/mapbox/standard" selected>🗺️ Standard</option>
            <option value="mapbox://styles/mapbox/streets-v12">🛣️ Streets</option>
            <option value="mapbox://styles/mapbox/outdoors-v12">🏞️ Outdoors</option>
            <option value="mapbox://styles/mapbox/light-v11">💡 Light</option>
            <option value="mapbox://styles/mapbox/dark-v11">🌑 Dark</option>
            <option value="mapbox://styles/mapbox/satellite-v9">🛰️ Satellite</option>
            <option value="mapbox://styles/mapbox/satellite-streets-v12">Satellite Streets</option>
            <option value="mapbox://styles/mapbox/navigation-day-v1">☀️ Navigation</option>
            <option value="mapbox://styles/mapbox/navigation-night-v1">🌙 Navigation</option>
        </select>
    </div>
    <div class="card-footer">
        <span class="d-flex">
            <h6 class="card-title me-auto">Light Preset</h6>
            <attr data-bs-toggle="tooltip" data-bs-html="true"
                data-bs-title="This Option is Limited to the <code>STANDARD</code> Map Style.">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z" />
                </svg>
            </attr>
        </span>
        <select class="form-select" id="lightPreset">
            <option value="dawn">⛅ Dawn</option>
            <option value="day">☀️ Day</option>
            <option value="dusk" selected>🌄 Dusk</option>
            <option value="night">🌙 Night</option>
        </select>
    </div>
    <div class="card-footer">
        <span class="d-flex">
            <h6 class="card-title me-auto" id="mapLabelsToggle" role="button" data-bs-toggle="collapse"
                data-bs-target="#mapLabels">Map Labels <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                    <path
                        d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg></h6>
            <attr data-bs-toggle="tooltip" data-bs-html="true"
                data-bs-title="This Option is Limited to the <code>STANDARD</code> Map Style.">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z" />
                </svg>
            </attr>
        </span>
    </div>
    <ul class="collapse list-group list-group-flush" id="mapLabels">
        <li class="list-group-item d-flex">
            <label class="stretched-link me-auto" for="showPlaceLabels">🏢 Place Labels</label>
            <input class="form-check-input" type="checkbox" id="showPlaceLabels" checked>
        </li>
        <li class="list-group-item d-flex">
            <label class="stretched-link me-auto" for="showPointOfInterestLabels">🏟️ POI Labels</label>
            <input class="form-check-input" type="checkbox" id="showPointOfInterestLabels" checked>
        </li>
        <li class="list-group-item d-flex">
            <label class="stretched-link me-auto" for="showRoadLabels">🛣️ Road Labels</label>
            <input class="form-check-input" type="checkbox" id="showRoadLabels" checked>
        </li>
        <li class="list-group-item d-flex">
            <label class="stretched-link me-auto" for="showTransitLabels">🚉 Transit Labels</label>
            <input class="form-check-input" type="checkbox" id="showTransitLabels" checked>
        </li>
    </ul>
</div>
<div class="card position-absolute start-0 ms-1 z-1" id="mapLegend" style="bottom: 5rem;">
    <div class="card-header">
        <h6 class="card-title">Map Legend</h6>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex">
            <label class="stretched-link me-1" for="serversPrs">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FF671F" class="bi bi-geo-alt-fill"
                    viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"></path>
                </svg> Servers Installed
            </label>
            <input class="form-check-input" type="checkbox" id="serversPrs" checked>
        </li>
        <li class="list-group-item d-flex">
            <label class="stretched-link me-auto" for="serversAbs">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0098e0" class="bi bi-geo-alt-fill"
                    viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"></path>
                </svg> No Servers
            </label>
            <input class="form-check-input" type="checkbox" id="serversAbs" checked>
        </li>
    </ul>
    <div class="card-footer" id="mouseCoord"></div>
</div>