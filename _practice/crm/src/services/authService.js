const userRepo = require('../repo/userRepo');

exports.login = async (data) => {
    const email = data.email;
    const password = data.password;
    if (!email || !password) {
        throw new Error('Email and password required');
    }
    const user = await userRepo.findByEmail(email);
    if (!user) {
        throw new Error('User not found');
    }
    if (user.password !== password) {
        throw new Error('Invalid credentials');
    }
    return {
        message: 'Login successful',
        user: {
            id: user.id,
            email: user.email
        }
    };
};