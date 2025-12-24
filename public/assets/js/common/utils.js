/**
 * Common JavaScript Utilities for SafarStep
 * Shared functions, API helpers, and utility methods
 */

// Global SafarStep namespace
window.SafarStep = {
    // Configuration
    config: {
        apiUrl: '/api/v1',
        tenant: null,
        user: null,
        token: null
    },

    // Initialize the application
    init() {
        this.loadConfig();
        this.setupGlobalEventListeners();
        this.initializeAuth();
    },

    // Load configuration from DOM or localStorage
    loadConfig() {
        // Load tenant info from meta tags or global variables
        const tenantMeta = document.querySelector('meta[name="tenant-id"]');
        if (tenantMeta) {
            this.config.tenant = tenantMeta.content;
        }

        // Load auth token from localStorage
        const token = localStorage.getItem('safarstep_token');
        if (token) {
            this.config.token = token;
            this.setAuthHeaders();
        }
    },

    // Set authentication headers for API calls
    setAuthHeaders() {
        if (this.config.token) {
            // Set default headers for fetch requests
            window.fetch = ((originalFetch) => {
                return (...args) => {
                    if (args[1]) {
                        args[1].headers = {
                            ...args[1].headers,
                            'Authorization': `Bearer ${this.config.token}`,
                            'Content-Type': 'application/json'
                        };
                    } else {
                        args[1] = {
                            headers: {
                                'Authorization': `Bearer ${this.config.token}`,
                                'Content-Type': 'application/json'
                            }
                        };
                    }
                    return originalFetch.apply(this, args);
                };
            })(window.fetch);
        }
    },

    // Initialize authentication state
    initializeAuth() {
        // Check if user is logged in and token is valid
        if (this.config.token) {
            this.validateToken();
        }
    },

    // Validate authentication token
    async validateToken() {
        try {
            const response = await this.api.get('/auth/me');
            if (response.success) {
                this.config.user = response.data;
                this.updateUserUI();
            } else {
                this.logout();
            }
        } catch (error) {
            console.error('Token validation failed:', error);
            this.logout();
        }
    },

    // Update user information in UI
    updateUserUI() {
        if (this.config.user) {
            // Update user name in header
            const userNameElements = document.querySelectorAll('[data-user-name]');
            userNameElements.forEach(el => {
                el.textContent = this.config.user.name;
            });

            // Update user email
            const userEmailElements = document.querySelectorAll('[data-user-email]');
            userEmailElements.forEach(el => {
                el.textContent = this.config.user.email;
            });

            // Update user avatar
            const userAvatarElements = document.querySelectorAll('[data-user-avatar]');
            userAvatarElements.forEach(el => {
                el.src = this.config.user.avatar || '/assets/images/default-avatar.png';
            });
        }
    },

    // Logout user
    logout() {
        localStorage.removeItem('safarstep_token');
        this.config.token = null;
        this.config.user = null;
        window.location.href = '/login';
    },

    // Setup global event listeners
    setupGlobalEventListeners() {
        // Handle logout clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-logout]')) {
                e.preventDefault();
                this.logout();
            }
        });

        // Handle modal close clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('.modal-backdrop')) {
                this.closeAllModals();
            }
        });

        // Handle escape key for modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    },

    // Close all open modals
    closeAllModals() {
        document.querySelectorAll('[x-data]').forEach(el => {
            const alpineData = Alpine.$data(el);
            if (alpineData && typeof alpineData.closeModal === 'function') {
                alpineData.closeModal();
            }
        });
    },

    // API helper methods
    api: {
        // GET request
        async get(endpoint, params = {}) {
            const url = new URL(window.SafarStep.config.apiUrl + endpoint, window.location.origin);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
            
            try {
                const response = await fetch(url);
                return await response.json();
            } catch (error) {
                console.error('API GET error:', error);
                throw error;
            }
        },

        // POST request
        async post(endpoint, data = {}) {
            try {
                const response = await fetch(window.SafarStep.config.apiUrl + endpoint, {
                    method: 'POST',
                    body: JSON.stringify(data)
                });
                return await response.json();
            } catch (error) {
                console.error('API POST error:', error);
                throw error;
            }
        },

        // PUT request
        async put(endpoint, data = {}) {
            try {
                const response = await fetch(window.SafarStep.config.apiUrl + endpoint, {
                    method: 'PUT',
                    body: JSON.stringify(data)
                });
                return await response.json();
            } catch (error) {
                console.error('API PUT error:', error);
                throw error;
            }
        },

        // DELETE request
        async delete(endpoint) {
            try {
                const response = await fetch(window.SafarStep.config.apiUrl + endpoint, {
                    method: 'DELETE'
                });
                return await response.json();
            } catch (error) {
                console.error('API DELETE error:', error);
                throw error;
            }
        }
    },

    // Utility functions
    utils: {
        // Format currency
        formatCurrency(amount, currency = 'USD') {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },

        // Format date
        formatDate(date, options = {}) {
            const defaultOptions = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            
            return new Date(date).toLocaleDateString('en-US', {
                ...defaultOptions,
                ...options
            });
        },

        // Format date and time
        formatDateTime(date, options = {}) {
            const defaultOptions = {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            
            return new Date(date).toLocaleDateString('en-US', {
                ...defaultOptions,
                ...options
            });
        },

        // Generate UUID
        generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                const r = Math.random() * 16 | 0;
                const v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        },

        // Debounce function
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // Throttle function
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        // Deep clone object
        deepClone(obj) {
            return JSON.parse(JSON.stringify(obj));
        },

        // Check if object is empty
        isEmpty(obj) {
            return Object.keys(obj).length === 0;
        },

        // Sanitize HTML string
        sanitizeHTML(str) {
            const temp = document.createElement('div');
            temp.textContent = str;
            return temp.innerHTML;
        },

        // Validate email format
        isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        // Validate phone format
        isValidPhone(phone) {
            const re = /^[\+]?[1-9][\d]{0,15}$/;
            return re.test(phone.replace(/\s/g, ''));
        },

        // Generate random string
        randomString(length = 10) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        },

        // Calculate percentage
        percentage(part, total) {
            return total === 0 ? 0 : Math.round((part / total) * 100);
        },

        // Format file size
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    },

    // Notification system
    notify: {
        // Show success notification
        success(message, duration = 3000) {
            this.show(message, 'success', duration);
        },

        // Show error notification
        error(message, duration = 5000) {
            this.show(message, 'error', duration);
        },

        // Show warning notification
        warning(message, duration = 4000) {
            this.show(message, 'warning', duration);
        },

        // Show info notification
        info(message, duration = 3000) {
            this.show(message, 'info', duration);
        },

        // Show notification with custom type
        show(message, type = 'info', duration = 3000) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${this.getTypeClass(type)}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${this.getIcon(type)}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Auto remove
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, duration);
        },

        // Get CSS class for notification type
        getTypeClass(type) {
            const classes = {
                'success': 'bg-green-100 border border-green-400 text-green-700',
                'error': 'bg-red-100 border border-red-400 text-red-700',
                'warning': 'bg-yellow-100 border border-yellow-400 text-yellow-700',
                'info': 'bg-accent-100 border border-accent-400 text-accent-700'
            };
            return classes[type] || classes.info;
        },

        // Get icon for notification type
        getIcon(type) {
            const icons = {
                'success': '<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                'error': '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                'warning': '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
                'info': '<svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
            };
            return icons[type] || icons.info;
        }
    },

    // Loading states
    loading: {
        // Show loading spinner
        show(target = 'body', message = 'Loading...') {
            const loader = document.createElement('div');
            loader.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            loader.id = 'safarstep-loader';
            loader.innerHTML = `
                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-accent-600"></div>
                    <span class="text-gray-700">${message}</span>
                </div>
            `;
            
            document.body.appendChild(loader);
        },

        // Hide loading spinner
        hide() {
            const loader = document.getElementById('safarstep-loader');
            if (loader) {
                loader.remove();
            }
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.SafarStep.init();
});

// Make utilities available globally
window.utils = window.SafarStep.utils;
window.notify = window.SafarStep.notify;
window.api = window.SafarStep.api;

// Export for Node.js environments
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.SafarStep;
}