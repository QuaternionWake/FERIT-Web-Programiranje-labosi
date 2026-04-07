const express = require('express');
const path = require('path');
const app = express();

const PORT = process.env.PORT || 3000;

app.use(express.static(path.join(__dirname, 'public')));
app.get('/', (req, res) => {
	res.send("ERROR: index.html not found");
});

app.listen(3000, () => {
	console.log(`Server started on port ${PORT}`);
});
