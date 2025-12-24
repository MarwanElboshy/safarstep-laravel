/**
 * SafarStep Widgets JavaScript
 * Widget management and interactions
 */

// Widget utilities
window.Widgets = {

    /**
     * Initialize all widgets
     */
    init: function() {
        // Widget system initialized
        
        this.initializeAnimations();
        this.setupWidgetInteractions();
        this.initializeWidgetData();
    },

    /**
     * Initialize widget animations
     */
    initializeAnimations: function() {
        // Widget animations initialized
        
        // Add intersection observer for widget animations
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.dashboard-widget').forEach(widget => {
                observer.observe(widget);
            });
        }
    },

    /**
     * Setup widget interactions
     */
    setupWidgetInteractions: function() {
        // Widget interactions setup
        
        // Widget click handlers
        document.querySelectorAll('.dashboard-widget').forEach(widget => {
            const widgetType = widget.dataset.widget;
            
            widget.addEventListener('click', () => {
                this.handleWidgetClick(widgetType, widget);
            });

            // Add keyboard accessibility
            widget.setAttribute('tabindex', '0');
            widget.setAttribute('role', 'button');
            widget.setAttribute('aria-label', `${widgetType} widget`);
            
            widget.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.handleWidgetClick(widgetType, widget);
                }
            });
        });
    },

    /**
     * Handle widget click
     */
    handleWidgetClick: function(widgetType, widget) {
        console.log(`Widget clicked: ${widgetType}`);
        
        // Add click animation
        widget.classList.add('transform', 'scale-95');
        setTimeout(() => {
            widget.classList.remove('scale-95');
        }, 150);

        // Widget-specific actions
        switch (widgetType) {
            case 'tourism_offers':
                this.navigateToModule('offers');
                break;
            case 'confirmed_bookings':
            case 'active_trips':
            case 'completed_bookings':
            case 'cancelled_bookings':
                this.navigateToModule('bookings', widgetType.replace('_bookings', '').replace('_trips', ''));
                break;
            case 'conversion_rate':
                this.showWidgetDetails(widgetType);
                break;
            case 'total_revenue':
                this.navigateToModule('financial');
                break;
            default:
                console.log(`No specific action defined for widget: ${widgetType}`);
        }
    },

    /**
     * Navigate to module
     */
    navigateToModule: function(module, filter = null) {
        const baseUrl = window.safarStepConfig ? window.safarStepConfig.baseUrl : this.detectBaseUrl();
        let url = `${baseUrl}/dashboard/${module}`;
        
        if (filter) {
            url += `?filter=${filter}`;
        }
        
        console.log(`Navigating to: ${url}`);
        window.location.href = url;
    },

    /**
     * Detect base URL dynamically (fallback method)
     */
    detectBaseUrl: function() {
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
                return match[1];
            }
        }

        // Default fallback
        return '';
    },

    /**
     * Show widget details
     */
    showWidgetDetails: function(widgetType) {
        console.log(`Showing details for widget: ${widgetType}`);
        
        // Create modal or details view
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900">${this.getWidgetTitle(widgetType)} Details</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ${this.getWidgetDescription(widgetType)}
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button class="px-4 py-2 bg-accent-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-accent-300" onclick="this.closest('.fixed').remove()">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    },

    /**
     * Initialize widget data
     */
    initializeWidgetData: function() {
        console.log('Widget data initialization');
        
        // Update widget values with animation
        this.animateNumbers();
        this.updateTrends();
    },

    /**
     * Animate number counters
     */
    animateNumbers: function() {
        document.querySelectorAll('[data-value]').forEach(element => {
            const target = element.textContent.replace(/[^\d]/g, '');
            if (target && !isNaN(target)) {
                this.animateCounter(element, 0, parseInt(target), 1000);
            }
        });
    },

    /**
     * Animate counter
     */
    animateCounter: function(element, start, end, duration) {
        const startTime = performance.now();
        const originalText = element.textContent;
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(start + (end - start) * progress);
            
            // Preserve formatting
            if (originalText.includes('$')) {
                element.textContent = '$' + current.toLocaleString();
            } else if (originalText.includes('%')) {
                element.textContent = current + '%';
            } else {
                element.textContent = current.toLocaleString();
            }
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    },

    /**
     * Update trend indicators
     */
    updateTrends: function() {
        document.querySelectorAll('[data-trend]').forEach(element => {
            const trendValue = Math.floor(Math.random() * 20) - 10; // -10 to +10
            const icon = trendValue >= 0 ? '↗' : '↘';
            const colorClass = trendValue >= 0 ? 'text-green-600' : 'text-red-600';
            
            element.innerHTML = `<span class="${colorClass}">${icon} ${Math.abs(trendValue)}%</span>`;
            
            // Add animation
            element.classList.add('animate-pulse');
            setTimeout(() => element.classList.remove('animate-pulse'), 500);
        });
    },

    /**
     * Get widget title
     */
    getWidgetTitle: function(widgetType) {
        const titles = {
            'tourism_offers': 'Tourism Offers',
            'confirmed_bookings': 'Confirmed Bookings',
            'conversion_rate': 'Conversion Rate',
            'active_trips': 'Active Trips',
            'completed_bookings': 'Completed Bookings',
            'cancelled_bookings': 'Cancelled Bookings',
            'total_revenue': 'Total Revenue'
        };
        
        return titles[widgetType] || 'Widget';
    },

    /**
     * Get widget description
     */
    getWidgetDescription: function(widgetType) {
        const descriptions = {
            'tourism_offers': 'Total number of active tourism offers available to customers.',
            'confirmed_bookings': 'Number of bookings that have been confirmed by customers.',
            'conversion_rate': 'Percentage of offers that convert to confirmed bookings.',
            'active_trips': 'Number of trips currently in progress.',
            'completed_bookings': 'Total number of successfully completed bookings.',
            'cancelled_bookings': 'Number of bookings that have been cancelled.',
            'total_revenue': 'Total revenue generated from all bookings.'
        };
        
        return descriptions[widgetType] || 'Widget information not available.';
    }
};

// Initialize widgets when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Widgets JavaScript loaded
    
    // Initialize Widgets
    if (typeof Widgets !== 'undefined') {
        Widgets.init();
    }
});

// CSS animations (will be injected)
const widgetStyles = `
    @keyframes animate-fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: animate-fade-in 0.6s ease-out;
    }
    
    .dashboard-widget {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .dashboard-widget:hover {
        transform: translateY(-2px);
    }
    
    .dashboard-widget:active {
        transform: translateY(0);
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = widgetStyles;
document.head.appendChild(styleSheet);