<link rel="stylesheet" href="style.css">
<head>
	<title>Login Response</title>
</head>
<body>
<h2>Login was successful: <?php echo " ".$Username; ?></h2>
<?php 
    header("Location: blog.php");
?>
</body>