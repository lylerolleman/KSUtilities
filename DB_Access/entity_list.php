<?php
$servername = "127.0.0.1";
$username = "root";
$password = "dev";
$dbName = "KSUtilities";

session_start();
$profile = $_SESSION["profile"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT e_id, name FROM entity WHERE profile = '$profile'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $name = $row["name"];
        $e_id = $row["e_id"];
        echo "<option value='" . $e_id . "'>" . $name . "</option>";
    }
} 

mysqli_close($conn);
?>