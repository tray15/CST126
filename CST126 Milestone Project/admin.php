<head>
    <title>Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
    <h2>Admin Control Panel</h2>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signUp.html">Register</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="search.php">Search</a></li>
        </ul>
    </nav>

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