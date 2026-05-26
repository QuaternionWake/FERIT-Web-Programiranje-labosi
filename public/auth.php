<?php
	include "db_conn.php";

	session_start();
	$is_logged_in = false;
	$is_admin = false;

	if (isset($_SESSION["username"])) {
		$is_logged_in = true;

		$stmt = $conn->prepare("SELECT role FROM users WHERE name = ?");
		$stmt->bind_param("s", $_SESSION["username"]);
		$stmt->execute();
		$stmt->store_result();

		$stmt->bind_result($user_role);
		$stmt->fetch();

		$stmt->close();
		$conn->close();

		if ($user_role === "admin") {
			$is_admin = true;
		}
	}
?>
