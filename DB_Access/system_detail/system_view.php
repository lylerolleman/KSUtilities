<?php
$servername = "localhost";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";
$sys_id = $_GET["sys_id"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT e.e_id AS e_id, e.name AS name, e.img_url AS img_url, p.p_id AS p_id, p.x AS x, p.y AS y, p.size AS size FROM entity e, entity_position p WHERE e.e_id = p.e_id AND p.sys_id = $sys_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row["name"];
        $img_url = $row["img_url"];
        $e_id = $row["e_id"];
        $p_id = $row["p_id"];
        $x = $row["x"];
        $y = $row["y"];
        $size = $row["size"];
        $style = "'left:$x" . "px;" . "top:$y" . "px;'";
        echo "<div id='$p_id' class='system_item' style=$style><img src='$img_url' height='$size' width='$size'/><p class='entity_label'>$name</p></div>";
    }
} else {
    echo "error getting system";
}

mysqli_close($conn);
?>