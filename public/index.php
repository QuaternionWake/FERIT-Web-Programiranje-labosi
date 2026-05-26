<?php include "auth.php" ?>
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
		<?php include "./include/header.php" ?>
		<?php include "./include/navbar.php" ?>

		<main>
			<section aria-label="Main">
				<h1>Music info</h1>
				<div class="horizontal-vertical">
					<div class="flex-col">
						<div class="flex-row almost-max-width">
							<input id="music-filters" type="text">
							<button style="width: 10em" onclick="filterTable()">Filter</button>
							<button style="width: 15em" onclick="showPlaylist()">Show playlist</button>
						</div>
						<table id="music-table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Artist</th>
									<th>Spotify Preview</th>
									<th>Tags</th>
									<th>Genre</th>
									<th>Year</th>
									<th>Duration</th>
									<?php if ($is_admin): ?>
										<th>Edit</th>
										<th>Delete</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<div class="flex-row">
							<button id="page-down-button" onclick="pageDown()" style="width: 3em">&lt;</button>
							<input id="page-input" type="text" style="width: 5em">
							<button onclick="gotoPage()" style="width: 10em">Go to page</button>
							<button id="page-up-button" onclick="pageUp()" style="width: 3em">&gt;</button>
						</div>
					</div>
					<aside>
						<h1>A picture!</h1>
						<picture>
							<source media="(max-width: 786px)" srcset="/images/music-1.jpg">
							<img id="side-image" src="/images/music-2.jpg" alt="A picture of music">
						</picture>
					</aside>
				</div>
			</section>
		</main>

		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
		<script>const is_admin = <?php echo $is_admin ? "true" : "false"; ?></script>
		<script src="/table.js"></script>
	</body>
</html>
