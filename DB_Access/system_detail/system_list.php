<?php
$servername = "localhost";
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
$sql = "SELECT sys_id, name FROM system WHERE profile = '$profile'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $name = $row["name"];
        $sys_id = $row["sys_id"];
        echo "<option value='" . $sys_id . "'>" . $name . "</option>";
    }
} 

mysqli_close($conn);
?>