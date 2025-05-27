class CategoryService {
    constructor() {
        this.baseUrl = '/backend/rest/categories';
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
                throw new Error(`Failed to fetch categories: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get all categories error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch category: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get category by ID error:', error);
            throw error;
        }
    }

    async create(categoryData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...this.getAuthHeaders()
                },
                body: JSON.stringify(categoryData)
            });
            if (!response.ok) {
                throw new Error(`Failed to create category: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Create category error:', error);
            throw error;
        }
    }

    async update(id, categoryData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    ...this.getAuthHeaders()
                },
                body: JSON.stringify(categoryData)
            });
            if (!response.ok) {
                throw new Error(`Failed to update category: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Update category error:', error);
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
                throw new Error(`Failed to delete category: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Delete category error:', error);
            throw error;
        }
    }

    async getSubcategories(parentId) {
        try {
            const response = await fetch(`${this.baseUrl}/${parentId}/subcategories`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch subcategories: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get subcategories error:', error);
            throw error;
        }
    }

    async getCategoryTree() {
        try {
            const response = await fetch(`${this.baseUrl}/tree`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch category tree: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get category tree error:', error);
            throw error;
        }
    }

    async getWithBlogCount() {
        try {
            const response = await fetch(`${this.baseUrl}/with-blog-count`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch categories with blog count: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get categories with blog count error:', error);
            throw error;
        }
    }

    async getWithActivityCount() {
        try {
            const response = await fetch(`${this.baseUrl}/with-activity-count`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to fetch categories with activity count: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Get categories with activity count error:', error);
            throw error;
        }
    }

    async search(query) {
        try {
            const response = await fetch(`${this.baseUrl}/search?q=${encodeURIComponent(query)}`, {
                headers: this.getAuthHeaders()
            });
            if (!response.ok) {
                throw new Error(`Failed to search categories: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Search categories error:', error);
            throw error;
        }
    }
}

// Export the service
window.CategoryService = CategoryService; 