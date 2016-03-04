<?php
$servername = "localhost";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

session_start();
$profile = $_SESSION["profile"];
$e_name = $_POST["name"];
$img_url = $_POST["img_url"];
$atmo = $_POST["atmo"];
$canland = $_POST["canland"];
//$e_name = $_GET["name"];
//$img_url = $_GET["img_url"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "SELECT MAX(e_id) AS e_id FROM entity";
$result = mysqli_query($conn, $sql);
$e_id = 1;
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $e_id = $row["e_id"] + 1;
} else {
    echo "no selection";
}
$sql = "INSERT INTO entity (e_id, name, img_url, profile, atmo, canland) VALUES($e_id, '$e_name', '$img_url', '$profile', '$atmo', '$canland')";
if (!mysqli_query($conn, $sql)) {
    echo "error in insert " . mysqli_error($conn);
} else {
    echo "successful insertion";
}
mysqli_close($conn);
?>

