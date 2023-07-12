<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="index.css">
	<title></title>
</head>
<body>
	<?php // updates the view count on the movie
		$host = "";
		$username = "";
		$pwd = "";
		$dbName = "";
		
		$dbConn = mysqli_connect($host, $username, $pwd, $dbName);
		
		$id = $_GET['movie'];
	
		$movies = mysqli_query($dbConn, "SELECT * FROM movies WHERE id = $id");

		$results = mysqli_num_rows($movies);

		if ($results != 0) {
			while ($movie = $movies->fetch_assoc()) {
				$views = $movie['views'] + 1;
				mysqli_query($dbConn, "UPDATE movies SET views = $views WHERE id = $id");
				$url = $movie['url'];
				header("Location: $url"); // take the user to the movie
			}
		}

	?>
</body>
</html>