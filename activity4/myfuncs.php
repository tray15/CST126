<?php

function dbConnect() {
    $link = mysqli_connect("localhost:3307", "root", "root", "activity1");
    if (!$link) {
        die("ERROR: Could not connect." . mysqli_connect_error());
    }
    return $link;
}

function saveUserId($id) {
    session_start();
    $_SESSION["USER_ID"] = $id;
}
function getUserId() {
    session_start();
    return $_SESSION["USER_ID"];
}
function saveUsername($Username) {
    session_start();
    $_SESSION["Username"] = $Username;
}
function getUserName() {
    session_start();
    return $_SESSION["Username"];
}
?>