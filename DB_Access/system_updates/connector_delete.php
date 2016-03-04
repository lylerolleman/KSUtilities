<?php
$servername = "localhost";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$c_id = $_POST["c_id"];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "DELETE FROM connection WHERE c_id = $c_id";
if (!mysqli_query($conn, $sql)) {
    echo "error in delete " . mysqli_error($conn);
} 
mysqli_close($conn);
?>