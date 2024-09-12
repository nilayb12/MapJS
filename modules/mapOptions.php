<!-- <div class="card position-absolute top-0 start-0 mt-1 ms-1 z-3 rubik-font" id="mapOptions"> -->
<div class="card float-start mt-1 ms-1 z-3 rubik-font d-none" id="mapOptions">
    <div class="card-header">
        <span class="d-flex">
            <h6 class="card-title me-auto">Map Style</h6>
            <attr id="hideOpt" role="button" data-bs-toggle="tooltip" title="Close">❌</attr>
        </span>
        <select class="form-select" id="mapStyle">
            <option value="mapbox://styles/mapbox/standard" selected>🗺️ Standard</option>
            <option value="mapbox://styles/mapbox/standard-satellite">Standard Satellite</option>
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
    <div class="card-header">
        <span class="d-flex">
            <h6 class="card-title me-auto">Light Preset</h6>
            <attr data-bs-toggle="tooltip" data-bs-html="true"
                title="This Option is Limited to <code><strong>STANDARD</strong></code> Map Styles.">
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
        <h6 class="card-title mb-0">Map Legend</h6>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="serversPrs">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FF671F" class="bi bi-geo-alt-fill"
                    viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"></path>
                </svg> Servers Installed
            </label>
            <input class="form-check-input" type="checkbox" id="serversPrs" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="serversAbs">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0098e0" class="bi bi-geo-alt-fill"
                    viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"></path>
                </svg> No Servers
            </label>
            <input class="form-check-input" type="checkbox" id="serversAbs" checked>
        </li>
    </ul>
    <table class="card-header table table-sm table-bordered table-striped table-hover" id="mouseCoord"></table>
    <div class="card-footer">
        <span class="d-flex">
            <h6 class="card-title me-auto mb-0" id="mapLabelsToggle" role="button" data-bs-toggle="collapse"
                data-bs-target="#mapLabels">Map Labels<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-caret-down-fill ms-2" viewBox="0 0 16 16">
                    <path
                        d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg></h6>
            <attr data-bs-toggle="tooltip" data-bs-html="true"
                title="This Option is Limited to <code><strong>STANDARD</strong></code> Map Styles.">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z" />
                </svg>
            </attr>
        </span>
    </div>
    <ul class="collapse list-group list-group-flush" id="mapLabels">
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="showPlaceLabels">🏢 Place Labels</label>
            <input class="form-check-input" type="checkbox" id="showPlaceLabels" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="showPointOfInterestLabels">🏟️ POI Labels</label>
            <input class="form-check-input" type="checkbox" id="showPointOfInterestLabels" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center d-none">
            <label class="stretched-link me-auto" for="showRoadsAndTransit">🛣️ Roads/Transit</label>
            <input class="form-check-input" type="checkbox" id="showRoadsAndTransit" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center d-none">
            <label class="stretched-link me-auto" for="showPedestrianRoads">⛙ Paths/Trails</label>
            <input class="form-check-input" type="checkbox" id="showPedestrianRoads" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="showRoadLabels">🛣️ Road Labels</label>
            <input class="form-check-input" type="checkbox" id="showRoadLabels" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="showTransitLabels">🚏 Transit Labels</label>
            <input class="form-check-input" type="checkbox" id="showTransitLabels" checked>
        </li>
        <li class="list-group-item d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="show3dObjects">🧊 3D Objects</label>
            <input class="form-check-input" type="checkbox" id="show3dObjects" checked>
        </li>
    </ul>
</div>
<!-- <div class="card position-absolute start-0 ms-1 z-1" id="mapLegend" style="bottom: 5rem;"></div> -->
<button class="btn btn-sm btn-info position-relative float-start mt-1 ms-1 z-3" id="showOpt" data-bs-toggle="tooltip"
    title="Show Map Options"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        <path
            d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" />
    </svg>
    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
    </svg> -->
</button>