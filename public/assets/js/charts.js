/**
 * SafarStep Charts JavaScript
 * Chart management and data visualization
 */

// Chart utilities
window.Charts = {

    /**
     * Initialize all charts
     */
    init: function() {
        console.log('Charts.init() called - chart system initialized');
        
        this.initializeRevenueChart();
        this.initializePerformanceCharts();
        this.setupChartInteractions();
    },

    /**
     * Initialize revenue overview chart
     */
    initializeRevenueChart: function() {
        console.log('Initializing revenue chart');
        
        const ctx = document.getElementById('revenueChart');
        if (!ctx && typeof Chart !== 'undefined') {
            // Create a placeholder chart area if it doesn't exist
            const chartContainer = document.querySelector('.h-64');
            if (chartContainer) {
                const canvas = document.createElement('canvas');
                canvas.id = 'revenueChart';
                canvas.style.maxHeight = '256px';
                
                // Replace the placeholder content
                chartContainer.innerHTML = '';
                chartContainer.appendChild(canvas);
                
                this.createRevenueChart(canvas);
            }
        } else if (ctx && typeof Chart !== 'undefined') {
            this.createRevenueChart(ctx);
        }
    },

    /**
     * Create revenue chart
     */
    createRevenueChart: function(ctx) {
        const revenueData = {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue',
                data: [1200, 1900, 3000, 5000, 2000, 3000, 4500],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };

        const config = {
            type: 'line',
            data: revenueData,
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
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        };

        new Chart(ctx, config);
        console.log('Revenue chart created successfully');
    },

    /**
     * Initialize performance charts
     */
    initializePerformanceCharts: function() {
        console.log('Initializing performance charts');
        
        // Initialize any additional charts
        this.initializeConversionChart();
        this.initializeBookingTrendsChart();
    },

    /**
     * Initialize conversion rate chart
     */
    initializeConversionChart: function() {
        const ctx = document.getElementById('conversionChart');
        if (ctx && typeof Chart !== 'undefined') {
            const conversionData = {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Conversion Rate %',
                    data: [23, 28, 24, 31],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 2
                }]
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: conversionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    },

    /**
     * Initialize booking trends chart
     */
    initializeBookingTrendsChart: function() {
        const ctx = document.getElementById('bookingTrendsChart');
        if (ctx && typeof Chart !== 'undefined') {
            const trendData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Confirmed',
                        data: [12, 19, 25, 32, 28, 35],
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 2
                    },
                    {
                        label: 'Cancelled',
                        data: [2, 3, 1, 4, 2, 3],
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 2
                    }
                ]
            };

            new Chart(ctx, {
                type: 'bar',
                data: trendData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }
    },

    /**
     * Setup chart interactions
     */
    setupChartInteractions: function() {
        console.log('Chart interactions setup');
        
        // Chart period buttons
        document.querySelectorAll('.chart-period-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const period = e.target.dataset.period;
                this.updateChartPeriod(period);
            });
        });
    },

    /**
     * Update chart data based on period
     */
    updateChartPeriod: function(period) {
        console.log(`Updating charts for period: ${period}`);
        
        // Update chart data based on selected period
        // This would typically fetch new data from the API
        const mockData = this.getMockDataForPeriod(period);
        
        // Update existing charts with new data
        Chart.instances.forEach(chart => {
            if (chart.canvas.id === 'revenueChart') {
                chart.data.datasets[0].data = mockData.revenue;
                chart.update();
            }
        });
    },

    /**
     * Get mock data for different periods
     */
    getMockDataForPeriod: function(period) {
        const mockData = {
            '7d': {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                revenue: [1200, 1900, 3000, 5000, 2000, 3000, 4500]
            },
            '30d': {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                revenue: [15000, 22000, 18000, 25000]
            },
            '90d': {
                labels: ['Month 1', 'Month 2', 'Month 3'],
                revenue: [45000, 52000, 48000]
            }
        };
        
        return mockData[period] || mockData['7d'];
    },

    /**
     * Create chart from data
     */
    createChart: function(canvas, type, data, options = {}) {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js is not available');
            return null;
        }

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            }
        };

        const chartConfig = {
            type: type,
            data: data,
            options: { ...defaultOptions, ...options }
        };

        return new Chart(canvas, chartConfig);
    },

    /**
     * Destroy all charts
     */
    destroyAllCharts: function() {
        if (typeof Chart !== 'undefined' && Chart.instances) {
            Chart.instances.forEach(chart => {
                chart.destroy();
            });
        }
    },

    /**
     * Refresh all charts
     */
    refreshAllCharts: function() {
        console.log('Refreshing all charts');
        
        if (typeof Chart !== 'undefined' && Chart.instances) {
            Chart.instances.forEach(chart => {
                chart.update();
            });
        }
    }
};

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Charts JavaScript loaded');
    
    // Wait for Chart.js to be available
    const initCharts = () => {
        if (typeof Chart !== 'undefined') {
            Charts.init();
        } else {
            console.log('Waiting for Chart.js to load...');
            setTimeout(initCharts, 100);
        }
    };
    
    initCharts();
});

// Global chart functions
window.loadChartData = function(period) {
    if (window.Charts) {
        window.Charts.updateChartPeriod(period);
    }
};

window.refreshCharts = function() {
    if (window.Charts) {
        window.Charts.refreshAllCharts();
    }
};