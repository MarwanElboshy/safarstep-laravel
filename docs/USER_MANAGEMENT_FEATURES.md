# User Management Features

This document outlines all the dynamic features implemented in the user management system that are connected to the backend API.

## View Modes

### Table View
- Traditional data table with sorting, filtering, and pagination
- Shows all user details in a structured format
- Accessible via the table icon in the view toggle

### Grid View
- Kanban-style card layout
- Visual indicators for online status (green dot for active within 15 minutes)
- Performance bars showing conversion rate and average deal value
- Color-coded card headers with gradients based on role
- Accessible via the grid icon in the view toggle

## User Details Modal

Clicking on any user (in table or grid view) opens a comprehensive detail modal showing:
- **Basic Info**: Name, email, status, department, role
- **Performance Metrics**:
  - Total Sales with visual bar indicator
  - Conversion Rate with visual bar indicator
  - Avg Deal Value with visual bar indicator
- **Activity Summary**: Recent activities and timeline

**Backend Integration**: Fetches from `/api/v1/users/{id}`

## Bulk Operations

Select multiple users using checkboxes to perform bulk actions:

### 1. Bulk Activate
- Activates selected inactive users
- **Endpoint**: `PUT /api/v1/users/bulk/activate`
- **Payload**: `{ user_ids: [1, 2, 3] }`

### 2. Bulk Deactivate
- Deactivates selected active users
- **Endpoint**: `PUT /api/v1/users/bulk/deactivate`
- **Payload**: `{ user_ids: [1, 2, 3] }`

### 3. Bulk Delete
- Permanently deletes selected users (with confirmation)
- Prevents self-deletion on backend
- **Endpoint**: `DELETE /api/v1/users/bulk/delete`
- **Payload**: `{ user_ids: [1, 2, 3] }`

### 4. Bulk Role Change
Opens a modal with three operation modes:
- **Replace**: Replace all existing roles with selected roles
- **Add**: Add selected roles without removing existing ones
- **Remove**: Remove selected roles from users

Shows preview of affected users before applying changes.

**Endpoint**: `POST /api/v1/users/bulk/role-change`
**Payload**:
```json
{
  "user_ids": [1, 2, 3],
  "role_ids": [1, 2],
  "mode": "replace|add|remove"
}
```

### 5. Bulk Department Change
Opens a modal showing:
- Current department distribution of selected users
- Smart recommendations for department moves
- Impact preview showing how many users will be affected

**Endpoint**: `POST /api/v1/users/bulk/department-change`
**Payload**:
```json
{
  "user_ids": [1, 2, 3],
  "department_id": 5
}
```

### 6. Bulk Invite
Opens a modal to invite multiple users by email:
- Paste email list (comma or line-separated)
- Select role and department
- Option to send welcome email
- Validates email format before sending
- Shows count of valid/invalid emails
- Reports which emails already exist vs which were created

**Endpoint**: `POST /api/v1/users/bulk/invite`
**Payload**:
```json
{
  "emails": ["user1@example.com", "user2@example.com"],
  "role_id": 1,
  "department_id": 2,
  "send_welcome_email": true
}
```

**Backend Behavior**:
- Creates users with 'inactive' status
- Generates random password (user must reset via email)
- Assigns specified role
- Skips existing users and reports them

## Export Functionality

Enhanced export feature that:
- Respects current filters (search, role, department)
- Exports only filtered users (not all users)
- Creates CSV file with timestamp in filename
- Includes columns: ID, Name, Email, Role, Department, Status

**Format**: `users_export_YYYYMMDD_HHMMSS.csv`

## Filtering & Search

All filters work dynamically with backend data:
- **Search**: Real-time search by name or email
- **Role Filter**: Filter by specific role
- **Department Filter**: Filter by specific department
- All filters work together (AND logic)
- Filters apply to both table and grid views
- Export respects active filters

## Backend Architecture

### Controllers
- `UserController`: CRUD operations for individual users
- `UserBulkController`: Bulk operations (activate, deactivate, delete, role-change, department-change, invite)
- `RoleController`: Role management
- `DepartmentController`: Department management

### Middleware Stack
All endpoints protected by:
- `auth:sanctum,web`: Authentication via Sanctum token or web session
- `tenant`: Resolves tenant from X-Tenant-ID header
- `enforce-tenant`: Ensures tenant context is set

### Authorization
- Uses Laravel Gates for permission checks
- All bulk operations check appropriate permissions
- Prevents unauthorized actions (e.g., self-deletion)

### Tenant Isolation
- All queries scoped to current tenant
- TenantContext service ensures data isolation
- Foreign key relationships respect tenant boundaries

## API Response Format

All endpoints return consistent JSON format:

**Success Response**:
```json
{
  "success": true,
  "count": 5,
  "message": "Operation completed successfully",
  "data": { ... }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Error description",
  "errors": { ... }
}
```

## Frontend State Management

Uses Alpine.js with reactive state:
- `viewMode`: 'table' | 'grid'
- `selectedUsers`: Array of selected user IDs
- `users`: Full user list from backend
- `filteredUsers`: Computed based on filters
- `roles`: Available roles
- `departments`: Available departments
- Various modal states for different operations

## Testing the Features

1. **Activate/Deactivate**: Select users and use bulk action buttons
2. **Role Change**: Select users, click "Change Role", choose mode and roles
3. **Department Change**: Select users, click "Move Department", select new department
4. **Bulk Invite**: Click "Bulk Invite", paste emails, select role/department
5. **Export**: Apply filters, click Export to download filtered CSV
6. **View Switch**: Toggle between table and grid views
7. **User Details**: Click on any user to see detailed modal

All operations show toast notifications on success/failure and reload the user list automatically.
