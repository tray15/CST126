<?php

$link = mysqli_connect("activity4sql.mysql.database.azure.com", "tannergcu", "p4p3rcu7!", "activity1");
if (!$link) {
    die("ERROR: Could not connect." . mysqli_connect_error());
}

$sql = "SELECT * FROM users";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "Data:<br>";
    while ($row = mysqli_fetch_assoc($result)) {
        $field1name = $row["FIRST_NAME"];
        $field2name = $row["LAST_NAME"];
        echo "first: $field1name, last: $field2name <br>";
    }
    echo "end of list.";
    $result->free();
}

mysqli_close($link);

?>