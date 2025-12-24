/**
 * SafarStep Unsplash Wallpaper Manager
 * 
 * Handles dynamic wallpaper loading for authentication pages
 * Features: Session caching, smooth transitions, fallback support
 * 
 * @version 1.0.0
 * @since 2025-10-28
 */

class UnsplashWallpaperManager {
    constructor(config = {}) {
        this.config = {
            accessKey: 'CAzVOvrmezg2tv28ZuC88KsC8bgMi-glKeA5nrvjj4c',
            appId: '818430',
            baseUrl: 'https://api.unsplash.com',
            cacheExpiry: 1000 * 60 * 30, // 30 minutes
            cacheKey: 'safarstep_login_wallpaper',
            timestampKey: 'safarstep_wallpaper_timestamp',
            queries: ['landscape', 'nature', 'mountain', 'ocean', 'travel', 'scenic', 'blue sky', 'sunset', 'forest'],
            imageParams: {
                orientation: 'landscape',
                w: 1920,
                h: 1080,
                fit: 'crop',
                fm: 'jpg',
                q: 85
            },
            ...config
        };
        
        this.cache = {
            isSupported: this.isStorageSupported(),
            get: (key) => this.cache.isSupported ? sessionStorage.getItem(key) : null,
            set: (key, value) => this.cache.isSupported ? sessionStorage.setItem(key, value) : false,
            remove: (key) => this.cache.isSupported ? sessionStorage.removeItem(key) : false
        };
    }
    
    /**
     * Check if sessionStorage is supported and available
     */
    isStorageSupported() {
        try {
            const test = '__storage_test__';
            sessionStorage.setItem(test, test);
            sessionStorage.removeItem(test);
            return true;
        } catch(e) {
            return false;
        }
    }
    
    /**
     * Load wallpaper with caching support
     */
    async loadWallpaper() {
        try {
            // Check cache first
            const cachedData = this.getCachedWallpaper();
            if (cachedData) {
                return cachedData;
            }
            
            // Fetch new wallpaper
            const wallpaperData = await this.fetchFromUnsplash();
            
            // Cache the result
            this.cacheWallpaper(wallpaperData);
            
            return wallpaperData;
            
        } catch (error) {
            console.warn('Wallpaper loading failed:', error);
            return this.getFallbackWallpaper();
        }
    }
    
    /**
     * Get cached wallpaper if available and not expired
     */
    getCachedWallpaper() {
        if (!this.cache.isSupported) return null;
        
        const cachedWallpaper = this.cache.get(this.config.cacheKey);
        const cacheTimestamp = this.cache.get(this.config.timestampKey);
        
        if (!cachedWallpaper || !cacheTimestamp) return null;
        
        const currentTime = Date.now();
        const isExpired = (currentTime - parseInt(cacheTimestamp)) > this.config.cacheExpiry;
        
        if (isExpired) {
            this.clearCache();
            return null;
        }
        
        try {
            return JSON.parse(cachedWallpaper);
        } catch (error) {
            console.warn('Failed to parse cached wallpaper:', error);
            this.clearCache();
            return null;
        }
    }
    
    /**
     * Fetch wallpaper from Unsplash API
     */
    async fetchFromUnsplash() {
        const randomQuery = this.getRandomQuery();
        const url = this.buildApiUrl(randomQuery);
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Unsplash API error: ${response.status} ${response.statusText}`);
        }
        
        const data = await response.json();
        
        return this.formatWallpaperData(data);
    }
    
    /**
     * Get random search query for variety
     */
    getRandomQuery() {
        const queries = this.config.queries;
        return queries[Math.floor(Math.random() * queries.length)];
    }
    
    /**
     * Build Unsplash API URL with parameters
     */
    buildApiUrl(query) {
        const params = new URLSearchParams({
            client_id: this.config.accessKey,
            query: query,
            ...this.config.imageParams
        });
        
        return `${this.config.baseUrl}/photos/random?${params.toString()}`;
    }
    
    /**
     * Format Unsplash response data
     */
    formatWallpaperData(data) {
        const wallpaperData = {
            imageUrl: data.urls.regular,
            thumbUrl: data.urls.thumb,
            photographer: data.user.name,
            photographerUrl: data.user.links.html,
            unsplashUrl: data.links.html,
            description: data.description || data.alt_description || 'Beautiful landscape',
            color: data.color || '#2A50BC',
            width: data.width,
            height: data.height,
            downloadUrl: data.links.download,
            id: data.id
        };
        
        console.log('Formatted wallpaper data:', wallpaperData);
        return wallpaperData;
    }
    
    /**
     * Cache wallpaper data
     */
    cacheWallpaper(wallpaperData) {
        if (!this.cache.isSupported) return;
        
        try {
            this.cache.set(this.config.cacheKey, JSON.stringify(wallpaperData));
            this.cache.set(this.config.timestampKey, Date.now().toString());
        } catch (error) {
            console.warn('Failed to cache wallpaper:', error);
        }
    }
    
    /**
     * Clear wallpaper cache
     */
    clearCache() {
        this.cache.remove(this.config.cacheKey);
        this.cache.remove(this.config.timestampKey);
    }
    
    /**
     * Preload wallpaper image for smooth transition
     */
    async preloadImage(imageUrl) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error('Failed to load image'));
            
            img.src = imageUrl;
        });
    }
    
    /**
     * Get fallback wallpaper data
     */
    getFallbackWallpaper() {
        return {
            imageUrl: null,
            photographer: null,
            description: 'Gradient background',
            color: '#2A50BC',
            isFallback: true
        };
    }
    
    /**
     * Refresh wallpaper (force new fetch)
     */
    async refreshWallpaper() {
        this.clearCache();
        return await this.loadWallpaper();
    }
}

// Export for global use
window.UnsplashWallpaperManager = UnsplashWallpaperManager;

// Initialize global instance immediately with error handling
window.SafarStep = window.SafarStep || {};

try {
    window.SafarStep.wallpaperManager = new UnsplashWallpaperManager();
} catch (error) {
    console.warn('Failed to initialize wallpaper manager:', error);
    // Create a fallback manager that returns fallback data
    window.SafarStep.wallpaperManager = {
        loadWallpaper: () => Promise.resolve({ isFallback: true }),
        preloadImage: () => Promise.reject(new Error('Fallback mode')),
        refreshWallpaper: () => Promise.resolve({ isFallback: true })
    };
}

// Additional initialization when DOM is ready (for any DOM-dependent operations)
document.addEventListener('DOMContentLoaded', function() {
    // Wallpaper manager should be ready
    if (!window.SafarStep || !window.SafarStep.wallpaperManager) {
        console.warn('Wallpaper manager not available on DOM ready');
    }
});