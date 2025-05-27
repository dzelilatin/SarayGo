class ReviewService {
    constructor() {
        this.baseUrl = '/api/reviews';
    }

    async getAll() {
        try {
            const response = await fetch(this.baseUrl);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch reviews');
            }
            return data;
        } catch (error) {
            console.error('Get reviews error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch review');
            }
            return data;
        } catch (error) {
            console.error('Get review error:', error);
            throw error;
        }
    }

    async create(reviewData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(reviewData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to create review');
            }
            return data;
        } catch (error) {
            console.error('Create review error:', error);
            throw error;
        }
    }

    async update(id, reviewData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(reviewData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update review');
            }
            return data;
        } catch (error) {
            console.error('Update review error:', error);
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
                throw new Error(data.message || 'Failed to delete review');
            }
            return data;
        } catch (error) {
            console.error('Delete review error:', error);
            throw error;
        }
    }

    async getByUser(userId) {
        try {
            const response = await fetch(`${this.baseUrl}/user/${userId}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch reviews by user');
            }
            return data;
        } catch (error) {
            console.error('Get reviews by user error:', error);
            throw error;
        }
    }

    async getByActivity(activityId) {
        try {
            const response = await fetch(`${this.baseUrl}/activity/${activityId}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch reviews by activity');
            }
            return data;
        } catch (error) {
            console.error('Get reviews by activity error:', error);
            throw error;
        }
    }

    async getByBlog(blogId) {
        try {
            const response = await fetch(`${this.baseUrl}/blog/${blogId}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch reviews by blog');
            }
            return data;
        } catch (error) {
            console.error('Get reviews by blog error:', error);
            throw error;
        }
    }

    async getRecent(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/recent?limit=${limit}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recent reviews');
            }
            return data;
        } catch (error) {
            console.error('Get recent reviews error:', error);
            throw error;
        }
    }

    async getTopRated(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/top-rated?limit=${limit}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch top rated reviews');
            }
            return data;
        } catch (error) {
            console.error('Get top rated reviews error:', error);
            throw error;
        }
    }

    async search(query) {
        try {
            const response = await fetch(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to search reviews');
            }
            return data;
        } catch (error) {
            console.error('Search reviews error:', error);
            throw error;
        }
    }

    async likeReview(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/like`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to like review');
            }
            return data;
        } catch (error) {
            console.error('Like review error:', error);
            throw error;
        }
    }

    async unlikeReview(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/unlike`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to unlike review');
            }
            return data;
        } catch (error) {
            console.error('Unlike review error:', error);
            throw error;
        }
    }
}

// Export the service
window.ReviewService = ReviewService; 