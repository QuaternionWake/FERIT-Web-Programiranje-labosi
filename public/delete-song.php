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
		$stmt = $conn->prepare("DELETE FROM music WHERE id = ?");
		$stmt->bind_param("i", $song_id);
		if ($stmt->execute()) {
			$message = "Song deleted";
		} else {
			$message = "Error: " . $stmt->error;
		}
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
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
			<span>your not supposed to be here whoops</span>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
		<script src="/table.js"></script>
	</body>
</html>
