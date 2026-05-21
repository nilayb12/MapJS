<?php include_once('modules/dbConfig.php'); ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circle Server Map</title>
    <link rel="icon" type="image/png" href="india.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha256-2FMn2Zx6PuH5tdBQDRNwrOo60ts5wWPC9R8jK67b3t4=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        integrity="sha256-pdY4ejLKO67E0CM2tbPtq1DJ3VGDVVdqAR6j3ZwdiE4=" crossorigin="anonymous">
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.24.0/mapbox-gl.css" rel="stylesheet">
    <!-- <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.1.0/mapbox-gl-geocoder.css"
        type="text/css"> -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-geocoder@5.1.2/dist/mapbox-gl-geocoder.css"
        integrity="sha256-+ysQczq0ZTGoBw6DsFGi/RAIoQTsO4KdjPXNRqreJl8=" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha256-5P1JGBOIxI7FBAvT/mb1fCnI5n/NhQKzNUuW7Hq0fMc=" crossorigin="anonymous"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.24.0/mapbox-gl.js"></script>
    <!-- <script
        src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.1.0/mapbox-gl-geocoder.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@mapbox/mapbox-gl-geocoder@5.1.2/dist/mapbox-gl-geocoder.min.js"
        integrity="sha256-DZAVZrQqWa3b3ma/Csuj5Czkm25vJyBFymuuzlJyzLM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7.3.5/turf.min.js"
        integrity="sha256-UF06NdqKi/H4rtWQza0P+hHnDVHyjQwjWQB7r+0P04I=" crossorigin="anonymous"></script>
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