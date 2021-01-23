<head>
    <title>Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
	<h2>Welcome to the blog.</h2>
	<a href="index.html">Home</a><br>
	<a href="login.html">Login</a><br>
	<a href="signUp.html">Register</a><br>
	<a href="blog.php">Blog</a><br>
	<a href="search.php">Search</a><br>
	<?php 
	   include 'myfuncs.php';
	   adminControl();
	?>
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
                mysqli_close($link);
                //check request method. This is a POST request meaning we are putting data into
                //the database. This is an update from an existing post.
            } elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
                $link = dbConnect();
                $blogTitle = $_POST['BlogTitle'];
                $blogMessage = $_POST['BlogMessage'];
                
                $updateQuery = "UPDATE `blog` SET `blogtitle`='$blogTitle', `blogmessage`='$blogMessage' WHERE `post_id`='$editID'";
                $updateResult = $link->query($updateQuery);
                alert("Post has been updated!");
                mysqli_close($link);
            }
            //check for flag post click, get ID from URL. Correlates to `post_id` in database
        } elseif (isset($_GET['flagID'])) {
            flagPost();
            //met none of the requirements of previous checks. This means this is a new post and
            //we are adding it to the database
        } elseif (isset($_POST['BlogTitle'])) {
            postBlog();
        }
        //render blog from database
        populateBlog();
    ?>
    <div>
        <form id ="Blog" action="blog.php<?php if ($editID != 0) echo '?editID='.$editID; ?>" method="POST">
            <h3>Add post</h3><hr>
            <label for="BlogTitle">Title:<br></label>
            <input type="text" id="BlogTitle" name="BlogTitle" value="<?php if ($titleData != null) { echo implode('',$titleData); } ?>" required><br><br>
            <label for=BlogMessage>Message:<br></label>
            <textarea id="BlogMessage" name="BlogMessage" rows="10" cols="48"><?php if ($messageData != null) { echo implode('',$messageData); } ?></textarea><br><br>
            <input type="submit" value="Submit"><br>
        </form>
    </div>
</body>