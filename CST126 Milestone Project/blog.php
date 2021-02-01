<head>
    <title>Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
    <h2>Welcome to the blog.</h2>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signUp.html">Register</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="search.php">Search</a></li>
	<?php 
	   include 'myfuncs.php';
	   logout();
	   adminControl();
	?>
	    </ul>
    </nav>
</header>
<body>
    <?php
        //set variables to null
        $editID = null;
        $titleData = null;
        $messageData = null;
        
        //check for delete click, get deleteID from url. This correlates to `post_id` in database.
        if (isset($_GET['deleteID'])) {
            deletePost();
            //check for edit clicked
        } elseif (isset($_GET['editID'])) {
            //set editID variable to the ID set in URL. This correlates to `post_id` in database.
            $editID = $_GET['editID'];
            //check request method. If it's a GET request, we are populating the textarea with
            //an existing message.
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $link = dbConnect();
                
                //retrieve title data from database
                $titleQuery = "SELECT `blogtitle` FROM `blog` WHERE `post_id`='$editID'";
                $titleResult = $link->query($titleQuery);
                $titleData = $titleResult->fetch_assoc();
                
                //retrieve message data from database
                $messageQuery = "SELECT `blogmessage` FROM `blog` WHERE `post_id`='$editID'";
                $messageResult = $link->query($messageQuery);
                $messageData = $messageResult->fetch_assoc();
                $link->close();
                //check request method. This is a POST request meaning we are putting data into
                //the database. This is an update from an existing post.
            } elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
                $link = dbConnect();
                $blogTitle = $_POST['BlogTitle'];
                $blogMessage = $_POST['BlogMessage'];
                
                $updateQuery = "UPDATE `blog` SET `blogtitle`='$blogTitle', `blogmessage`='$blogMessage' WHERE `post_id`='$editID'";
                $updateResult = $link->query($updateQuery);
                alert("Post has been updated!");
                $link->close();
            }
        } elseif (isset($_GET['editCommentID'])) {
            $editCommentID = $_GET['editCommentID'];
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $link = dbConnect();
                
                //retrieve message data from database
                $comQuery = "SELECT `comment` FROM `comments` WHERE `comment_id`='$editCommentID'";
                $comResult = $link->query($comQuery);
                $comData = $comResult->fetch_assoc();
                echo '<div class="comment-container">';
                echo '<form method="POST" action="blog.php?editCommentID=' . $editCommentID . '">';
                echo '<textarea id="CommentMessage" name="CommentMessage">';
                if ($comData != null ) { echo implode('', $comData); }
                echo '</textarea>';
                echo '<input type="submit"></input>';
                echo '</form>';
                echo '</div>';
                
                $link->close();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $link = dbConnect();
                //pull username from session
                $user = getUserName();
                //data from user fields
                $comMsg = $_POST['CommentMessage'];
                // Verify that blog title or message is not empty {
                if (is_null($comMsg) || empty($comMsg)) {
                    alert("Please enter a message.");
                    return;
                }
                //Filter the message and title of profanity
                $filterMsg = profanityFilter($comMsg);
                
                //queries
                $sqlCom = "UPDATE `comments` SET `comment`='$filterMsg' WHERE `comment_id`='$editCommentID'";
                $link->query($sqlCom);
                alert("Comment has been updated!");
                $link->close();
            }
        }
        elseif (isset($_GET['commentID'])) {
            $commentID = $_GET['commentID'];
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                echo '<div class="comment-container">';
                echo '<form method="POST" action="blog.php?commentID=' . $commentID . '">';
                echo '<textarea id="CommentMessage" name="CommentMessage"></textarea>';
                echo '<input type="submit"></input>';
                echo '</form>';
                echo '</div>';
            } elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
                $link = dbConnect();
                //pull username from session
                $user = getUserName();
                $link = dbConnect();
                $commentID = $_GET['commentID'];
                //data from user fields
                $comMsg = $_POST['CommentMessage'];
                // Verify that blog title or message is not empty {
                if (is_null($comMsg) || empty($comMsg)) {
                    alert("Please enter a message.");
                    return;
                }
                //Filter the message and title of profanity
                $filterMsg = profanityFilter($comMsg);
                
                //queries
                $sqlCom = "INSERT INTO `comments` (username, post_id, comment) VALUES ('$user', '$commentID', '$filterMsg')";
                $link->query($sqlCom);
                $link->close();
            }
        } elseif (isset($_GET['flagID'])) {
            //check for flag post click, get ID from URL. Correlates to `post_id` in database
            flagPost();
            //met none of the requirements of previous checks. This means this is a new post and
            //we are adding it to the database
        } elseif (isset($_GET['upvoteID'])) {
            upvote();
        } elseif (isset($_GET['downvoteID'])) {
            downvote();
        } elseif (isset($_POST['BlogTitle'])) {
            postBlog();
        } elseif (isset($_GET['deleteCommentID'])) {
            deleteComment();
        } elseif (isset($_GET['flagCommentID'])) {
            flagComment();
        }
        //render blog from database
        populateBlog();
    ?>
    <div>
        <form id ="Blog" action="blog.php<?php if ($editID != 0) echo '?editID='.$editID; ?>" method="POST">
            <h3>Add post</h3><hr>
            <label for="BlogTitle" class="blogstuff">Title:<br></label>
            <input type="text" id="BlogTitle" name="BlogTitle" value="<?php if ($titleData != null) { echo implode('',$titleData); } ?>"><br><br>
            <label for="BlogMessage" class="blogstuff">Message:<br></label>
            <textarea id="BlogMessage" name="BlogMessage"><?php if ($messageData != null) { echo implode('',$messageData); } ?></textarea><br><br>
            <input type="submit" value="Submit"><br>
        </form>
    </div>
</body>