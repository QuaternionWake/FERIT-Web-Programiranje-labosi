<?php include "auth.php" ?>
<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="My awesome graph about music">
		<link rel="stylesheet" href="/styles/style.css">
		<link rel="stylesheet" href="/styles/style_grafikon.css">
		<title>Grafovi O Glazbi</title>
	</head>

	<body>
		<?php include "./includm/header.php" ?>
		<?php include "./includm/navbar.php" ?>

		<main>
			<section aria-label="Main">
				<h1>Music info</h1>
				<section class="flex-col">
					<h2>Number of songs by genre</h2>
					<ul class="barchart">
						<li><span class="row-name">n/a</span><span class="row-value w-12">12</span></li>
						<li><span class="row-name">Electronic</span><span class="row-value w-8">8</span></li>
						<li><span class="row-name">Rock</span><span class="row-value w-5">5</span></li>
						<li><span class="row-name">Jazz</span><span class="row-value w-2">2</span></li>
						<li><span class="row-name">Pop</span><span class="row-value w-2">2</span></li>
						<li><span class="row-name">Rap</span><span class="row-value w-1">1</span></li>
					</ul>
				</section>
			</section>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
	</body>
</html>
