<?php
$servername = "localhost";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$p_id = $_POST["p_id"];
$sys_id = $_POST["sys_id"];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "DELETE FROM entity_position WHERE p_id = $p_id AND sys_id = $sys_id";
if (!mysqli_query($conn, $sql)) {
    echo "error in delete " . mysqli_error($conn);
} 
$sql = "DELETE FROM connection WHERE sys_id = $sys_id AND (p1 = $p_id OR p2 = $p_id)";
if (!mysqli_query($conn, $sql)) {
    echo "error in delete " . mysqli_error($conn);
} 
mysqli_close($conn);
?>

