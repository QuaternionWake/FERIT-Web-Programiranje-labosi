fetch("rate-image.php")
	.then(res => res.json())
	.then(json => json.forEach(d => {
		setClasses(d.image_id, d.rating);
	}));

function rateImage(id, rating) {
	fetch(`rate-image.php?image-id=${id}&rating=${rating}`, {method: "PUT"})
	setClasses(id, rating);
}

function unrateImage(id) {
	fetch(`rate-image.php?image-id=${id}`, {method: "DELETE"})
	setClasses(id, 0);
}

function setClasses(id, rating) {
	const ratingLine = document.getElementById(`rating-${id}`);
	for (const star of ratingLine.getElementsByClassName("star")) {
		star.classList.remove("rated");
	}
	for (const star of ratingLine.getElementsByClassName(`r-${rating}`)) {
		star.classList.add("rated");
	}
}
