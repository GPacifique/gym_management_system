# Asset Management Guide for Production

## Overview
This Laravel application uses a combination of:
1. **Vite** for building CSS and JavaScript assets
2. **Laravel's asset() helper** for referencing static files
3. **CDN links** for external libraries (Bootstrap, Chart.js)

## Asset Structure

```
public/
├── build/                      # Vite compiled assets (generated)
│   ├── assets/
│   │   ├── app-[hash].css     # Compiled CSS
│   │   └── app-[hash].js      # Compiled JavaScript
│   └── manifest.json          # Asset manifest
├── images/                     # Static images
│   ├── favicon.svg
│   ├── logo.svg
│   └── screenshots/
│       ├── dashboard.svg
│       ├── members.svg
│       ├── classes.svg
│       └── payments.svg
└── storage/                    # Symlink to storage/app/public
    └── [user uploads]
```

## How Assets are Loaded

### 1. Built Assets (CSS/JS)
**In Blade Templates:**
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**What it does:**
- In development: Loads assets from Vite dev server
- In production: Loads compiled assets from `public/build/`
- Automatically handles versioning and cache busting

### 2. Static Assets (Images, Fonts)
**Always use Laravel's `asset()` helper:**
```blade
<img src="{{ asset('images/logo.svg') }}" alt="Logo">
<link rel="icon" href="{{ asset('images/favicon.svg') }}">
```

**Why?**
- Respects `ASSET_URL` from .env
- Works in subdirectories
- Handles HTTPS/HTTP correctly

### 3. External Libraries (CDN)
```blade
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

**Benefits:**
- Faster loading (cached by users)
- No build process needed
- Reduced server bandwidth

## Production Deployment Steps

### 1. Build Assets Locally
```bash
npm install
npm run build
```

This creates optimized files in `public/build/`:
- Minified CSS
- Bundled JavaScript
- Manifest file for asset versioning

### 2. Set Environment Variables
In your `.env` file:
```env
APP_URL=https://yourdomain.com
ASSET_URL=https://yourdomain.com
APP_ENV=production
APP_DEBUG=false
```

### 3. Upload Files
Upload these directories to your hosting:
- `public/` → Goes to `public_html/` or web root
- `public/build/` → Must include all built assets
- `public/images/` → Static images
- Everything else → Outside web root for security

### 4. Verify Asset Loading
After deployment, check:
- [ ] CSS loads correctly (check browser console)
- [ ] JavaScript works (no console errors)
- [ ] Images display properly
- [ ] Favicon appears
- [ ] SVG screenshots in carousel work

## Troubleshooting

### CSS/JS Not Loading (404 Errors)

**Problem:** Vite assets returning 404

**Solution 1 - Check build files exist:**
```bash
ls -la public/build/
cat public/build/manifest.json
```

**Solution 2 - Rebuild assets:**
```bash
npm run build
```

**Solution 3 - Check .env:**
```env
ASSET_URL=https://yourdomain.com  # Must match your domain
```

### Images Not Displaying

**Problem:** Images show broken icon

**Solution 1 - Use asset() helper:**
```blade
<!-- WRONG -->
<img src="/images/logo.svg">

<!-- CORRECT -->
<img src="{{ asset('images/logo.svg') }}">
```

**Solution 2 - Check file exists:**
```bash
ls -la public/images/
```

**Solution 3 - Check permissions:**
```bash
chmod 644 public/images/*.svg
```

### Storage Images Not Working

**Problem:** User uploads not displaying

**Solution 1 - Create storage link:**
```bash
php artisan storage:link
```

**Solution 2 - Verify symlink:**
```bash
ls -la public/storage
# Should show: storage -> ../storage/app/public
```

**Solution 3 - Use correct path:**
```blade
<!-- For files in storage/app/public -->
<img src="{{ asset('storage/' . $filename) }}">
```

### Mixed Content Warnings (HTTP/HTTPS)

**Problem:** Assets loading via HTTP on HTTPS site

**Solution 1 - Force HTTPS in .env:**
```env
APP_URL=https://yourdomain.com  # Use https://
```

**Solution 2 - Update .htaccess:**
```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## Asset Best Practices

### ✅ DO:
- Always use `{{ asset() }}` for static files
- Use `@vite()` for CSS/JS in templates
- Build assets before deploying
- Set ASSET_URL in production
- Use CDN for external libraries
- Optimize images before uploading
- Enable browser caching in .htaccess

### ❌ DON'T:
- Hardcode asset paths like `/images/logo.png`
- Reference assets with relative paths `../images/`
- Forget to build assets before deploy
- Upload `node_modules/` to production
- Use development mode in production
- Store sensitive files in public directory

## Performance Optimization

### 1. Image Optimization
```bash
# Compress SVGs
svgo --multipass public/images/*.svg

# Compress PNGs
optipng -o7 public/images/*.png
```

### 2. Enable Compression (.htaccess)
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/css text/javascript application/javascript
</IfModule>
```

### 3. Browser Caching (.htaccess)
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

### 4. Optimize Vite Build
In `vite.config.js`:
```javascript
export default defineConfig({
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
```

## CDN Configuration (Optional)

If using a CDN for assets:

### 1. Update .env:
```env
ASSET_URL=https://cdn.yourdomain.com
```

### 2. Upload public assets to CDN:
- public/build/*
- public/images/*
- public/storage/* (if using CDN for uploads)

### 3. Configure CORS (if needed):
```apache
<FilesMatch "\.(svg|woff|woff2|ttf|eot)$">
    Header set Access-Control-Allow-Origin "*"
</FilesMatch>
```

## Monitoring Asset Loading

### Check in Browser DevTools:
1. Open DevTools (F12)
2. Go to Network tab
3. Reload page
4. Check for:
   - All assets load (200 status)
   - No 404 errors
   - No mixed content warnings
   - Assets load from correct domain

### Monitor Performance:
```bash
# Check asset sizes
du -sh public/build/assets/*

# Check total built assets
du -sh public/build/
```

## Quick Reference

### Common Asset Patterns:

```blade
<!-- Static images -->
<img src="{{ asset('images/logo.svg') }}">

<!-- User uploads -->
<img src="{{ asset('storage/' . $user->photo) }}">

<!-- Built CSS/JS -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- External CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- Inline styles (avoid in production) -->
<style>
    /* Only for critical above-the-fold CSS */
</style>
```

### Useful Commands:

```bash
# Build for production
npm run build

# Watch for changes (development)
npm run dev

# Clear Laravel caches
php artisan cache:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link
```

## Support

If assets still not loading after following this guide:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify file permissions: 644 for files, 755 for directories
4. Test with browser cache disabled
5. Check .htaccess rewrite rules
