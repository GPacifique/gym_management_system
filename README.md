# GymMate - Modern Gym Management System

GymMate is a comprehensive gym management system designed to streamline operations and enhance member experience. Built with Laravel and modern web technologies, it offers an intuitive interface with role-based access control for different user types.

## Features

### Core Functionality
- **Member Management** - Track member information, subscriptions, and activity
- **Trainer Management** - Manage trainer profiles and assignments
- **Subscription Plans** - Flexible subscription management with automated tracking
- **Payment Processing** - Record and track all payments with detailed history
- **Workout Plans** - Create and assign personalized workout routines
- **Class Scheduling** - Organize and manage gym classes
- **Attendance Tracking** - Monitor member check-ins and class attendance
- **Analytics Dashboard** - Real-time insights with interactive charts

### Role-Based Access Control (RBAC)
GymMate implements a comprehensive 5-role permission system:

| Role | Access Level | Key Permissions |
|------|--------------|-----------------|
| **Admin** | Full System | All modules with complete CRUD access |
| **Manager** | Management | Members, Subscriptions, Payments, Plans, Classes, Attendance |
| **Receptionist** | Front Desk | Payments, Attendance tracking |
| **Trainer** | Personal | Dashboard (extendable for workout plans) |
| **Member** | Self-Service | Personal dashboard (extendable for portal) |

#### Test Credentials
```
Admin:        admin@gymmate.com / password
Manager:      manager@gymmate.com / password
Receptionist: receptionist@gymmate.com / password
Trainer:      trainer@gymmate.com / password
Member:       member@gymmate.com / password
```

For detailed RBAC documentation, see [RBAC_DOCUMENTATION.md](RBAC_DOCUMENTATION.md)

## Technology Stack

- **Backend:** Laravel 12.35.1
- **PHP:** 8.4.11
- **Database:** MySQL
- **Frontend:** Bootstrap 5, Bootstrap Icons
- **Charts:** Chart.js
- **Authentication:** Laravel Breeze

## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd gym_management_system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   Edit `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gym_management
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=GymSeeder
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   Visit: `http://localhost:8000`

## Usage

### Quick Start
1. Navigate to `http://localhost:8000`
2. Log in with one of the test credentials above
3. Explore features based on your role

### Common Operations

**Record Attendance (Receptionist/Admin/Manager)**
- Navigate to Members → Click check icon on member row
- Or go to Attendance → Add New Attendance

**Add New Member (Admin/Manager)**
- Members → Add New Member
- Fill in required information
- Assign trainer if needed

**Process Payment (Admin/Manager/Receptionist)**
- Payments → Record Payment
- Select member and subscription
- Amount auto-fills, confirm and submit

**Create Workout Plan (Admin/Manager)**
- Workout Plans → Create New
- Assign to member and trainer
- Set schedule and exercises

## Project Structure

```
gym_management_system/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # All resource controllers
│   │   └── Middleware/      # CheckRole middleware
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers with Blade directives
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # RoleSeeder, GymSeeder
├── resources/
│   └── views/
│       ├── components/      # Reusable components (sidebar, etc.)
│       ├── layouts/         # Base layouts
│       └── [modules]/       # Feature-specific views
├── routes/
│   └── web.php             # All application routes with RBAC
└── public/                 # Compiled assets
```

## Development

### Adding New Roles
1. Update migration: `database/migrations/*_add_role_to_users_table.php`
2. Add helper methods to `app/Models/User.php`
3. Create route groups in `routes/web.php`
4. Update sidebar in `resources/views/components/sidebar.blade.php`

### Creating New Features
```bash
# Generate controller
php artisan make:controller FeatureController --resource

# Generate model with migration
php artisan make:model Feature -m

# Generate seeder
php artisan make:seeder FeatureSeeder
```

## Security

- All routes protected by authentication
- Role-based middleware on sensitive operations
- CSRF protection on all forms
- Password hashing with bcrypt
- Email verification available

## Troubleshooting

### Common Issues

**403 Forbidden Error**
- Verify user role in database
- Check route middleware configuration
- Ensure logged in with correct credentials

**Migrations Failing**
- Check database credentials in `.env`
- Ensure MySQL service is running
- Drop all tables and re-run migrations

**Assets Not Loading**
```bash
npm run build
php artisan config:clear
php artisan cache:clear
```

## About GymMate

GymMate leverages Laravel's powerful features:

- [Simple, fast routing engine](https://laravel.com/docs/routing)
- [Powerful dependency injection container](https://laravel.com/docs/container)
- [Expressive, intuitive database ORM](https://laravel.com/docs/eloquent)
- [Database agnostic schema migrations](https://laravel.com/docs/migrations)
- [Robust background job processing](https://laravel.com/docs/queues)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
