<?php
require_once 'myfuncs.php';

function getAllUsers() {
    $link = dbConnect();
    $sql = "SELECT ID, FIRST_NAME, LAST_NAME FROM users";
    $result = $link->query($sql);
    $users = array();
    
    $index = 0;
    while($row = $result->fetch_assoc()) {
        $users[$index] = array($row["ID"], $row["FIRST_NAME"], $row["LAST_NAME"]);
        ++$index;
    }
    
    return $users;
    $link->close();
}