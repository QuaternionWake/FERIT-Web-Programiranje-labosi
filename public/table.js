const songsPerPage = 10;

let music;
let playlistMusic = [];
let currentPage = 0;
fetch("get-music.php")
	.then(res => res.json())
	.then(json => json.map(song => ({
		id: song.id,
		name: song.name,
		artist: song.artist,
		spotify_preview: song.spotify_preview,
		tags: formatTags(song.tags),
		genre: song.genre,
		year: song.year,
		duration: sToDuration(Number(song.duration)),
	})))
	.then(songs => {
		music = songs;
		fetch("playlist.php")
			.then(res => res.json())
			.then(json => json.map(song_id =>
				music.find(d => d.id == song_id.song_id)
			))
			.then(playlist => {
				playlistMusic = playlist;
				showTable(music, 0);
			})
	});

function formatTags(tags) {
	tags = tags.split(",");
	tags = tags.map(tag => {
		tag = tag.trim().replace("_", " ");
		tag = tag.charAt(0).toUpperCase() + tag.slice(1);
		return tag;
	});
	return tags.join(", ");
}

function sToDuration(dur_s) {
	const m = Math.trunc(dur_s / 60);
	const s = Math.trunc(dur_s % 60);
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
			 ${song.spotify_preview
			 	? `<td><a href="${song.spotify_preview}">Preview</a></td>`
			 	: `<td>No preview</td>`}
			 <td>${song.tags}</td>
			 <td>${song.genre}</td>
			 <td>${song.year}</td>
			 <td>${song.duration}</td>`;
		if (is_admin) {
			row.innerHTML +=
				`<td><a onclick="event.stopPropagation()" href="edit-song.php?song-id=${song.id}">Edit</a></td>
				 <td><a onclick="event.stopPropagation()" href="delete-song.php?song-id=${song.id}">Delete</a></td>`;
		}
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
	if (music == undefined) return;
	let filterStr = document.getElementById("music-filters").value;
	let filterStrs = filterStr.split(" ").filter(str => str.length !== 0);
	let urlStr = "";
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
		urlStr += `&${column}=${value}`;
	}
	if (urlStr.length != 0) {
		urlStr = "?" + urlStr.slice(1);
	}

	fetch("get-music.php" + urlStr)
		.then(res => res.json())
		.then(json => json.map(song => ({
			id: song.id,
			name: song.name,
			artist: song.artist,
			spotify_preview: song.spotify_preview_url,
			tags: formatTags(song.tags),
			genre: song.genre,
			year: song.year,
			duration: sToDuration(Number(song.duration)),
		})))
		.then(songs => {
			music = songs;
			showTable(music, 0);
		});
}

function fixPageSelect() {
	let upButton = document.getElementById("page-up-button");
	if (music.length > (currentPage+1) * songsPerPage) {
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
	if (page >= 0 && music.length > page * songsPerPage) {
		currentPage = page;
		showTable(music, currentPage);
	}
}

function pageUp() {
	if (music.length > (currentPage+1) * songsPerPage) {
		currentPage += 1;
		showTable(music, currentPage);
	}
}

function pageDown() {
	if (currentPage !== 0) {
		currentPage -= 1;
		showTable(music, currentPage);
	}
}

function showPlaylist() {
	music = playlistMusic;
	currentPage = 0;
	showTable(music, 0);
}

function togglePlaylist(n) {
	let songIdx = currentPage*songsPerPage + n;
	let song = music[songIdx];
	let row = document.getElementById(`table-row-${n}`);
	if (isInPlaylist(song)) {
		row.classList.remove("in-playlist");
		playlistMusic = playlistMusic.filter(arrsong => arrsong.id != song.id);
		console.log(`playlist.php?song-id=${song.id}`)
		fetch(`playlist.php?song-id=${song.id}`, { method: "DELETE"});
	} else {
		row.classList.add("in-playlist");
		playlistMusic.push(song);
		console.log(`playlist.php?song-id=${song.id}`)
		fetch(`playlist.php?song-id=${song.id}`, { method: "POST"});
	}
}

function isInPlaylist(song) {
	if (playlistMusic === undefined) return false;
	return playlistMusic.map(s => s.id).findIndex(id => id === song.id) >= 0;
}
