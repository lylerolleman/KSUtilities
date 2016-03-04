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
$p1sql = "SELECT e.name AS name, e.atmo AS atmo, e.canland AS canland, c.type AS type FROM entity e, entity_position p, connection c WHERE e.e_id = p.e_id AND p.p_id = c.p1 AND p.sys_id = $sys_id";
$p1result = mysqli_query($conn, $p1sql);
$p2sql = "SELECT e.name AS name, e.atmo AS atmo, e.canland AS canland FROM entity e, entity_position p, connection c WHERE e.e_id = p.e_id AND p.p_id = c.p2 AND p.sys_id = $sys_id";
$p2result = mysqli_query($conn, $p2sql);
if (mysqli_num_rows($p1result) == 0 || mysqli_num_rows($p2result) == 0) {
    echo "failed to fetch rows";
}
    // output data of each row
    while($p1row = mysqli_fetch_assoc($p1result)) {
        $p2row = mysqli_fetch_assoc($p2result);
        $name1 = $p1row["name"];
        $atmo1 = $p1row["atmo"];
        $canland1 = $p1row["canland"];
        $name2 = $p2row["name"];
        $atmo2 = $p2row["atmo"];
        $canland2 = $p2row["canland"];
        $type = $p1row["type"];
        echo "$name1,$atmo1,$canland1,$name2,$atmo2,$canland2,$type;";
    }

mysqli_close($conn);
?>