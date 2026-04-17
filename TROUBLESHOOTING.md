# 🔧 Troubleshooting Guide

If you encounter errors while running the landing page template, follow this guide.

## Common Issues & Solutions

### Issue 1: HTTP 500 Error
**Symptoms:** "HTTP ERROR 500" or "Internal Server Error"

**Causes & Solutions:**
1. **Invalid .env file (Most Common)**
   - ✅ Solution: Wrap values with special characters in quotes
   ```env
   COMPANY_TAGLINE="Smart Income & Expense Management"
   COMPANY_LOCATION="Kigali, Rwanda"
   COMPANY_HOURS="Monday - Saturday: 8:00 AM - 6:00 PM"
   ```

2. **Cache not cleared**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

3. **Database not set up**
   ```bash
   php artisan migrate
   ```

---

### Issue 2: "Class not found" Error
**Symptoms:** "Class 'App\View\Components\Landing\...' not found"

**Solution:**
The Blade components are in the wrong location. Make sure files are in:
```
resources/views/components/landing/
├── navbar.blade.php
├── footer.blade.php
├── section-title.blade.php
├── card.blade.php
├── feature-card.blade.php
├── testimonial-card.blade.php
└── stat-card.blade.php
```

If components are missing, see TEMPLATE_SETUP.md for details.

---

### Issue 3: Config not Loading
**Symptoms:** Landing page shows placeholder text instead of your company info

**Solutions:**
1. Clear config cache:
   ```bash
   php artisan config:cache
   php artisan config:clear
   ```

2. Make sure `config/landing.php` exists and is valid
3. Check `php artisan config:show landing` to verify config loaded

---

### Issue 4: Styling Looks Broken
**Symptoms:** Page loads but CSS/Tailwind styling missing

**Solutions:**
1. Clear view cache:
   ```bash
   php artisan view:clear
   ```

2. Make sure you ran `npm install`:
   ```bash
   npm install
   npm run dev
   ```

3. Check that `resources/css/app.css` and `resources/js/app.js` exist

---

### Issue 5: Components Not Rendering
**Symptoms:** Page shows errors about missing components

**Solutions:**
1. Verify component files exist in `resources/views/components/landing/`
2. Component names are lowercase with hyphens: `<x-landing.navbar />`
3. Clear caches:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

---

### Issue 6: "Service Provider" Errors
**Symptoms:** "Service provider not registered"

**Solution:**
Run these commands:
```bash
composer dump-autoload
php artisan optimize
php artisan config:cache
```

---

## Quick Diagnostics

Run these commands to check your setup:

### 1. Check PHP Version
```bash
php --version
# Should be PHP 8.1 or higher
```

### 2. Check Laravel Version
```bash
php artisan --version
# Should be Laravel 11.x
```

### 3. Verify Configuration
```bash
php artisan config:show app
php artisan config:show landing
```

### 4. Test Database
```bash
php artisan tinker
# Then type: DB::connection()->getPdo()
# Should return connection or error
```

### 5. Clear Everything
```bash
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Step-by-Step Fix (Start Here)

If you're stuck, follow this order:

1. **Fix .env file**
   ```bash
   # Make sure all values with spaces/special chars are quoted:
   COMPANY_NAME="Your Company"
   COMPANY_TAGLINE="Your tagline with & symbols"
   COMPANY_LOCATION="City, Country"
   ```

2. **Clear all caches**
   ```bash
   php artisan optimize:clear
   ```

3. **Verify files exist**
   ```bash
   # Check these files exist:
   - config/landing.php
   - resources/views/welcome.blade.php
   - resources/views/layouts/landing.blade.php
   - resources/views/components/landing/*.blade.php (7 files)
   ```

4. **Reinstall dependencies**
   ```bash
   composer install
   npm install
   npm run dev
   ```

5. **Set up database**
   ```bash
   php artisan migrate
   ```

6. **Start server**
   ```bash
   php artisan serve
   ```

7. **Visit page**
   ```
   http://localhost:8000
   ```

---

## Common .env Issues

### ❌ WRONG
```env
COMPANY_TAGLINE=Smart Income & Expense Management
COMPANY_LOCATION=Kigali, Rwanda
COMPANY_PHONE=+250 786 163 963
```

### ✅ CORRECT
```env
COMPANY_TAGLINE="Smart Income & Expense Management"
COMPANY_LOCATION="Kigali, Rwanda"
COMPANY_PHONE="+250 786 163 963"
```

**Rule:** If your value contains:
- Spaces
- Special characters (`&`, `@`, `!`, `#`, etc.)
- Commas
- Hyphens

→ **Wrap it in double quotes!**

---

## Error Log Location

Check Laravel logs for detailed errors:

**File:** `storage/logs/laravel.log`

**Command:**
```bash
# Show last 50 lines
tail -n 50 storage/logs/laravel.log

# Or on Windows:
Get-Content storage/logs/laravel.log -Tail 50
```

---

## Getting Help

If you're still stuck:

1. **Check QUICKSTART.md** - Basic setup
2. **Check TEMPLATE_SETUP.md** - Detailed guide
3. **Read error logs** - Most specific info
4. **Check Laravel docs** - https://laravel.com/docs
5. **Try the fix above** - Usually works

---

## Emergency Reset

If everything breaks, start fresh:

```bash
# Backup your customizations first!
cp config/landing.php config/landing.php.backup
cp .env .env.backup

# Then reset:
php artisan optimize:clear
php artisan migrate:refresh
rm storage/logs/laravel.log
php artisan serve
```

---

**Most errors are caused by .env file formatting. Make sure to quote all values with special characters!** ✅
