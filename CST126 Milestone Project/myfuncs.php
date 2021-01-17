<?php
/*
 * Global functions
 */
function dbConnect() {
    $link = mysqli_connect("localhost:3307", "root", "root", "cst126milestoneproject");
    //$link = mysqli_connect("cst126sql.mysql.database.azure.com", "tannergcu", "p4p3rcu7!", "cst126milestoneproject");
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
    //uses regex to filter based off of list of words added to it, returns filtered text.
    $filter_terms = array('/\bass(es|holes?)?\b/i', '/\bshit(ting|e|ty|head|ter?)?\b/i', '/\bfuck(er|ed|ing|head?)?\b/i');
    $filtered_text = preg_replace($filter_terms, '****', $text);
    return $filtered_text;
}
function adminControl() {
    $link = dbConnect();
    
    $query = "SELECT * FROM `users`";
    $result = $link->query($query);
    $data = $result->fetch_assoc();
    
    if ($result) {
        if ($data["role"] == "admin") {
            echo '<a href="admin.php">Admin</a>';
        }
    }
}
function populateBlog() {
    $link = dbConnect();
    
    $userQuery = "SELECT * FROM `users`";
    $userResult = $link->query($userQuery);
    $userData = $userResult->fetch_assoc();
    
    //read posts from database
    $blogQuery = "SELECT * FROM `blog`";
    $blogResult = $link->query($blogQuery);
    $blogData = $blogResult->fetch_assoc();
    
    echo "<div>";
    echo "<form>";
    echo "<h3>Recent Posts</h3>";
    echo "<table>";
    //populate blog posts
    while ($row = $blogResult->fetch_assoc()) {
        $author = $row["author"];
        $title = $row["blogtitle"];
        $message = $row["blogmessage"];
        echo "<th>Title: " . $title . "</th><tr><td>Author: " . $author . "</td></tr><tr><td>" . $message . "</td></tr>";
        echo "<tr><td>";
        if ($userResult) {
            //check if user has admin priviledges
            if ($userData["role"] == "admin") {
                echo '<a href="blog.php?deleteID=' . $row['post_id'] . '">Delete</a>';
                echo ' ';
            }
            //compares username in users to author in blog to see if
            //the user is the same as author, if so, renders edit link
            if (strcmp($userData['username'], $blogData['author']) == 0) {
                echo '<a href="blog.php?editID=' . $row['post_id'] . '">Edit</a>';
                echo ' ';
            }
            echo '<a href="blog.php?flagID=' . $row['post_id'] . '">Flag</a>';
            echo ' ';
            echo '<hr>';
        }
        echo "</td></tr>";
    }
    echo "</table>";
    echo "</form>";
    echo "</div>";
    mysqli_close($link);
}
function populateAdmin() {
    $link = dbConnect();
    
    $roleQuery = "SELECT * FROM `users`";
    $roleResult = mysqli_query($link, $roleQuery);
    $roleData = $roleResult->fetch_assoc();
    
    //read the FLAGGED posts in database and display them. if no flagged posts,
    //it will be empty
    $blogQuery = "SELECT * FROM `blog` WHERE `flagged`='1'";
    $result = mysqli_query($link, $blogQuery);
    
    echo "<div>";
    echo "<form>";
    echo "<h3>Flagged Posts</h3>";
    echo "<table>";
    //populate flagged blog posts
    while ($row = mysqli_fetch_assoc(
        $result)) {
        $author = $row["author"];
        $title = $row["blogtitle"];
        $message = $row["blogmessage"];
        echo "<th>Title: " . $title . "</th><tr><td>Author: " . $author . "</td></tr><tr><td>" . $message . "</td></tr>";
        echo "<tr><td>";
        if ($roleResult) {
            //check if user has admin priviledges
            if ($roleData["role"] == "admin") {
                echo '<a href="admin.php?deleteID=' . $row['post_id'] . '">Delete</a>';
                echo '<a href="admin.php?flagID=' . $row['post_id'] . '">Unflag</a>';
            }
        }
        echo "</td></tr>";
    }
    //if there are no flagged posts, notify admin of no posts
    if (mysqli_fetch_assoc($result) == 0) {
        echo 'There are no flagged posts.';
    }
    echo "</table>";
    echo "</form>";
    echo "</div>";
    mysqli_close($link);
}
function postBlog() {
    $link = dbConnect();
    
    //pull username from session
    $author = getUserName();
    
    $link = dbConnect();
    
    $editID = $_GET['editID'];
    
    //data from user fields
    $BlogTitle = $_POST['BlogTitle'];
    $BlogMessage = $_POST['BlogMessage'];
    
    // Verify that blog title or message is not empty
    if (is_null($BlogTitle) || empty($BlogTitle)) {
        echo "Please enter a title.";
        return;
    } elseif (is_null($BlogMessage) || empty($BlogMessage)) {
        echo "Please write a message.";
        return;
    }
    
    //Filter the message and title of profanity
    $filteredTitle = profanityFilter($BlogTitle);
    $filteredMessage = profanityFilter($BlogMessage);
    
    //queries
    $sqlPost = "INSERT INTO `blog` (author, blogtitle, blogmessage) VALUES('$author', '$filteredTitle', '$filteredMessage')";
    $updateQuery = "UPDATE `blog` SET `blogtitle'='$filteredTitle', `blogmessage`='$filteredMessage' WHERE `post_id`='$editID'";
    $editQuery = "SELECT * FROM `blog` WHERE `post_id`='$editID'";
    
    $editResult = $link->query($editQuery);
    
    if ($editResult->num_rows > 0) {
        alert("not implemented");
    } elseif ($editResult->num_rows == 0) {
        $link->query($sqlPost);
        alert("Successfully uploaded message.");
    } else {
        alert("ERROR: Not able to execute $sqlPost." . mysqli_error($link));
    }
    mysqli_close($link);
}
function deletePost() {
    $deleteID = $_GET['deleteID'];
    $link = dbConnect();
    $delQuery = "DELETE FROM `blog` WHERE `post_id`='$deleteID'";
    if ($link->query($delQuery)) {
        $message = "Record successfully deleted.";
        alert($message);
    }
}
function flagPost() {
    $flagID = $_GET['flagID'];
    $link = dbConnect();
    $flagQuery = "UPDATE `blog` SET `flagged` = '1' WHERE `blog`.`post_id` = '$flagID'";
    if ($link->query($flagQuery)) {
        $message = "Post has been flagged for admins.";
        alert($message);
    }
    mysqli_close($link);
}
function unflagPost() {
    $flagID = $_GET['flagID'];
    $link = dbConnect();
    $unflagQuery = "UPDATE `blog` SET `flagged` = '0' WHERE `blog`.`post_id` = '$flagID'";
    if ($link->query($unflagQuery)) {
        $message = "Post has been approved.";
        alert($message);
    }
    mysqli_close($link);
}
?>