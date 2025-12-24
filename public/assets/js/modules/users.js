/**
 * Users Management JavaScript Module for SafarStep
 * Handles user CRUD operations, filtering, and role management
 */

// Users Component Functions
window.usersComponent = {
    // Mock users data
    users: [
        {
            id: 1,
            name: 'Sarah Johnson',
            email: 'sarah.johnson@company.com',
            firstName: 'Sarah',
            lastName: 'Johnson',
            role: 'manager',
            status: 'active',
            last_login: '2025-01-12 09:30:00',
            performance: 92,
            avatar: null,
            created_at: '2024-10-15'
        },
        {
            id: 2,
            name: 'Michael Brown',
            email: 'michael.brown@company.com',
            firstName: 'Michael',
            lastName: 'Brown',
            role: 'employee',
            status: 'active',
            last_login: '2025-01-12 08:15:00',
            performance: 87,
            avatar: null,
            created_at: '2024-11-02'
        },
        {
            id: 3,
            name: 'Emily Davis',
            email: 'emily.davis@company.com',
            firstName: 'Emily',
            lastName: 'Davis',
            role: 'admin',
            status: 'active',
            last_login: '2025-01-12 10:45:00',
            performance: 95,
            avatar: null,
            created_at: '2024-09-20'
        },
        {
            id: 4,
            name: 'John Smith',
            email: 'john.smith@company.com',
            firstName: 'John',
            lastName: 'Smith',
            role: 'employee',
            status: 'inactive',
            last_login: '2025-01-10 16:20:00',
            performance: 73,
            avatar: null,
            created_at: '2024-12-01'
        },
        {
            id: 5,
            name: 'Lisa Wilson',
            email: 'lisa.wilson@company.com',
            firstName: 'Lisa',
            lastName: 'Wilson',
            role: 'manager',
            status: 'active',
            last_login: '2025-01-12 07:30:00',
            performance: 89,
            avatar: null,
            created_at: '2024-10-30'
        },
        {
            id: 6,
            name: 'David Martinez',
            email: 'david.martinez@company.com',
            firstName: 'David',
            lastName: 'Martinez',
            role: 'employee',
            status: 'active',
            last_login: '2025-01-11 18:45:00',
            performance: 84,
            avatar: null,
            created_at: '2024-11-15'
        }
    ],

    // Get users data
    getUsers() {
        return this.users;
    },

    // Get role badge CSS class
    getRoleBadgeClass(role) {
        const classes = {
            'admin': 'bg-red-100 text-red-800',
            'manager': 'bg-accent-100 text-accent-800',
            'employee': 'bg-green-100 text-green-800'
        };
        return classes[role] || 'bg-gray-100 text-gray-800';
    },

    // Get status badge CSS class
    getStatusBadgeClass(status) {
        const classes = {
            'active': 'bg-green-100 text-green-800',
            'inactive': 'bg-red-100 text-red-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    },

    // Get performance bar color class
    getPerformanceBarClass(performance) {
        if (performance >= 90) return 'bg-green-500';
        if (performance >= 75) return 'bg-accent-500';
        if (performance >= 60) return 'bg-yellow-500';
        return 'bg-red-500';
    },

    // Format date for display
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    },

    // Create new user
    createUser(userData) {
        const newUser = {
            id: Math.max(...this.users.map(u => u.id)) + 1,
            name: `${userData.firstName} ${userData.lastName}`,
            email: userData.email,
            firstName: userData.firstName,
            lastName: userData.lastName,
            role: userData.role,
            status: 'active',
            last_login: null,
            performance: 0,
            avatar: null,
            created_at: new Date().toISOString().split('T')[0]
        };

        this.users.push(newUser);
        return newUser;
    },

    // Update user
    updateUser(userId, userData) {
        const userIndex = this.users.findIndex(u => u.id === userId);
        if (userIndex !== -1) {
            this.users[userIndex] = { ...this.users[userIndex], ...userData };
            if (userData.firstName || userData.lastName) {
                this.users[userIndex].name = `${userData.firstName || this.users[userIndex].firstName} ${userData.lastName || this.users[userIndex].lastName}`;
            }
            return this.users[userIndex];
        }
        return null;
    },

    // Delete user
    deleteUser(userId) {
        const userIndex = this.users.findIndex(u => u.id === userId);
        if (userIndex !== -1) {
            const deletedUser = this.users.splice(userIndex, 1)[0];
            return deletedUser;
        }
        return null;
    },

    // Filter users based on search and filters
    filterUsers(searchQuery, roleFilter, statusFilter) {
        return this.users.filter(user => {
            const matchesSearch = !searchQuery || 
                user.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                user.email.toLowerCase().includes(searchQuery.toLowerCase()) ||
                user.role.toLowerCase().includes(searchQuery.toLowerCase());
            
            const matchesRole = !roleFilter || user.role === roleFilter;
            const matchesStatus = !statusFilter || user.status === statusFilter;
            
            return matchesSearch && matchesRole && matchesStatus;
        });
    },

    // Show notification
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${this.getNotificationClass(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${this.getNotificationIcon(type)}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    },

    // Get notification CSS classes
    getNotificationClass(type) {
        const classes = {
            'success': 'bg-green-100 border border-green-400 text-green-700',
            'error': 'bg-red-100 border border-red-400 text-red-700',
            'warning': 'bg-yellow-100 border border-yellow-400 text-yellow-700',
            'info': 'bg-accent-100 border border-accent-400 text-accent-700'
        };
        return classes[type] || classes.info;
    },

    // Get notification icon
    getNotificationIcon(type) {
        const icons = {
            'success': '<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            'error': '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            'warning': '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            'info': '<svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };
        return icons[type] || icons.info;
    }
};

// Alpine.js Users Data
document.addEventListener('alpine:init', () => {
    Alpine.data('usersData', () => ({
        // Users data
        users: [],
        filteredUsers: [],
        
        // Filter states
        searchQuery: '',
        roleFilter: '',
        statusFilter: '',
        
        // Modal states
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        
        // Form data
        newUser: {
            firstName: '',
            lastName: '',
            email: '',
            role: '',
            password: ''
        },
        
        editingUser: null,
        deletingUser: null,
        
        // Loading states
        isLoading: false,

        // Initialize component
        init() {
            this.loadUsers();
        },

        // Load users data
        loadUsers() {
            this.users = window.usersComponent.getUsers();
            this.filteredUsers = [...this.users];
        },

        // Filter users
        filterUsers() {
            this.filteredUsers = window.usersComponent.filterUsers(
                this.searchQuery,
                this.roleFilter,
                this.statusFilter
            );
        },

        // Get role badge class
        getRoleBadgeClass(role) {
            return window.usersComponent.getRoleBadgeClass(role);
        },

        // Get status badge class
        getStatusBadgeClass(status) {
            return window.usersComponent.getStatusBadgeClass(status);
        },

        // Get performance bar class
        getPerformanceBarClass(performance) {
            return window.usersComponent.getPerformanceBarClass(performance);
        },

        // Format date
        formatDate(dateString) {
            if (!dateString) return 'Never';
            return window.usersComponent.formatDate(dateString);
        },

        // Create new user
        createUser() {
            if (!this.newUser.firstName || !this.newUser.lastName || !this.newUser.email || !this.newUser.role) {
                window.usersComponent.showNotification('Please fill in all required fields', 'error');
                return;
            }

            // Check if email already exists
            const emailExists = this.users.some(user => user.email === this.newUser.email);
            if (emailExists) {
                window.usersComponent.showNotification('Email already exists', 'error');
                return;
            }

            const createdUser = window.usersComponent.createUser(this.newUser);
            
            // Update local data
            this.loadUsers();
            this.filterUsers();
            
            // Reset form and close modal
            this.newUser = {
                firstName: '',
                lastName: '',
                email: '',
                role: '',
                password: ''
            };
            this.showCreateModal = false;
            
            window.usersComponent.showNotification('Employee created successfully!', 'success');
        },

        // View user details
        viewUser(user) {
            // In a real app, this would open a detailed view modal
            // For now, show basic user info (could be replaced with modal)
            console.log('Viewing user:', user.name);
        },

        // Edit user
        editUser(user) {
            this.editingUser = { ...user };
            this.showEditModal = true;
        },

        // Update user
        updateUser() {
            if (!this.editingUser.firstName || !this.editingUser.lastName || !this.editingUser.email) {
                window.usersComponent.showNotification('Please fill in all required fields', 'error');
                return;
            }

            const updatedUser = window.usersComponent.updateUser(this.editingUser.id, {
                firstName: this.editingUser.firstName,
                lastName: this.editingUser.lastName,
                email: this.editingUser.email,
                role: this.editingUser.role,
                status: this.editingUser.status
            });

            if (updatedUser) {
                // Update local data
                this.loadUsers();
                this.filterUsers();
                
                this.showEditModal = false;
                this.editingUser = null;
                
                window.usersComponent.showNotification('Employee updated successfully!', 'success');
            } else {
                window.usersComponent.showNotification('Failed to update employee', 'error');
            }
        },

        // Delete user confirmation
        deleteUser(user) {
            this.deletingUser = user;
            this.showDeleteModal = true;
        },

        // Confirm delete user
        confirmDeleteUser() {
            if (this.deletingUser) {
                const deletedUser = window.usersComponent.deleteUser(this.deletingUser.id);
                
                if (deletedUser) {
                    // Update local data
                    this.loadUsers();
                    this.filterUsers();
                    
                    this.showDeleteModal = false;
                    this.deletingUser = null;
                    
                    window.usersComponent.showNotification('Employee deleted successfully!', 'success');
                } else {
                    window.usersComponent.showNotification('Failed to delete employee', 'error');
                }
            }
        },

        // Refresh users
        refreshUsers() {
            this.isLoading = true;
            
            // Simulate API call
            setTimeout(() => {
                this.loadUsers();
                this.filterUsers();
                this.isLoading = false;
                
                window.usersComponent.showNotification('Users refreshed successfully!', 'success');
            }, 1000);
        },

        // Reset filters
        resetFilters() {
            this.searchQuery = '';
            this.roleFilter = '';
            this.statusFilter = '';
            this.filterUsers();
        }
    }));
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize users component
    if (typeof window.usersComponent !== 'undefined') {
        console.log('Users component loaded');
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.usersComponent;
}