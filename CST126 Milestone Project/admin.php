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
	<?php 
	include 'myfuncs.php';
	   adminControl();
	?>
</header>
<body>
    <?php
    if (isset($_GET['flagID'])) {
        unflagPost();
    }
        populateAdmin();
    ?>
</body>