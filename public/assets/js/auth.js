/**
 * SafarStep Authentication Module
 * Shared authentication functions for all pages
 */

class SafarStepAuth {
    constructor() {
        // Use dynamic config if available, fallback to detecting from current URL
        this.API_BASE = window.safarStepConfig ? window.safarStepConfig.apiBase : this.detectApiBase();
        this.token = localStorage.getItem('safarstep_token');
    }

    /**
     * Detect API base URL dynamically (fallback method)
     */
    detectApiBase() {
        const currentPath = window.location.pathname;
        
        // Try to extract base path from current URL
        const patterns = [
            /^(\/[^\/]+)\/(?:public\/)?(?:login|dashboard|admin|api|assets)/,
            /^(\/[^\/]+)\/$/,
            /^(\/[^\/]+)$/
        ];

        for (const pattern of patterns) {
            const match = currentPath.match(pattern);
            if (match) {
                return match[1] + '/api';
            }
        }

        // Default fallback
        return '/api';
    }

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return !!this.token;
    }

    /**
     * Get current authentication token
     */
    getToken() {
        return this.token;
    }

    /**
     * Set authentication token
     */
    setToken(token) {
        this.token = token;
        localStorage.setItem('safarstep_token', token);
        // Also store with access_token key for compatibility
        localStorage.setItem('access_token', token);
    }

    /**
     * Remove authentication token
     */
    removeToken() {
        this.token = null;
        localStorage.removeItem('safarstep_token');
        localStorage.removeItem('access_token');
        localStorage.removeItem('safarstep_refresh_token');
        localStorage.removeItem('refresh_token');
    }

    /**
     * Get authorization headers for API requests
     */
    getAuthHeaders() {
        const headers = {
            'Content-Type': 'application/json'
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    /**
     * Make authenticated API request
     */
    async apiRequest(endpoint, options = {}) {
        const url = `${this.API_BASE}${endpoint}`;
        
        const config = {
            ...options,
            headers: {
                ...this.getAuthHeaders(),
                ...(options.headers || {})
            }
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            // Handle token expiration
            if (response.status === 401) {
                this.removeToken();
                this.redirectToLogin();
                throw new Error('Authentication required');
            }

            return { response, data };
        } catch (error) {
            throw error;
        }
    }

    /**
     * Login user
     */
    async login(email, password, tenantId) {
        try {
            const { response, data } = await this.apiRequest('/auth/login', {
                method: 'POST',
                body: JSON.stringify({
                    email,
                    password,
                    tenant_id: tenantId
                })
            });

            if (data.success && data.data.access_token) {
                this.setToken(data.data.access_token);
                // Also store refresh token if available
                if (data.data.refresh_token) {
                    localStorage.setItem('safarstep_refresh_token', data.data.refresh_token);
                }
                return data.data;
            } else {
                throw new Error(data.message || 'Login failed');
            }
        } catch (error) {
            throw error;
        }
    }

    /**
     * Logout user
     */
    async logout() {
        try {
            if (this.token) {
                await this.apiRequest('/auth/logout', {
                    method: 'POST'
                });
            }
        } catch (error) {
            // Continue with logout even if API call fails
            console.warn('Logout API call failed:', error);
        } finally {
            this.removeToken();
            this.redirectToLogin();
        }
    }

    /**
     * Get current user information
     */
    async getCurrentUser() {
        try {
            const { data } = await this.apiRequest('/auth/me');
            return data.success ? data.data : null;
        } catch (error) {
            return null;
        }
    }

    /**
     * Refresh authentication token
     */
    async refreshToken() {
        try {
            const { data } = await this.apiRequest('/auth/refresh', {
                method: 'POST'
            });

            if (data.success && data.data.token) {
                this.setToken(data.data.token);
                return data.data.token;
            } else {
                throw new Error('Token refresh failed');
            }
        } catch (error) {
            this.removeToken();
            this.redirectToLogin();
            throw error;
        }
    }

    /**
     * Get available tenants
     */
    async getTenants() {
        try {
            const { data } = await this.apiRequest('/auth/tenants');
            console.log('getTenants response:', data);
            
            if (data.success && data.data && data.data.tenants) {
                return data.data.tenants;
            }
            return [];
        } catch (error) {
            console.error('Error fetching tenants:', error);
            return [];
        }
    }

    /**
     * Redirect to login page
     */
    redirectToLogin() {
        // Use a more direct approach to generate the login URL
        const currentPath = window.location.pathname;
        let loginUrl;
        
        // Extract base path from current URL
        const match = currentPath.match(/^(\/[^\/]+)\/(?:login|dashboard|admin|api|assets)/);
        if (match) {
            loginUrl = match[1] + '/login/';
        } else if (window.safarStepConfig) {
            loginUrl = window.safarStepConfig.url('login/');
        } else {
            loginUrl = this.getBaseUrl() + '/login/';
        }
        
        if (window.location.pathname !== loginUrl.replace(window.location.origin, '')) {
            window.location.href = loginUrl;
        }
    }

    /**
     * Redirect to dashboard
     */
    redirectToDashboard() {
        // Use a more direct approach to generate the dashboard URL
        const currentPath = window.location.pathname;
        let dashboardUrl;
        
        // Extract base path from current URL
        const match = currentPath.match(/^(\/[^\/]+)\/(?:login|dashboard|admin|api|assets)/);
        if (match) {
            dashboardUrl = match[1] + '/dashboard/';
        } else if (window.safarStepConfig) {
            dashboardUrl = window.safarStepConfig.url('dashboard/');
        } else {
            dashboardUrl = this.getBaseUrl() + '/dashboard/';
        }
        
        window.location.href = dashboardUrl;
    }

    /**
     * Get base URL (fallback method)
     */
    getBaseUrl() {
        const currentPath = window.location.pathname;
        
        const patterns = [
            /^(\/[^\/]+)\/(?:public\/)?(?:login|dashboard|admin|api|assets)/,
            /^(\/[^\/]+)\/$/,
            /^(\/[^\/]+)$/
        ];

        for (const pattern of patterns) {
            const match = currentPath.match(pattern);
            if (match) {
                return match[1];
            }
        }

        return '';
    }

    /**
     * Check authentication and redirect if needed
     */
    async requireAuth() {
        if (!this.isAuthenticated()) {
            this.redirectToLogin();
            return false;
        }

        // Verify token is still valid
        const user = await this.getCurrentUser();
        if (!user) {
            this.redirectToLogin();
            return false;
        }

        return true;
    }

    /**
     * Initialize authentication for a page
     */
    async initPage(requiresAuth = false) {
        if (requiresAuth) {
            const isAuthenticated = await this.requireAuth();
            if (!isAuthenticated) {
                return null;
            }
        }

        return await this.getCurrentUser();
    }

    /**
     * Format error message for display
     */
    formatError(error) {
        if (error.message) {
            return error.message;
        }
        
        if (typeof error === 'string') {
            return error;
        }

        return 'An unexpected error occurred';
    }

    /**
     * Show loading state
     */
    showLoading(element, show = true) {
        if (!element) return;

        if (show) {
            element.disabled = true;
            element.classList.add('opacity-50', 'cursor-not-allowed');
            
            // Add spinner if button
            if (element.tagName === 'BUTTON') {
                const originalText = element.textContent;
                element.dataset.originalText = originalText;
                element.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                `;
            }
        } else {
            element.disabled = false;
            element.classList.remove('opacity-50', 'cursor-not-allowed');
            
            // Restore original text if button
            if (element.tagName === 'BUTTON' && element.dataset.originalText) {
                element.textContent = element.dataset.originalText;
                delete element.dataset.originalText;
            }
        }
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'info', duration = 3000) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-accent-500 text-white'
        };
        
        toast.className += ` ${colors[type] || colors.info}`;
        toast.textContent = message;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after duration
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, duration);
    }
}

// Create global instance
window.safarStepAuth = new SafarStepAuth();

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SafarStepAuth;
}