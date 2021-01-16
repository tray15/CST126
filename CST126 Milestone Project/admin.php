<head>
    <title>Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
	<h2>Admin Control Panel</h2>
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
    if (isset($_GET['flagID'])) {
        unflagPost();
    }
        populateAdmin();
    ?>
</body>