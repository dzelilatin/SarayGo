class BlogService {
    constructor() {
        this.baseUrl = '/backend/rest/blogs';
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
                throw new Error(`Failed to fetch blogs: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get all blogs error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch blog: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get blog by ID error:', error);
            throw error;
        }
    }

    async create(blogData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...this.getAuthHeaders()
                },
                body: JSON.stringify(blogData)
            });
            if (!response.ok) {
                throw new Error(`Failed to create blog: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Create blog error:', error);
            throw error;
        }
    }

    async update(id, blogData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    ...this.getAuthHeaders()
                },
                body: JSON.stringify(blogData)
            });
            if (!response.ok) {
                throw new Error(`Failed to update blog: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Update blog error:', error);
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
                throw new Error(`Failed to delete blog: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Delete blog error:', error);
            throw error;
        }
    }

    async getByCategory(categoryId) {
        try {
            const response = await fetch(`${this.baseUrl}/category/${categoryId}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch blogs by category: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get blogs by category error:', error);
            throw error;
        }
    }

    async getByAuthor(authorId) {
        try {
            const response = await fetch(`${this.baseUrl}/author/${authorId}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch blogs by author: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get blogs by author error:', error);
            throw error;
        }
    }

    async search(query) {
        try {
            const response = await fetch(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to search blogs: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Search blogs error:', error);
            throw error;
        }
    }

    async getPopular(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/popular?limit=${limit}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch popular blogs: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get popular blogs error:', error);
            throw error;
        }
    }

    async getRecent(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/recent?limit=${limit}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch recent blogs: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get recent blogs error:', error);
            throw error;
        }
    }

    async incrementViews(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/views`, {
                method: 'POST',
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to increment views: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Increment views error:', error);
            throw error;
        }
    }
}

// Export the service
window.BlogService = BlogService; 