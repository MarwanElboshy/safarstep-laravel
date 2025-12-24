/**
 * SafarStep Main Application JavaScript
 * Entry point for the SafarStep SaaS Tourism Management Platform
 */

// Application initialization
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize SafarStep application
    // SafarStep Tourism Management Platform - Loading...
    
    // Check if all required dependencies are loaded
    const requiredLibraries = [
        { name: 'Alpine.js', check: () => typeof Alpine !== 'undefined' },
        { name: 'Chart.js', check: () => typeof Chart !== 'undefined' },
        { name: 'SafarStep Utils', check: () => typeof SafarStep !== 'undefined' }
    ];
    
    let missingLibraries = [];
    requiredLibraries.forEach(lib => {
        if (!lib.check()) {
            missingLibraries.push(lib.name);
        }
    });
    
    if (missingLibraries.length > 0) {
        console.error('❌ Missing required libraries:', missingLibraries.join(', '));
        return;
    }
    
    // All dependencies loaded successfully
    
    // Initialize Alpine.js global data
    initializeAlpineGlobals();
    
    // Setup global Alpine.js directives
    setupAlpineDirectives();
    
    // Initialize application features
    initializeFeatures();
    
    // Setup error handling
    setupErrorHandling();
    
    // SafarStep application initialized successfully
});

/**
 * Initialize Alpine.js global data and stores
 */
function initializeAlpineGlobals() {
    
    // Global application state
    Alpine.store('app', {
        // Loading states
        isLoading: false,
        
        // Current user
        user: null,
        
        // Current tenant
        tenant: null,
        
        // Navigation state
        sidebarOpen: false,
        mobileMenuOpen: false,
        
        // Notification state
        notifications: [],
        
        // Modal states
        activeModal: null,
        
        // Theme state
        theme: localStorage.getItem('safarstep_theme') || 'light',
        
        // Methods
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('safarstep_sidebar_open', this.sidebarOpen);
        },
        
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        
        closeMobileMenu() {
            this.mobileMenuOpen = false;
        },
        
        openModal(modalName) {
            this.activeModal = modalName;
        },
        
        closeModal() {
            this.activeModal = null;
        },
        
        addNotification(notification) {
            notification.id = Date.now();
            this.notifications.push(notification);
            
            // Auto remove after duration
            setTimeout(() => {
                this.removeNotification(notification.id);
            }, notification.duration || 5000);
        },
        
        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        
        setTheme(theme) {
            this.theme = theme;
            localStorage.setItem('safarstep_theme', theme);
            document.documentElement.classList.toggle('dark', theme === 'dark');
        },
        
        init() {
            // Restore sidebar state
            const sidebarState = localStorage.getItem('safarstep_sidebar_open');
            if (sidebarState !== null) {
                this.sidebarOpen = sidebarState === 'true';
            }
            
            // Apply theme
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
            
            // Load user data
            this.loadUserData();
        },
        
        loadUserData() {
            // Load from SafarStep config if available
            if (window.SafarStep && window.SafarStep.config.user) {
                this.user = window.SafarStep.config.user;
                this.tenant = window.SafarStep.config.tenant;
            }
        }
    });
    
    // Global form handling store
    Alpine.store('forms', {
        // Form submission state
        submitting: {},
        
        // Form validation errors
        errors: {},
        
        // Form data
        data: {},
        
        // Methods
        setSubmitting(formName, state) {
            this.submitting[formName] = state;
        },
        
        isSubmitting(formName) {
            return this.submitting[formName] || false;
        },
        
        setErrors(formName, errors) {
            this.errors[formName] = errors;
        },
        
        getErrors(formName) {
            return this.errors[formName] || {};
        },
        
        hasError(formName, field) {
            const formErrors = this.getErrors(formName);
            return formErrors[field] && formErrors[field].length > 0;
        },
        
        getError(formName, field) {
            const formErrors = this.getErrors(formName);
            return formErrors[field] ? formErrors[field][0] : '';
        },
        
        clearErrors(formName) {
            delete this.errors[formName];
        },
        
        setData(formName, data) {
            this.data[formName] = data;
        },
        
        getData(formName) {
            return this.data[formName] || {};
        },
        
        clearData(formName) {
            delete this.data[formName];
        }
    });
}

/**
 * Setup custom Alpine.js directives
 */
function setupAlpineDirectives() {
    
    // Auto-focus directive
    Alpine.directive('auto-focus', (el) => {
        setTimeout(() => el.focus(), 100);
    });
    
    // Click outside directive
    Alpine.directive('click-outside', (el, { expression }, { evaluate }) => {
        const handler = (e) => {
            if (!el.contains(e.target)) {
                evaluate(expression);
            }
        };
        
        document.addEventListener('click', handler);
        
        // Cleanup
        el._clickOutsideHandler = handler;
    });
    
    // Tooltip directive
    Alpine.directive('tooltip', (el, { expression }, { evaluate }) => {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg opacity-0 pointer-events-none transition-opacity duration-200';
        tooltip.textContent = evaluate(expression);
        
        const showTooltip = () => {
            document.body.appendChild(tooltip);
            const rect = el.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
            tooltip.style.opacity = '1';
        };
        
        const hideTooltip = () => {
            tooltip.style.opacity = '0';
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            }, 200);
        };
        
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', hideTooltip);
    });
    
    // Auto-resize textarea directive
    Alpine.directive('auto-resize', (el) => {
        const resize = () => {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        };
        
        el.addEventListener('input', resize);
        resize(); // Initial resize
    });
}

/**
 * Initialize application features
 */
function initializeFeatures() {
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
    
    // Initialize auto-save functionality
    initializeAutoSave();
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Initialize performance monitoring
    initializePerformanceMonitoring();
    
    // Initialize accessibility features
    initializeAccessibility();
    
    // Initialize PWA features
    initializePWA();
}

/**
 * Initialize keyboard shortcuts
 */
function initializeKeyboardShortcuts() {
    const shortcuts = {
        // Global shortcuts
        'ctrl+k': () => {
            // Open command palette (future feature)
            console.log('Command palette shortcut');
        },
        'ctrl+/': () => {
            // Show keyboard shortcuts help
            Alpine.store('app').openModal('shortcuts-help');
        },
        'esc': () => {
            // Close active modal
            Alpine.store('app').closeModal();
        },
        // Navigation shortcuts
        'alt+1': () => window.location.href = '/dashboard',
        'alt+2': () => window.location.href = '/users',
        'alt+3': () => window.location.href = '/bookings',
        'alt+4': () => window.location.href = '/offers',
        'alt+5': () => window.location.href = '/financial'
    };
    
    document.addEventListener('keydown', (e) => {
        const key = [];
        if (e.ctrlKey) key.push('ctrl');
        if (e.altKey) key.push('alt');
        if (e.shiftKey) key.push('shift');
        key.push(e.key.toLowerCase());
        
        const shortcut = key.join('+');
        
        if (shortcuts[shortcut]) {
            e.preventDefault();
            shortcuts[shortcut]();
        }
    });
}

/**
 * Initialize auto-save functionality
 */
function initializeAutoSave() {
    let autoSaveTimeout;
    
    // Auto-save forms after user stops typing
    document.addEventListener('input', (e) => {
        if (e.target.matches('[data-auto-save]')) {
            clearTimeout(autoSaveTimeout);
            
            autoSaveTimeout = setTimeout(() => {
                const formName = e.target.getAttribute('data-auto-save');
                autoSaveForm(formName, e.target.form);
            }, 2000); // Save after 2 seconds of inactivity
        }
    });
    
    function autoSaveForm(formName, form) {
        if (!form) return;
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Save to localStorage as backup
        localStorage.setItem(`safarstep_autosave_${formName}`, JSON.stringify({
            data: data,
            timestamp: Date.now()
        }));
        
        // Show auto-save indicator
        showAutoSaveIndicator();
    }
    
    function showAutoSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-3 py-1 rounded-lg text-sm z-50';
        indicator.textContent = 'Auto-saved';
        
        document.body.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    }
}

/**
 * Initialize real-time updates (WebSocket or polling)
 */
function initializeRealTimeUpdates() {
    // Check if real-time updates are enabled
    if (!window.REALTIME_UPDATES_ENABLED) return;
    
    // Initialize WebSocket connection for real-time updates
    // This would connect to your WebSocket server
    // For now, we'll use polling as a fallback
    
    setInterval(() => {
        // Poll for updates every 30 seconds
        if (document.visibilityState === 'visible') {
            checkForUpdates();
        }
    }, 30000);
    
    function checkForUpdates() {
        // Check for new notifications, messages, etc.
        // This would make API calls to check for updates
        
        // Example: Check for new notifications
        if (window.SafarStep && window.SafarStep.api) {
            window.SafarStep.api.get('/notifications/unread')
                .then(response => {
                    if (response.success && response.data.length > 0) {
                        // Update notification counter
                        updateNotificationCounter(response.data.length);
                    }
                })
                .catch(error => {
                    console.error('Failed to check for updates:', error);
                });
        }
    }
    
    function updateNotificationCounter(count) {
        const counters = document.querySelectorAll('[data-notification-count]');
        counters.forEach(counter => {
            counter.textContent = count;
            counter.style.display = count > 0 ? 'block' : 'none';
        });
    }
}

/**
 * Initialize performance monitoring
 */
function initializePerformanceMonitoring() {
    // Monitor page load performance
    window.addEventListener('load', () => {
        setTimeout(() => {
            const perfData = performance.timing;
            const loadTime = perfData.loadEventEnd - perfData.navigationStart;
            
            console.log(`📊 Page load time: ${loadTime}ms`);
            
            // Send performance data to analytics (if configured)
            if (window.ANALYTICS_ENABLED) {
                // gtag('event', 'page_load_time', { value: loadTime });
            }
        }, 0);
    });
    
    // Monitor JavaScript errors
    window.addEventListener('error', (e) => {
        console.error('JavaScript error:', e.error);
        
        // Log error to monitoring service
        if (window.ERROR_REPORTING_ENABLED) {
            // Send error to monitoring service
        }
    });
    
    // Monitor unhandled promise rejections
    window.addEventListener('unhandledrejection', (e) => {
        console.error('Unhandled promise rejection:', e.reason);
        
        // Log error to monitoring service
        if (window.ERROR_REPORTING_ENABLED) {
            // Send error to monitoring service
        }
    });
}

/**
 * Initialize accessibility features
 */
function initializeAccessibility() {
    // Skip to content link
    const skipLink = document.createElement('a');
    skipLink.href = '#main-content';
    skipLink.textContent = 'Skip to main content';
    skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-accent-600 text-white p-2 z-50';
    document.body.insertBefore(skipLink, document.body.firstChild);
    
    // Focus management for modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            const activeModal = document.querySelector('[role="dialog"]:not([style*="display: none"])');
            if (activeModal) {
                trapFocus(e, activeModal);
            }
        }
    });
    
    function trapFocus(e, container) {
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            if (document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            }
        } else {
            if (document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    }
    
    // Announce dynamic content changes to screen readers
    window.announceToScreenReader = function(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    };
}

/**
 * Initialize PWA features
 */
function initializePWA() {
    // Register service worker (if available)
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('✅ Service Worker registered:', registration);
            })
            .catch(error => {
                console.log('❌ Service Worker registration failed:', error);
            });
    }
    
    // Handle install prompt
    let deferredPrompt;
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Show install button
        const installButton = document.querySelector('[data-install-app]');
        if (installButton) {
            installButton.style.display = 'block';
            installButton.addEventListener('click', () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        }
    });
}

/**
 * Setup global error handling
 */
function setupErrorHandling() {
    // Global error handler
    window.addEventListener('error', (e) => {
        console.error('Global error:', e.error);
        
        // Show user-friendly error message
        if (window.SafarStep && window.SafarStep.notify) {
            window.SafarStep.notify.error('Something went wrong. Please try again.');
        }
    });
    
    // Handle network errors
    window.addEventListener('online', () => {
        if (window.SafarStep && window.SafarStep.notify) {
            window.SafarStep.notify.success('Connection restored');
        }
    });
    
    window.addEventListener('offline', () => {
        if (window.SafarStep && window.SafarStep.notify) {
            window.SafarStep.notify.warning('You are currently offline');
        }
    });
}

// Export application object for external use
window.SafarStepApp = {
    version: '1.0.0',
    
    // Utility methods
    utils: {
        initializeAlpineGlobals,
        setupAlpineDirectives,
        initializeFeatures
    },
    
    // Configuration
    config: {
        version: '1.0.0',
        buildDate: new Date().toISOString(),
        environment: 'development'
    },
    
    // Methods
    restart() {
        location.reload();
    },
    
    getVersion() {
        return this.version;
    }
};

// SafarStep App initialized