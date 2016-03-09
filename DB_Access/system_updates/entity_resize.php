<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$p_id = $_POST["p_id"];
$size = $_POST["size"];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "UPDATE entity_position SET size = $size WHERE p_id = $p_id";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} 
mysqli_close($conn);
?>

