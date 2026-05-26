<?php
	include "auth.php";
	include "db_conn.php";
	$message = "";

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$username = $_POST["username"];
		$password = $_POST["password"];

		$stmt = $conn->prepare("SELECT pw_hash FROM users WHERE name = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			$stmt->bind_result($db_pw_hash);
			$stmt->fetch();

			if (password_verify($password, $db_pw_hash)) {
				$stmt = $conn->prepare("SELECT id, role FROM users WHERE name = ?");
				$stmt->bind_param("s", $username);
				$stmt->execute();
				$stmt->store_result();

				$stmt->bind_result($user_id, $user_role);
				$stmt->fetch();

				$message = "Login successful";
				$_SESSION["username"] = $username;
				$_SESSION["user_id"] = $user_id;
				header("Location: index.php");
				exit();
			} else {
				$message = "Incorrct password";
			}
			$stmt->close();
		} else {
			$message = "Username not found";
		}

		$conn->close();
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
			<?php if ($message): ?>
				<span><?php echo $message; ?></span>
			<?php endif; ?>
			<form method="post">
				<h4>Login</h4>
				<div class="flex-row">
					<label for="username">Username</label>
					<input type="text" id="username" name="username">
				</div>
				<div class="flex-row">
					<label for="password">Password</label>
					<input type="password" id="password" name="password">
				</div>
				<input type="submit" value="Login">
			</form>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
	</body>
</html>
