const express = require('express');
const path = require('path');
const fs = require('fs');
const app = express();

const PORT = process.env.PORT || 3000;

app.set('view engine', 'ejs');

app.use(express.static(path.join(__dirname, 'public')));
app.get('/', (req, res) => {
	res.send("ERROR: index.html not found");
});

app.get('/slike', (req, res) => {
	const imagesPath = path.join(__dirname, 'public', 'images', 'gallery');
	const files = fs.readdirSync(imagesPath);

	const images = files
		.filter(file => file.endsWith('.jpg') || file.endsWith('.png'))
		.map((file, i) => ({
			url: `/images/gallery/${file}`,
			id: `img-${i+1}`,
			title: `Slika ${i+1}`,
		}));

	res.render('slike', { images });
});

app.listen(3000, () => {
	console.log(`Server started on port ${PORT}`);
});
