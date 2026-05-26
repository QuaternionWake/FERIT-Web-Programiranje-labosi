<?php
	$name = $_POST["name"];
	if (!$name) { $message = "Name is missing"; goto END; }
	$artist = $_POST["artist"];
	if (!$artist) { $message = "Artist is missing"; goto END; }
	$spotify_preview = $_POST["spotify-preview"] ?? "";
	$tags = $_POST["tags"] ?? "";
	$genre = $_POST["genre"] ?? "";
	$year = $_POST["year"] ?? "";
	if ($year !== "") {
		if (is_numeric($year)) {
			$year = intval($year);
			if ($year < 1900 or $year > 2030) {
				$message = "Year is out of bounds"; goto END;
			}
		} else { $message = "Year must be a number"; goto END; }
	}
	$duration = $_POST["duration"] ?? "";
	if ($duration !== "") {
		$chunks = explode(":", $duration);
		switch (count($chunks)) {
		case 1:
			if (is_numeric($chunks[0])) {
				$duration = intval($chunks[0]);
			} else { $message = "Invalid time format"; goto END; }
		break;
		case 2:
			if (is_numeric($chunks[1])) {
				$duration = intval($chunks[1]);
			} else { $message = "Invalid time format"; goto END; }
			if (is_numeric($chunks[0])) {
				$duration += 60 * intval($chunks[0]);
			} else { $message = "Invalid time format"; goto END; }
		break;
		case 3:
			if (is_numeric($chunks[2])) {
				$duration = intval($chunks[2]);
			} else { $message = "Invalid time format"; goto END; }
			if (is_numeric($chunks[1])) {
				$duration += 60 * intval($chunks[1]);
			} else { $message = "Invalid time format"; goto END; }
			if (is_numeric($chunks[0])) {
				$duration += 60 * 60 * intval($chunks[0]);
			} else { $message = "Invalid time format"; goto END; }
		break;
		default:
			$message = "Invalid time format";
			goto END;
		}
		if ($duration < 20 or $duration > 60 * 60 * 24) {
			$message = "Duration is out of bounds"; goto END;
		}
	}
	END:
?>
