<button class="btn btn-sm btn-primary position-relative float-start mt-1 ms-1 z-1 shadow" id="showOpt"
    data-bs-toggle="tooltip" title="Show Map Options"><i class="bi bi-list"
        style="font-size: medium; line-height: 0"></i>
    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
        viewBox="0 0 448 512">!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.
        <path
            d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" />
    </svg> -->
</button>
<!-- <div class="card position-absolute top-0 start-0 mt-1 ms-1 z-3 rubik-font" id="mapOptions"> -->
<div class="card float-start mt-1 ms-1 z-1 rubik-font d-none" id="mapOptions">
    <div class="card-header">
        <span class="d-flex">
            <h6 class="card-title me-auto">Map Style</h6>
            <attr id="hideOpt" role="button" data-bs-toggle="tooltip" title="Close">❌</attr>
        </span>
        <select class="form-select form-select-sm rounded-pill" id="mapStyle">
            <optgroup label="Standard Maps">
                <option value="mapbox://styles/mapbox/standard" selected>🗺️ Standard</option>
                <option value="mapbox://styles/mapbox/standard-satellite">Standard Satellite</option>
            </optgroup>
            <optgroup label="Classic Maps">
                <option value="mapbox://styles/mapbox/streets-v12">🛣️ Streets</option>
                <option value="mapbox://styles/mapbox/outdoors-v12">🏞️ Outdoors</option>
                <option value="mapbox://styles/mapbox/light-v11">💡 Light</option>
                <option value="mapbox://styles/mapbox/dark-v11">🌑 Dark</option>
                <option value="mapbox://styles/mapbox/satellite-v9">🛰️ Satellite</option>
                <option value="mapbox://styles/mapbox/satellite-streets-v12">Satellite Streets</option>
                <option value="mapbox://styles/mapbox/navigation-day-v1">☀️ Navigation</option>
                <option value="mapbox://styles/mapbox/navigation-night-v1">🌙 Navigation</option>
            </optgroup>
        </select>
    </div>
    <div class="card-header">
        <span class="d-flex">
            <h6 class="card-title me-auto">Light Preset</h6>
            <attr data-bs-toggle="tooltip" data-bs-html="true"
                title="This Option is Limited to <code><strong>STANDARD</strong></code> Map Styles.">
                <i class="bi bi-question-circle-fill"></i>
            </attr>
        </span>
        <select class="form-select form-select-sm rounded-pill" id="lightPreset">
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
        <li class="list-group-item form-switch d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="serversPrs">
                <i class="bi bi-geo-alt-fill" style="color: #FF671F;"></i> Live Servers
            </label>
            <input class="form-check-input" type="checkbox" id="serversPrs" checked>
        </li>
        <li class="list-group-item form-switch d-flex !justify-content-between align-items-center">
            <label class="stretched-link me-auto" for="serversAbs">
                <i class="bi bi-geo-alt-fill" style="color: #0098E0;"></i> No Servers
            </label>
            <input class="form-check-input" type="checkbox" id="serversAbs" checked>
        </li>
    </ul>
    <!-- <table class="card-header table table-sm table-bordered table-striped table-hover" id="mouseCoord">
        <tbody>
            <tr>
                <td class="w-25 text-center">Long</td>
                <td id="longVal"></td>
            </tr>
            <tr>
                <td class="text-center">Lat</td>
                <td id="latVal"></td>
            </tr>
            <tr>
                <td class="text-center">Zoom</td>
                <td id="zoomLvl">6</td>
            </tr>
        </tbody>
    </table> -->
    <div class="card-footer">
        <span class="d-flex">
            <h6 class="card-title me-auto mb-0" id="mapLabelsToggle" role="button" data-bs-toggle="collapse"
                data-bs-target="#mapLabels">Map Labels<i class="bi bi-caret-down-fill ms-2"></i></h6>
            <attr data-bs-toggle="tooltip" data-bs-html="true"
                title="This Option is Limited to <code><strong>STANDARD</strong></code> Map Styles.">
                <i class="bi bi-question-circle-fill"></i>
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
<form class="position-absolute start-50 translate-middle-x mt-1 z-1" id="searchForm">
    <fieldset class="input-group input-group-sm rounded-pill shadow">
        <span class="input-group-text rounded-start-pill"><i class="bi bi-search"></i></span>
        <input type="search" class="form-control rounded-end-pill" id="search" minlength="11"
            placeholder="IPv6 (No Colons; Min. 10 Char.)">
    </fieldset>
    <ul class="dropdown-menu overflow-auto shadow" id="searchRes" style="max-height: 14rem;">
    </ul>
</form>