<script type="text/javascript">
    const copyIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">' +
        '<path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>' +
        '</svg>';
    const copiedIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check-fill" viewBox="0 0 16 16">' +
        '<path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>' +
        '<path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>' +
        '</svg>';

    <?php $query = "SELECT * FROM cities";
    $result = mysqli_query($db, $query);
    $infoIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">' .
        '<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>' .
        '</svg>';

    while ($data = mysqli_fetch_assoc($result)) {
        ?>
        const marker<?php echo $data['Idx']; ?> = document.createElement('div');
        const options<?php echo $data['Idx']; ?> = document.createElement('div');
        $(marker<?php echo $data['Idx']; ?>).addClass("card text-center");
        $(options<?php echo $data['Idx']; ?>).addClass("card-footer");

        marker<?php echo $data['Idx']; ?>.innerHTML = '<div class="card-header">' +
            '<h5 class="card-title" style="color: #FF671F;"><?php echo $data['City']; ?></h5>' +
            '<span>Long: <?php echo $data['Lng']; ?></span><br><span>Lat: <?php echo $data['Lat']; ?></span>' +
            '</div><!--<div id="carouselExample" class="carousel carousel-dark slide">' +
            '<div class="carousel-inner"><div class="carousel-item active">' +
            '<img src="pink_blue_red_oil_paint_abstraction_4k_8k_hd_abstract.jpg" class="d-block w-100" alt="..."></div><div class="carousel-item">' +
            '<img src="pink_blue_red_oil_paint_abstraction_4k_8k_hd_abstract.jpg" class="d-block w-100" alt="..."></div><div class="carousel-item">' +
            '<img src="google-maps.png" class="d-block w-100" alt="..."></div></div>' +
            '<button class="carousel-control-prev btn btn-outline-secondary" data-bs-target="#carouselExample" data-bs-slide="prev">' +
            '<span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>' +
            '</button><button class="carousel-control-next btn btn-outline-secondary" data-bs-target="#carouselExample" data-bs-slide="next">' +
            '<span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>' +
            '</button></div>--><?php $city = $data['City'];
            $query1 = "SELECT * FROM servers WHERE City = ('$city')";
            $result1 = mysqli_query($db, $query1);

            if (mysqli_num_rows($result1) == 0)
                echo '<div class="card-body">' . $infoIcon . ' No Servers Installed at this Location.' . '</div>';
            else {
                echo '<table class="card-body table table-sm table-bordered table-striped table-hover table-group-divider">' .
                    '<caption>' . $infoIcon . ' Click a Data Item to Copy its Value.</caption>' .
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
            This.html(copiedIcon + ' Copied!');
            This.attr('disabled', 'disabled');
            navigator.clipboard.writeText('<?php echo $data['Lng']; ?>');
            setTimeout(function () {
                This.html(oldText);
                This.removeAttr('disabled');
            }, 2000);
        });
        $(document).on('click', '#lat<?php echo $data['Idx']; ?>', function () {
            var This = $(this);
            var oldText = This.html();
            This.html(copiedIcon + ' Copied!');
            This.attr('disabled', 'disabled');
            navigator.clipboard.writeText('<?php echo $data['Lat']; ?>');
            setTimeout(function () {
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
            maxWidth: '300px'
        }).setDOMContent(marker<?php echo $data['Idx']; ?>)
        ).addTo(map);
        <?php
    }
    ?>

    document.getElementById('serversPrs').addEventListener('change', function () {
        document.querySelectorAll('.mapboxgl-marker').forEach((marker) => {
            if (marker.children[0].children[2].getAttribute('fill') == '#FF671F') {
                this.checked == false ? marker.style.setProperty('visibility', 'hidden') : marker.style.removeProperty('visibility');
            }
        });
    });
    document.getElementById('serversAbs').addEventListener('change', function () {
        document.querySelectorAll('.mapboxgl-marker').forEach((marker) => {
            if (marker.children[0].children[2].getAttribute('fill') == '#0098e0') {
                this.checked == false ? marker.style.setProperty('visibility', 'hidden') : marker.style.removeProperty('visibility');
            }
        });
    });
</script>