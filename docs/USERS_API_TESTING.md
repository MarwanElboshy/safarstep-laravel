# Users API Testing Guide

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
All requests require:
- Header: `Authorization: Bearer {token}`
- Header: `Accept: application/json`
- Header: `Content-Type: application/json`
- Header: `X-Tenant-ID: {tenant_id}` (for tenant context)

---

## Endpoints

### 1. List Users
**GET** `/api/v1/users`

**Query Parameters:**
- `search` - Search by name or email
- `status` - Filter by status (active/inactive)
- `role_id` - Filter by role ID
- `department_id` - Filter by department ID
- `sort_by` - Sort field (name, email, created_at, last_login_at)
- `sort_order` - Sort direction (asc, desc)
- `per_page` - Items per page (for pagination)

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/users?search=john&status=active" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "X-Tenant-ID: 1"
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "status": "active",
      "department_id": 2,
      "tenant_id": 1,
      "last_login_at": "2024-12-20T10:30:00Z",
      "created_at": "2024-01-01T00:00:00Z",
      "updated_at": "2024-12-20T10:30:00Z",
      "department": {
        "id": 2,
        "name": "Sales"
      },
      "roles": [
        {
          "id": 3,
          "name": "Manager",
          "slug": "manager"
        }
      ],
      "initials": "JD",
      "is_active": true,
      "avatar_color": "from-blue-400 to-blue-600"
    }
  ],
  "meta": {
    "total": 15,
    "stats": {
      "total": 15,
      "active": 12,
      "inactive": 3
    }
  }
}
```

---

### 2. Get Single User
**GET** `/api/v1/users/{id}`

**Example Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/users/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "X-Tenant-ID: 1"
```

---

### 3. Create User
**POST** `/api/v1/users`

**Required Fields:**
- `name` - User's full name
- `email` - Unique email address
- `password` - Minimum 8 characters
- `password_confirmation` - Must match password
- `role_id` - Role ID to assign

**Optional Fields:**
- `department_id` - Department ID
- `status` - active or inactive (default: active)

**Example Request:**
```bash
curl -X POST "http://localhost:8000/api/v1/users" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123",
    "role_id": 3,
    "department_id": 2,
    "status": "active"
  }'
```

**Example Response:**
```json
{
  "data": {
    "id": 25,
    "name": "Jane Smith",
    "email": "jane@example.com",
    "status": "active",
    "department_id": 2,
    "tenant_id": 1,
    "created_at": "2024-12-24T15:30:00Z",
    "updated_at": "2024-12-24T15:30:00Z",
    "roles": [
      {
        "id": 3,
        "name": "Manager",
        "slug": "manager"
      }
    ]
  }
}
```

---

### 4. Update User
**PUT/PATCH** `/api/v1/users/{id}`

**All fields are optional:**
- `name`
- `email`
- `password` + `password_confirmation`
- `role_id`
- `department_id`
- `status`

**Example Request:**
```bash
curl -X PUT "http://localhost:8000/api/v1/users/25" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{
    "name": "Jane Smith-Johnson",
    "status": "inactive"
  }'
```

---

### 5. Delete User
**DELETE** `/api/v1/users/{id}`

**Example Request:**
```bash
curl -X DELETE "http://localhost:8000/api/v1/users/25" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "X-Tenant-ID: 1"
```

**Example Response:**
```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

---

## Bulk Operations

### 6. Bulk Activate Users
**PUT** `/api/v1/users/bulk/activate`

**Example Request:**
```bash
curl -X PUT "http://localhost:8000/api/v1/users/bulk/activate" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{
    "user_ids": [10, 11, 12, 15]
  }'
```

**Example Response:**
```json
{
  "success": true,
  "count": 4,
  "message": "4 user(s) activated successfully"
}
```

---

### 7. Bulk Deactivate Users
**PUT** `/api/v1/users/bulk/deactivate`

**Example Request:**
```bash
curl -X PUT "http://localhost:8000/api/v1/users/bulk/deactivate" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{
    "user_ids": [10, 11, 12]
  }'
```

---

### 8. Bulk Delete Users
**DELETE** `/api/v1/users/bulk/delete`

**Note:** Cannot delete your own account

**Example Request:**
```bash
curl -X DELETE "http://localhost:8000/api/v1/users/bulk/delete" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{
    "user_ids": [15, 16, 17]
  }'
```

**Example Response:**
```json
{
  "success": true,
  "count": 3,
  "message": "3 user(s) deleted successfully"
}
```

---

### 9. Bulk Assign Role
**PUT** `/api/v1/users/bulk/assign-role`

**Example Request:**
```bash
curl -X PUT "http://localhost:8000/api/v1/users/bulk/assign-role" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Tenant-ID: 1" \
  -d '{
    "user_ids": [10, 11, 12],
    "role_id": 4
  }'
```

**Example Response:**
```json
{
  "success": true,
  "count": 3,
  "message": "3 user(s) assigned new role successfully"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email has already been taken."
    ],
    "password": [
      "The password confirmation does not match."
    ]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "This action is unauthorized."
}
```

### Not Found (404)
```json
{
  "message": "User not found."
}
```

---

## Testing with Postman

1. Create a new collection named "SafarStep Users API"
2. Add environment variables:
   - `base_url`: http://localhost:8000
   - `token`: YOUR_AUTH_TOKEN
   - `tenant_id`: 1

3. Set collection-level headers:
   - Authorization: Bearer {{token}}
   - Accept: application/json
   - Content-Type: application/json
   - X-Tenant-ID: {{tenant_id}}

4. Import the requests above as individual items in the collection

---

## Frontend Integration (Next Step)

Update the Alpine.js component in `users.blade.php`:

```javascript
// Replace mock loadUsers() with:
async loadUsers() {
    this.isLoading = true;
    try {
        const params = new URLSearchParams({
            search: this.filters.search,
            status: this.filters.status,
            role_id: this.filters.role
        });
        
        const response = await fetch(`/api/v1/users?${params}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json',
                'X-Tenant-ID': localStorage.getItem('tenant_id')
            }
        });
        
        if (!response.ok) throw new Error('Failed to load users');
        
        const data = await response.json();
        this.users = data.data;
        this.filteredUsers = [...this.users];
        this.calculateStats();
    } catch (error) {
        this.showToast('Failed to load users', 'error');
        console.error(error);
    } finally {
        this.isLoading = false;
    }
}

// Update bulk operations similarly
async bulkActivate() {
    try {
        const response = await fetch('/api/v1/users/bulk/activate', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Tenant-ID': localStorage.getItem('tenant_id')
            },
            body: JSON.stringify({ user_ids: this.selectedUsers })
        });
        
        if (!response.ok) throw new Error('Failed to activate users');
        
        const data = await response.json();
        this.showToast(data.message, 'success');
        this.clearSelection();
        await this.loadUsers();
    } catch (error) {
        this.showToast('Failed to activate users', 'error');
        console.error(error);
    }
}
```
