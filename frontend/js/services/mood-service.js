class MoodService {
    constructor() {
        this.baseUrl = '/api/moods';
    }

    async getAll() {
        try {
            const response = await fetch(this.baseUrl);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch moods');
            }
            return data;
        } catch (error) {
            console.error('Get moods error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch mood');
            }
            return data;
        } catch (error) {
            console.error('Get mood error:', error);
            throw error;
        }
    }

    async create(moodData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(moodData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to create mood');
            }
            return data;
        } catch (error) {
            console.error('Create mood error:', error);
            throw error;
        }
    }

    async update(id, moodData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(moodData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update mood');
            }
            return data;
        } catch (error) {
            console.error('Update mood error:', error);
            throw error;
        }
    }

    async delete(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to delete mood');
            }
            return data;
        } catch (error) {
            console.error('Delete mood error:', error);
            throw error;
        }
    }

    async getByCategory(categoryId) {
        try {
            const response = await fetch(`${this.baseUrl}/category/${categoryId}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch moods by category');
            }
            return data;
        } catch (error) {
            console.error('Get moods by category error:', error);
            throw error;
        }
    }

    async getPopular(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/popular?limit=${limit}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch popular moods');
            }
            return data;
        } catch (error) {
            console.error('Get popular moods error:', error);
            throw error;
        }
    }

    async getRecent(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/recent?limit=${limit}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recent moods');
            }
            return data;
        } catch (error) {
            console.error('Get recent moods error:', error);
            throw error;
        }
    }

    async search(query) {
        try {
            const response = await fetch(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to search moods');
            }
            return data;
        } catch (error) {
            console.error('Search moods error:', error);
            throw error;
        }
    }

    async getMoodWithActivities(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/activities`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch mood with activities');
            }
            return data;
        } catch (error) {
            console.error('Get mood with activities error:', error);
            throw error;
        }
    }
}

// Export the service
window.MoodService = MoodService; 