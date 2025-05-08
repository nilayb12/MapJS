<?php include_once('modules/dbConfig.php'); ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Server Map</title>
    <link rel="icon" type="image/png" href="india.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
        integrity="sha256-oxqX0LQclbvrsJt8IymkxnISn4Np2Wy2rY9jjoQlDEg=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css"
        integrity="sha256-Zg/iJvZCQS2D/o8gg44o0y7grnNfckmhKPK6Wk5L1Fg=" crossorigin="anonymous">
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.11.1/mapbox-gl.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.3/mapbox-gl-geocoder.css"
        type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha256-y3ibfOyBqlgBd+GzwFYQEVOZdNJD06HeDXihongBXKs=" crossorigin="anonymous"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.11.1/mapbox-gl.js"></script>
    <script
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.3/mapbox-gl-geocoder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7.2.0/turf.min.js"
        integrity="sha256-1XhsCK5OmIs3qlwm3jKwUewuRoD2AvKpjKO8SzKONDI=" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@mapbox-controls/ruler@1.2.0/src/index.min.js"></script> -->
    <!-- <script src="JS/colorToggle.js"></script> -->
    <div id="map">
        <!-- <div id="distance" class="distance-container"></div> -->
        <?php include('modules/mapOptions.php'); ?>
    </div>

    <script src="JS/map.js"></script>
    <script src="JS/script.js"></script>
    <!-- <script src="JS/ruler.js"></script> -->
    <?php include('modules/mapMarkers.php') ?>
</body>

</html>