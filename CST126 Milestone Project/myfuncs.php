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
    $_SESSION["id"] = $id;
}
function getUserId() {
    session_start();
    return $_SESSION["id"];
}
function saveUsername($Username) {
    session_start();
    $_SESSION["username"] = $Username;
}
function getUserName() {
    session_start();
    return $_SESSION["username"];
}
function logout() {
    echo '<li><a href="login.html?logout">Logout</a></li>';
    
    if (isset($_GET['logout'])) {
        session_start();
        session_destroy();
        alert("You have been logged out. Redirecting to login page.");
        header('Location: login.html');
    }
}
//sends alert box with customized message to notify of any changes.
function alert($message) {
    echo "<script>alert('$message');</script>";
}
function profanityFilter($text) {
    //uses regex to filter based off of list of words added to it, returns filtered text.
    //not many terms added, but things can be added as it goes.
    $filter_terms = array('/\bass(es|holes?)?\b/i', '/\bshit(ting|e|ty|head|ter?)?\b/i', '/\bfuck(er|ed|ing|head?)?\b/i');
    $filtered_text = preg_replace($filter_terms, '****', $text);
    return $filtered_text;
}
function adminControl() {
    //render admin control panel navigation if user is admin
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
    //render the blog and control based on user role
    $link = dbConnect();
    
    $userQuery = "SELECT * FROM `users`";
    $userResult = $link->query($userQuery);
    $userData = $userResult->fetch_assoc();
    
    //read posts from database
    $blogQuery = "SELECT * FROM `blog`";
    $blogResult = $link->query($blogQuery);
    
    echo "</div><form>";
    echo "<h3>Recent Posts</h3>";
    if ($blogResult->num_rows == 0) {
        echo "There are no blog posts.";
    }
    echo "</form></div>";
    //populate blog posts
    while ($row = $blogResult->fetch_assoc()) {
        echo '<div id="post-' . $row['post_id'] . '">';
        echo "<form>";
        echo "<table>";
        $author = $row["author"];
        $title = $row["blogtitle"];
        $message = $row["blogmessage"];
        $rating = $row["votes"];
        //render title, message, author, rating
        echo "<th>Title: " . $title . "</th><tr><td>Rating: " . $rating . "</td></tr><tr><td>Author: " . $author . "</td></tr><tr><td>" . $message . "</td></tr>";
        //render controls
        echo "<tr><td>";
        echo '<a href="blog.php?upvoteID=' . $row['post_id'] . '">Upvote</a>';
        echo ' ';
        echo '<a href="blog.php?downvoteID=' . $row['post_id'] . '">Downvote</a>';
        echo ' ';
        echo '<a href="?commentID=' .$row['post_id'] . '">Comment</a>';
        echo ' ';
        if ($userResult) {
            //check if user has admin priviledges or owner of post
            if ($userData["role"] == "admin"  || strcmp($userData['username'], $comRow['username'] == 0)) {
                echo '<a href="blog.php?deleteID=' . $row['post_id'] . '">Delete</a>';
                echo ' ';
            }
            //compares username in users to author in blog to see if
            //the user is the same as author, if so, renders edit link
            if (strcmp($userData['username'], $row['author']) == 0 ) {
                echo '<a href="blog.php?editID=' . $row['post_id'] . '">Edit</a>';
                echo ' ';
            }
            echo '<a href="blog.php?flagID=' . $row['post_id'] . '">Flag</a>';
            echo ' ';
        }
        echo "</td></tr>";
        echo "</table>";
        echo "</form>";
        echo "</div>";
        $commentQuery = 'SELECT * FROM comments WHERE post_id=' . $row['post_id'] . '';
        $commentResult = $link->query($commentQuery);
        
        while ($comRow = $commentResult->fetch_assoc()) {
            echo "<div><form id='Comments'><table>";
            $user = $comRow['username'];
            $com = $comRow['comment'];
            echo "<tr><td>User: $user</td></tr><tr><td>$com</td></tr>";
            echo "<tr><td>";
            if ($userResult) {
                //check if user has admin priviledges or owner of comment
                if ($userData["role"] == "admin" || strcmp(getUserName(), $comRow['username'] == 0)) {
                    echo '<a href="blog.php?deleteCommentID=' . $comRow['comment_id'] . '&postID=' . $comRow['post_id'] . '">Delete</a>';
                    echo ' ';
                }
                if (strcmp(getUserName(), $comRow['username']) == 0 ) {
                    echo '<a href="blog.php?editCommentID=' . $comRow['comment_id'] . '">Edit</a>';
                    echo ' ';
                }
                echo '<a href="blog.php?flagCommentID=' . $comRow['comment_id'] . '&postID=' . $comRow['post_id'] .'">Flag</a>';
                echo ' ';
            }
            echo "</td></tr>";
            echo "</table></form></div>";
        }
    }
    mysqli_close($link);
}
//populates admin page with flagged posts and users
function populateAdmin() {
    $link = dbConnect();
    
    $roleQuery = "SELECT * FROM `users`";
    $roleResult = mysqli_query($link, $roleQuery);
    $roleData = $roleResult->fetch_assoc();
    
    //read the FLAGGED posts in database and display them. if no flagged posts,
    //it will be empty
    $blogQuery = "SELECT * FROM `blog` WHERE `flagged`='1'";
    $result = mysqli_query($link, $blogQuery);
    echo "<div><form><table>";
    echo "<h3>Flagged Posts</h3>";
    if ($result->num_rows == 0) {
        echo "There are no flagged posts.";
    }
    //populate flagged blog posts
    while ($row = mysqli_fetch_assoc($result)) {
        $author = $row["author"];
        $title = $row["blogtitle"];
        $message = $row["blogmessage"];
        $rating = $row["votes"];
        echo "<th>Title: " . $title . "</th><tr><td>Rating: " . $rating . "</td></tr><tr><td>Author: " . $author . "</td></tr><tr><td>" . $message . "</td></tr>";
        if ($roleResult) {
            //check if user has admin priviledges
            if ($roleData["role"] == "admin") {
                echo "<tr><td>";
                echo '<a href="admin.php?deleteID=' . $row['post_id'] . '">Delete</a>';
                echo ' ';
                echo '<a href="admin.php?flagID=' . $row['post_id'] . '">Unflag</a>';
                echo ' ';
                echo "</td></tr>";
            }
        }
    }
    echo "</table></form></div>";
    
    $commentQuery = 'SELECT * FROM comments WHERE flagged ="1"';
    $commentResult = $link->query($commentQuery);
    
    echo "<div><form><table>";
    echo "<h3>Flagged comments</h3>";
    //verify that we have any comments, if not
    //tell admin no comments flagged!
    if ($commentResult->num_rows == 0) {
        echo "There are no flagged comments.";
    }
    //populate the comments
    while ($comRow = $commentResult->fetch_assoc()) {
        $user = $comRow['username'];
        $com = $comRow['comment'];
        echo "<tr><td>User: $user</td></tr><tr><td>$com</td></tr>";
        if ($roleResult) {
            //check if user has admin priviledges or owner of comment
            echo "<tr><td>";
            if ($roleData["role"] == "admin") {
                echo '<a href="admin.php?deleteCommentID=' . $comRow['comment_id'] . '&postID=' . $comRow['post_id'] . '">Delete</a>';
                echo ' ';
                echo '<a href="admin.php?unflagCommentID=' . $comRow['comment_id'] . '&postID=' . $comRow['post_id'] . '">Unflag</a>';
                echo ' ';
            }
            echo "</td></tr>";
        }
    }
    echo "</table></form></div>";
    
    $userQuery = "SELECT * FROM `users`";
    $userResult = $link->query($userQuery);
    //populate users and add role controls
    echo "<div><form><table>";
    echo "<h3>Users</h3>";
    while ($row = mysqli_fetch_assoc($userResult)) {
            $user = $row["username"];
            $role = $row["role"];
            echo "<th>User: " . $user . "</th><tr><td>Role: " . $role . "</td></tr>";
            if ($roleResult) {
                //check if user has admin priviledges
                if ($roleData["role"] == "admin") {
                    echo "<tr><td>";
                    echo '<a href="admin.php?promoteID=' . $row['id'] . '">Promote</a>';
                    echo ' ';
                    echo '<a href="admin.php?demoteID=' . $row['id'] . '">Demote</a>';
                    echo ' ';
                    echo '<a href="admin.php?banID=' . $row['id'] . '">Ban</a>';
                    echo ' ';
                    echo '<a href="admin.php?unbanID=' . $row['id'] . '">Unban</a>';
                    echo "</td></tr>";
                }
            }
        }
        echo "</table></form></div>";
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

    // Verify that blog title or message is not empty {
    if (is_null($BlogTitle) || empty($BlogTitle)) {
        alert("Please enter a title.");
        return;
    } elseif (is_null($BlogMessage) || empty($BlogMessage)) {
        alert("Please write a message.");
        return;
    }
    
    //add extra apostrophe to make sure it's added into the SQL!
    $aposTitle = str_replace("'", "''", $BlogTitle);
    $aposMessage = str_replace("'", "''", $BlogMessage);
    
    //Filter the message and title of profanity
    $filteredTitle = profanityFilter($aposTitle);
    $filteredMessage = profanityFilter($aposMessage);
    
    //queries
    $sqlPost = "INSERT INTO `blog` (author, blogtitle, blogmessage) VALUES('$author', '$filteredTitle', '$filteredMessage')";
    $updateQuery = "UPDATE `blog` SET `blogtitle'='$filteredTitle', `blogmessage`='$filteredMessage' WHERE `post_id`='$editID'";
    $editQuery = "SELECT * FROM `blog` WHERE `post_id`='$editID'";
    $editResult = $link->query($editQuery);
    
    if ($editResult->num_rows > 0) {
        alert("How did you get here?!");
    } elseif ($editResult->num_rows == 0) {
        $link->query($sqlPost);
        alert("Successfully uploaded message.");
    } else {
        alert("ERROR: Not able to execute $sqlPost." . mysqli_error($link));
    }
    mysqli_close($link);
}
function upvote() {
    $upvote = $_GET['upvoteID'];
    
    $link = dbConnect();
    $userid = getUserId();
    $sql = "SELECT * FROM rating WHERE user_id='$userid' AND post_id='$upvote'";
    $result = $link->query($sql);
    //see if user has an existing vote on the post we are trying to upvote or downvote
    //if returns a number greater than 0 user has already voted.
    if ($result->num_rows > 0) {
         alert("You have already voted.");
    } else {
        //cast a vote!
        $upvoteQuery = "UPDATE blog SET votes = votes+1 WHERE post_id='$upvote'";
        $upvoteResult = $link->query($upvoteQuery);
        //update ratings table tos how that a user has voted!
        $link->query("INSERT INTO rating (post_id, user_id) VALUES ($upvote, $userid)");
    } 
    $link->close();
}
function downvote() {
    $downvote = $_GET['downvoteID'];
    
    $link = dbConnect();
    $userid = getUserId();
    $sql = "SELECT * FROM rating WHERE user_id='$userid' AND post_id='$downvote'";
    $result = $link->query($sql);
    //see if user has an existing vote on the post we are trying to upvote or downvote
    //if returns a number greater than 0 user has already voted.
    if ($result->num_rows > 0) {
        alert("You have already voted.");
    } else {
        //cast a vote!
        $downvoteQuery = "UPDATE blog SET votes = votes-1 WHERE post_id='$downvote'";
        $downvoteResult = $link->query($downvoteQuery);
        //update ratings table to show that a user has voted!
        $insert = "INSERT INTO rating (user_id, post_id) VALUES ($userid, $downvote)";
        $link->query($insert);
    }
}
function deletePost() {
    $deleteID = $_GET['deleteID'];
    $link = dbConnect();
    $delQuery = "DELETE FROM `blog` WHERE `post_id`='$deleteID'";
    if ($link->query($delQuery)) {
        alert("Record successfully deleted.");
    }
}
function deleteComment() {
    $commentID = $_GET['deleteCommentID'];
    $postID = $_GET['postID'];
    $link = dbConnect();
    $delQuery = "DELETE FROM `comments` WHERE `comment_id`='$commentID' AND `post_id`='$postID'";
    if ($link->query($delQuery)) {
        alert("Record successfully deleted.");
    } else {
        alert(mysqli_error($link));
    }
}
function flagPost() {
    $flagID = $_GET['flagID'];
    $link = dbConnect();
    $flagQuery = "UPDATE `blog` SET `flagged` = '1' WHERE `blog`.`post_id` = '$flagID'";
    if ($link->query($flagQuery)) {
        alert("Post has been flagged for admins.");
    }
    mysqli_close($link);
}
function flagComment() {
    $commentID = $_GET['flagCommentID'];
    $postID = $_GET['postID'];
    $link = dbConnect();
    $flagQuery = "UPDATE `comments` SET `flagged`='1' WHERE `comment_id`='$commentID' AND `post_id`='$postID'";
    if ($link->query($flagQuery)) {
        alert("Comment has been flagged for admins.");
    } else {
        alert(mysqli_error($link));
    }
}
//admin control
function unflagPost() {
    $flagID = $_GET['flagID'];
    $link = dbConnect();
    $unflagQuery = "UPDATE `blog` SET `flagged` = '0' WHERE `blog`.`post_id` = '$flagID'";
    if ($link->query($unflagQuery)) {
        alert("Post has been approved.");
    }
    mysqli_close($link);
}
//admin control
function unflagComment() {
    $commentID = $_GET['unflagCommentID'];
    $postID = $_GET['postID'];
    $link = dbConnect();
    $flagQuery = "UPDATE `comments` SET `flagged`='0' WHERE `comment_id`='$commentID' AND `post_id`='$postID'";
    if ($link->query($flagQuery)) {
        alert("Comment has been approved.");
    } else {
        alert(mysqli_error($link));
    }
}
//admin control
function promote() {
    $promote = $_GET['promoteID'];
    $link = dbConnect();
    $promoteQuery = "UPDATE `users` SET `role` = 'admin' WHERE `users`.`id` = '$promote'";
    if ($link->query($promoteQuery)) {
        alert("User has been promoted to admin.");
    }
    mysqli_close($link);
}
//admin control
function demote() {
    $demote = $_GET['demoteID'];
    $link = dbConnect();
    $demoteQuery = "UPDATE `users` SET `role` = 'standard' WHERE `users`.`id` = '$demote'";
    if ($link->query($demoteQuery)) {
        alert("User has been demoted to a standard user.");
    }
    mysqli_close($link);
}
//admin control
function banUser() {
    $ban = $_GET['banID'];
    $link = dbConnect();
    $banQuery = "UPDATE `users` SET `banned` = '1' WHERE `users`.`id` = '$ban'";
    if ($link->query($banQuery)) {
        alert("User has been banned.");
    }
    mysqli_close($link);
}
//admin control
function unbanUser() {
    $unban = $_GET['unbanID'];
    $link = dbConnect();
    $unbanQuery = "UPDATE `users` SET `banned` = '0' WHERE `users`.`id` = '$unban'";
    if ($link->query($unbanQuery)) {
        alert("User has been banned.");
    }
    mysqli_close($link);
}
?>