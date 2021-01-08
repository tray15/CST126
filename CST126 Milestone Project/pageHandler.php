<?php
$link = mysqli_connect("localhost:3307", "root", "root", "cst126milestoneproject");
if (!$link) {
    die("ERROR: Could not connect." . mysqli_connect_error());
}

echo "Connected!<br>";
$FirstName = $_POST["FirstName"];
$LastName = $_POST["LastName"];
$Email = $_POST["Email"];

$sql = "INSERT INTO users (FIRST_NAME, LAST_NAME, EMAIL) VALUES 
('$FirstName', '$LastName', '$Email')";

if (mysqli_query($link, $sql)) {
    echo "Records inserted successfully.";
} else {
    echo "ERROR: Not able to execute $sql." . mysqli_error($link);
}

mysqli_close($link);
?>
<br>You are now registered!<br>