<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$p1 = $_POST["p1"];
$p2 = $_POST["p2"];
$type = $_POST["type"];
$sys_id = $_POST["sys_id"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT MAX(c_id) AS c_id FROM connection";
$result = mysqli_query($conn, $sql);
$c_id = 1;
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $c_id = $row["c_id"] + 1;
} else {
    echo "no selection";
}
$sql = "INSERT INTO connection (c_id, p1, p2, type, sys_id) VALUES($c_id, $p1, $p2, '$type', $sys_id)";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} else {
    echo $c_id;
}
mysqli_close($conn);
?>