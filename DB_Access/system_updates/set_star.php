<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$sys_id = $_POST["sys_id"];
$star = $_POST["star"];

//$sys_name = $_GET["name"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "UPDATE system SET star = $star WHERE sys_id = $sys_id";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} 
mysqli_close($conn);
?>

