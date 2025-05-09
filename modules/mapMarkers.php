<script type="text/javascript">
    const copyIcon = '<i class="bi bi-clipboard me-1"></i>';
    const copiedIcon = '<i class="bi bi-clipboard-check me-1"></i>';

    <?php $query = "SELECT * FROM cities";
    $result = mysqli_query($db, $query);

    while ($data = mysqli_fetch_assoc($result)) {
        ?>
        const marker<?php echo $data['Idx']; ?> = document.createElement('div');
        const options<?php echo $data['Idx']; ?> = document.createElement('div');
        $(marker<?php echo $data['Idx']; ?>).addClass("card text-center rubik-font");
        $(options<?php echo $data['Idx']; ?>).addClass("card-footer");

        marker<?php echo $data['Idx']; ?>.innerHTML = '<div class="card-header">' +
            '<h5 class="card-title" style="color: #FF671F;"><?php echo $data['City']; ?></h5>' +
            '<div class="d-flex justify-content-evenly"><span id="lng<?php echo $data['Idx']; ?>">' + copyIcon +
            'Long: <?php echo round($data['Lng'], 5); ?></span>' +
            '<span class="vr mx-2"></span><span id="lat<?php echo $data['Idx']; ?>">' + copyIcon +
            'Lat: <?php echo round($data['Lat'], 5); ?></span></div>' +
            '</div><!--<div id="carouselExample" class="carousel carousel-dark slide">' +
            '<div class="carousel-inner"><div class="carousel-item active">' +
            '<img src="pink_blue_red_oil_paint_abstraction_4k_8k_hd_abstract.jpg" class="d-block w-100" alt="..."></div><div class="carousel-item">' +
            '<img src="pink_blue_red_oil_paint_abstraction_4k_8k_hd_abstract.jpg" class="d-block w-100" alt="..."></div><div class="carousel-item">' +
            '<img src="google-maps.png" class="d-block w-100" alt="..."></div><div class="carousel-item">' +
            '<input class="form-control" type="file" /></div></div>' +
            '<button class="carousel-control-prev btn btn-outline-secondary" data-bs-target="#carouselExample" data-bs-slide="prev">' +
            '<span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>' +
            '</button><button class="carousel-control-next btn btn-outline-secondary" data-bs-target="#carouselExample" data-bs-slide="next">' +
            '<span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>' +
            '</button></div>--><?php $cityIdx = $data['Idx'];
            $query1 = "SELECT * FROM servers WHERE CityIdx = ('$cityIdx')";
            $result1 = mysqli_query($db, $query1);

            if (mysqli_num_rows($result1) == 0)
                echo '<div class="card-body" style="color: brown;"><i class="bi bi-info-circle-fill me-1"></i>No Servers Installed at this Location.' . '</div>';
            else {
                echo '<table class="card-body table table-sm table-bordered table-striped table-hover table-group-divider mb-0">' .
                    '<caption><i class="bi bi-info-circle-fill me-1"></i>Click an IP to Copy its Value.</caption>' .
                    '<thead><tr><th>Server</th><!--<th>Mgmt IP</th>--><th>IPv6</th></tr></thead><tbody class="table-group-divider">';
                while ($data1 = mysqli_fetch_assoc($result1)) {
                    echo '<tr><td>' . $data1['Server'] . '</td><!--<td><button class="btn btn-sm" ' .
                        'onclick="navigator.clipboard.writeText(`' . inet_ntop(hex2bin($data1['MgmtIP'])) . '`);">' .
                        inet_ntop(hex2bin($data1['MgmtIP'])) . '</button></td>--><td><button class="btn btn-sm" ' .
                        'onclick="navigator.clipboard.writeText(`' . inet_ntop(hex2bin($data1['IPv6'])) . '`);">' .
                        inet_ntop(hex2bin($data1['IPv6'])) . '</button></td></tr>';
                }
                echo '</tbody></table>';
            }
            // echo 'Long: ' . $data['Lng'] . ', Lat: ' . $data['Lat']; ?>';

        options<?php echo $data['Idx']; ?>.innerHTML = '<div class="btn-group btn-group-sm">' +
            '<button class="btn btn-outline-primary" id="lng<?php echo $data['Idx']; ?>" title="Copy Longitude">' + copyIcon + ' Long</button>' +
            '<button class="btn btn-outline-primary" id="lat<?php echo $data['Idx']; ?>" title="Copy Latitude">' + copyIcon + ' Lat</button></div>';
        // marker<!?php echo $data['Idx']; ?>.appendChild(options<!?php echo $data['Idx']; ?>);

        $(document).on('click', '#lng<?php echo $data['Idx']; ?>', function () {
            var This = $(this);
            var oldText = This.html();
            This.html(copiedIcon + 'Copied!');
            This.prop('disabled', 'true');
            navigator.clipboard.writeText('<?php echo $data['Lng']; ?>');
            setTimeout(function () {
                This.html(oldText);
                This.removeAttr('disabled');
            }, 2000);
        });
        $(document).on('click', '#lat<?php echo $data['Idx']; ?>', function () {
            var This = $(this);
            var oldText = This.html();
            This.html(copiedIcon + 'Copied!');
            This.prop('disabled', 'true');
            navigator.clipboard.writeText('<?php echo $data['Lat']; ?>');
            setTimeout(function () {
                This.html(oldText);
                This.removeAttr('disabled');
            }, 2000);
        });

        new mapboxgl.Marker({
            color: '<?php echo mysqli_num_rows($result1) == 0 ? '#0098E0' : '#FF671F'; ?>',
            draggable: false
        }).setLngLat([<?php echo $data['Lng']; ?>, <?php echo $data['Lat']; ?>]
        ).setPopup(new mapboxgl.Popup({
            closeButton: true,
            closeOnClick: true,
            closeOnMove: false,
            maxWidth: '300px'
        }).setDOMContent(marker<?php echo $data['Idx']; ?>)
        ).addTo(map);
        <?php
    }
    ?>

    $('#serversPrs').on('change', function () {
        document.querySelectorAll('.mapboxgl-marker').forEach((marker) => {
            if (marker.children[0].children[2].getAttribute('fill') == '#FF671F') {
                this.checked == false ? marker.style.setProperty('visibility', 'hidden') : marker.style.removeProperty('visibility');
            }
        });
    });
    $('#serversAbs').on('change', function () {
        document.querySelectorAll('.mapboxgl-marker').forEach((marker) => {
            if (marker.children[0].children[2].getAttribute('fill') == '#0098E0') {
                this.checked == false ? marker.style.setProperty('visibility', 'hidden') : marker.style.removeProperty('visibility');
            }
        });
    });
</script>