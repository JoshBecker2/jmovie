<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>jmovie | Movie Search Engine</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Ubuntu&display=swap" rel="stylesheet">
</head>
<body onload="loadHome()">
	<div id="whiteoutContainer"></div>
	<div id="header">
		<h1><b style="color: #DA0037;">j</b>movie</h1>
		<h2><x>No</x> ads, <x>no</x> viruses, j movie.</h2>
	</div>

	<div id="searchContainer">
		<h2>Search for movies:</h2>
		<form action="search.php" method="POST" id="search">
			<input id="searchBar" type="text" name="sq" placeholder="Search for Movies">
		</form>
	</div>

	<div id="bodyContainer">
		<h2><x>Most Viewed Titles</x></h2>
		<?php

			$host = "";
			$username = "";
			$pwd = "";
			$dbName = "";
			
			$dbConn = mysqli_connect($host, $username, $pwd, $dbName);
			
			if (!$dbConn) {
			    echo "<h2>Sorry, there was an error connecting to the database!</h2>";
			}
			
			$movies = mysqli_query($dbConn, "SELECT * FROM movies ORDER BY views DESC LIMIT 10"); // get the ten most viewed movies in the db
			$results = mysqli_num_rows($movies);

			if ($results != 0) {
				while ($movie = $movies->fetch_assoc()) { 
					// write ten movie classes to the DOM and fill in placeholders with the SQL results
					echo '
						<div class="movie">
							<img class="poster" src="'.$movie["poster"].'" width="150px" height="225px">
							<a class="movieTitle" href="movie.php/?movie='.$movie["id"].'" style="font-size: 20px; padding-top: 15px; padding-bottom: 15px;">'.$movie["title"].'</a>
						</div>
					';
				}

			} else {
				echo "<h2>There was an issue getting the movies!<br><br>Try searching for a movie you like</h2>";
			}

		?>

	</div>

	<script type="text/javascript">
		
		// does all of the js required for the onload event
		
		function loadHome() {
			
			// fade effect
			setTimeout(function effect() {
				document.getElementById("whiteoutContainer").style.backgroundColor = "transparent";
				document.getElementById("whiteoutContainer").style.zIndex = "-1";
			}, 400);
			
			dynamicPlaceholder();
		}

		// give some suggestions on what to search for
		function dynamicPlaceholder() {
			titles = [
				"The Gentlemen",
				"Guardians of the Galaxy",
				"John Wick: Chapter 4",
				"The Flash",
				"The Wolf of Wall Street",
				"Creed III",
				"21 Jump Street"];

			document.getElementById("searchBar").placeholder = titles[Math.floor(Math.random() * titles.length)]; 

		}


	</script>
</body>
</html>