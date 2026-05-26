<?php
	include "auth.php";
	include "db_conn.php";

	if (!$is_admin) {
		header("Location: index.php");
		exit();
	}

	$message = "";

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		include "get-song-params.php";
		if ($message !== "") goto END;

		$stmt = $conn->prepare("INSERT INTO music (name, artist, spotify_preview, tags, genre, year, duration) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssii", $name, $artist, $spotify_preview, $tags, $genre, $year, $duration);
		if ($stmt->execute()) {
			$message = "Song added";
		} else {
			$message = "Error: " . $stmt->error;
		}

		$stmt->close();
		$conn->close();
	}
	END:
?>
<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="My awesome website about music">
		<link rel="stylesheet" href="/styles/style.css">
		<title>Stranica O Glazbi</title>
	</head>

	<body>
		<?php include './include/header.php' ?>
		<?php include './include/navbar.php' ?>

		<main>
			<?php if ($message): ?>
				<span><?php echo $message; ?></span>
			<?php endif; ?>
			<form method="post">
				<h4>Add song</h4>
				<div class="flex-row">
					<label for="name">Name</label>
					<input type="text" id="name" name="name">
				</div>
				<div class="flex-row">
					<label for="artist">Artist</label>
					<input type="text" id="artist" name="artist">
				</div>
				<div class="flex-row">
					<label for="spotify-preview">Spotify Preview</label>
					<input type="text" id="spotify-preview" name="spotify-preview">
				</div>
				<div class="flex-row">
					<label for="tags">Tags</label>
					<input type="text" id="tags" name="tags">
				</div>
				<div class="flex-row">
					<label for="genre">Genre</label>
					<input type="text" id="genre" name="genre">
				</div>
				<div class="flex-row">
					<label for="year">Year</label>
					<input type="text" id="year" name="year">
				</div>
				<div class="flex-row">
					<label for="duration">Duration</label>
					<input type="text" id="duration" name="duration">
				</div>
				<input type="submit" value="Add song">
			</form>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
		<script src="/table.js"></script>
	</body>
</html>
