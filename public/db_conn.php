<?php
	$servername = "mysql";
	$username = "root";
	$password = "root";
	$dbname = "da base";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("DB connection failed: " . $conn->connect_error);
	}
?>
