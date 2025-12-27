# Users API Integration

## Overview
The users page now integrates with the Laravel backend API to fetch and manage users dynamically.

## API Endpoints Used

### 1. List Users
```
GET /api/v1/users
Headers:
  - Accept: application/json
  - Content-Type: application/json
  - X-Tenant-ID: {tenant_id}

Response:
{
  "data": [
    {
      "id": 1,
      "name": "SafarStep Admin",
      "email": "iosmarawan@gmail.com",
      "status": "active",
      "role": {...},
      "department": {...},
      "last_login": "2025-01-12T09:30:00.000000Z"
    },
    ...
  ]
}
```

### 2. Delete User
```
DELETE /api/v1/users/{id}
Headers:
  - Accept: application/json
  - Content-Type: application/json
  - X-Tenant-ID: {tenant_id}

Response:
{
  "message": "User deleted successfully"
}
```

### 3. Bulk Activate
```
PUT /api/v1/users/bulk/activate
Headers:
  - Accept: application/json
  - Content-Type: application/json
  - X-Tenant-ID: {tenant_id}

Body:
{
  "user_ids": [1, 2, 3]
}

Response:
{
  "message": "Users activated successfully",
  "count": 3
}
```

### 4. Bulk Deactivate
```
PUT /api/v1/users/bulk/deactivate
Headers:
  - Accept: application/json
  - Content-Type: application/json
  - X-Tenant-ID: {tenant_id}

Body:
{
  "user_ids": [1, 2, 3]
}

Response:
{
  "message": "Users deactivated successfully",
  "count": 3
}
```

### 5. Bulk Delete
```
DELETE /api/v1/users/bulk/delete
Headers:
  - Accept: application/json
  - Content-Type: application/json
  - X-Tenant-ID: {tenant_id}

Body:
{
  "user_ids": [1, 2, 3]
}

Response:
{
  "message": "Users deleted successfully",
  "count": 3
}
```

## Frontend Implementation

### Users Management Page
File: `resources/views/users.blade.php`

**Updated Methods:**
- `loadUsers()` - Now calls GET /api/v1/users
- `bulkActivate()` - Now calls PUT /api/v1/users/bulk/activate
- `bulkDeactivate()` - Now calls PUT /api/v1/users/bulk/deactivate
- `bulkDelete()` - Now calls DELETE /api/v1/users/bulk/delete
- `deleteUser()` - Now calls DELETE /api/v1/users/{id}

**New Methods:**
- `getTenantId()` - Retrieves tenant ID from meta tag or localStorage

### Tenant Context
The tenant ID is stored in a meta tag in the dashboard layout:
```html
<meta name="tenant-id" content="1">
```

This is automatically included in all API requests via the `X-Tenant-ID` header.

## Error Handling
All API calls include try-catch blocks that:
1. Show error notifications using the notification system
2. Log errors to console for debugging
3. Prevent page crashes on API failures

## Testing

### 1. Seed the Database
```bash
php artisan db:seed --class=UsersTableSeeder
```

### 2. Test API Endpoints
```bash
# List users
curl -X GET http://localhost:8000/api/v1/users \
  -H "Accept: application/json" \
  -H "X-Tenant-ID: 1"

# Bulk activate
curl -X PUT http://localhost:8000/api/v1/users/bulk/activate \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{"user_ids":[2,3]}'
```

### 3. Browser Console
Check browser console for:
- API request/response logs
- Error messages
- Network tab for request details

## Next Steps

1. **Authentication**: Add Sanctum token authentication
2. **Create/Edit Forms**: Add modal forms for creating and editing users
3. **Role Management**: Integrate RBAC functionality
4. **Pagination**: Add pagination support for large user lists
5. **Advanced Filters**: Add more filter options (date range, custom fields)
6. **Export**: Implement CSV/Excel export functionality
7. **Import**: Add bulk user import feature

## Notes

- All operations require proper tenant context
- Status can be: 'active', 'inactive', 'suspended'
- CORS headers are configured in `config/cors.php`
- API versioning is maintained with `/api/v1` prefix
