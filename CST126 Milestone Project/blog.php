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
	$link = dbConnect();
	
	$query = "SELECT * FROM `users`";
	$result = $link->query($query);
	$data = $result->fetch_assoc();
	
	if ($result) {
	    if ($data["role"] == "admin") {
	        echo '<a href="admin.php">Admin</a>';
	    }
	}
	?>
</header>
<body>
    <?php
        if (isset($_POST['BlogTitle'])) {
            postBlog();
        }
        if (isset($_GET['deleteID'])) {
            deletePost();
        }
        if (isset($_GET['editID'])) {
            editPost();
        }
        if (isset($_GET['flagID'])) {
            flagPost();
        }
        populateBlog();
    ?>
    <div>
        <form action="blog.php" method="POST">
            <h3>Add post</h3><hr>
            <label for="BlogTitle">Title:<br></label>
            <input type="text" id="BlogTitle" name="BlogTitle" required><br><br>
            <label for=BlogMessage>Message:<br></label>
            <textarea id="BlogMessage" name="BlogMessage" rows="10" cols="48"></textarea><br><br>
            <input type="submit" value="submit"><br>
        </form>
    </div>
</body>