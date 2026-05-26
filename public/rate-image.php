<?php
	include "auth.php";
	include "db_conn.php";

	if (!$is_logged_in) {
		header("Location: slike.php");
		exit();
	}

	$message = "";

	if ($_SERVER["REQUEST_METHOD"] === "GET") {
		$stmt = $conn->prepare("SELECT image_id, rating FROM image_ratings WHERE user_id = ?");
		$stmt->bind_param("i", $_SESSION["user_id"]);

		$stmt->execute();
		$result = $stmt->get_result();

		$ratings = [];

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$ratings[] = $row;
			}
		}

		echo json_encode($ratings);
	} else if ($_SERVER["REQUEST_METHOD"] === "PUT") {
		$image_id = $_GET["image-id"];
		$rating = $_GET["rating"];
		if ($rating < 1 or $rating > 5) die("invalid rating");
		$stmt = $conn->prepare("REPLACE INTO image_ratings (user_id, image_id, rating) VALUES (?, ?, ?)");
		$stmt->bind_param("iii", $_SESSION["user_id"], $image_id, $rating);
		if ($stmt->execute()) {
			$message = "Image rated";
		} else {
			$message = "Error: " . $stmt->error;
		}
	} else if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
		$image_id = $_GET["image-id"];
		$stmt = $conn->prepare("DELETE FROM image_ratings WHERE user_id = ? AND image_id = ?");
		$stmt->bind_param("ii", $_SESSION["user_id"], $image_id);
		if ($stmt->execute()) {
			$message = "Rating removed";
		} else {
			$message = "Error: " . $stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
?>
