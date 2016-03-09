<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

session_start();
$sys_name = $_POST["name"];
$profile = $_SESSION["profile"];
//$sys_name = $_GET["name"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT MAX(sys_id) AS sys_id FROM system";
$result = mysqli_query($conn, $sql);
$sys_id = 1;
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $sys_id = $row["sys_id"] + 1;
} 
$sql = "INSERT INTO system (sys_id, name, profile) VALUES($sys_id, '$sys_name', '$profile')";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} else {
    echo $sys_id;
}
mysqli_close($conn);
?>

