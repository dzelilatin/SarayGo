class UserService {
    constructor() {
        this.baseUrl = '/backend/rest';
    }

    async login(credentials) {
        try {
            const response = await fetch(`${this.baseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(credentials)
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || `Login failed: ${response.statusText}`);
            }

            if (data.data && data.data.token) {
                localStorage.setItem('token', data.data.token);
                localStorage.setItem('userRole', data.data.role || 'user');
                return data.data;
            } else {
                throw new Error('No token received from server');
            }
        } catch (error) {
            console.error('Login error:', error);
            throw error;
        }
    }

    async register(userData) {
        try {
            const response = await fetch(`${this.baseUrl}/auth/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            });

            if (!response.ok) {
                throw new Error(`Registration failed: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Registration error:', error);
            throw error;
        }
    }

    logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('userRole');
    }

    async getCurrentUser() {
        try {
            const response = await fetch(`${this.baseUrl}/users/me`, {
                headers: {
                    'Authorization': `Bearer ${this.getToken()}`
                }
            });

            if (!response.ok) {
                throw new Error(`Failed to get current user: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Get current user error:', error);
            throw error;
        }
    }

    async updateProfile(userData) {
        try {
            const response = await fetch(`${this.baseUrl}/users/profile`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.getToken()}`
                },
                body: JSON.stringify(userData)
            });

            if (!response.ok) {
                throw new Error(`Profile update failed: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Profile update error:', error);
            throw error;
        }
    }

    async changePassword(currentPassword, newPassword) {
        try {
            const response = await fetch(`${this.baseUrl}/users/change-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.getToken()}`
                },
                body: JSON.stringify({
                    currentPassword,
                    newPassword
                })
            });

            if (!response.ok) {
                throw new Error(`Password change failed: ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Password change error:', error);
            throw error;
        }
    }

    isAuthenticated() {
        return !!this.getToken();
    }

    getUserRole() {
        return localStorage.getItem('userRole');
    }

    hasPermission(permission) {
        const role = this.getUserRole();
        // Implement permission checking logic based on role
        return role === 'admin'; // Simplified for now
    }

    getToken() {
        return localStorage.getItem('token');
    }
}

// Export the service
window.UserService = UserService; 