<?php include ('modules/dbConfig.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Server Map</title>
    <link rel="icon" type="image/png" href="india.png">
    <link rel="stylesheet" href="style.css">
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css' rel='stylesheet' />
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.2/mapbox-gl-geocoder.min.js"></script>
    <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.2/mapbox-gl-geocoder.css"
        type="text/css">
    <div id='map'></div>
    <pre id="mouseCoord"></pre>

    <script src="JS/map.js"></script>
    <?php $query = "SELECT * FROM cities";
    $result = mysqli_query($db, $query);

    while ($data = mysqli_fetch_assoc($result)) {
        ?>
        <script type="text/javascript">
            const marker<?php echo $data['Idx']; ?> = new mapboxgl.Marker({
                color: 'blue',
                draggable: false
            }).setLngLat([<?php echo $data['Lng']; ?>, <?php echo $data['Lat']; ?>]
            ).setPopup(new mapboxgl.Popup({
                closeButton: true,
                closeOnClick: true,
                closeOnMove: false,
                maxWidth: '300px'
            }).setHTML('<h3 style="color: #FF671F;"><?php echo $data['City']; ?></h3><pre style="color: #046A38;"><?php echo $data['Lng'] . ', ' . $data['Lat']; ?></pre>')
            ).addTo(map);
        </script>
        <?php
    }
    ?>
</body>

</html>