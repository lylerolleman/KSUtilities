<?php
$servername = "localhost";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$p_id = $_POST["p_id"];
$x = $_POST["x"];
$y = $_POST["y"];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "UPDATE entity_position SET x = $x, y = $y WHERE p_id = $p_id";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} 
mysqli_close($conn);
?>

