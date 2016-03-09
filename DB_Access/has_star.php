<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";
//$sys_id = $_POST["sys_id"];
$sys_id = $_GET["sys_id"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT star FROM system WHERE sys_id = $sys_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($row["star"] == NULL) {
        echo "false";
    }
    echo "true";
} else {
    echo "false";
}

mysqli_close($conn);
?>