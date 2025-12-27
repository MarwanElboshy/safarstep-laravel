# Users Module - Quick Reference

## 🚀 Quick Start

### View the Page
```bash
php artisan serve
# Navigate to: http://localhost:8000/dashboard/users
```

### Key Files
```
resources/views/users.blade.php          # Main page (enhanced)
resources/views/users-old.blade.php      # Backup (original)
routes/web.php                           # Routes
docs/USERS_MODULE.md                     # Full documentation
docs/USERS_IMPLEMENTATION_SUMMARY.md     # Implementation details
docs/USERS_BEFORE_AFTER.md              # Visual comparison
```

## 📋 Main Features

### 1. Bulk Actions Toast Bar
```html
<!-- Appears when users are selected -->
<div x-show="selectedUsers.length > 0">
    <button @click="bulkActivate()">Activate</button>
    <button @click="bulkDeactivate()">Deactivate</button>
    <button @click="bulkDelete()">Delete</button>
</div>
```

### 2. Toast Notifications
```javascript
// Show notification
showToast('User activated!', 'success');  // Green
showToast('Error occurred', 'error');     // Red
showToast('Please confirm', 'warning');   // Amber
```

### 3. Selection Management
```javascript
// Select all users
toggleSelectAll(event)

// Check if all selected
isAllSelected()

// Clear selection
clearSelection()

// Selected IDs array
selectedUsers: [1, 2, 3]
```

### 4. Filtering
```javascript
// Reactive filters
searchQuery: ''      // Real-time search
roleFilter: ''       // Filter by role
statusFilter: ''     // Filter by status

// Apply filters
filterUsers()

// Reset all filters
resetFilters()
```

## 🎨 Color Reference

### Role Badges
```css
super_admin → Dark (bg-slate-900 text-white)
admin       → Blue (bg-blue-100 text-blue-700)
manager     → Purple (bg-purple-100 text-purple-700)
employee    → Amber (bg-amber-100 text-amber-700)
```

### Status Badges
```css
active   → Green (bg-emerald-100 text-emerald-700)
inactive → Gray (bg-slate-100 text-slate-500)
```

### Avatar Gradients
```javascript
getAvatarColor(id) {
    const colors = [
        '#3b82f6, #1d4ed8', // Blue
        '#10b981, #059669', // Emerald
        '#f59e0b, #d97706', // Amber
        '#8b5cf6, #7c3aed', // Purple
        '#ec4899, #db2777', // Pink
        '#06b6d4, #0891b2', // Cyan
    ];
    return colors[id % colors.length];
}
```

## 🔧 Common Tasks

### Add New Filter
```javascript
// 1. Add to data
departmentFilter: '',

// 2. Update filterUsers()
const matchesDepartment = !this.departmentFilter || 
    user.department === this.departmentFilter;

// 3. Add to HTML
<select x-model="departmentFilter" @change="filterUsers()">
    <option value="">All Departments</option>
    <!-- ... -->
</select>
```

### Customize Toast Duration
```javascript
showToast(message, type = 'success') {
    this.toast = { show: true, message, type };
    
    // Change from 3000ms to 5000ms
    setTimeout(() => {
        this.toast.show = false;
    }, 5000);
}
```

### Add Custom Action
```javascript
// In Alpine component
customAction(userId) {
    // Your logic here
    this.showToast('Action completed', 'success');
}

// In HTML
<button @click="customAction(user.id)">
    Custom Action
</button>
```

### Integrate with API
```javascript
// Replace mock data
async loadUsers() {
    this.isLoading = true;
    try {
        const response = await fetch('/api/v1/users');
        const data = await response.json();
        this.users = data.users;
        this.filteredUsers = [...this.users];
        this.calculateStats();
    } catch (error) {
        this.showToast('Failed to load users', 'error');
    } finally {
        this.isLoading = false;
    }
}
```

## 📊 Alpine.js State

### Data Structure
```javascript
{
    // Arrays
    users: [],           // All users from backend
    filteredUsers: [],   // After search/filters
    selectedUsers: [],   // Selected user IDs
    
    // Objects
    stats: {
        total: 0,
        active: 0,
        managers: 0
    },
    toast: {
        show: false,
        message: '',
        type: 'success'
    },
    
    // Strings
    searchQuery: '',
    roleFilter: '',
    statusFilter: '',
    
    // Boolean
    isLoading: false
}
```

### Key Methods
```javascript
// Lifecycle
init()                    // Initialize component
loadUsers()               // Fetch data
calculateStats()          // Update stats

// Filtering
filterUsers()             // Apply filters
resetFilters()            // Clear filters
refreshUsers()            // Reload data

// Selection
toggleSelectAll(event)    // Select/deselect all
isAllSelected()           // Check state
clearSelection()          // Clear all

// Bulk Actions
bulkActivate()            // Activate selected
bulkDeactivate()          // Deactivate selected
bulkDelete()              // Delete selected

// Individual Actions
viewUser(user)            // View details
editUser(user)            // Edit user
deleteUser(user)          // Delete user

// Helpers
getInitials(name)         // Get initials
getAvatarColor(id)        // Avatar color
formatRole(role)          // Format role
formatDate(dateString)    // Format date
getRoleBadgeClass(role)   // Role badge class
getStatusBadgeClass(status) // Status badge class

// UI
showToast(message, type)  // Show notification
getToastClass()           // Toast styling
```

## 🎯 Event Handlers

### Input Events
```html
<!-- Search input -->
<input x-model="searchQuery" @input="filterUsers()" />

<!-- Dropdowns -->
<select x-model="roleFilter" @change="filterUsers()">

<!-- Checkboxes -->
<input type="checkbox" @change="toggleSelectAll($event)" />
<input type="checkbox" x-model="selectedUsers" :value="user.id" />
```

### Click Events
```html
<!-- Buttons -->
<button @click="bulkActivate()">Activate</button>
<button @click="resetFilters()">Reset</button>
<button @click="viewUser(user)">View</button>
```

## 🔍 Debugging

### Check State
```javascript
// In browser console
Alpine.store('usersEnhancedData')  // Access component

// Or add to methods
console.log('Users:', this.users);
console.log('Selected:', this.selectedUsers);
console.log('Filtered:', this.filteredUsers);
```

### Common Issues

**Toast not showing**
```javascript
// Check toast state
console.log(this.toast);

// Verify transition classes
x-transition:enter="..."
x-transition:leave="..."
```

**Selection not working**
```javascript
// Ensure unique IDs
this.users.forEach(u => console.log(u.id));

// Check x-model binding
<input x-model="selectedUsers" :value="user.id" />
```

**Filters not applying**
```javascript
// Verify method is called
filterUsers() {
    console.log('Filtering...', this.searchQuery);
    // ...
}
```

## 📱 Responsive Classes

```css
/* Mobile First */
.grid-cols-1          /* Default: 1 column */
sm:grid-cols-2        /* 640px+: 2 columns */
lg:grid-cols-4        /* 1024px+: 4 columns */

/* Flex Direction */
.flex-col             /* Default: column */
md:flex-row           /* 768px+: row */

/* Visibility */
.hidden               /* Hidden */
sm:block              /* 640px+: visible */

/* Width */
.w-full               /* Full width */
.max-w-full           /* Max width 100% */
lg:w-auto             /* 1024px+: auto */
```

## 🚨 Important Notes

### Security
- ✅ Validate all user input
- ✅ Use CSRF tokens for forms
- ✅ Check permissions on backend
- ✅ Sanitize data from API

### Performance
- ✅ Debounce search input
- ✅ Use Alpine.js x-if for large lists
- ✅ Lazy load avatars
- ✅ Implement pagination for 100+ users

### Accessibility
- ✅ Use semantic HTML
- ✅ Add ARIA labels
- ✅ Test keyboard navigation
- ✅ Ensure color contrast

## 🔗 API Endpoints (To Implement)

```http
GET    /api/v1/users                    # List users
POST   /api/v1/users                    # Create user
GET    /api/v1/users/{id}               # Get user
PUT    /api/v1/users/{id}               # Update user
DELETE /api/v1/users/{id}               # Delete user
PUT    /api/v1/users/bulk/activate      # Bulk activate
PUT    /api/v1/users/bulk/deactivate    # Bulk deactivate
DELETE /api/v1/users/bulk/delete        # Bulk delete
GET    /api/v1/users/export             # Export users
```

## 📖 Further Reading

- [Alpine.js Documentation](https://alpinejs.dev)
- [Tailwind CSS Documentation](https://tailwindcss.com)
- [Full Module Documentation](./USERS_MODULE.md)
- [Implementation Summary](./USERS_IMPLEMENTATION_SUMMARY.md)
- [Before/After Comparison](./USERS_BEFORE_AFTER.md)

## ✅ Testing Checklist

- [ ] Search users by name
- [ ] Search users by email
- [ ] Filter by role
- [ ] Filter by status
- [ ] Reset filters
- [ ] Select individual users
- [ ] Select all users
- [ ] Bulk activate
- [ ] Bulk deactivate
- [ ] Bulk delete with confirmation
- [ ] View user details
- [ ] Edit user
- [ ] Delete single user
- [ ] Toast notifications appear
- [ ] Refresh users
- [ ] Export functionality
- [ ] Mobile responsive
- [ ] Tablet responsive
- [ ] Desktop layout
- [ ] Keyboard navigation
- [ ] Loading states
- [ ] Empty states

---

**Quick Tip**: Use browser DevTools → Elements → Event Listeners to debug Alpine.js event handlers!

**Pro Tip**: Add `x-cloak` to prevent FOUC (Flash of Unstyled Content) on page load.

**Remember**: Always test with real data and edge cases (empty lists, long names, etc.)!
