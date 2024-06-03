<?php include ('modules/dbConfig.php'); ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Server Map</title>
    <link rel="icon" type="image/png" href="india.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.2/mapbox-gl-geocoder.css"
        type="text/css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.js'></script>
    <script
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.2/mapbox-gl-geocoder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@mapbox-controls/ruler@1.2.0/src/index.min.js"></script> -->
    <!-- <script src="JS/colorToggle.js"></script> -->
    <div id="map">
    <!-- <div id="distance" class="distance-container"></div> -->
    <div class="card position-absolute top-0 start-0 mt-2 ms-2" id="mapOptions">
        <div class="card-header">
            <h6 class="card-title">Map Style</h6>
            <select class="form-select" id="mapStyle">
                <option value="mapbox://styles/mapbox/standard" selected>Standard</option>
                <option value="mapbox://styles/mapbox/streets-v12">🛣️ Streets</option>
                <option value="mapbox://styles/mapbox/outdoors-v12">Outdoors</option>
                <option value="mapbox://styles/mapbox/light-v11">💡 Light</option>
                <option value="mapbox://styles/mapbox/dark-v11">🌑 Dark</option>
                <option value="mapbox://styles/mapbox/satellite-v9">🛰️ Satellite</option>
                <option value="mapbox://styles/mapbox/satellite-streets-v12">Satellite Streets</option>
                <option value="mapbox://styles/mapbox/navigation-day-v1">☀️ Navigation</option>
                <option value="mapbox://styles/mapbox/navigation-night-v1">🌙 Navigation</option>
            </select>
        </div>
        <div class="card-footer">
            <span class="d-flex !align-items-baseline">
                <h6 class="card-title me-auto">Light Preset</h6>
                <attr title="ONLY for 'Standard' Map Style.">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
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
            <span class="d-flex align-items-baseline">
                <h6 class="card-title dropdown-toggle me-auto" id="mapLabelsToggle" role="button" data-bs-toggle="collapse" data-bs-target="#mapLabels">Map Legend</h6>
                <attr title="ONLY for 'Standard' Map Style.">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.496 6.033h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286a.237.237 0 0 0 .241.247m2.325 6.443c.61 0 1.029-.394 1.029-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94 0 .533.425.927 1.01.927z"/>
                    </svg>
                </attr>
            </span>
        </div>
        <ul class="collapse list-group list-group-flush" id="mapLabels">
            <li class="list-group-item">
                <input class="form-check-input" type="checkbox" id="showPlaceLabels" checked>
                <label class="stretched-link" for="showPlaceLabels">Place Labels</label>
            </li>
            <li class="list-group-item">
                <input class="form-check-input" type="checkbox" id="showPointOfInterestLabels" checked>
                <label class="stretched-link" for="showPointOfInterestLabels">POI Labels</label>
            </li>
            <li class="list-group-item">
                <input class="form-check-input" type="checkbox" id="showRoadLabels" checked>
                <label class="stretched-link" for="showRoadLabels">Road Labels</label>
            </li>
            <li class="list-group-item">
                <input class="form-check-input" type="checkbox" id="showTransitLabels" checked>
                <label class="stretched-link" for="showTransitLabels">Transit Labels</label>
            </li>
        </ul>
    </div>
    </div>
    <pre id="mouseCoord"></pre>

    <script src="JS/map.js"></script>
    <!-- <script src="JS/ruler.js"></script> -->
    <script type="text/javascript">
        const copyIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">'+
        '<path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>'+
        '</svg>';
        const copiedIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check-fill" viewBox="0 0 16 16">'+
        '<path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>'+
        '<path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>'+
        '</svg>';

        <?php $query = "SELECT * FROM cities";
        $result = mysqli_query($db, $query);
        $infoIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">'.
        '<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>'.
        '</svg>';

        while ($data = mysqli_fetch_assoc($result)) {
            ?>
            const marker<?php echo $data['Idx']; ?> = document.createElement('div');
            const options<?php echo $data['Idx']; ?> = document.createElement('div');
            $(marker<?php echo $data['Idx']; ?>).addClass("card text-center");
            $(options<?php echo $data['Idx']; ?>).addClass("card-footer");

            marker<?php echo $data['Idx']; ?>.innerHTML = '<div class="card-header">'+
            '<h5 class="card-title" style="color: #FF671F;"><?php echo $data['City']; ?></h5>'+
            '<span>Long: <?php echo $data['Lng']; ?></span><br><span>Lat: <?php echo $data['Lat']; ?></span>'+
            '</div><?php $city = $data['City'];
            $query1 = "SELECT * FROM servers WHERE City = ('$city')";
            $result1 = mysqli_query($db, $query1);

            if (mysqli_num_rows($result1) == 0) echo '<div class="card-body">' . $infoIcon . ' No Servers Installed at this Location.' . '</div>';
            else {
                echo '<table class="card-body table table-sm table-bordered table-striped table-hover table-group-divider">'.
                '<caption>' . $infoIcon . ' Click a Data Item to Copy its Value.</caption>'.
                '<thead><tr><th>Mgmt IP</th><th>IPv6</th></tr></thead><tbody class="table-group-divider">';
                while ($data1 = mysqli_fetch_assoc($result1)) {
                    echo '<tr><td><button class="btn btn-sm" '.
                    'onclick="navigator.clipboard.writeText(`' . inet_ntop(hex2bin($data1['MgmtIP'])) . '`);">'.
                    inet_ntop(hex2bin($data1['MgmtIP']))  . '</button></td><td><button class="btn btn-sm" '.
                    'onclick="navigator.clipboard.writeText(`' . inet_ntop(hex2bin($data1['IPv6'])) . '`);">'.
                    inet_ntop(hex2bin($data1['IPv6'])) . '</button></td></tr>';
                }
                echo '</tbody></table>';
            }
            // echo 'Long: ' . $data['Lng'] . ', Lat: ' . $data['Lat']; ?>';

            options<?php echo $data['Idx']; ?>.innerHTML = '<div class="btn-group btn-group-sm">'+
            '<button class="btn btn-outline-primary" id="lng<?php echo $data['Idx']; ?>" title="Copy Longitude">'+copyIcon+' Long</button>'+
            '<button class="btn btn-outline-primary" id="lat<?php echo $data['Idx']; ?>" title="Copy Latitude">'+copyIcon+' Lat</button></div>';
            marker<?php echo $data['Idx']; ?>.appendChild(options<?php echo $data['Idx']; ?>);

            $(document).on('click', '#lng<?php echo $data['Idx']; ?>', function() {
                var This = $(this);
                var oldText = This.html();
                This.html(copiedIcon+' Copied!');
                This.attr('disabled', 'disabled');
                navigator.clipboard.writeText('<?php echo $data['Lng']; ?>');
                setTimeout(function() {
                    This.html(oldText);
                    This.removeAttr('disabled');
                }, 2000);
            });
            $(document).on('click', '#lat<?php echo $data['Idx']; ?>', function() {
                var This = $(this);
                var oldText = This.html();
                This.html(copiedIcon+' Copied!');
                This.attr('disabled', 'disabled');
                navigator.clipboard.writeText('<?php echo $data['Lat']; ?>');
                setTimeout(function() {
                    This.html(oldText);
                    This.removeAttr('disabled');
                }, 2000);
            });

            new mapboxgl.Marker({
                color: '<?php echo mysqli_num_rows($result1) == 0 ? '#0098e0' : '#FF671F'; ?>',
                draggable: false
            }).setLngLat([<?php echo $data['Lng']; ?>, <?php echo $data['Lat']; ?>]
            ).setPopup(new mapboxgl.Popup({
                closeButton: true,
                closeOnClick: true,
                closeOnMove: false,
                maxWidth: '320px'
            }).setDOMContent(marker<?php echo $data['Idx']; ?>)
            ).addTo(map);
            <?php
        }
        ?>
    </script>
</body>

</html>