/**
 * SafarStep Dashboard JavaScript
 * Main dashboard functionality and widget management
 */

// Dashboard configuration
window.dashboardConfig = window.dashboardConfig || {};

// Dashboard utilities
window.Dashboard = {
    
    /**
     * Initialize dashboard components
     */
    init: function() {
        console.log('Dashboard.init() called - dashboard components initialized');
        
        // Initialize dashboard-specific components
        this.initializeWidgets();
        this.setupEventListeners();
        this.startAutoRefresh();
    },

    /**
     * Initialize dashboard widgets
     */
    initializeWidgets: function() {
        console.log('Dashboard widgets initialized');
        
        // Widget-specific initialization
        document.querySelectorAll('.dashboard-widget').forEach(widget => {
            const widgetType = widget.dataset.widget;
            console.log(`Initializing widget: ${widgetType}`);
            
            // Add hover effects
            widget.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg', 'scale-105');
                this.classList.remove('shadow-md');
            });
            
            widget.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg', 'scale-105');
                this.classList.add('shadow-md');
            });
        });
    },

    /**
     * Setup dashboard event listeners
     */
    setupEventListeners: function() {
        console.log('Dashboard event listeners setup');
        
        // Chart period buttons
        document.querySelectorAll('.chart-period-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.chart-period-btn').forEach(b => {
                    b.classList.remove('active', 'text-accent-600', 'bg-accent-100');
                    b.classList.add('text-gray-500', 'bg-gray-100');
                });
                this.classList.add('active', 'text-accent-600', 'bg-accent-100');
                this.classList.remove('text-gray-500', 'bg-gray-100');
                
                console.log(`Chart period changed to: ${this.dataset.period}`);
            });
        });

        // Refresh button
        const refreshBtn = document.querySelector('[onclick="refreshDashboard()"]');
        if (refreshBtn) {
            refreshBtn.removeAttribute('onclick');
            refreshBtn.addEventListener('click', this.refresh.bind(this));
        }

        // Export button
        const exportBtn = document.querySelector('[onclick="exportDashboard()"]');
        if (exportBtn) {
            exportBtn.removeAttribute('onclick');
            exportBtn.addEventListener('click', this.export.bind(this));
        }
    },

    /**
     * Refresh dashboard data
     */
    refresh: function() {
        console.log('Dashboard refresh triggered');
        
        // Show loading state
        const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]') || 
                          document.querySelector('button:has([class*="rotate"])');
        
        if (refreshBtn) {
            const icon = refreshBtn.querySelector('svg');
            if (icon) {
                icon.classList.add('animate-spin');
                setTimeout(() => icon.classList.remove('animate-spin'), 2000);
            }
        }

        // Trigger refresh via existing function if available
        if (typeof window.initializeDashboard === 'function') {
            window.initializeDashboard();
        }
    },

    /**
     * Export dashboard data
     */
    export: function() {
        console.log('Dashboard export triggered');
        
        // Show loading state on export button
        const exportBtn = document.querySelector('button:has([viewBox="0 0 24 24"]):has([d*="download"])') ||
                         document.querySelector('button:contains("Export")');
        
        if (exportBtn) {
            const originalText = exportBtn.textContent;
            exportBtn.textContent = 'Exporting...';
            exportBtn.disabled = true;
            
            setTimeout(() => {
                exportBtn.textContent = originalText;
                exportBtn.disabled = false;
            }, 2000);
        }

        // Attempt to trigger export via existing function
        if (typeof window.exportDashboard === 'function') {
            window.exportDashboard();
        } else {
            // Fallback: show success message
            this.showNotification('success', 'Export', 'Dashboard export initiated');
        }
    },

    /**
     * Start auto-refresh functionality
     */
    startAutoRefresh: function() {
        console.log('Dashboard auto-refresh started');
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            console.log('Auto-refreshing dashboard...');
            if (typeof window.loadWidgetData === 'function') {
                window.loadWidgetData();
            }
        }, 30000);
    },

    /**
     * Show notification
     */
    showNotification: function(type, title, message) {
        console.log(`Notification: ${type} - ${title}: ${message}`);
        
        // Try to use Alpine.js notification if available
        if (window.Alpine && window.Alpine.store) {
            try {
                const app = window.Alpine.store('app');
                if (app && app.showNotification) {
                    app.showNotification(type, title, message);
                    return;
                }
            } catch (e) {
                console.log('Alpine notification not available, using fallback');
            }
        }

        // Fallback notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 bg-${type === 'success' ? 'green' : 'blue'}-100 border border-${type === 'success' ? 'green' : 'blue'}-400 text-${type === 'success' ? 'green' : 'blue'}-700 px-4 py-3 rounded z-50`;
        notification.innerHTML = `
            <strong>${title}</strong>
            <span class="block sm:inline">${message}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        `;
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => notification.remove(), 5000);
    }
};

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JavaScript loaded');
    
    // Initialize Dashboard
    if (typeof Dashboard !== 'undefined') {
        Dashboard.init();
    }
});

// Global functions for compatibility
window.refreshDashboard = function() {
    if (window.Dashboard) {
        window.Dashboard.refresh();
    }
};

window.exportDashboard = function() {
    if (window.Dashboard) {
        window.Dashboard.export();
    }
};