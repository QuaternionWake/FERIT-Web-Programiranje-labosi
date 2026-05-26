<?php
	include "auth.php";
	include "db_conn.php";

	if (!$is_admin) {
		header("Location: index.php");
		exit();
	}

	$message = "";

	if ($_SERVER["REQUEST_METHOD"] === "GET") {
		$song_id = $_GET["song-id"];
	} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$song_id = $_POST["song-id"];
	} else die("Unsupported method");

	$stmt = $conn->prepare("SELECT * FROM music WHERE ID = ?");
	$stmt->bind_param("i", $song_id);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
		$stmt->bind_result($_, $name, $artist, $spotify_preview, $tags, $genre, $year, $duration);
		$stmt->fetch();
		if ($duration >= 3600) {
			$duration = sprintf("%d:%02d:%02d", $duration/3600, $duration/60%60, $duration%60);
		} else {
			$duration = sprintf("%d:%02d", $duration/60, $duration%60);
		}
	} else {
		$message = "Song id not found";
	}

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		include "get-song-params.php";
		if ($message !== "") goto END;

		$stmt = $conn->prepare("UPDATE music SET name = ?, artist = ?, spotify_preview = ?, tags = ?, genre = ?, year = ?, duration = ? WHERE id = ?");
		$stmt->bind_param("sssssiii", $name, $artist, $spotify_preview, $tags, $genre, $year, $duration, $song_id);
		if ($stmt->execute()) {
			$message = "Song updated";
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
				<h4>Edit song</h4>
				<input type="hidden" id="song-id" name="song-id" value="<?php echo $song_id; ?>">
				<div class="flex-row">
					<label for="name">Name</label>
					<input type="text" id="name" name="name" value="<?php echo $name; ?>">
				</div>
				<div class="flex-row">
					<label for="artist">Artist</label>
					<input type="text" id="artist" name="artist" value="<?php echo $artist; ?>">
				</div>
				<div class="flex-row">
					<label for="spotify-preview">Spotify Preview</label>
					<input type="text" id="spotify-preview" name="spotify-preview" value="<?php echo $spotify_preview; ?>">
				</div>
				<div class="flex-row">
					<label for="tags">Tags</label>
					<input type="text" id="tags" name="tags" value="<?php echo $tags; ?>">
				</div>
				<div class="flex-row">
					<label for="genre">Genre</label>
					<input type="text" id="genre" name="genre" value="<?php echo $genre; ?>">
				</div>
				<div class="flex-row">
					<label for="year">Year</label>
					<input type="text" id="year" name="year" value="<?php echo $year; ?>">
				</div>
				<div class="flex-row">
					<label for="duration">Duration</label>
					<input type="text" id="duration" name="duration" value="<?php echo $duration; ?>">
				</div>
				<input type="submit" value="Edit song">
			</form>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
		<script src="/table.js"></script>
	</body>
</html>
