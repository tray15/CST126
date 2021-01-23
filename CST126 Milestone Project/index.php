<link rel="stylesheet" href="style.css">
<head>
	<title>My Milestone Project</title>
</head>
<header>
	<h2>My Milestone Project</h2>
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
	<div>
		<form id="Menus" action="signUp.php" method="POST">
			<h3>Sign Up</h3><hr>
			<label for="Username">Username:<br></label>
			<input type="text" id="Username" name="Username" maxlength="16" required><br><br>
			<label for="Email">Email:<br></label>
			<input type="email" id="Email" name="Email" required><br><br>
			<label for="Password">Password:<br></label>
			<input type="password" id="Password" name="Password" required><br><br>
			<label for="ConfirmPassword">Confirm Password:<br></label>
			<input type="password" id="ConfirmPassword" name="ConfirmPassword" required><br><br>
			<input type="submit" value="Sign Up"><br>
			<p>Already have an account? <a href="login.html">Sign In here</a>.</p>
		</form>
	</div>
</body>