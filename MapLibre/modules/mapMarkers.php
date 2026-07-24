<script type="text/javascript">
    const copyIcon = '<i class="bi bi-clipboard me-1"></i>', copiedIcon = '<i class="bi bi-clipboard-check me-1"></i>';

    <?php
    // $ping controls whether the per-server ping-status column is rendered.
    // Ping only works on the server intranet, so index.php can set `$ping = false;`
    // before including this file for public/off-intranet deployments. Defaults on.
    if (!isset($ping)) { $ping = true; }

    $query = "SELECT * FROM cities";
    $result = mysqli_query($db, $query);
    $countPrs = 0; $countAbs = 0; // legend live counts

    while ($data = mysqli_fetch_assoc($result)) {
        ?>
        const marker<?php echo $data['Idx']; ?> = document.createElement('div');
        const options<?php echo $data['Idx']; ?> = document.createElement('div');
        $(marker<?php echo $data['Idx']; ?>).addClass("card text-center rubik-font");
        $(options<?php echo $data['Idx']; ?>).addClass("card-footer");

        marker<?php echo $data['Idx']; ?>.innerHTML = '<div class="card-header">' +
            '<h5 class="card-title" style="color: #FF671F;"><?php echo $data['City']; ?></h5>' +
            '<div class="d-flex justify-content-evenly"><span id="lng<?php echo $data['Idx']; ?>" role="button">' + copyIcon +
            'Long: <?php echo round($data['Lng'], 5); ?></span>' +
            '<span class="vr mx-2"></span><span id="lat<?php echo $data['Idx']; ?>" role="button">' + copyIcon +
            'Lat: <?php echo round($data['Lat'], 5); ?></span></div>' +
            '</div><?php $cityIdx = $data['Idx'];
            $query1 = "SELECT * FROM servers WHERE CityIdx = ('$cityIdx')";
            $result1 = mysqli_query($db, $query1);

            if (mysqli_num_rows($result1) == 0) {
                $countAbs++;
                echo '<div class="card-body" style="color: brown;"><i class="bi bi-info-circle-fill me-1"></i>No Servers Installed at this Location.' . '</div>';
            }
            else {
                $countPrs++;
                echo '<table class="card-body table table-sm table-bordered table-striped table-hover table-group-divider mb-0">' .
                    '<caption><i class="bi bi-info-circle-fill me-1"></i>Click an IP to Copy its Value.</caption>' .
                    '<thead><tr><th>Server</th><!--<th>Mgmt IP</th>--><th>IPv6</th>' . ($ping ? '<th>Status</th>' : '') . '</tr></thead><tbody class="table-group-divider">';
                while ($data1 = mysqli_fetch_assoc($result1)) {
                    echo '<tr><td>' . $data1['Server'] . '</td><!--<td><button class="btn btn-sm" ' .
                        'onclick="navigator.clipboard.writeText(`' . inet_ntop(hex2bin($data1['MgmtIP'])) . '`);">' .
                        inet_ntop(hex2bin($data1['MgmtIP'])) . '</button></td>--><td><button class="btn btn-sm" ' .
                        'onclick="navigator.clipboard.writeText(`' . inet_ntop(hex2bin($data1['IPv6'])) . '`);">' .
                        inet_ntop(hex2bin($data1['IPv6'])) . '</button></td>' .
                        ($ping ? '<td><span class="ping-status" data-host="' . inet_ntop(hex2bin($data1['IPv6'])) . '">Checking...</span></td>' : '') .
                        '</tr>';
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
            This.html(copiedIcon + 'Copied!').prop('disabled', 'true').css('pointer-events', 'none');
            navigator.clipboard.writeText('<?php echo $data['Lng']; ?>');
            setTimeout(function () {
                This.html(oldText).removeAttr('disabled').css('pointer-events', '');
            }, 2000);
        });
        $(document).on('click', '#lat<?php echo $data['Idx']; ?>', function () {
            var This = $(this);
            var oldText = This.html();
            This.html(copiedIcon + 'Copied!').prop('disabled', 'true').css('pointer-events', 'none');
            navigator.clipboard.writeText('<?php echo $data['Lat']; ?>');
            setTimeout(function () {
                This.html(oldText).removeAttr('disabled').css('pointer-events', '');
            }, 2000);
        });

        const mkr<?php echo $data['Idx']; ?> = new maplibregl.Marker({
            color: '<?php echo mysqli_num_rows($result1) == 0 ? '#0098E0' : '#FF671F'; ?>',
            draggable: false
        }).setLngLat([<?php echo $data['Lng']; ?>, <?php echo $data['Lat']; ?>]
        ).setPopup(new maplibregl.Popup({
            closeButton: true,
            closeOnClick: true,
            closeOnMove: false,
            maxWidth: '400px'
        }).setDOMContent(marker<?php echo $data['Idx']; ?>)
        ).addTo(map);
        // Tag the marker element so legend toggles don't depend on fragile SVG child indices.
        mkr<?php echo $data['Idx']; ?>.getElement().dataset.srv = '<?php echo mysqli_num_rows($result1) == 0 ? 'abs' : 'prs'; ?>';
        // Register this pin's exact coordinates so the ruler tool can snap to it.
        (window.serverPins = window.serverPins || []).push({
            lng: <?php echo $data['Lng']; ?>,
            lat: <?php echo $data['Lat']; ?>
        });
        <?php
    }
    ?>

    // ── Legend live counts (computed server-side during the marker loop) ──────
    const serverCounts = { prs: <?php echo $countPrs; ?>, abs: <?php echo $countAbs; ?> };
    (function populateLegendCounts() {
        // The badge spans (#cntPrs / #cntAbs) exist in the markup as their own flex
        // slot between label and switch, so we only need to fill in their values.
        const prs = document.getElementById('cntPrs');
        const abs = document.getElementById('cntAbs');
        if (prs) prs.textContent = serverCounts.prs;
        if (abs) abs.textContent = serverCounts.abs;
    })();

    $('#serversPrs').on('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.maplibregl-marker[data-srv="prs"]').forEach((marker) => {
            checked ? marker.style.removeProperty('visibility') : marker.style.setProperty('visibility', 'hidden');
        });
    });
    $('#serversAbs').on('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.maplibregl-marker[data-srv="abs"]').forEach((marker) => {
            checked ? marker.style.removeProperty('visibility') : marker.style.setProperty('visibility', 'hidden');
        });
    });
</script>