/**
 * SafarStep Business Components
 * Tourism platform specific components
 */

// =============================================================================
// DASHBOARD COMPONENTS
// =============================================================================

/**
 * Dashboard Widget Component - Individual dashboard widgets
 */
function DashboardWidgetComponent() {
    return {
        ...SafarStepComponents.get('baseComponent')(),
        widgetId: '',
        widgetType: 'counter',
        title: '',
        value: 0,
        change: 0,
        changeType: 'positive',
        icon: 'chart',
        refreshInterval: null,
        autoRefresh: false,

        init() {
            if (this.autoRefresh) {
                this.startAutoRefresh();
            }
            this.loadWidgetData();
        },

        async loadWidgetData() {
            if (!this.widgetId) return;

            try {
                const response = await this.apiRequest(`/dashboard/widgets/${this.widgetId}`);
                if (response.success) {
                    this.updateWidgetData(response.data);
                }
            } catch (error) {
                console.error(`Failed to load widget ${this.widgetId}:`, error);
            }
        },

        updateWidgetData(data) {
            this.value = data.value || 0;
            this.change = data.change || 0;
            this.changeType = data.change >= 0 ? 'positive' : 'negative';
            
            // Trigger animation for value change
            this.$refs.value?.classList.add('animate-pulse');
            setTimeout(() => {
                this.$refs.value?.classList.remove('animate-pulse');
            }, 600);
        },

        startAutoRefresh(interval = 30000) {
            this.refreshInterval = setInterval(() => {
                this.loadWidgetData();
            }, interval);
        },

        stopAutoRefresh() {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
                this.refreshInterval = null;
            }
        },

        getIconSVG() {
            const icons = {
                chart: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
                users: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 2.25a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>',
                money: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>',
                booking: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>',
                offer: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>'
            };
            return icons[this.icon] || icons.chart;
        },

        formatValue(value) {
            if (this.widgetType === 'currency') {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(value);
            } else if (this.widgetType === 'percentage') {
                return `${value}%`;
            } else {
                return value.toLocaleString();
            }
        }
    };
}

/**
 * Dashboard Stats Component - Multiple widgets container
 */
function DashboardStatsComponent() {
    return {
        ...SafarStepComponents.get('baseComponent')(),
        widgets: [
            { id: 'tourism_offers', title: 'Tourism Offers', type: 'counter', icon: 'offer' },
            { id: 'confirmed_bookings', title: 'Confirmed Bookings', type: 'counter', icon: 'booking' },
            { id: 'conversion_rate', title: 'Conversion Rate', type: 'percentage', icon: 'chart' },
            { id: 'active_trips', title: 'Active Trips', type: 'counter', icon: 'users' },
            { id: 'completed_bookings', title: 'Completed Bookings', type: 'counter', icon: 'booking' },
            { id: 'cancelled_bookings', title: 'Cancelled Bookings', type: 'counter', icon: 'booking' },
            { id: 'total_revenue', title: 'Total Revenue', type: 'currency', icon: 'money' }
        ],
        statsData: {},

        async init() {
            await this.loadAllStats();
            this.startPeriodicRefresh();
        },

        async loadAllStats() {
            try {
                const response = await this.apiRequest('/dashboard/stats');
                if (response.success) {
                    this.statsData = response.data;
                }
            } catch (error) {
                console.error('Failed to load dashboard stats:', error);
            }
        },

        startPeriodicRefresh() {
            setInterval(() => {
                this.loadAllStats();
            }, 60000); // Refresh every minute
        },

        getWidgetValue(widgetId) {
            return this.statsData[widgetId]?.value || 0;
        },

        getWidgetChange(widgetId) {
            return this.statsData[widgetId]?.change || 0;
        }
    };
}

// =============================================================================
// BOOKING MANAGEMENT COMPONENTS
// =============================================================================

/**
 * Booking List Component - Manage booking listings
 */
function BookingListComponent() {
    return {
        ...SafarStepComponents.get('dataTableComponent')(),
        bookings: [],
        statusFilter: 'all',
        dateFilter: 'all',

        async init() {
            await this.loadBookings();
        },

        async loadBookings() {
            try {
                const response = await this.apiRequest('/bookings', {
                    method: 'GET'
                });
                
                if (response.success) {
                    this.data = response.data.bookings || [];
                    this.bookings = this.data;
                }
            } catch (error) {
                this.showError('Failed to load bookings');
            }
        },

        async updateBookingStatus(bookingId, newStatus) {
            try {
                const response = await this.apiRequest(`/bookings/${bookingId}/status`, {
                    method: 'PUT',
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.success) {
                    // Update local data
                    const booking = this.data.find(b => b.id === bookingId);
                    if (booking) {
                        booking.status = newStatus;
                    }
                    this.showSuccess('Booking status updated successfully');
                }
            } catch (error) {
                this.showError('Failed to update booking status');
            }
        },

        getStatusBadgeClass(status) {
            const classes = {
                draft: 'bg-gray-100 text-gray-800',
                sent: 'bg-accent-100 text-accent-800',
                confirmed: 'bg-green-100 text-green-800',
                paid: 'bg-purple-100 text-purple-800',
                active: 'bg-yellow-100 text-yellow-800',
                completed: 'bg-emerald-100 text-emerald-800',
                cancelled: 'bg-red-100 text-red-800'
            };
            return classes[status] || classes.draft;
        },

        filterByStatus(status) {
            this.statusFilter = status;
            this.applyFilters();
        },

        filterByDate(dateRange) {
            this.dateFilter = dateRange;
            this.applyFilters();
        },

        applyFilters() {
            let filtered = [...this.bookings];

            // Apply status filter
            if (this.statusFilter !== 'all') {
                filtered = filtered.filter(booking => booking.status === this.statusFilter);
            }

            // Apply date filter
            if (this.dateFilter !== 'all') {
                const now = new Date();
                const startDate = new Date();

                switch (this.dateFilter) {
                    case 'today':
                        startDate.setHours(0, 0, 0, 0);
                        break;
                    case 'week':
                        startDate.setDate(now.getDate() - 7);
                        break;
                    case 'month':
                        startDate.setMonth(now.getMonth() - 1);
                        break;
                }

                filtered = filtered.filter(booking => {
                    const bookingDate = new Date(booking.created_at);
                    return bookingDate >= startDate;
                });
            }

            this.data = filtered;
        }
    };
}

/**
 * Booking Form Component - Create/edit bookings
 */
function BookingFormComponent() {
    return {
        ...SafarStepComponents.get('formComponent')(),
        isEditMode: false,
        bookingId: null,
        customers: [],
        offers: [],

        init() {
            this.initForm({
                customer_name: '',
                customer_email: '',
                customer_phone: '',
                offer_id: '',
                start_date: '',
                end_date: '',
                adults_count: 1,
                children_count: 0,
                infants_count: 0,
                special_requests: ''
            });

            this.loadRelatedData();
        },

        async loadRelatedData() {
            try {
                // Load offers for selection
                const offersResponse = await this.apiRequest('/offers');
                if (offersResponse.success) {
                    this.offers = offersResponse.data.offers || [];
                }
            } catch (error) {
                console.error('Failed to load related data:', error);
            }
        },

        validateField(field) {
            switch (field) {
                case 'customer_name':
                    if (!this.formData[field] || this.formData[field].length < 2) {
                        this.errors[field] = 'Customer name is required (minimum 2 characters)';
                        return false;
                    }
                    break;
                case 'customer_email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!this.formData[field] || !emailRegex.test(this.formData[field])) {
                        this.errors[field] = 'Valid email address is required';
                        return false;
                    }
                    break;
                case 'offer_id':
                    if (!this.formData[field]) {
                        this.errors[field] = 'Please select an offer';
                        return false;
                    }
                    break;
                case 'start_date':
                    if (!this.formData[field]) {
                        this.errors[field] = 'Start date is required';
                        return false;
                    }
                    break;
            }

            // Clear error if validation passes
            delete this.errors[field];
            return true;
        },

        async saveBooking() {
            const endpoint = this.isEditMode ? `/bookings/${this.bookingId}` : '/bookings';
            const method = this.isEditMode ? 'PUT' : 'POST';

            const response = await this.submitForm(endpoint, method);
            if (response) {
                // Emit event or callback for parent component
                this.$dispatch('booking-saved', response);
                return true;
            }
            return false;
        },

        async loadBooking(bookingId) {
            this.bookingId = bookingId;
            this.isEditMode = true;

            try {
                const response = await this.apiRequest(`/bookings/${bookingId}`);
                if (response.success) {
                    this.initForm(response.data);
                }
            } catch (error) {
                this.showError('Failed to load booking details');
            }
        }
    };
}

// =============================================================================
// USER MANAGEMENT COMPONENTS
// =============================================================================

/**
 * User List Component - Employee management
 */
function UserListComponent() {
    return {
        ...SafarStepComponents.get('dataTableComponent')(),
        roles: [],
        selectedRole: 'all',

        async init() {
            await Promise.all([
                this.loadUsers(),
                this.loadRoles()
            ]);
        },

        async loadUsers() {
            try {
                const response = await this.apiRequest('/users');
                if (response.success) {
                    this.data = response.data.users || [];
                }
            } catch (error) {
                this.showError('Failed to load users');
            }
        },

        async loadRoles() {
            try {
                const response = await this.apiRequest('/rbac/roles');
                if (response.success) {
                    this.roles = response.data.roles || [];
                }
            } catch (error) {
                console.error('Failed to load roles:', error);
            }
        },

        async toggleUserStatus(userId) {
            try {
                const user = this.data.find(u => u.id === userId);
                const newStatus = user.status === 'active' ? 'inactive' : 'active';

                const response = await this.apiRequest(`/users/${userId}/status`, {
                    method: 'PUT',
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.success) {
                    user.status = newStatus;
                    this.showSuccess(`User ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully`);
                }
            } catch (error) {
                this.showError('Failed to update user status');
            }
        },

        filterByRole(roleId) {
            this.selectedRole = roleId;
            if (roleId === 'all') {
                this.filteredData = [...this.data];
            } else {
                this.filteredData = this.data.filter(user => 
                    user.roles && user.roles.some(role => role.id == roleId)
                );
            }
            this.currentPage = 1;
        }
    };
}

// =============================================================================
// REGISTER BUSINESS COMPONENTS
// =============================================================================

SafarStepComponents.register('dashboardWidget', DashboardWidgetComponent);
SafarStepComponents.register('dashboardStats', DashboardStatsComponent);
SafarStepComponents.register('bookingList', BookingListComponent);
SafarStepComponents.register('bookingForm', BookingFormComponent);
SafarStepComponents.register('userList', UserListComponent);