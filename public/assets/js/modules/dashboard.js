/**
 * Dashboard JavaScript Module for SafarStep
 * Handles charts, widgets, and dashboard interactions
 */

// Dashboard Component Functions
window.dashboardComponent = {
    // Initialize charts when page loads
    init() {
        this.initializeCharts();
        this.loadRecentActivities();
    },

    // Initialize all dashboard charts
    initializeCharts() {
        this.initRevenueChart();
        this.initBookingsChart();
        this.initStatusChart();
    },

    // Revenue Overview Chart
    initRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [12400, 19800, 8600, 15200, 22400, 18900, 25100],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    },

    // Bookings Trend Chart
    initBookingsChart() {
        const ctx = document.getElementById('bookingsChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Bookings',
                    data: [8, 12, 6, 9, 14, 11, 16],
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    },

    // Booking Status Distribution Chart
    initStatusChart() {
        const ctx = document.getElementById('statusChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Pending', 'Cancelled', 'Completed'],
                datasets: [{
                    data: [45, 25, 15, 35],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // Green - Confirmed
                        'rgba(251, 191, 36, 0.8)',  // Yellow - Pending
                        'rgba(239, 68, 68, 0.8)',   // Red - Cancelled
                        'rgba(99, 102, 241, 0.8)'   // Indigo - Completed
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                        'rgb(99, 102, 241)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    },

    // Load recent activities data
    loadRecentActivities() {
        // Mock data - would come from API
        const activities = [
            {
                id: 1,
                description: 'New booking created for Dubai Package',
                user: 'Sarah Johnson',
                date: '2025-01-12 10:30 AM',
                status: 'confirmed'
            },
            {
                id: 2,
                description: 'Payment received for Turkey Tour',
                user: 'Michael Brown',
                date: '2025-01-12 09:15 AM',
                status: 'completed'
            },
            {
                id: 3,
                description: 'Offer sent to client for Maldives Trip',
                user: 'Emily Davis',
                date: '2025-01-12 08:45 AM',
                status: 'pending'
            },
            {
                id: 4,
                description: 'Booking cancelled for Paris Package',
                user: 'John Smith',
                date: '2025-01-11 04:20 PM',
                status: 'cancelled'
            },
            {
                id: 5,
                description: 'New customer registration',
                user: 'Lisa Wilson',
                date: '2025-01-11 02:10 PM',
                status: 'confirmed'
            }
        ];

        // Set activities data for Alpine.js
        this.activities = activities;
        return activities;
    },

    // Get status badge CSS classes
    getStatusClass(status) {
        const classes = {
            'confirmed': 'bg-green-100 text-green-800',
            'pending': 'bg-yellow-100 text-yellow-800',
            'cancelled': 'bg-red-100 text-red-800',
            'completed': 'bg-accent-100 text-accent-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    },

    // Refresh dashboard data
    refreshData() {
        // Show loading state
        this.showNotification('Refreshing dashboard data...', 'info');
        
        // Simulate API call
        setTimeout(() => {
            // Reload activities
            this.loadRecentActivities();
            
            // Update widgets (would fetch from API)
            this.updateWidgets();
            
            this.showNotification('Dashboard refreshed successfully!', 'success');
        }, 1500);
    },

    // Update widget data
    updateWidgets() {
        // Mock updated data
        const updatedData = {
            tourism_offers: 26,
            confirmed_bookings: 20,
            conversion_rate: 26.3,
            active_trips: 14,
            completed_bookings: 35,
            cancelled_bookings: 3,
            total_revenue: 132800
        };

        // Update widget values (would update DOM elements)
        Object.keys(updatedData).forEach(key => {
            const element = document.querySelector(`[data-widget="${key}"] .widget-value`);
            if (element) {
                element.textContent = key === 'total_revenue' 
                    ? '$' + updatedData[key].toLocaleString()
                    : key === 'conversion_rate' 
                    ? updatedData[key] + '%'
                    : updatedData[key];
            }
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

// Alpine.js Dashboard Data
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardData', () => ({
        // Dashboard widget data
        widgets: window.dashboardData || {},
        
        // Recent activities
        activities: [],
        
        // Loading states
        isLoading: false,
        isRefreshing: false,

        // Initialize component
        init() {
            this.loadRecentActivities();
            
            // Initialize charts after DOM is ready
            this.$nextTick(() => {
                window.dashboardComponent.init();
            });
        },

        // Load recent activities
        loadRecentActivities() {
            this.activities = window.dashboardComponent.loadRecentActivities();
        },

        // Get status class for activity badges
        getStatusClass(status) {
            return window.dashboardComponent.getStatusClass(status);
        },

        // Refresh dashboard
        async refreshDashboard() {
            this.isRefreshing = true;
            await new Promise(resolve => setTimeout(resolve, 1500)); // Simulate API call
            
            this.loadRecentActivities();
            window.dashboardComponent.updateWidgets();
            
            this.isRefreshing = false;
            window.dashboardComponent.showNotification('Dashboard refreshed!', 'success');
        },

        // Format date for display
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        }
    }));
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard component
    if (typeof window.dashboardComponent !== 'undefined') {
        window.dashboardComponent.init();
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.dashboardComponent;
}