<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$e_id = $_POST["e_id"];
$x = $_POST["x"];
$y = $_POST["y"];
$sys_id = $_POST["sys_id"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT MAX(p_id) AS p_id FROM entity_position";
$result = mysqli_query($conn, $sql);
$p_id = 1;
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $p_id = $row["p_id"] + 1;
} else {
    echo "no selection";
}
$sql = "INSERT INTO entity_position (e_id, p_id, x, y, sys_id) VALUES($e_id, $p_id, $x, $y, $sys_id)";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} else {
    echo $p_id;
}
mysqli_close($conn);
?>