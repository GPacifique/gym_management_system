# Role-Based Access Control (RBAC) Documentation

## Overview
GymMate implements a comprehensive Role-Based Access Control system with 5 distinct roles, each with specific permissions and access levels.

## Available Roles

### 1. Admin
- **Email:** admin@gymmate.com
- **Password:** password
- **Access Level:** Full system access
- **Permissions:**
  - ✅ Dashboard (view)
  - ✅ Members (full CRUD)
  - ✅ Trainers (full CRUD)
  - ✅ Subscriptions (full CRUD)
  - ✅ Payments (full CRUD)
  - ✅ Workout Plans (full CRUD)
  - ✅ Classes (full CRUD)
  - ✅ Attendance (full CRUD + export)

### 2. Manager
- **Email:** manager@gymmate.com
- **Password:** password
- **Access Level:** Management operations
- **Permissions:**
  - ✅ Dashboard (view)
  - ✅ Members (full CRUD)
  - ✅ Subscriptions (full CRUD)
  - ✅ Payments (full CRUD)
  - ✅ Workout Plans (full CRUD)
  - ✅ Classes (full CRUD)
  - ✅ Attendance (full CRUD + export)
  - ❌ Trainers (no access)

### 3. Receptionist
- **Email:** receptionist@gymmate.com
- **Password:** password
- **Access Level:** Front desk operations
- **Permissions:**
  - ✅ Dashboard (view)
  - ✅ Payments (full CRUD)
  - ✅ Attendance (full CRUD + export)
  - ❌ Members (no access)
  - ❌ Trainers (no access)
  - ❌ Subscriptions (no access)
  - ❌ Workout Plans (no access)
  - ❌ Classes (no access)

### 4. Trainer
- **Email:** trainer@gymmate.com
- **Password:** password
- **Access Level:** Training operations (future implementation)
- **Permissions:**
  - ✅ Dashboard (view)
  - ❌ All other modules (no access currently)
  - 📝 Note: Can be extended to view assigned members and classes

### 5. Member
- **Email:** member@gymmate.com
- **Password:** password
- **Access Level:** Personal dashboard (future implementation)
- **Permissions:**
  - ✅ Dashboard (view own data)
  - ❌ All other modules (no access)
  - 📝 Note: Can be extended for self-service portal

## Permission Matrix

| Module | Admin | Manager | Receptionist | Trainer | Member |
|--------|-------|---------|--------------|---------|--------|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Members | ✅ | ✅ | ❌ | ❌ | ❌ |
| Trainers | ✅ | ❌ | ❌ | ❌ | ❌ |
| Subscriptions | ✅ | ✅ | ❌ | ❌ | ❌ |
| Payments | ✅ | ✅ | ✅ | ❌ | ❌ |
| Workout Plans | ✅ | ✅ | ❌ | ❌ | ❌ |
| Classes | ✅ | ✅ | ❌ | ❌ | ❌ |
| Attendance | ✅ | ✅ | ✅ | ❌ | ❌ |

## Technical Implementation

### Database Schema
```php
// Migration: add_role_to_users_table
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'manager', 'receptionist', 'trainer', 'member'])
        ->default('member')
        ->after('email');
});
```

### Middleware
**CheckRole Middleware** (`app/Http/Middleware/CheckRole.php`)
- Validates user authentication
- Checks if user has required role(s)
- Returns 403 Forbidden if unauthorized

### Usage in Routes
```php
// Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('trainers', TrainerController::class);
});

// Admin and Manager
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('members', MemberController::class);
});

// Admin, Manager, and Receptionist
Route::middleware(['auth', 'role:admin,manager,receptionist'])->group(function () {
    Route::resource('payments', PaymentController::class);
});
```

### Model Methods
**User Model** (`app/Models/User.php`)
```php
// Check specific role
$user->hasRole('admin'); // returns boolean

// Check multiple roles
$user->hasAnyRole(['admin', 'manager']); // returns boolean

// Helper methods
$user->isAdmin();
$user->isManager();
$user->isReceptionist();
$user->isTrainer();
$user->isMember();
```

### Blade Directives
```blade
{{-- Check for specific role --}}
@role('admin')
    <button>Admin Only Action</button>
@endrole

{{-- Check for any of multiple roles --}}
@hasAnyRole('admin', 'manager')
    <a href="{{ route('members.index') }}">Manage Members</a>
@endhasAnyRole

{{-- Using Auth facade --}}
@if(Auth::user()->isAdmin())
    <div>Admin Panel</div>
@endif
```

### UI Components
The sidebar (`resources/views/components/sidebar.blade.php`) automatically shows/hides menu items based on user roles:

```blade
@if(Auth::user()->hasAnyRole(['admin', 'manager']))
    <li class="nav-item">
        <a href="{{ route('members.index') }}">Members</a>
    </li>
@endif
```

## Security Features

1. **Route Protection**: All routes are protected by middleware
2. **UI Hiding**: Unauthorized menu items are hidden from the interface
3. **403 Forbidden**: Direct URL access returns proper error response
4. **Default Role**: New users default to 'member' role
5. **Role Display**: User's role is visible in navigation and profile

## Testing Credentials

### Quick Test Access
```
Admin:        admin@gymmate.com / password
Manager:      manager@gymmate.com / password
Receptionist: receptionist@gymmate.com / password
Trainer:      trainer@gymmate.com / password
Member:       member@gymmate.com / password
```

## Future Enhancements

### Trainer Portal
- View assigned members
- Manage personal workout plans
- View class schedules
- Track member progress

### Member Portal
- View personal subscription
- Check workout plans
- View attendance history
- Make payments
- Book classes

### Advanced Permissions
- Implement granular permissions (view, create, edit, delete)
- Add permission groups
- Create custom roles
- Implement role hierarchy
- Add audit logging

## Migration Commands

```bash
# Run migration
php artisan migrate

# Seed roles
php artisan db:seed --class=RoleSeeder

# Refresh and seed (WARNING: destroys data)
php artisan migrate:refresh --seed
```

## Troubleshooting

### 403 Forbidden Error
- Check if user is logged in
- Verify user has required role
- Check route middleware configuration
- Ensure role is properly set in database

### Menu Items Not Showing
- Verify role-based conditionals in sidebar
- Check if Auth::user() is available
- Clear view cache: `php artisan view:clear`

### Role Not Applied
- Check User model $fillable includes 'role'
- Verify migration ran successfully
- Ensure seeder ran without errors
- Check database users table has role column

## Support
For issues or questions, refer to Laravel's authorization documentation:
https://laravel.com/docs/authorization
