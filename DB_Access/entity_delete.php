<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

$e_id = $_POST["e_id"];
$sys_id = $_POST["sys_id"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

$sql = "DELETE FROM entity WHERE e_id = $e_id";
if (!mysqli_query($conn, $sql)) {
    echo "error in delete " . mysqli_error($conn);
} 
$sql = "SELECT p_id FROM entity_position WHERE e_id = $e_id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $p_id = $row["p_id"];
        echo $p_id . ",";
    }
    
} else {
    echo "no selection";
}
mysqli_close($conn);
?>