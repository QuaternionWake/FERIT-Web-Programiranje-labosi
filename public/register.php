<?php
	include "auth.php";
	include "db_conn.php";
	$message = "";

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$confirm_password = $_POST["confirm-password"];
		if ($password !== $confirm_password) {
			$message = "Passwords do not match";
			goto END;
		}

		$checkUsername = $conn->prepare("SELECT name FROM users WHERE name = ?");
		$checkUsername->bind_param("s", $username);
		$checkUsername->execute();
		$checkUsername->store_result();

		if ($checkUsername->num_rows > 0) {
			$message = "Username already exists";
		} else {
			$pw_hash = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $conn->prepare("INSERT INTO users (name, pw_hash, role) VALUES (?, ?, 'user')");
			$stmt->bind_param("ss", $username, $pw_hash);
			if ($stmt->execute()) {
				$message = "Account created";
				session_start();
				$_SESSION["username"] = $username;
				header("Location: index.php");
				$stmt->close();
				$conn->close();
				exit();
			} else {
				$message = "Error: " . $stmt->error;
			}
			$stmt->close();
		}

		$checkUsername->close();
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
				<h4>Register</h4>
				<div class="flex-row">
					<label for="username">Username</label>
					<input type="text" id="username" name="username">
				</div>
				<div class="flex-row">
					<label for="password">Password</label>
					<input type="password" id="password" name="password">
				</div>
				<div class="flex-row">
					<label for="confirm-password">Confirm Password</label>
					<input type="password" id="confirm-password" name="confirm-password">
				</div>
				<input type="submit" value="Create account">
			</form>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
	</body>
</html>
