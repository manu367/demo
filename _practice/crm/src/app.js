const express = require('express');
const app = express();
const port = 3000;

const authRoutes = require('./route/AuthRoute');

// app.use(express.json());


app.use('/auth', authRoutes);

app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`);
});