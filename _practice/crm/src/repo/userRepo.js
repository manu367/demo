const users = require('../util/fakeDB');

// simulate DB call
exports.findByEmail = async (email) => {
    return users.find(u => u.email === email);
};

