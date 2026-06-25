// =============================================
// AUTH SYSTEM
// =============================================

const Auth = {
    login(username, password) {
        const users = DB.get(DB.keys.users);
        const user = users.find(u => 
            u.username === username && 
            u.password === btoa(password)
        );
        
        if (user) {
            const session = {
                user_id: user.id,
                username: user.username,
                display_name: user.display_name,
                role: user.role,
                logged_in: true,
                login_time: new Date().toISOString()
            };
            localStorage.setItem('cms_session', JSON.stringify(session));
            return { success: true, user };
        }
        return { success: false, message: 'نام کاربری یا رمز عبور اشتباه است!' };
    },

    logout() {
        localStorage.removeItem('cms_session');
        window.location.href = 'login.html';
    },

    isLoggedIn() {
        const session = this.getSession();
        return session && session.logged_in === true;
    },

    getSession() {
        try {
            return JSON.parse(localStorage.getItem('cms_session'));
        } catch {
            return null;
        }
    },

    getCurrentUser() {
        const session = this.getSession();
        if (!session) return null;
        return DB.getById(DB.keys.users, session.user_id);
    },

    hasRole(role) {
        const user = this.getCurrentUser();
        if (!user) return false;
        if (user.role === 'admin') return true;
        return user.role === role;
    },

    getUsers() {
        return DB.get(DB.keys.users);
    },

    createUser(data) {
        const users = this.getUsers();
        if (users.find(u => u.username === data.username)) {
            return { success: false, message: 'نام کاربری تکراری است!' };
        }
        if (users.find(u => u.email === data.email)) {
            return { success: false, message: 'ایمیل تکراری است!' };
        }
        const newUser = DB.create(DB.keys.users, {
            ...data,
            password: btoa(data.password)
        });
        return { success: true, user: newUser };
    },

    deleteUser(id) {
        const users = this.getUsers();
        const admins = users.filter(u => u.role === 'admin');
        if (admins.length === 1 && admins[0].id === id) {
            return { success: false, message: 'نمی‌توان آخرین ادمین را حذف کرد!' };
        }
        return DB.delete(DB.keys.users, id);
    }
};

window.Auth = Auth;