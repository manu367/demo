const User = require('../model/User');

const users = [
    new User(1, 'test@example.com', '1234'),
    new User(2, 'manu@example.com', 'abcd')
];

module.exports = users;