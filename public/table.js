const songsPerPage = 10;

let allMusic;
let currentMusic;
let playlistMusic = [];
let currentPage = 0;
fetch("music-truncated.csv")
	.then(res => res.text())
	.then(csv => Papa.parse(csv, { header: true, skipEmptyLines: true }).data.map(song => ({
		id: song.track_id,
		name: song.name,
		artist: song.artist,
		spotify_preview: song.spotify_preview_url,
		tags: formatTags(song.tags),
		genre: song.genre,
		year: song.year,
		duration: msToDuration(Number(song.duration_ms)),
	})))
	.then(songs => {
		allMusic = songs;
		currentMusic = allMusic;
		showTable(currentMusic, 0);
	});

playlistMusic = JSON.parse(localStorage.getItem("playlist")) || [];

function formatTags(tags) {
	tags = tags.split(",");
	tags = tags.map(tag => {
		tag = tag.trim().replace("_", " ");
		tag = tag.charAt(0).toUpperCase() + tag.slice(1);
		return tag;
	});
	return tags.join(", ");
}

function msToDuration(dur_ms) {
	const dur_s = Math.round(dur_ms / 1000);
	const m = Math.round(dur_s / 60);
	const s = Math.round(dur_s % 60);
	return m.toString() + ":" + s.toString().padStart(2, "0");
}

function showTable(music, page) {
	const tbody = document.querySelector("#music-table tbody");
	tbody.innerHTML = "";
	if (music.length === 0) {
		tbody.innerHTML = "<tr><td colspan=\"100%\"><span>No music to show</span></td></tr>"
	}
	const slice = music.slice(page*songsPerPage, (page+1)*songsPerPage);
	let i = 0;
	for (const song of slice) {
		const row = document.createElement("tr");
		row.innerHTML =
			`<td>${song.name}</td>
			 <td>${song.artist}</td>
			 <td><a href="${song.spotify_preview}">Preview</a></td>
			 <td>${song.tags}</td>
			 <td>${song.genre}</td>
			 <td>${song.year}</td>
			 <td>${song.duration}</td>`;
		const thisisstupid = i;
		row.onclick = () => { togglePlaylist(thisisstupid) };
		row.id = `table-row-${i}`;
		if (isInPlaylist(song)) {
			row.classList.add("in-playlist")
		}
		tbody.appendChild(row);
		i += 1;
	}
	fixPageSelect();
}

function filterTable() {
	if (allMusic == undefined) return;
	let filterStr = document.getElementById("music-filters").value;
	let filterStrs = filterStr.split(" ").filter(str => str.length !== 0);
	let music = allMusic;
	let filters = [];
	for (const filter of filterStrs) {
		let [column, value] = filter.split('=', 2);
		if (value.length === 0) continue;
		switch (column.toLowerCase()) {
			case "name": case "title":
				column = "name"
				break;
			case "artist": case "musician": case "creator":
				column = "artist"
				break;
			case "tags": case "tag":
				column = "tags"
				break;
			case "genre":
				column = "genre"
				break;
			case "year":
				column = "year"
				break;
			case "duration":
				column = "duration"
				break;
			default:
				console.error(`Invalid column: ${column}`);
				return;
		}
		filters.push({ column, value });
	}
	for (let filter of filters) {
		music = music.filter(song => song[filter.column].toLowerCase().includes(filter.value.toLowerCase()));
	}
	currentMusic = music;
	currentPage = 0;
	showTable(currentMusic, currentPage);
}

function fixPageSelect() {
	let upButton = document.getElementById("page-up-button");
	if (currentMusic.length > (currentPage+1) * songsPerPage) {
		upButton.disabled = false;
	} else {
		upButton.disabled = true;
	}
	let downButton = document.getElementById("page-down-button");
	if (currentPage !== 0) {
		downButton.disabled = false;
	} else {
		downButton.disabled = true;
	}
	let input = document.getElementById("page-input");
	input.placeholder = currentPage + 1;
}

function gotoPage() {
	let input = document.getElementById("page-input");
	let page = Number(input.value) - 1;
	if (page >= 0 && currentMusic.length > page * songsPerPage) {
		currentPage = page;
		showTable(currentMusic, currentPage);
	}
}

function pageUp() {
	if (currentMusic.length > (currentPage+1) * songsPerPage) {
		currentPage += 1;
		showTable(currentMusic, currentPage);
	}
}

function pageDown() {
	if (currentPage !== 0) {
		currentPage -= 1;
		showTable(currentMusic, currentPage);
	}
}

function showPlaylist() {
	currentMusic = playlistMusic;
	currentPage = 0;
	showTable(currentMusic, 0);
}

function togglePlaylist(n) {
	let songIdx = currentPage*songsPerPage + n;
	let song = currentMusic[songIdx];
	let row = document.getElementById(`table-row-${n}`);
	if (isInPlaylist(song)) {
		row.classList.remove("in-playlist");
		playlistMusic = playlistMusic.filter(arrsong => arrsong.id != song.id);
	} else {
		row.classList.add("in-playlist");
		playlistMusic.push(song);
	}
	localStorage.setItem("playlist", JSON.stringify(playlistMusic));
}

function isInPlaylist(song) {
	if (playlistMusic === undefined) return false;
	return playlistMusic.map(s => s.id).findIndex(id => id === song.id) >= 0;
}
