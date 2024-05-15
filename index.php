<?php include ('modules/dbConfig.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Server Map</title>
    <link rel="icon" type="image/png" href="india.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css' rel='stylesheet' />
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
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js'></script>
    <script
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.2/mapbox-gl-geocoder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>
    <div id='map'></div>
    <pre id="mouseCoord"></pre>

    <script src="JS/map.js"></script>
    <script type="text/javascript">
        const copyIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">'+
                '<path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>'+
                '</svg>';
        <?php $query = "SELECT * FROM cities";
        $result = mysqli_query($db, $query);

        while ($data = mysqli_fetch_assoc($result)) {
            ?>
            const marker<?php echo $data['Idx']; ?> = '<h4 style="color: #FF671F;"><?php echo $data['City']; ?></h4>'+
                '<pre style="color: #046A38;"><?php echo 'Long: ' . $data['Lng'] . ', Lat: ' . $data['Lat']; ?></pre>'+
                '<button class="btn btn-outline-primary btn-sm">'+copyIcon+' Long/Lat</button>';
            new mapboxgl.Marker({
                color: '#FF671F',
                draggable: false
            }).setLngLat([<?php echo $data['Lng']; ?>, <?php echo $data['Lat']; ?>]
            ).setPopup(new mapboxgl.Popup({
                closeButton: true,
                closeOnClick: true,
                closeOnMove: false,
                maxWidth: '400px'
            }).setHTML(marker<?php echo $data['Idx']; ?>)
            ).addTo(map);
            <?php
        }
        ?>
    </script>
</body>

</html>