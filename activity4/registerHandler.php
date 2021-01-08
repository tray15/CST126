<?php
include 'myfuncs.php';

$link = dbConnect();

echo "Connected!<br>";
$FirstName = $_POST["FirstName"];
$LastName = $_POST["LastName"];
$Username = $_POST["Username"];
$Password = $_POST["Password"];

if (is_null($FirstName) || empty($FirstName)) {
    echo "First name required";
    return;
} elseif (is_null($LastName) || empty($LastName)) {
    echo "Last name required";
    return;
} elseif (is_null($Username) || empty($Username)) {
    echo "Username required";
    return;
} elseif (is_null($Password) || empty($Password)) {
    echo "Password required";
    return;
}

$sql = "INSERT INTO users (FIRST_NAME, LAST_NAME, USERNAME, PASSWORD) VALUES ('$FirstName', '$LastName', '$Username', '$Password')";

mysqli_close($link);
?>
<br>You are now registered!<br>
