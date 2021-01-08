<?php

function dbConnect() {
    $link = mysqli_connect("localhost:3307", "root", "root", "cst126milestoneproject");
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
function alert($message) {
    echo "<script>alert('$message');</script>";
}
function profanityFilter($text) {
    $filter_terms = array('/\bass(es|holes?)?\b/i', '/\bshit(ting|e|ty|head|ter?)?\b/i', '/\bfuck(er|ed|ing|head?)?\b/i');
    $filtered_text = preg_replace($filter_terms, '****', $text);
    return $filtered_text;
}
function populateBlog() {
    $link = dbConnect();
    
    $blogQuery = "SELECT * FROM `blog`";
    $result = mysqli_query($link, $blogQuery);
    echo "<div>";
    echo "<form>";
    echo "<h3>Recent Posts</h3>";
    echo "<table>";
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row["blogtitle"];
        $message = $row["blogmessage"];
        echo "<th>Title: " . $title . "</th><tr><td>" . $message . "</td></tr>";
    }
    echo "</table>";
    echo "</form>";
    echo "</div>";
}
function postBlog() {
    $link = dbConnect();
    
    $BlogTitle = $_POST['BlogTitle'];
    $BlogMessage = $_POST['BlogMessage'];
    
    if (is_null($BlogTitle) || empty($BlogTitle)) {
        echo "Please enter a title.";
        return;
    } elseif (is_null($BlogMessage) || empty($BlogMessage)) {
        echo "Please write a message.";
        return;
    }
    
    $filteredTitle = profanityFilter($BlogTitle);
    $filteredMessage = profanityFilter($BlogMessage);
    
    $sqlPost = "INSERT INTO `blog` (blogtitle, blogmessage) VALUES('$filteredTitle', '$filteredMessage')";
    
    if (mysqli_query($link, $sqlPost)) {
        alert("Successfully uploaded message.");
    } else {
        echo "ERROR: Not able to execute $sqlPost." . mysqli_error($link);
    }
}

?>