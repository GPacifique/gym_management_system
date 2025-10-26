#!/bin/bash

# Post-Upload Configuration Script for Namecheap Shared Hosting
# Run this script AFTER uploading files to your Namecheap hosting
# Usage: bash post-upload-setup.sh

echo "========================================="
echo "Namecheap Post-Upload Setup"
echo "========================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}! $1${NC}"
}

# Detect current directory structure
CURRENT_DIR=$(pwd)
echo "Current directory: $CURRENT_DIR"
echo ""

# Step 1: Set correct permissions
echo "Step 1: Setting file permissions..."
if [ -d "storage" ]; then
    chmod -R 775 storage
    print_success "Storage permissions set"
fi

if [ -d "bootstrap/cache" ]; then
    chmod -R 775 bootstrap/cache
    print_success "Bootstrap cache permissions set"
fi
echo ""

# Step 2: Create storage link
echo "Step 2: Creating storage symbolic link..."
if [ -f "artisan" ]; then
    php artisan storage:link
    print_success "Storage link created"
else
    print_warning "artisan file not found. You may need to run: php artisan storage:link manually"
fi
echo ""

# Step 3: Run migrations
echo "Step 3: Running database migrations..."
read -p "Run database migrations? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    print_success "Migrations completed"
fi
echo ""

# Step 4: Clear and cache configs
echo "Step 4: Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Application optimized"
echo ""

# Step 5: Verify .env file
echo "Step 5: Checking .env configuration..."
if [ -f ".env" ]; then
    print_success ".env file exists"
    
    # Check critical .env values
    if grep -q "APP_KEY=base64:" .env; then
        print_success "APP_KEY is set"
    else
        print_warning "APP_KEY may not be set. Run: php artisan key:generate"
    fi
    
    if grep -q "APP_DEBUG=false" .env; then
        print_success "APP_DEBUG is set to false"
    else
        print_warning "APP_DEBUG should be false in production"
    fi
    
    if grep -q "APP_ENV=production" .env; then
        print_success "APP_ENV is set to production"
    else
        print_warning "APP_ENV should be production"
    fi
else
    print_warning ".env file not found! Please create it from .env.production"
fi
echo ""

# Step 6: Test database connection
echo "Step 6: Testing database connection..."
php artisan db:show 2>/dev/null && print_success "Database connection successful" || print_warning "Could not connect to database. Check your .env settings"
echo ""

echo "========================================="
echo "Setup Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Visit your domain to verify the application works"
echo "2. Create admin user if needed: php artisan tinker"
echo "3. Configure your domain SSL certificate in cPanel"
echo "4. Set up cron jobs for scheduled tasks"
echo ""
echo "Cron job example (for cPanel):"
echo "* * * * * cd /home/username/gym_management && php artisan schedule:run >> /dev/null 2>&1"
echo ""
