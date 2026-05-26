<?php include "auth.php" ?>
<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="My awesome website about music">
		<link rel="stylesheet" href="/styles/style.css">
		<link rel="stylesheet" href="/styles/style_slike.css">
		<title>Stranica O Glazbi</title>
	</head>

	<body>
		<?php include "./include/header.php" ?>
		<?php include "./include/navbar.php" ?>

		<section class="galerija">
			<h1>Galerija slika</h1>
			<div class="img-gallery-magnific">
				<?php
					include "db_conn.php";
					$result = $conn->query("SELECT image_id, AVG(rating) FROM image_ratings GROUP BY image_id");
					$ratings = [];
					while ($row = $result->fetch_assoc()) {
						$ratings[$row["image_id"]] = sprintf("%.2f", $row["AVG(rating)"]);
					}
					$dirpath = "images/gallery/";
					$pics = scandir($dirpath);
					$i = 1;
					foreach ($pics as $pic) {
						if (!(str_ends_with($pic, ".jpg") or str_ends_with($pic, ".jpeg") or str_ends_with($pic, ".png"))) continue;
						$r = $ratings["$i"] ?? "N/A";
						echo '<figure class="galerija_slika magnific-img">';
						echo '  <button popovertarget="img-'.$i.'">';
						echo '    <img class="image image-popup-vertical-fit" title="Slika '.$i.'" src="/images/gallery/'.$pic.'" alt="Slika '.$i.'">';
						echo '  </button>';
						echo '  <figcaption>Slika '.$i.'</figcaption>';
						echo '  <div id="rating-'.$i.'" class="flex-row">';
						echo '    <span class="rating">'.$r,'</span>';
						echo '    <span class="star r-5 r-4 r-3 r-2 r-1" onclick="rateImage('.$i.', 1)">★</span>';
						echo '    <span class="star r-5 r-4 r-3 r-2" onclick="rateImage('.$i.', 2)">★</span>';
						echo '    <span class="star r-5 r-4 r-3" onclick="rateImage('.$i.', 3)">★</span>';
						echo '    <span class="star r-5 r-4" onclick="rateImage('.$i.', 4)">★</span>';
						echo '    <span class="star r-5" onclick="rateImage('.$i.', 5)">★</span>';
						echo '    <span class="unrate" onclick="unrateImage('.$i.')">Unrate</span>';
						echo '  </div>';
						echo '</figure>';
						echo '<dialog id="img-'.$i.'" popover>';
						echo '  <div class="lightbox">';
						echo '    <a class="image-popup-vertical-fit" href="/images/gallery/'.$pic.'" title="Slika '.$i.'">';
						echo '      <img src="/images/gallery/'.$pic.'" alt="Slika '.$i.'">';
						echo '    </a>';
						echo '    <span class="caption">Slika '.$i.'</span>';
						echo '    <button popovertarget="img-'.$i.'" popovertargetaction="hide">Close</button>';
						echo '  </div>';
						echo '</dialog>';
						$i += 1;
					}
					$conn->close();
				?>
			</div>

		</section>
		<footer>
			<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
		<script src="/slike.js"></script>
	</body>
</html>
