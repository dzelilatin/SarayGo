class ActivityService {
    constructor() {
        this.baseUrl = '/backend/rest/activities';
    }

    getAuthHeaders() {
        return {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        };
    }

    async getAll() {
        try {
            const response = await fetch(this.baseUrl, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch activities: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get all activities error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch activity: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get activity by ID error:', error);
            throw error;
        }
    }

    async create(activityData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...this.getAuthHeaders()
                },
                body: JSON.stringify(activityData)
            });
            if (!response.ok) {
                throw new Error(`Failed to create activity: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Create activity error:', error);
            throw error;
        }
    }

    async update(id, activityData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    ...this.getAuthHeaders()
                },
                body: JSON.stringify(activityData)
            });
            if (!response.ok) {
                throw new Error(`Failed to update activity: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Update activity error:', error);
            throw error;
        }
    }

    async delete(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'DELETE',
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to delete activity: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Delete activity error:', error);
            throw error;
        }
    }

    async getByCategory(categoryId) {
        try {
            const response = await fetch(`${this.baseUrl}/category/${categoryId}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch activities by category: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get activities by category error:', error);
            throw error;
        }
    }

    async search(query) {
        try {
            const response = await fetch(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to search activities: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Search activities error:', error);
            throw error;
        }
    }

    async getPopular(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/popular?limit=${limit}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch popular activities: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get popular activities error:', error);
            throw error;
        }
    }

    async getRecent(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/recent?limit=${limit}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch recent activities: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get recent activities error:', error);
            throw error;
        }
    }

    async getByMood(moodId) {
        try {
            const response = await fetch(`${this.baseUrl}/mood/${moodId}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch activities by mood: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get activities by mood error:', error);
            throw error;
        }
    }
}

// Export the service
window.ActivityService = ActivityService; 