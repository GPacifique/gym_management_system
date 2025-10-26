# Deployment Guide for Namecheap Shared Hosting

## Prerequisites
- Namecheap shared hosting with PHP 8.1+ support
- SSH access (if available)
- cPanel access
- MySQL database created via cPanel

## Step 1: Prepare Your Application

### 1.1 Build Assets for Production
```bash
npm install
npm run build
```

This will create optimized assets in `public/build/` directory.

### 1.2 Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 2: Update Environment Configuration

### 2.1 Copy and edit .env.production
```bash
cp .env.production .env
```

### 2.2 Update these values in .env:
```
APP_URL=https://yourdomain.com
APP_DEBUG=false
APP_ENV=production

DB_DATABASE=your_cpanel_database_name
DB_USERNAME=your_cpanel_database_user
DB_PASSWORD=your_cpanel_database_password

ASSET_URL=https://yourdomain.com
```

### 2.3 Generate Application Key
```bash
php artisan key:generate
```

## Step 3: Upload Files to Namecheap

### Option A: Via FTP/SFTP
1. Connect to your hosting via FTP client (FileZilla, etc.)
2. Upload all files EXCEPT:
   - `.env` (upload separately with correct values)
   - `node_modules/`
   - `.git/`
   - `tests/`

### Option B: Via cPanel File Manager
1. Compress your project: `tar -czf project.tar.gz .`
2. Upload via cPanel File Manager
3. Extract in your account

## Step 4: Configure Directory Structure

### Typical Namecheap structure:
```
/home/username/
├── public_html/          # Web root
└── gym_management/       # Laravel app
```

### 4.1 Move Laravel Files
- Upload Laravel app to: `/home/username/gym_management/`
- Move only `public/` contents to: `/home/username/public_html/`

### 4.2 Update index.php
Edit `/home/username/public_html/index.php`:

```php
<?php

// Update these paths
require __DIR__.'/../gym_management/vendor/autoload.php';
$app = require_once __DIR__.'/../gym_management/bootstrap/app.php';
```

## Step 5: Set Permissions

Via SSH or cPanel Terminal:
```bash
chmod -R 755 /home/username/gym_management
chmod -R 775 /home/username/gym_management/storage
chmod -R 775 /home/username/gym_management/bootstrap/cache
```

## Step 6: Configure .htaccess

### 6.1 Root .htaccess (in public_html)
Create `/home/username/public_html/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect to HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    
    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Disable Directory Browsing
Options -Indexes

# Prevent access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

## Step 7: Database Setup

### 7.1 Import Database
Via cPanel phpMyAdmin or SSH:
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7.2 Create Storage Link
```bash
php artisan storage:link
```

## Step 8: Verify Asset URLs

All assets should use Laravel's `asset()` helper which automatically uses ASSET_URL from .env:
- ✓ `{{ asset('images/logo.svg') }}`
- ✓ `{{ asset('build/assets/app.css') }}`
- ✗ `/images/logo.svg` (hardcoded, avoid)

## Step 9: Test Your Application

1. Visit your domain: `https://yourdomain.com`
2. Check all pages load correctly
3. Verify images and CSS load
4. Test login functionality
5. Check database connections

## Troubleshooting

### CSS/JS Not Loading
1. Check ASSET_URL in .env matches your domain
2. Verify `public/build/` directory exists
3. Check .htaccess redirects

### 500 Internal Server Error
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify file permissions (775 for storage, 755 for others)
3. Check .env database credentials
4. Clear caches: `php artisan cache:clear`

### Images Not Displaying
1. Run: `php artisan storage:link`
2. Check `public/storage` symlink exists
3. Verify image paths use `asset()` helper

### Can't Access Admin Panel
1. Create admin user via SSH:
```bash
php artisan tinker
>>> $user = new App\Models\User();
>>> $user->name = 'Admin';
>>> $user->email = 'admin@yourdomain.com';
>>> $user->password = bcrypt('your-password');
>>> $user->role = 'admin';
>>> $user->save();
```

## Maintenance Mode

Enable:
```bash
php artisan down --secret="bypass-token"
```

Access via: `https://yourdomain.com/bypass-token`

Disable:
```bash
php artisan up
```

## Performance Optimization

### 1. Enable OPcache (via php.ini or cPanel)
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### 2. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Optimize Composer Autoloader
```bash
composer install --optimize-autoloader --no-dev
```

## Security Checklist

- [ ] APP_DEBUG=false in production
- [ ] Strong APP_KEY generated
- [ ] Database credentials secure
- [ ] HTTPS enabled (SSL certificate)
- [ ] File permissions set correctly
- [ ] .env file protected (not in public_html)
- [ ] Regular backups configured
- [ ] Error logging enabled

## Backup Strategy

### Files to Backup:
- `.env` file
- `storage/app/` (uploads)
- `public/storage/` (public files)
- Database export

### Automated Backup (cron job):
```bash
0 2 * * * cd /home/username/gym_management && php artisan backup:run
```

## Support

For issues specific to:
- **Namecheap Hosting**: Contact Namecheap support
- **Laravel Application**: Check Laravel documentation
- **Database Issues**: Verify cPanel MySQL settings
