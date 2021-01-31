<head>
    <title>Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
	<h2>Admin Control Panel</h2>
	<a href="index.html">Home</a><br>
	<a href="login.html">Login</a><br>
	<a href="signUp.html">Register</a><br>
	<a href="blog.php">Blog</a><br>
	<a href="search.php">Search</a><br>
	<?php 
	   include 'myfuncs.php';
	   logout();
	   adminControl();
	?>
</header>
<body>
    <?php
    if (isset($_GET['flagID'])) {
        unflagPost();
    } elseif (isset($_GET['promoteID'])) {
        promote();
    } elseif (isset($_GET['demoteID'])) {
        demote();
    } elseif (isset($_GET['banID'])) {
        banUser();
    } elseif (isset($_GET['deleteID'])) {
        deletePost();
    } elseif (isset($_GET['deleteCommentID'])) {
        deleteComment();
    } elseif (isset($_GET['unflagCommentID'])) {
        unflagComment();
    }
        populateAdmin();
    ?>
</body>