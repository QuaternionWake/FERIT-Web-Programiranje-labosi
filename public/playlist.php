<?php
	include "auth.php";
	include "db_conn.php";

	$message = "";

	if ($_SERVER["REQUEST_METHOD"] === "GET") {
		$stmt = $conn->prepare("SELECT song_id FROM user_playlists WHERE user_id = ?");
		$stmt->bind_param("i", $_SESSION["user_id"]);

		$stmt->execute();
		$result = $stmt->get_result();

		$ids = [];

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$ids[] = $row;
			}
		}

		echo json_encode($ids);
		exit();
	}

	if (!$is_logged_in) {
		header("Location: index.php");
		exit();
	}

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$song_id = $_GET["song-id"];
		$stmt = $conn->prepare("INSERT INTO user_playlists (user_id, song_id) VALUES (?, ?)");
		$stmt->bind_param("ii", $_SESSION["user_id"], $song_id);
		if ($stmt->execute()) {
			$message = "Song added";
		} else {
			$message = "Error: " . $stmt->error;
		}
	} else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
		$song_id = $_GET["song-id"];
		$stmt = $conn->prepare("DELETE FROM user_playlists WHERE user_id = ? AND song_id = ?");
		$stmt->bind_param("ii", $_SESSION["user_id"], $song_id);
		if ($stmt->execute()) {
			$message = "Song removed";
		} else {
			$message = "Error: " . $stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
?>
