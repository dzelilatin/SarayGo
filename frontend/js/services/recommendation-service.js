class RecommendationService {
    constructor() {
        this.baseUrl = '/api/recommendations';
    }

    async getAll() {
        try {
            const response = await fetch(this.baseUrl);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recommendations');
            }
            return data;
        } catch (error) {
            console.error('Get recommendations error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recommendation');
            }
            return data;
        } catch (error) {
            console.error('Get recommendation error:', error);
            throw error;
        }
    }

    async create(recommendationData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(recommendationData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to create recommendation');
            }
            return data;
        } catch (error) {
            console.error('Create recommendation error:', error);
            throw error;
        }
    }

    async update(id, recommendationData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(recommendationData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update recommendation');
            }
            return data;
        } catch (error) {
            console.error('Update recommendation error:', error);
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
                throw new Error(data.message || 'Failed to delete recommendation');
            }
            return data;
        } catch (error) {
            console.error('Delete recommendation error:', error);
            throw error;
        }
    }

    async getByCategory(categoryId) {
        try {
            const response = await fetch(`${this.baseUrl}/category/${categoryId}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recommendations by category');
            }
            return data;
        } catch (error) {
            console.error('Get recommendations by category error:', error);
            throw error;
        }
    }

    async getByUser(userId) {
        try {
            const response = await fetch(`${this.baseUrl}/user/${userId}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recommendations by user');
            }
            return data;
        } catch (error) {
            console.error('Get recommendations by user error:', error);
            throw error;
        }
    }

    async getPopular(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/popular?limit=${limit}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch popular recommendations');
            }
            return data;
        } catch (error) {
            console.error('Get popular recommendations error:', error);
            throw error;
        }
    }

    async getRecent(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/recent?limit=${limit}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recent recommendations');
            }
            return data;
        } catch (error) {
            console.error('Get recent recommendations error:', error);
            throw error;
        }
    }

    async search(query) {
        try {
            const response = await fetch(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to search recommendations');
            }
            return data;
        } catch (error) {
            console.error('Search recommendations error:', error);
            throw error;
        }
    }

    async getRecommendationWithDetails(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/details`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recommendation details');
            }
            return data;
        } catch (error) {
            console.error('Get recommendation details error:', error);
            throw error;
        }
    }

    async incrementViews(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/views`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to increment views');
            }
            return data;
        } catch (error) {
            console.error('Increment views error:', error);
            throw error;
        }
    }
}

// Export the service
window.RecommendationService = RecommendationService; 