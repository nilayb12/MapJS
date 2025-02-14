<?php include_once('dbConfig.php');

if (isset($_POST['term'])) {
    $term = strtoupper($_POST['term']);
    // $query = "SELECT * FROM global_search('$term')";
    $query = "SELECT City, Lng, Lat FROM cities WHERE Idx IN (SELECT CityIdx FROM servers WHERE IPv6 LIKE '$term%')";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $res = mysqli_num_rows($result) > 1 ? 'results' : 'result';
        echo '<li class="dropdown-item disabled">🔍 ' . mysqli_num_rows($result) . ' ' . $res . '</li>
            <li><hr class="dropdown-divider"></li>';
        // echo '<li><a href="javascript:void(0);" class="dropdown-item" onclick="mapFlyTo(' . $data[1] . ', ' . $data[2] . ')">' . $data[0] . '</a></li>';
        while ($data = mysqli_fetch_array($result)) { ?>
            <li><a href="javascript:void(0);" class="dropdown-item"
                    onclick="mapFlyTo(<?php echo $data[1] . ', ' . $data[2]; ?>)"><?php echo $data[0]; ?></a></li>
        <?php }
    } else {
        echo '<li class="dropdown-item disabled">❌ No Data</li>';
    }
}
mysqli_close($db);
?>