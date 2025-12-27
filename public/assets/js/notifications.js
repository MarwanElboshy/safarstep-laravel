/**
 * SafarStep Enhanced Notification System
 * Advanced toast notifications with sound support and animations
 */

window.SafarStepNotifications = {
    // Configuration
    config: {
        soundsEnabled: true,
        defaultDuration: 3000,
        maxNotifications: 5,
        position: 'top-right', // top-right, top-left, bottom-right, bottom-left, top-center
        soundVolume: 0.3
    },

    // Get base URL dynamically
    getBaseUrl() {
        const script = document.currentScript || document.querySelector('script[src*="notifications.js"]');
        if (script && script.src) {
            const url = new URL(script.src);
            return url.origin + url.pathname.substring(0, url.pathname.lastIndexOf('/js/'));
        }
        // Fallback to window location
        return window.location.origin + '/assets';
    },

    // Sound files (initialized dynamically)
    sounds: {},

    // Active notifications
    notifications: [],

    /**
     * Initialize notification system
     */
    init() {
        // Initialize sound URLs dynamically
        const baseUrl = this.getBaseUrl();
        this.sounds = {
            success: `${baseUrl}/sounds/press.mp3`,
            info: `${baseUrl}/sounds/press.mp3`,
            error: `${baseUrl}/sounds/delete.mp3`,
            warning: `${baseUrl}/sounds/delete.mp3`,
        };
        this.createContainer();
        this.loadSettings();
        this.preloadSounds();
    },

    /**
     * Create notification container
     */
    createContainer() {
        if (document.getElementById('safarstep-notifications-container')) {
            return;
        }

        const container = document.createElement('div');
        container.id = 'safarstep-notifications-container';
        container.className = this.getContainerPosition();
        container.style.cssText = 'position: fixed; z-index: 9999; display: flex; flex-direction: column; gap: 12px; max-width: 420px;';
        
        document.body.appendChild(container);
    },

    /**
     * Get container position classes
     */
    getContainerPosition() {
        const positions = {
            'top-right': 'top-20 right-6',
            'top-left': 'top-20 left-6',
            'bottom-right': 'bottom-6 right-6',
            'bottom-left': 'bottom-6 left-6',
            'top-center': 'top-20 left-1/2 transform -translate-x-1/2'
        };
        return positions[this.config.position] || positions['top-right'];
    },

    /**
     * Load settings from localStorage
     */
    loadSettings() {
        const savedSettings = localStorage.getItem('safarstep_notification_settings');
        if (savedSettings) {
            try {
                const settings = JSON.parse(savedSettings);
                this.config = { ...this.config, ...settings };
            } catch (e) {
                console.error('Failed to load notification settings:', e);
            }
        }
    },

    /**
     * Save settings to localStorage
     */
    saveSettings() {
        localStorage.setItem('safarstep_notification_settings', JSON.stringify(this.config));
    },

    /**
     * Preload sound files
     */
    preloadSounds() {
        Object.values(this.sounds).forEach(soundUrl => {
            const audio = new Audio(soundUrl);
            audio.volume = this.config.soundVolume;
            audio.preload = 'auto';
        });
    },

    /**
     * Play notification sound
     */
    playSound(type) {
        if (!this.config.soundsEnabled) return;

        const soundUrl = this.sounds[type] || this.sounds.info;
        const audio = new Audio(soundUrl);
        audio.volume = this.config.soundVolume;
        audio.play().catch(e => console.warn('Sound play failed:', e));
    },

    /**
     * Show success notification
     */
    success(message, options = {}) {
        return this.show(message, 'success', options);
    },

    /**
     * Show error notification
     */
    error(message, options = {}) {
        return this.show(message, 'error', options);
    },

    /**
     * Show warning notification
     */
    warning(message, options = {}) {
        return this.show(message, 'warning', options);
    },

    /**
     * Show info notification
     */
    info(message, options = {}) {
        return this.show(message, 'info', options);
    },

    /**
     * Show notification
     */
    show(message, type = 'info', options = {}) {
        // Check max notifications limit
        if (this.notifications.length >= this.config.maxNotifications) {
            this.removeOldest();
        }

        const duration = options.duration !== undefined ? options.duration : (this.config.defaultDuration || 3000);
        const id = Date.now() + Math.random();
        const playSound = options.sound !== false;

        // Pass duration to createElement
        const notificationOptions = { ...options, duration };

        // Create notification element
        const notification = this.createElement(id, message, type, notificationOptions);
        
        // Add to container
        const container = document.getElementById('safarstep-notifications-container');
        container.appendChild(notification);

        // Store notification reference
        this.notifications.push({ id, element: notification, type });

        // Play sound
        if (playSound) {
            this.playSound(type);
        }

        // Animate in
        setTimeout(() => {
            notification.classList.add('notification-enter-active');
        }, 10);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, duration);
        }

        return id;
    },

    /**
     * Create notification element
     */
    createElement(id, message, type, options = {}) {
        const notification = document.createElement('div');
        notification.id = `notification-${id}`;
        notification.className = `notification notification-${type} notification-enter transform transition-all duration-300 ease-out`;
        notification.setAttribute('data-notification-id', id);

        const config = this.getTypeConfig(type);
        const title = options.title || config.title;
        const icon = options.icon || config.icon;
        const showProgress = options.showProgress !== false && options.duration > 0;

        notification.innerHTML = `
            <div class="relative flex items-start gap-4 p-4 rounded-xl shadow-xl backdrop-blur-sm border ${config.bgClass} ${config.borderClass} ${config.textClass}">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center ${config.iconBgClass} shadow-inner">
                        <svg class="w-5 h-5 ${config.iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icon}
                        </svg>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    ${title ? `<p class="text-sm font-semibold ${config.titleClass} mb-1">${title}</p>` : ''}
                    <p class="text-sm ${config.messageClass}">${message}</p>
                    ${options.action ? `
                        <button onclick="SafarStepNotifications.handleAction(${id}, '${options.action}')" 
                                class="mt-2 text-xs font-medium ${config.actionClass} hover:underline">
                            ${options.actionText || 'View Details'}
                        </button>
                    ` : ''}
                </div>

                <!-- Close button -->
                <button onclick="SafarStepNotifications.remove(${id})" 
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Progress bar -->
                ${showProgress ? `
                    <div class="absolute bottom-0 left-0 right-0 h-1 ${config.progressBgClass} rounded-b-xl overflow-hidden">
                        <div class="notification-progress h-full ${config.progressClass} rounded-b-xl" 
                             style="animation: progress ${options.duration || this.config.defaultDuration}ms linear forwards;"></div>
                    </div>
                ` : ''}
            </div>
        `;

        return notification;
    },

    /**
     * Get type configuration
     */
    getTypeConfig(type) {
        const configs = {
            success: {
                title: 'Success',
                bgClass: 'bg-emerald-50/95 dark:bg-emerald-900/20',
                borderClass: 'border-emerald-200 dark:border-emerald-800',
                textClass: 'text-emerald-900 dark:text-emerald-100',
                titleClass: 'text-emerald-900 dark:text-emerald-100',
                messageClass: 'text-emerald-700 dark:text-emerald-300',
                iconBgClass: 'bg-emerald-100 dark:bg-emerald-800',
                iconClass: 'text-emerald-600 dark:text-emerald-400',
                actionClass: 'text-emerald-700 dark:text-emerald-400',
                progressBgClass: 'bg-emerald-100 dark:bg-emerald-900',
                progressClass: 'bg-emerald-500',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            },
            error: {
                title: 'Error',
                bgClass: 'bg-red-50/95 dark:bg-red-900/20',
                borderClass: 'border-red-200 dark:border-red-800',
                textClass: 'text-red-900 dark:text-red-100',
                titleClass: 'text-red-900 dark:text-red-100',
                messageClass: 'text-red-700 dark:text-red-300',
                iconBgClass: 'bg-red-100 dark:bg-red-800',
                iconClass: 'text-red-600 dark:text-red-400',
                actionClass: 'text-red-700 dark:text-red-400',
                progressBgClass: 'bg-red-100 dark:bg-red-900',
                progressClass: 'bg-red-500',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            },
            warning: {
                title: 'Warning',
                bgClass: 'bg-amber-50/95 dark:bg-amber-900/20',
                borderClass: 'border-amber-200 dark:border-amber-800',
                textClass: 'text-amber-900 dark:text-amber-100',
                titleClass: 'text-amber-900 dark:text-amber-100',
                messageClass: 'text-amber-700 dark:text-amber-300',
                iconBgClass: 'bg-amber-100 dark:bg-amber-800',
                iconClass: 'text-amber-600 dark:text-amber-400',
                actionClass: 'text-amber-700 dark:text-amber-400',
                progressBgClass: 'bg-amber-100 dark:bg-amber-900',
                progressClass: 'bg-amber-500',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
            },
            info: {
                title: 'Information',
                bgClass: 'bg-blue-50/95 dark:bg-blue-900/20',
                borderClass: 'border-blue-200 dark:border-blue-800',
                textClass: 'text-blue-900 dark:text-blue-100',
                titleClass: 'text-blue-900 dark:text-blue-100',
                messageClass: 'text-blue-700 dark:text-blue-300',
                iconBgClass: 'bg-blue-100 dark:bg-blue-800',
                iconClass: 'text-blue-600 dark:text-blue-400',
                actionClass: 'text-blue-700 dark:text-blue-400',
                progressBgClass: 'bg-blue-100 dark:bg-blue-900',
                progressClass: 'bg-blue-500',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            }
        };

        return configs[type] || configs.info;
    },

    /**
     * Remove notification
     */
    remove(id) {
        const notification = document.querySelector(`[data-notification-id="${id}"]`);
        if (!notification) return;

        // Animate out
        notification.classList.remove('notification-enter-active');
        notification.classList.add('notification-leave-active');

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
            this.notifications = this.notifications.filter(n => n.id !== id);
        }, 300);
    },

    /**
     * Remove oldest notification
     */
    removeOldest() {
        if (this.notifications.length > 0) {
            this.remove(this.notifications[0].id);
        }
    },

    /**
     * Clear all notifications
     */
    clearAll() {
        this.notifications.forEach(n => this.remove(n.id));
    },

    /**
     * Handle notification action
     */
    handleAction(id, action) {
        if (typeof action === 'function') {
            action();
        } else if (typeof action === 'string') {
            window.location.href = action;
        }
        this.remove(id);
    },

    /**
     * Toggle sound
     */
    toggleSound() {
        this.config.soundsEnabled = !this.config.soundsEnabled;
        this.saveSettings();
        return this.config.soundsEnabled;
    },

    /**
     * Set volume
     */
    setVolume(volume) {
        this.config.soundVolume = Math.max(0, Math.min(1, volume));
        this.saveSettings();
    },

    /**
     * Set position
     */
    setPosition(position) {
        this.config.position = position;
        const container = document.getElementById('safarstep-notifications-container');
        if (container) {
            container.className = this.getContainerPosition();
        }
        this.saveSettings();
    }
};

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    .notification-enter {
        opacity: 0;
        transform: translateX(100%);
    }

    .notification-enter-active {
        opacity: 1;
        transform: translateX(0);
    }

    .notification-leave-active {
        opacity: 0;
        transform: translateX(100%);
    }

    @keyframes progress {
        from { width: 100%; }
        to { width: 0%; }
    }

    .notification-progress {
        transition: width 0.1s linear;
    }

    /* Hover effects */
    .notification:hover .notification-progress {
        animation-play-state: paused;
    }
`;
document.head.appendChild(style);

// Initialize on DOM load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        SafarStepNotifications.init();
    });
} else {
    SafarStepNotifications.init();
}

// Global shortcuts
window.notify = {
    success: (msg, opts) => SafarStepNotifications.success(msg, opts),
    error: (msg, opts) => SafarStepNotifications.error(msg, opts),
    warning: (msg, opts) => SafarStepNotifications.warning(msg, opts),
    info: (msg, opts) => SafarStepNotifications.info(msg, opts),
    show: (msg, type, opts) => SafarStepNotifications.show(msg, type, opts)
};
