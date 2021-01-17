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
$data = $result->fetch_assoc();
$count = mysqli_num_rows($result);


//verify that username and password combination exists
//also verifies that user is not banned!
if ($count == 1 && $data['banned'] != 1) {
    session_start();
    $row = $result->fetch_assoc();
    saveUserId($row["id"]);
    saveUsername($row["username"]);
    include('loginResponse.php');
} elseif ($count > 1) {
    alert("There are multiple users registered.");
} elseif ($count == 0) {
    $message = 'Login failed. Incorrect username or password.';
    alert($message);
    include 'login.html';
} elseif ($data['banned'] == 1) {
    $message = 'User was banned. Cannot login.';
    alert($message);
} else {
    alert(mysqli_connect_error());
}

mysqli_close($link);

?>