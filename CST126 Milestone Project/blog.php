<head>
    <title>Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
	<h2>Welcome to the blog.</h2>
	<a href="index.html">Home</a><br>
	<a href="login.html">Login</a><br>
	<a href="signUp.html">Register</a><br>
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
        
        //check for delete click, if ID present, delete post by id
        if (isset($_GET['deleteID'])) {
            deletePost();
            //check for edit click
        } elseif (isset($_GET['editID'])) {
            $editID = $_GET['editID'];
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $link = dbConnect();
                
                //retrieve title data
                $titleQuery = "SELECT `blogtitle` FROM `blog` WHERE `post_id`='$editID'";
                $titleResult = $link->query($titleQuery);
                $titleData = $titleResult->fetch_assoc();
                
                //retrieve message data
                $messageQuery = "SELECT `blogmessage` FROM `blog` WHERE `post_id`='$editID'";
                $messageResult = $link->query($messageQuery);
                $messageData = $messageResult->fetch_assoc();
                mysqli_close($link);
            } elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
                $link = dbConnect();
                $blogTitle = $_POST['BlogTitle'];
                $blogMessage = $_POST['BlogMessage'];
                
                $updateQuery = "UPDATE `blog` SET `blogtitle`='$blogTitle', `blogmessage`='$blogMessage' WHERE `post_id`='$editID'";
                $updateResult = $link->query($updateQuery);
                alert("Post has been updated!");
                mysqli_close($link);
            }
            //check for flag post click
        } elseif (isset($_GET['flagID'])) {
            flagPost();
            //check for new blog data
        } elseif (isset($_POST['BlogTitle'])) {
            postBlog();
        }
        //generate blog
        populateBlog();
    ?>
    <div>
        <form action="blog.php<?php if ($editID != 0) echo '?editID='.$editID; ?>" method="POST">
            <h3>Add post</h3><hr>
            <label for="BlogTitle">Title:<br></label>
            <input type="text" id="BlogTitle" name="BlogTitle" value="<?php if ($titleData != null) { echo implode('',$titleData); }?>" required><br><br>
            <label for=BlogMessage>Message:<br></label>
            <textarea id="BlogMessage" name="BlogMessage" rows="10" cols="48"><?php if ($messageData != null) { echo implode('',$messageData); } ?></textarea><br><br>
            <input type="submit" value="Submit"><br>
        </form>
    </div>
</body>