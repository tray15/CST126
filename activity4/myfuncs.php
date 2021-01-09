<?php

function dbConnect() {
    $link = mysqli_connect("activity4sql.mysql.database.azure.com", "tannergcu", "p4p3rcu7!", "activity1");
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