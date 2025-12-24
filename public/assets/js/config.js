/**
 * SafarStep Configuration - Dynamic App URL Detection
 * Automatically detects the application base URL for portability
 */

class SafarStepConfig {
    constructor() {
        this.baseUrl = this.detectBaseUrl();
        this.apiBase = `${this.baseUrl}/api`;
        this.assetsBase = `${this.baseUrl}/assets`;
    }

    /**
     * Detect the application base URL dynamically
     */
    detectBaseUrl() {
        // Method 1: detect from current page URL (more reliable for nested routes)
        const currentPath = window.location.pathname;
        
        // Common patterns to detect base path from current URL
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

        // Method 2: Get from script location (fallback)
        const scripts = document.getElementsByTagName('script');
        const currentScript = scripts[scripts.length - 1];
        
        if (currentScript && currentScript.src) {
            // Extract base URL from script src
            const scriptUrl = new URL(currentScript.src);
            const pathname = scriptUrl.pathname;
            
            // Handle nested routes - if script URL contains nested path, extract the real base
            // E.g., /safarstep/login/assets/js/config.js -> /safarstep
            const nestedMatch = pathname.match(/^(\/[^\/]+)\/[^\/]+\/assets\/js\/config\.js$/);
            if (nestedMatch) {
                return nestedMatch[1];
            }
            
            // Direct path - remove /assets/js/config.js from the path
            const basePath = pathname.replace(/\/assets\/js\/config\.js$/, '');
            return basePath || '';
        }

        // If no pattern matches, assume root
        return '';
    }

    /**
     * Get full URL for a path
     */
    url(path = '') {
        if (path.startsWith('/')) {
            path = path.substring(1);
        }
        
        // Ensure we have a proper base URL - if baseUrl looks like it contains nested paths,
        // extract the real base (e.g., /safarstep/login -> /safarstep)
        let cleanBaseUrl = this.baseUrl;
        const nestedMatch = cleanBaseUrl.match(/^(\/[^\/]+)\/(?:login|dashboard|admin)$/);
        if (nestedMatch) {
            cleanBaseUrl = nestedMatch[1];
        }
        
        return cleanBaseUrl + (path ? '/' + path : '');
    }

    /**
     * Get API URL for an endpoint
     */
    apiUrl(endpoint = '') {
        if (endpoint.startsWith('/')) {
            endpoint = endpoint.substring(1);
        }
        return this.apiBase + (endpoint ? '/' + endpoint : '');
    }

    /**
     * Get asset URL for a resource
     */
    assetUrl(asset = '') {
        if (asset.startsWith('/')) {
            asset = asset.substring(1);
        }
        return this.assetsBase + (asset ? '/' + asset : '');
    }

    /**
     * Get the current domain and protocol
     */
    getFullBaseUrl() {
        return window.location.origin + this.baseUrl;
    }

    /**
     * Debug information
     */
    debug() {
        return {
            baseUrl: this.baseUrl,
            apiBase: this.apiBase,
            assetsBase: this.assetsBase,
            currentPath: window.location.pathname,
            fullBaseUrl: this.getFullBaseUrl()
        };
    }
}

// Create global instance
window.safarStepConfig = new SafarStepConfig();

// For debugging
// SafarStep Config initialized

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SafarStepConfig;
}