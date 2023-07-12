<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>results | jmovie</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Ubuntu&display=swap" rel="stylesheet">
</head>
<body>
	<div id="resultHeader">
		<h1 onclick="location.href='index.php'"><b style="color: #DA0037;">j</b>movie</h1>
		<form action="search.php" method="POST">
			<input id="resultSearchBar" type="text" name="sq" placeholder="New search...">
		</form>
	</div>

	<div id="resultContainer">
		<?php

			$host = "";
			$username = "";
			$pwd = "";
			$dbName = "";

			$dbConn = mysqli_connect($host, $username, $pwd, $dbName);
			
			if (!$dbConn) {
			    echo mysqli_connect_error();
			}

			$title = $_POST['sq'];
			$sq = $_POST['sq'];

			if ($sq == "") {
				header("Location: index.php");
			}

			// removes all non-alphanumeric characters for improved searching 
			$sq = preg_replace("/[^A-Za-z0-9 ]/", '', $sq);
			$sq = str_replace(" ", "", $sq);

			$movies = mysqli_query($dbConn, "SELECT * FROM movies WHERE search LIKE '%$sq%'");
			$results = mysqli_num_rows($movies);

			if ($results != 0) {

				echo "<h2>Showing results for<br> \"". $title ."\" | (".$results." titles)</h2>";

				while ($movie = $movies->fetch_assoc()) {
					echo '
						<div class="movie">
							<img class="poster" src="'.$movie["poster"].'" width="150px" height="225px">
							<a class="movieTitle" href="movie.php/?movie='.$movie["id"].'">'.$movie["title"].'</a>
						</div>
					';
				}

			} else {
				echo "<h1>No results found for \"". $sq . "\"!</h1><h2>Try searching for something else...</h2>";
			}

		?>
	</div>
</body>
</html>
