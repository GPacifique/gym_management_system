# Super Admin Feature

## Overview
The Super Admin is the **top-level system manager** with platform-wide access and control over all gym accounts. Super Admins operate at the system level, above individual gym administrators.

## Role Hierarchy

```
┌─────────────────────────────────────┐
│         SUPER ADMIN                 │  ← Platform-wide management
│  (System Manager)                   │
└─────────────────────────────────────┘
            │
            ├─ Gym 1 ──┬─ Admin
            │          ├─ Manager
            │          ├─ Receptionist
            │          ├─ Trainer
            │          └─ Member
            │
            ├─ Gym 2 ──┬─ Admin
            │          └─ (other roles)
            │
            └─ Gym N ──┬─ Admin
                       └─ (other roles)
```

### Key Differences:
- **Super Admin**: Platform-wide control, manages ALL gyms, no gym-specific restrictions
- **Admin**: Gym-specific control, manages ONE gym and its users
- **Other Roles**: Limited permissions within a specific gym

## Features

### 1. Gym Account Management
- View all registered gyms with filtering options
- Approve or reject new gym registrations
- Suspend active gym accounts
- Delete gym accounts and all related data
- Export gym data to CSV

### 2. Subscription Management
- Update subscription tiers (trial, basic, premium, enterprise)
- Manage trial period expiration dates
- View subscription statistics

### 3. Dashboard
- Platform-wide statistics
- Recent gym registrations
- Quick actions for common tasks

## Access

### Super Admin Credentials
**Email:** superadmin@gymsystem.com  
**Password:** password

> **Important:** Change the default password immediately after first login in production!

## Routes

All super admin routes are protected by authentication and `super_admin` middleware:

- `GET /super-admin/dashboard` - Super admin dashboard
- `GET /super-admin/gyms` - List all gyms
- `GET /super-admin/gyms/{gym}` - View gym details
- `PATCH /super-admin/gyms/{gym}/approve` - Approve gym registration
- `PATCH /super-admin/gyms/{gym}/reject` - Reject gym registration
- `PATCH /super-admin/gyms/{gym}/suspend` - Suspend gym account
- `PATCH /super-admin/gyms/{gym}/update-subscription` - Update subscription
- `DELETE /super-admin/gyms/{gym}` - Delete gym account
- `GET /super-admin/gyms-export` - Export gyms to CSV

## Permissions

Super admins have:
- **Platform-wide access**: Can view and manage ALL gyms without restrictions
- **Bypass gym context**: Not bound to any specific gym
- **Approval authority**: Can approve/reject gym registrations
- **Suspension power**: Can suspend or delete gym accounts
- **System oversight**: Access to platform-wide statistics and analytics
- **No gym-specific limitations**: Unrestricted access across the entire platform

### What Super Admins CANNOT Do:
- Super admins are system managers, not gym operators
- They don't manage individual members, trainers, or classes within gyms
- They focus on platform health, not day-to-day gym operations
- Gym-specific operations are delegated to gym admins

## Security

### Middleware
The `EnsureSuperAdmin` middleware protects all super admin routes:
```php
if (!$user || !$user->isSuperAdmin()) {
    abort(403, 'Unauthorized access. Super Admin privileges required.');
}
```

### Role Checking
User model includes `isSuperAdmin()` method:
```php
public function isSuperAdmin(): bool
{
    return $this->role === 'super_admin';
}
```

## Creating Super Admin Users

### Via Seeder (Development)
Run the database seeder:
```bash
php artisan db:seed
```

### Manually (Production)
Use tinker to create a super admin:
```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Your Name',
    'email' => 'your-email@example.com',
    'password' => Hash::make('your-secure-password'),
    'role' => 'super_admin',
    'email_verified_at' => now(),
]);
```

## Gym Approval Workflow

1. **Pending Status**
   - New gyms register through the system
   - Status is set to 'pending' by default
   - Super admin receives notification

2. **Approval**
   - Super admin reviews gym details
   - Clicks "Approve" button
   - Status changes to 'approved'
   - `approved_at` timestamp is set
   - `approved_by` is set to super admin's ID

3. **Rejection**
   - Super admin reviews gym details
   - Clicks "Reject" with reason
   - Status changes to 'rejected'
   - `rejection_reason` is saved

4. **Suspension**
   - Super admin can suspend approved gyms
   - Status changes to 'suspended'
   - `suspension_reason` is saved
   - Gym cannot be accessed until reactivated

## Deletion Process

When deleting a gym, the following cascade operations occur:
1. Delete gym logo from storage
2. Delete all gym members
3. Set `gym_id` to null for assigned users
4. Detach all user relationships
5. Delete the gym record

> **Warning:** Gym deletion is permanent and cannot be undone!

## Statistics

The dashboard displays:
- **Total Gyms:** Count of all registered gyms
- **Total Members:** Count of members across all gyms
- **Pending Approvals:** Count of gyms awaiting approval
- **Total Revenue:** Sum of all completed payments

## Filtering Options

### Status Filter
- All
- Approved
- Pending
- Rejected
- Suspended

### Subscription Tier Filter
- All
- Trial
- Basic
- Premium
- Enterprise

### Search
Search by gym name, email, or phone number

## Export Feature

Export gym data to CSV with the following columns:
- ID
- Name
- Email
- Phone
- Address
- Status
- Subscription Tier
- Trial Ends At
- Owner Name
- Owner Email
- Members Count
- Created At

## UI Components

### Badges
- **Approved:** Green badge
- **Pending:** Yellow badge
- **Rejected:** Red badge
- **Suspended:** Gray badge

### Trial Status
- **On Trial:** Blue badge with expiration date
- **Expiring Soon:** Orange badge (≤ 7 days remaining)
- **Trial Expired:** Red badge

## Best Practices

1. **Regular Monitoring**
   - Check pending approvals daily
   - Monitor trial expirations
   - Review suspended accounts

2. **Documentation**
   - Always provide reasons for rejection/suspension
   - Keep records of approval decisions

3. **Security**
   - Change default super admin password
   - Limit super admin accounts to trusted personnel
   - Use strong, unique passwords

4. **Data Management**
   - Export data regularly for backup
   - Review gym statistics periodically
   - Clean up rejected/suspended accounts

## Troubleshooting

### Cannot Access Super Admin Routes
- Verify user has `role = 'super_admin'` in database
- Check if middleware is registered in `bootstrap/app.php`
- Ensure you're logged in

### Approval Not Working
- Check if gym status is 'pending'
- Verify user is authenticated as super admin
- Check Laravel logs for errors

### Export Not Generating
- Verify storage permissions
- Check if PHP has sufficient memory
- Review server logs

## Future Enhancements

Potential additions:
- Platform-wide analytics and reports
- Email notifications for new registrations
- Bulk operations (approve/reject multiple gyms)
- Gym performance metrics
- Revenue reporting and analytics
- Activity logs and audit trails
