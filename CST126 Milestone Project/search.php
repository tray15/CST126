<head>
<title>Blog</title>
<link rel="stylesheet" href="style.css">
</head>
<header>
<h2>Welcome to the blog.</h2>
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
	<div>
    	<form id="Menus" action="search.php" method="POST">
    		<h3>Search</h3><hr>
    		<label for=Search>Search:<br></label>
    		<input type="text" id="Search" name="Search" value=""><br><br>
    		<input type="submit" value="Submit"><br><hr>
	<?php
    if (isset($_POST['Search'])) {
        $link = dbConnect();
        $search = $_POST['Search'];
        
        $sql = "SELECT * FROM blog WHERE blogtitle LIKE '%$search%' OR blogmessage LIKE '%$search%'";
        $result = $link->query($sql);
        
        //make sure that fields are not null or empty!
        if (is_null($search) || empty($search)) {
            alert("You need to enter something to search.");
            return;
        }
        
        echo "<h3>Search Result</h3>";
        echo "<table>";
        
        if ($result->num_rows == 0) {
            echo "<tr><td>There were no posts found.</td></tr>";
            return;
        }
        while ($row = $result->fetch_assoc()) {
            $title = $row['blogtitle'];
            echo '<tr><td><a href="blog.php?post_id' . $row['post_id'] . '#post-' . $row['post_id'] . '">' . $title . '</a></td></tr>';
        }
        echo "</table>";
}
?>
</form>
</div>
</body>