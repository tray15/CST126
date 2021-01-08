<?php
include 'myfuncs.php';

$link = dbConnect();

$Username = $_POST['Username'];
$Password = $_POST['Password'];

if (is_null($Username) || empty($Username)) {
    alert("Username required");
    return;
} elseif (is_null($Password) || empty($Password)) {
    alert("Password required");
    return;
}

$sql = "SELECT * FROM `users` WHERE `USERNAME`='$Username' AND `PASSWORD`='$Password'";
$result = mysqli_query($link, $sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    $row = $result->fetch_assoc();
    saveUserId($row["ID"]);
    saveUsername($row["Username"]);
    include('loginResponse.php');
} elseif ($count > 1) {
    alert("There are multiple users registered.");
} elseif ($count == 0) {
    include ('loginFailed.php');
} else {
    alert(mysqli_connect_error());
}

mysqli_close($link);

?>