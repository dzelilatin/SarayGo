class UIService {
    constructor() {
        this.userService = new UserService();
        this.currentUser = null;
        this.userRole = null;
        this.isAuthenticated = false;
    }

    async initialize() {
        try {
            if (this.userService.isAuthenticated()) {
                this.currentUser = await this.userService.getCurrentUser();
                this.userRole = this.userService.getUserRole();
                this.isAuthenticated = true;
            }
            this.updateUI();
        } catch (error) {
            console.error('UI initialization error:', error);
            this.handleLogout();
        }
    }

    async handleLogin(userData) {
        try {
            this.currentUser = userData;
            this.userRole = userData.role || this.userService.getUserRole();
            this.isAuthenticated = true;
            await this.updateUI();
        } catch (error) {
            console.error('Login handling error:', error);
            this.handleLogout();
        }
    }

    handleLogout() {
        this.currentUser = null;
        this.userRole = null;
        this.isAuthenticated = false;
        this.updateUI();
    }

    updateUI() {
        this.updateNavigation();
        this.updateDashboard();
        this.updateAdminPanel();
        this.updateUserSpecificElements();
    }

    updateNavigation() {
        const authLinks = document.querySelectorAll('[data-auth-required]');
        const guestLinks = document.querySelectorAll('[data-guest-only]');
        const adminLinks = document.querySelectorAll('[data-admin-only]');
        const userLinks = document.querySelectorAll('[data-user-only]');

        authLinks.forEach(link => {
            link.style.display = this.isAuthenticated ? 'block' : 'none';
        });

        guestLinks.forEach(link => {
            link.style.display = this.isAuthenticated ? 'none' : 'block';
        });

        adminLinks.forEach(link => {
            link.style.display = this.userRole === 'admin' ? 'block' : 'none';
        });

        userLinks.forEach(link => {
            link.style.display = this.isAuthenticated ? 'block' : 'none';
        });

        // Update user profile elements
        const userProfileElements = document.querySelectorAll('[data-user-profile]');
        userProfileElements.forEach(element => {
            if (this.currentUser) {
                element.textContent = this.currentUser.name || this.currentUser.email;
            }
        });
    }

    updateDashboard() {
        const dashboard = document.getElementById('dashboard');
        if (!dashboard) return;

        if (this.isAuthenticated) {
            this.loadPersonalizedDashboard();
        } else {
            dashboard.innerHTML = '<p>Please log in to view your personalized dashboard.</p>';
        }
    }

    async loadPersonalizedDashboard() {
        const dashboard = document.getElementById('dashboard');
        if (!dashboard) return;

        try {
            // Load user-specific data
            const [recentActivities, recentBlogs, recommendations] = await Promise.all([
                this.loadRecentActivities(),
                this.loadRecentBlogs(),
                this.loadPersonalizedRecommendations()
            ]);

            dashboard.innerHTML = `
                <div class="dashboard-container">
                    <div class="dashboard-header">
                        <h2>Welcome, ${this.currentUser.name || 'User'}</h2>
                    </div>
                    <div class="dashboard-content">
                        <div class="dashboard-section">
                            <h3>Recent Activities</h3>
                            ${this.renderActivitiesList(recentActivities)}
                        </div>
                        <div class="dashboard-section">
                            <h3>Recent Blogs</h3>
                            ${this.renderBlogsList(recentBlogs)}
                        </div>
                        <div class="dashboard-section">
                            <h3>Recommended for You</h3>
                            ${this.renderRecommendationsList(recommendations)}
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Dashboard loading error:', error);
            dashboard.innerHTML = '<p>Error loading dashboard. Please try again later.</p>';
        }
    }

    updateAdminPanel() {
        const adminPanel = document.getElementById('admin-panel');
        if (!adminPanel) return;

        if (this.userRole === 'admin') {
            this.loadAdminPanel();
        } else {
            adminPanel.style.display = 'none';
        }
    }

    async loadAdminPanel() {
        const adminPanel = document.getElementById('admin-panel');
        if (!adminPanel) return;

        try {
            // Load admin-specific data
            const [unreadMessages, recentUsers, systemStats] = await Promise.all([
                this.loadUnreadMessages(),
                this.loadRecentUsers(),
                this.loadSystemStats()
            ]);

            adminPanel.innerHTML = `
                <div class="admin-panel-container">
                    <div class="admin-header">
                        <h2>Admin Dashboard</h2>
                    </div>
                    <div class="admin-content">
                        <div class="admin-section">
                            <h3>Unread Messages</h3>
                            ${this.renderMessagesList(unreadMessages)}
                        </div>
                        <div class="admin-section">
                            <h3>Recent Users</h3>
                            ${this.renderUsersList(recentUsers)}
                        </div>
                        <div class="admin-section">
                            <h3>System Statistics</h3>
                            ${this.renderSystemStats(systemStats)}
                        </div>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Admin panel loading error:', error);
            adminPanel.innerHTML = '<p>Error loading admin panel. Please try again later.</p>';
        }
    }

    updateUserSpecificElements() {
        // Update elements that should only be visible to authenticated users
        const userSpecificElements = document.querySelectorAll('[data-user-specific]');
        userSpecificElements.forEach(element => {
            element.style.display = this.isAuthenticated ? 'block' : 'none';
        });

        // Update elements that should only be visible to admins
        const adminSpecificElements = document.querySelectorAll('[data-admin-specific]');
        adminSpecificElements.forEach(element => {
            element.style.display = this.userRole === 'admin' ? 'block' : 'none';
        });
    }

    // Helper methods for loading data
    async loadRecentActivities() {
        const activityService = new ActivityService();
        return await activityService.getRecent(5);
    }

    async loadRecentBlogs() {
        const blogService = new BlogService();
        return await blogService.getRecent(5);
    }

    async loadPersonalizedRecommendations() {
        const recommendationService = new RecommendationService();
        return await recommendationService.getRecent(5);
    }

    async loadUnreadMessages() {
        const contactService = new ContactService();
        return await contactService.getUnreadMessages();
    }

    async loadRecentUsers() {
        const userService = new UserService();
        return await userService.getRecent(5);
    }

    async loadSystemStats() {
        // Implement system statistics loading
        return {
            totalUsers: 0,
            totalActivities: 0,
            totalBlogs: 0,
            totalReviews: 0
        };
    }

    // Helper methods for rendering lists
    renderActivitiesList(activities) {
        if (!activities || activities.length === 0) {
            return '<p>No recent activities found.</p>';
        }

        return `
            <ul class="list-group">
                ${activities.map(activity => `
                    <li class="list-group-item">
                        <h4>${activity.title}</h4>
                        <p>${activity.description}</p>
                    </li>
                `).join('')}
            </ul>
        `;
    }

    renderBlogsList(blogs) {
        if (!blogs || blogs.length === 0) {
            return '<p>No recent blogs found.</p>';
        }

        return `
            <ul class="list-group">
                ${blogs.map(blog => `
                    <li class="list-group-item">
                        <h4>${blog.title}</h4>
                        <p>${blog.excerpt || blog.content.substring(0, 100)}...</p>
                    </li>
                `).join('')}
            </ul>
        `;
    }

    renderRecommendationsList(recommendations) {
        if (!recommendations || recommendations.length === 0) {
            return '<p>No recommendations found.</p>';
        }

        return `
            <ul class="list-group">
                ${recommendations.map(recommendation => `
                    <li class="list-group-item">
                        <h4>${recommendation.title}</h4>
                        <p>${recommendation.description}</p>
                    </li>
                `).join('')}
            </ul>
        `;
    }

    renderMessagesList(messages) {
        if (!messages || messages.length === 0) {
            return '<p>No unread messages.</p>';
        }

        return `
            <ul class="list-group">
                ${messages.map(message => `
                    <li class="list-group-item">
                        <h4>From: ${message.name}</h4>
                        <p>${message.message}</p>
                        <small>${new Date(message.created_at).toLocaleString()}</small>
                    </li>
                `).join('')}
            </ul>
        `;
    }

    renderUsersList(users) {
        if (!users || users.length === 0) {
            return '<p>No recent users found.</p>';
        }

        return `
            <ul class="list-group">
                ${users.map(user => `
                    <li class="list-group-item">
                        <h4>${user.name}</h4>
                        <p>${user.email}</p>
                        <small>Joined: ${new Date(user.created_at).toLocaleString()}</small>
                    </li>
                `).join('')}
            </ul>
        `;
    }

    renderSystemStats(stats) {
        return `
            <div class="stats-container">
                <div class="stat-item">
                    <h4>Total Users</h4>
                    <p>${stats.totalUsers}</p>
                </div>
                <div class="stat-item">
                    <h4>Total Activities</h4>
                    <p>${stats.totalActivities}</p>
                </div>
                <div class="stat-item">
                    <h4>Total Blogs</h4>
                    <p>${stats.totalBlogs}</p>
                </div>
                <div class="stat-item">
                    <h4>Total Reviews</h4>
                    <p>${stats.totalReviews}</p>
                </div>
            </div>
        `;
    }
}

// Export the service
window.UIService = UIService; 