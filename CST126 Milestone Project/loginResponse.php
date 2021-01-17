<link rel="stylesheet" href="style.css">
<head>
	<title>Login Response</title>
</head>
<body>
<h2>Login was successful: <?php 
    echo "".getUserId();
    echo "<br>";
    echo "".getUserName();
?></h2>
<?php 
    header("Location: blog.php");
?>
</body>