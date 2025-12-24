/**
 * SafarStep Utilities
 * Common utility functions for all pages
 */

class SafarStepUtils {
    /**
     * Format currency amount
     */
    static formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    /**
     * Format date
     */
    static formatDate(date, format = 'short') {
        const d = new Date(date);
        
        switch (format) {
            case 'short':
                return d.toLocaleDateString();
            case 'long':
                return d.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            case 'time':
                return d.toLocaleTimeString();
            case 'datetime':
                return d.toLocaleString();
            default:
                return d.toLocaleDateString();
        }
    }

    /**
     * Format file size
     */
    static formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Debounce function
     */
    static debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    /**
     * Throttle function
     */
    static throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Generate UUID
     */
    static generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    /**
     * Validate email
     */
    static validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Validate phone number
     */
    static validatePhone(phone) {
        const re = /^[\+]?[1-9][\d]{0,15}$/;
        return re.test(phone.replace(/\s/g, ''));
    }

    /**
     * Sanitize HTML
     */
    static sanitizeHTML(str) {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    }

    /**
     * Copy text to clipboard
     */
    static async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                document.body.removeChild(textArea);
                return true;
            } catch (err) {
                document.body.removeChild(textArea);
                return false;
            }
        }
    }

    /**
     * Get URL parameters
     */
    static getUrlParams() {
        const params = {};
        const urlSearchParams = new URLSearchParams(window.location.search);
        for (const [key, value] of urlSearchParams) {
            params[key] = value;
        }
        return params;
    }

    /**
     * Set URL parameter
     */
    static setUrlParam(key, value) {
        const url = new URL(window.location);
        url.searchParams.set(key, value);
        window.history.pushState({}, '', url);
    }

    /**
     * Remove URL parameter
     */
    static removeUrlParam(key) {
        const url = new URL(window.location);
        url.searchParams.delete(key);
        window.history.pushState({}, '', url);
    }

    /**
     * Local storage with expiration
     */
    static setStorageWithExpiry(key, value, ttl) {
        const now = new Date();
        const item = {
            value: value,
            expiry: now.getTime() + ttl
        };
        localStorage.setItem(key, JSON.stringify(item));
    }

    static getStorageWithExpiry(key) {
        const itemStr = localStorage.getItem(key);
        if (!itemStr) {
            return null;
        }
        
        const item = JSON.parse(itemStr);
        const now = new Date();
        
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(key);
            return null;
        }
        
        return item.value;
    }

    /**
     * Format number with commas
     */
    static formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    /**
     * Truncate text
     */
    static truncateText(text, length = 100, suffix = '...') {
        if (text.length <= length) {
            return text;
        }
        return text.substr(0, length) + suffix;
    }

    /**
     * Calculate percentage
     */
    static calculatePercentage(value, total) {
        if (total === 0) return 0;
        return Math.round((value / total) * 100);
    }

    /**
     * Get random color
     */
    static getRandomColor() {
        const colors = [
            '#2A50BC', '#10B981', '#F59E0B', '#EF4444',
            '#8B5CF6', '#06B6D4', '#84CC16', '#F97316',
            '#EC4899', '#6366F1', '#14B8A6', '#EAB308'
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    /**
     * Sleep function
     */
    static sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    /**
     * Download file
     */
    static downloadFile(data, filename, type = 'application/octet-stream') {
        const blob = new Blob([data], { type });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    /**
     * Export to CSV
     */
    static exportToCSV(data, filename = 'export.csv') {
        if (data.length === 0) return;
        
        const headers = Object.keys(data[0]);
        let csv = headers.join(',') + '\n';
        
        data.forEach(row => {
            const values = headers.map(header => {
                const value = row[header] || '';
                return typeof value === 'string' && value.includes(',') 
                    ? `"${value}"` 
                    : value;
            });
            csv += values.join(',') + '\n';
        });
        
        this.downloadFile(csv, filename, 'text/csv');
    }

    /**
     * Print element
     */
    static printElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        // Get dynamic base URL
        const baseUrl = window.safarStepConfig ? window.safarStepConfig.assetUrl('') : this.detectAssetUrl();
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print</title>
                    <link rel="stylesheet" href="${baseUrl}/css/app.css">
                    <style>
                        body { margin: 20px; }
                        @media print {
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    ${element.outerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }

    /**
     * Detect asset URL dynamically (fallback method)
     */
    static detectAssetUrl() {
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
                return match[1] + '/assets';
            }
        }

        return '/assets';
    }

    /**
     * Show confirmation dialog
     */
    static async confirm(message, title = 'Confirm') {
        return new Promise((resolve) => {
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-semibold mb-4">${title}</h3>
                    <p class="text-gray-600 mb-6">${message}</p>
                    <div class="flex justify-end space-x-3">
                        <button class="btn btn-secondary" onclick="this.closest('.fixed').remove(); window.confirmResult(false);">Cancel</button>
                        <button class="btn btn-danger" onclick="this.closest('.fixed').remove(); window.confirmResult(true);">Confirm</button>
                    </div>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(modal);
            
            // Handle result
            window.confirmResult = (result) => {
                resolve(result);
                delete window.confirmResult;
            };
        });
    }

    /**
     * Show loading overlay
     */
    static showLoading(show = true, message = 'Loading...') {
        let overlay = document.getElementById('loading-overlay');
        
        if (show) {
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'loading-overlay';
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
                    <div class="bg-white rounded-lg p-6 text-center">
                        <div class="spinner mb-4"></div>
                        <p class="text-gray-600">${message}</p>
                    </div>
                `;
                document.body.appendChild(overlay);
            }
            overlay.classList.remove('hidden');
        } else {
            if (overlay) {
                overlay.classList.add('hidden');
            }
        }
    }
}

// Make available globally
window.SafarStepUtils = SafarStepUtils;