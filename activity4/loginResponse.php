<?php 
require_once('utility.php');
?>
<head>
	<title>Login Response</title>
</head>
<body>
	<h2>Login was successful: <?php echo " ".$Username ?></h2>
	<a href="whoAmI.php">Who Am I</a>
	<?php 
	$users = getAllUsers();
        include '_displayUsers.php';
	?>
</body>