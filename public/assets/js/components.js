/**
 * SafarStep Components Library
 * Reusable Alpine.js components for the tourism platform
 */

// Component Manager - Handles component registration and initialization
class ComponentManager {
    constructor() {
        this.components = new Map();
        this.initialized = false;
    }

    /**
     * Register a component
     */
    register(name, component) {
        this.components.set(name, component);
        
        // If Alpine.js is already loaded, register immediately
        if (window.Alpine && this.initialized) {
            Alpine.data(name, component);
        }
    }

    /**
     * Initialize all components with Alpine.js
     */
    initialize() {
        if (window.Alpine && !this.initialized) {
            // Register all components
            for (const [name, component] of this.components) {
                Alpine.data(name, component);
            }
            this.initialized = true;
        }
    }

    /**
     * Get a component by name
     */
    get(name) {
        return this.components.get(name);
    }
}

// Create global component manager
window.SafarStepComponents = new ComponentManager();

// =============================================================================
// CORE COMPONENTS
// =============================================================================

/**
 * Base Component - Extended by all other components
 */
function BaseComponent() {
    return {
        loading: false,
        error: null,
        success: null,

        // API request helper
        async apiRequest(endpoint, options = {}) {
            this.loading = true;
            this.error = null;
            
            try {
                const { data } = await safarStepAuth.apiRequest(endpoint, options);
                return data;
            } catch (error) {
                this.error = safarStepAuth.formatError(error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        // Show success message
        showSuccess(message) {
            this.success = message;
            this.error = null;
            setTimeout(() => {
                this.success = null;
            }, 3000);
        },

        // Show error message
        showError(message) {
            this.error = message;
            this.success = null;
        },

        // Clear messages
        clearMessages() {
            this.error = null;
            this.success = null;
        }
    };
}

/**
 * Modal Component - Reusable modal dialog
 */
function ModalComponent() {
    return {
        ...BaseComponent(),
        isOpen: false,
        title: '',
        size: 'md', // sm, md, lg, xl

        open(title = '', size = 'md') {
            this.title = title;
            this.size = size;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = 'auto';
            this.clearMessages();
        },

        getSizeClasses() {
            const sizes = {
                sm: 'max-w-md',
                md: 'max-w-lg',
                lg: 'max-w-2xl',
                xl: 'max-w-4xl'
            };
            return sizes[this.size] || sizes.md;
        }
    };
}

/**
 * Data Table Component - Paginated table with sorting and filtering
 */
function DataTableComponent() {
    return {
        ...BaseComponent(),
        data: [],
        filteredData: [],
        currentPage: 1,
        itemsPerPage: 10,
        sortField: null,
        sortDirection: 'asc',
        searchQuery: '',
        selectedItems: [],

        init() {
            this.$watch('searchQuery', () => this.filterData());
            this.$watch('data', () => this.filterData());
        },

        // Filter data based on search query
        filterData() {
            if (!this.searchQuery) {
                this.filteredData = [...this.data];
            } else {
                const query = this.searchQuery.toLowerCase();
                this.filteredData = this.data.filter(item => 
                    Object.values(item).some(value => 
                        String(value).toLowerCase().includes(query)
                    )
                );
            }
            this.currentPage = 1; // Reset to first page after filtering
        },

        // Sort data by field
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }

            this.filteredData.sort((a, b) => {
                let valueA = a[field];
                let valueB = b[field];

                // Handle null/undefined values
                if (valueA == null) valueA = '';
                if (valueB == null) valueB = '';

                // Convert to strings for comparison
                valueA = String(valueA).toLowerCase();
                valueB = String(valueB).toLowerCase();

                if (this.sortDirection === 'asc') {
                    return valueA < valueB ? -1 : valueA > valueB ? 1 : 0;
                } else {
                    return valueA > valueB ? -1 : valueA < valueB ? 1 : 0;
                }
            });
        },

        // Get paginated data
        get paginatedData() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredData.slice(start, end);
        },

        // Get total pages
        get totalPages() {
            return Math.ceil(this.filteredData.length / this.itemsPerPage);
        },

        // Navigation methods
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },

        // Selection methods
        toggleSelectAll() {
            if (this.selectedItems.length === this.paginatedData.length) {
                this.selectedItems = [];
            } else {
                this.selectedItems = [...this.paginatedData.map(item => item.id)];
            }
        },

        toggleSelectItem(itemId) {
            const index = this.selectedItems.indexOf(itemId);
            if (index > -1) {
                this.selectedItems.splice(index, 1);
            } else {
                this.selectedItems.push(itemId);
            }
        },

        isSelected(itemId) {
            return this.selectedItems.includes(itemId);
        },

        get isAllSelected() {
            return this.paginatedData.length > 0 && 
                   this.selectedItems.length === this.paginatedData.length;
        }
    };
}

/**
 * Form Component - Enhanced form handling with validation
 */
function FormComponent() {
    return {
        ...BaseComponent(),
        formData: {},
        errors: {},
        touched: {},
        isSubmitting: false,

        // Initialize form data
        initForm(initialData = {}) {
            this.formData = { ...initialData };
            this.errors = {};
            this.touched = {};
        },

        // Mark field as touched
        touchField(field) {
            this.touched[field] = true;
        },

        // Set field value
        setField(field, value) {
            this.formData[field] = value;
            this.touchField(field);
            this.validateField(field);
        },

        // Validate individual field
        validateField(field) {
            // Override this method in specific form components
            return true;
        },

        // Validate entire form
        validateForm() {
            this.errors = {};
            let isValid = true;

            for (const field in this.formData) {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            }

            return isValid;
        },

        // Get field error
        getFieldError(field) {
            return this.touched[field] ? this.errors[field] : null;
        },

        // Check if field has error
        hasFieldError(field) {
            return this.touched[field] && this.errors[field];
        },

        // Submit form
        async submitForm(endpoint, method = 'POST') {
            if (!this.validateForm()) {
                return false;
            }

            this.isSubmitting = true;
            
            try {
                const response = await this.apiRequest(endpoint, {
                    method,
                    body: JSON.stringify(this.formData)
                });

                this.showSuccess('Form submitted successfully');
                return response;
            } catch (error) {
                // Handle validation errors from server
                if (error.response && error.response.status === 422) {
                    const errorData = await error.response.json();
                    if (errorData.errors) {
                        this.errors = errorData.errors;
                    }
                }
                return false;
            } finally {
                this.isSubmitting = false;
            }
        }
    };
}

/**
 * Chart Component - Wrapper for chart libraries
 */
function ChartComponent() {
    return {
        ...BaseComponent(),
        chartData: null,
        chartOptions: {},
        chartInstance: null,

        // Initialize chart (override in specific implementations)
        initChart() {
            // To be implemented by specific chart components
        },

        // Update chart data
        updateChart(newData) {
            this.chartData = newData;
            if (this.chartInstance) {
                this.chartInstance.data = newData;
                this.chartInstance.update();
            }
        },

        // Destroy chart instance
        destroyChart() {
            if (this.chartInstance) {
                this.chartInstance.destroy();
                this.chartInstance = null;
            }
        }
    };
}

// =============================================================================
// REGISTER COMPONENTS
// =============================================================================

SafarStepComponents.register('baseComponent', BaseComponent);
SafarStepComponents.register('modalComponent', ModalComponent);
SafarStepComponents.register('dataTableComponent', DataTableComponent);
SafarStepComponents.register('formComponent', FormComponent);
SafarStepComponents.register('chartComponent', ChartComponent);

// =============================================================================
// INITIALIZATION
// =============================================================================

// Initialize components when Alpine.js is ready
document.addEventListener('alpine:init', () => {
    SafarStepComponents.initialize();
});

// Fallback initialization if Alpine.js is already loaded
if (window.Alpine) {
    SafarStepComponents.initialize();
}