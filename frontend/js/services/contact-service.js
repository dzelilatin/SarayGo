class ContactService {
    constructor() {
        this.baseUrl = '/api/contacts';
    }

    async getAll() {
        try {
            const response = await fetch(this.baseUrl, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch contact messages');
            }
            return data;
        } catch (error) {
            console.error('Get contact messages error:', error);
            throw error;
        }
    }

    async getById(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch contact message');
            }
            return data;
        } catch (error) {
            console.error('Get contact message error:', error);
            throw error;
        }
    }

    async create(contactData) {
        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(contactData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to send contact message');
            }
            return data;
        } catch (error) {
            console.error('Send contact message error:', error);
            throw error;
        }
    }

    async update(id, contactData) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(contactData)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update contact message');
            }
            return data;
        } catch (error) {
            console.error('Update contact message error:', error);
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
                throw new Error(data.message || 'Failed to delete contact message');
            }
            return data;
        } catch (error) {
            console.error('Delete contact message error:', error);
            throw error;
        }
    }

    async getByEmail(email) {
        try {
            const response = await fetch(`${this.baseUrl}/email/${encodeURIComponent(email)}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch contact messages by email');
            }
            return data;
        } catch (error) {
            console.error('Get contact messages by email error:', error);
            throw error;
        }
    }

    async getUnreadMessages() {
        try {
            const response = await fetch(`${this.baseUrl}/unread`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch unread messages');
            }
            return data;
        } catch (error) {
            console.error('Get unread messages error:', error);
            throw error;
        }
    }

    async markAsRead(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/read`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to mark message as read');
            }
            return data;
        } catch (error) {
            console.error('Mark message as read error:', error);
            throw error;
        }
    }

    async markAsUnread(id) {
        try {
            const response = await fetch(`${this.baseUrl}/${id}/unread`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to mark message as unread');
            }
            return data;
        } catch (error) {
            console.error('Mark message as unread error:', error);
            throw error;
        }
    }

    async getRecentMessages(limit = 10) {
        try {
            const response = await fetch(`${this.baseUrl}/recent?limit=${limit}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Failed to fetch recent messages');
            }
            return data;
        } catch (error) {
            console.error('Get recent messages error:', error);
            throw error;
        }
    }
}

// Export the service
window.ContactService = ContactService; 