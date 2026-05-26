<?php
	include "db_conn.php";

	$query = "SELECT * FROM music WHERE 1=1";
	$params = [];
	$types = "";

	if (!empty($_GET["name"])) {
		$query .= " AND name LIKE ?";
		$params[] = "%" . $_GET["name"] . "%";
		$types .= "s";
	}
	if (!empty($_GET["artist"])) {
		$query .= " AND artist LIKE ?";
		$params[] = "%" . $_GET["artist"] . "%";
		$types .= "s";
	}
	if (!empty($_GET["tags"])) {
		$query .= " AND tags LIKE ?";
		$params[] = "%" . $_GET["tags"] . "%";
		$types .= "s";
	}
	if (!empty($_GET["genre"])) {
		$query .= " AND genre LIKE ?";
		$params[] = "%" . $_GET["genre"] . "%";
		$types .= "s";
	}
	if (!empty($_GET["year"])) {
		$query .= " AND year = ?";
		$params[] = $_GET["year"];
		$types .= "i";
	}
	if (!empty($_GET["duration"])) {
		$query .= " AND duration = ?";
		$params[] = $_GET["duration"];
		$types .= "i";
	}

	$stmt = $conn->prepare($query);
	if (!empty($params)) {
		$stmt->bind_param($types, ...$params);
	}
	$stmt->execute();
	$result = $stmt->get_result();

	$music = [];

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$music[] = $row;
		}
	}

	echo json_encode($music);

	$stmt->close();
	$conn->close();
?>
