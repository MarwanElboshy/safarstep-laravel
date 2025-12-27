# Users Module - Backend Integration Roadmap

## Current Status
✅ **Frontend Complete** - Enhanced UI with bulk actions, toast notifications, and advanced filtering  
⏳ **Backend Pending** - API endpoints, database operations, and business logic needed

---

## Phase 1: Database & Models ✅ (COMPLETED)

### Already Implemented
- [x] User model with tenant relationship
- [x] User migration with all fields
- [x] User factory for testing
- [x] User seeder with sample data
- [x] RBAC models (Role, Permission)
- [x] User policy for authorization

### Database Schema
```sql
users table:
- id (bigint, primary key)
- tenant_id (bigint, foreign key)
- name (string)
- email (string, unique per tenant)
- email_verified_at (timestamp, nullable)
- password (string)
- remember_token (string)
- status (enum: active, inactive)
- last_login_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable) -- soft deletes

roles table:
- id (bigint, primary key)
- tenant_id (bigint, foreign key)
- name (string)
- slug (string, unique per tenant)
- description (text, nullable)
- created_at, updated_at

permissions table:
- id (bigint, primary key)
- name (string)
- slug (string, unique)
- module (string)
- description (text, nullable)
- created_at, updated_at

role_user pivot table:
- role_id (bigint)
- user_id (bigint)

permission_role pivot table:
- permission_id (bigint)
- role_id (bigint)
```

---

## Phase 2: API Controllers (NEXT STEP)

### Required Controllers

#### 1. UserController
**Location**: `app/Http/Controllers/Api/V1/UserController.php`

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // GET /api/v1/users
    public function index(Request $request)
    {
        // Implement:
        // - Pagination (default 50 per page)
        // - Search by name, email
        // - Filter by role, status, department
        // - Sort by name, email, created_at, last_login
        // - Tenant scoping
    }
    
    // POST /api/v1/users
    public function store(Request $request)
    {
        // Implement:
        // - Validation
        // - Tenant assignment
        // - Password hashing
        // - Default role assignment
        // - Send welcome email (optional)
    }
    
    // GET /api/v1/users/{id}
    public function show(User $user)
    {
        // Implement:
        // - Load relationships (roles, permissions, department)
        // - Authorization check
        // - Activity history
    }
    
    // PUT /api/v1/users/{id}
    public function update(Request $request, User $user)
    {
        // Implement:
        // - Validation
        // - Authorization check
        // - Update fields
        // - Audit log
    }
    
    // DELETE /api/v1/users/{id}
    public function destroy(User $user)
    {
        // Implement:
        // - Authorization check
        // - Soft delete
        // - Audit log
        // - Can't delete self
    }
}
```

#### 2. UserBulkController
**Location**: `app/Http/Controllers/Api/V1/UserBulkController.php`

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserBulkController extends Controller
{
    // PUT /api/v1/users/bulk/activate
    public function activate(Request $request)
    {
        // Validate: user_ids array
        // Check permissions
        // Update status to 'active'
        // Return count of updated users
    }
    
    // PUT /api/v1/users/bulk/deactivate
    public function deactivate(Request $request)
    {
        // Validate: user_ids array
        // Check permissions
        // Update status to 'inactive'
        // Return count of updated users
    }
    
    // DELETE /api/v1/users/bulk/delete
    public function delete(Request $request)
    {
        // Validate: user_ids array
        // Check permissions
        // Soft delete users
        // Can't delete self
        // Return count of deleted users
    }
}
```

#### 3. UserExportController
**Location**: `app/Http/Controllers/Api/V1/UserExportController.php`

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserExportController extends Controller
{
    // GET /api/v1/users/export
    public function export(Request $request)
    {
        // Support formats: csv, excel, pdf
        // Apply same filters as index
        // Return downloadable file
    }
}
```

---

## Phase 3: Form Requests

### UserStoreRequest
**Location**: `app/Http/Requests/Api/V1/UserStoreRequest.php`

```php
<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_users');
    }
    
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,NULL,id,tenant_id,' . auth()->user()->tenant_id,
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'sometimes|in:active,inactive',
        ];
    }
}
```

### UserUpdateRequest
**Location**: `app/Http/Requests/Api/V1/UserUpdateRequest.php`

```php
public function rules(): array
{
    return [
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $this->user->id . ',id,tenant_id,' . auth()->user()->tenant_id,
        'role_id' => 'sometimes|exists:roles,id',
        'department_id' => 'nullable|exists:departments,id',
        'status' => 'sometimes|in:active,inactive',
    ];
}
```

### UserBulkRequest
**Location**: `app/Http/Requests/Api/V1/UserBulkRequest.php`

```php
public function rules(): array
{
    return [
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'required|integer|exists:users,id',
    ];
}
```

---

## Phase 4: Resources (API Transformers)

### UserResource
**Location**: `app/Http/Resources/Api/V1/UserResource.php`

```php
<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'status' => $this->status,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Relationships
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            
            // Computed
            'avatar_url' => $this->avatar_url,
            'initials' => $this->initials,
            'is_active' => $this->status === 'active',
        ];
    }
}
```

### UserCollection
**Location**: `app/Http/Resources/Api/V1/UserCollection.php`

```php
<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total(),
                'active_count' => $this->activeCount(),
                'inactive_count' => $this->inactiveCount(),
            ],
        ];
    }
}
```

---

## Phase 5: Services (Business Logic)

### UserService
**Location**: `app/Services/UserService.php`

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'tenant_id' => auth()->user()->tenant_id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => $data['status'] ?? 'active',
            ]);
            
            // Assign role
            if (isset($data['role_id'])) {
                $user->roles()->attach($data['role_id']);
            }
            
            // Assign department
            if (isset($data['department_id'])) {
                $user->department_id = $data['department_id'];
                $user->save();
            }
            
            // Send welcome email (optional)
            // Mail::to($user)->send(new WelcomeEmail($user));
            
            return $user->fresh('roles', 'department');
        });
    }
    
    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'name' => ($data['first_name'] ?? $user->first_name) . ' ' . ($data['last_name'] ?? $user->last_name),
                'email' => $data['email'] ?? $user->email,
                'status' => $data['status'] ?? $user->status,
            ]);
            
            // Update role
            if (isset($data['role_id'])) {
                $user->roles()->sync([$data['role_id']]);
            }
            
            // Update department
            if (isset($data['department_id'])) {
                $user->department_id = $data['department_id'];
                $user->save();
            }
            
            return $user->fresh('roles', 'department');
        });
    }
    
    public function bulkActivate(array $userIds): int
    {
        return User::whereIn('id', $userIds)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->update(['status' => 'active']);
    }
    
    public function bulkDeactivate(array $userIds): int
    {
        return User::whereIn('id', $userIds)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->update(['status' => 'inactive']);
    }
    
    public function bulkDelete(array $userIds): int
    {
        // Prevent deleting self
        $userIds = array_filter($userIds, fn($id) => $id !== auth()->id());
        
        return User::whereIn('id', $userIds)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->delete();
    }
}
```

---

## Phase 6: Routes

### API Routes
**Location**: `routes/api.php`

```php
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserBulkController;
use App\Http\Controllers\Api\V1\UserExportController;

Route::prefix('v1')->middleware(['auth:sanctum', 'tenant'])->group(function () {
    // Users CRUD
    Route::apiResource('users', UserController::class);
    
    // Bulk operations
    Route::put('users/bulk/activate', [UserBulkController::class, 'activate']);
    Route::put('users/bulk/deactivate', [UserBulkController::class, 'deactivate']);
    Route::delete('users/bulk/delete', [UserBulkController::class, 'delete']);
    
    // Export
    Route::get('users/export', [UserExportController::class, 'export']);
});
```

---

## Phase 7: Frontend Integration

### Update Alpine.js Component

Replace mock data with API calls:

```javascript
// Load users from API
async loadUsers() {
    this.isLoading = true;
    try {
        const response = await fetch('/api/v1/users', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
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

// Bulk activate
async bulkActivate() {
    try {
        const response = await fetch('/api/v1/users/bulk/activate', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ user_ids: this.selectedUsers })
        });
        
        if (!response.ok) throw new Error('Failed to activate users');
        
        const data = await response.json();
        this.showToast(`${data.count} user(s) activated successfully!`, 'success');
        this.clearSelection();
        await this.loadUsers();
    } catch (error) {
        this.showToast('Failed to activate users', 'error');
        console.error(error);
    }
}

// Similar for bulkDeactivate, bulkDelete, deleteUser, etc.
```

---

## Phase 8: Testing

### Feature Tests
**Location**: `tests/Feature/Api/V1/UserControllerTest.php`

```php
<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;

class UserControllerTest extends TestCase
{
    public function test_can_list_users()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->for($tenant)->create();
        
        $response = $this->actingAs($user)
            ->getJson('/api/v1/users');
        
        $response->assertOk()
            ->assertJsonStructure(['data', 'meta']);
    }
    
    public function test_can_create_user()
    {
        // Test user creation
    }
    
    public function test_can_bulk_activate_users()
    {
        // Test bulk activation
    }
    
    // More tests...
}
```

---

## Phase 9: Permissions & Authorization

### Required Permissions
```sql
INSERT INTO permissions (name, slug, module) VALUES
('View Users', 'view_users', 'users'),
('Create Users', 'create_users', 'users'),
('Update Users', 'update_users', 'users'),
('Delete Users', 'delete_users', 'users'),
('Bulk Update Users', 'bulk_update_users', 'users'),
('Export Users', 'export_users', 'users');
```

### Policy Enforcement
```php
// In UserController
public function index()
{
    $this->authorize('viewAny', User::class);
    // ...
}

public function store()
{
    $this->authorize('create', User::class);
    // ...
}
```

---

## Implementation Checklist

### Backend (Priority Order)
- [ ] 1. Create UserController with index, store, show, update, destroy
- [ ] 2. Create UserBulkController with bulk operations
- [ ] 3. Create Form Requests for validation
- [ ] 4. Create UserResource and UserCollection
- [ ] 5. Create UserService for business logic
- [ ] 6. Add API routes
- [ ] 7. Create UserExportController
- [ ] 8. Write feature tests
- [ ] 9. Add audit logging
- [ ] 10. Add rate limiting

### Frontend
- [ ] 11. Replace mock data with API calls
- [ ] 12. Add error handling for failed requests
- [ ] 13. Add loading states for all operations
- [ ] 14. Add confirmation dialogs for destructive actions
- [ ] 15. Test all bulk operations
- [ ] 16. Test filters and search
- [ ] 17. Test pagination
- [ ] 18. Test export functionality

### Documentation
- [ ] 19. Update API documentation (OpenAPI/Swagger)
- [ ] 20. Create postman collection
- [ ] 21. Document permission requirements
- [ ] 22. Add deployment notes

---

## Estimated Timeline

| Phase | Tasks | Estimated Time |
|-------|-------|----------------|
| Controllers | 3 controllers | 4 hours |
| Form Requests | 3 requests | 1 hour |
| Resources | 2 resources | 1 hour |
| Services | UserService | 2 hours |
| Routes | API routes | 30 mins |
| Testing | Feature tests | 3 hours |
| Frontend Integration | Update Alpine.js | 2 hours |
| Documentation | API docs | 1 hour |
| **Total** | | **14.5 hours** |

---

## Dependencies

### Required Packages (Already Installed)
- Laravel Sanctum (for API authentication)
- Laravel Pint (for code formatting)
- PHPUnit (for testing)

### Optional Enhancements
- Laravel Excel (for Excel exports)
- Spatie Laravel Permission (alternative RBAC)
- Laravel Telescope (for debugging)

---

## Security Considerations

1. **Authentication**: Sanctum tokens required for all endpoints
2. **Authorization**: Policy checks on every operation
3. **Tenant Isolation**: All queries scoped to user's tenant
4. **Rate Limiting**: Apply to bulk operations
5. **Audit Trail**: Log all create/update/delete operations
6. **Validation**: Strict input validation
7. **SQL Injection**: Use Eloquent ORM (parameterized queries)
8. **XSS**: Sanitize output in API responses

---

## Next Steps

1. **Start with Phase 2**: Create UserController
2. **Add validation**: Create Form Requests
3. **Transform data**: Create Resources
4. **Business logic**: Create UserService
5. **Test everything**: Write feature tests
6. **Integrate frontend**: Update Alpine.js
7. **Document API**: OpenAPI specification

---

**Priority**: HIGH  
**Complexity**: MEDIUM  
**Impact**: HIGH (Core functionality)

This roadmap provides a clear path to completing the users module with full backend integration!
