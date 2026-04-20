const express = require('express');
const router = express.Router();

const authService = require('../services/authService');


router.get('/login', async (req, res) => {
    try {
        const result = await authService.login(req.query);
        res.json(result);
    } catch (err) {
        res.status(400).json({ message: err.message });
    }
});

module.exports = router;