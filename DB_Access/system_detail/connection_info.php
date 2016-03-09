<?php
$servername = "127.0.0.1";
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
$sql = "SELECT p_id FROM entity_position WHERE sys_id = $sys_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $p_id = $row["p_id"];
        echo "$p_id,";
    }
} 
echo "|";
$sql = "SELECT c_id, p1, p2, type FROM connection WHERE sys_id = $sys_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $c_id = $row["c_id"];
        $p1 = $row["p1"];
        $p2 = $row["p2"];
        $type = $row["type"];
        echo "$c_id,$p1,$p2,$type;";
    }
} 

mysqli_close($conn);
?>