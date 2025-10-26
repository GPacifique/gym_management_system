#!/bin/bash

# Production Deployment Script for Namecheap
# This script prepares your Laravel application for production deployment

echo "========================================="
echo "Laravel Production Deployment Preparation"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}! $1${NC}"
}

# Check if running in correct directory
if [ ! -f "artisan" ]; then
    print_error "artisan file not found. Please run this script from your Laravel root directory."
    exit 1
fi

print_success "Found Laravel project"
echo ""

# Step 1: Install/Update Dependencies
echo "Step 1: Installing/Updating Dependencies..."
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
    print_success "Composer dependencies installed"
else
    print_error "Composer not found. Please install composer first."
    exit 1
fi
echo ""

# Step 2: Build Frontend Assets
echo "Step 2: Building Frontend Assets..."
if command -v npm &> /dev/null; then
    npm install
    npm run build
    print_success "Frontend assets built successfully"
else
    print_error "npm not found. Please install Node.js and npm first."
    exit 1
fi
echo ""

# Step 3: Clear all caches
echo "Step 3: Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
print_success "All caches cleared"
echo ""

# Step 4: Optimize for production
echo "Step 4: Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Application optimized"
echo ""

# Step 5: Check file permissions
echo "Step 5: Checking file permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
print_success "File permissions set correctly"
echo ""

# Step 6: Create production archive
echo "Step 6: Creating deployment archive..."
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
ARCHIVE_NAME="deployment_${TIMESTAMP}.tar.gz"

# Files and directories to exclude
tar -czf "$ARCHIVE_NAME" \
    --exclude=node_modules \
    --exclude=.git \
    --exclude=.env \
    --exclude=tests \
    --exclude=storage/logs/*.log \
    --exclude=*.tar.gz \
    .

print_success "Deployment archive created: $ARCHIVE_NAME"
echo ""

# Step 7: Verify critical files
echo "Step 7: Verifying critical files..."
CRITICAL_FILES=(
    "public/build/manifest.json"
    "vendor/autoload.php"
    "bootstrap/app.php"
)

ALL_PRESENT=true
for file in "${CRITICAL_FILES[@]}"; do
    if [ -f "$file" ]; then
        print_success "$file exists"
    else
        print_error "$file is missing!"
        ALL_PRESENT=false
    fi
done
echo ""

# Step 8: Display deployment checklist
echo "========================================="
echo "DEPLOYMENT CHECKLIST"
echo "========================================="
echo ""
echo "Before uploading to Namecheap:"
echo ""
echo "□ 1. Create database in cPanel"
echo "□ 2. Note database name, username, and password"
echo "□ 3. Update .env.production with your domain and database details"
echo "□ 4. Generate APP_KEY: php artisan key:generate"
echo "□ 5. Set APP_DEBUG=false in .env"
echo "□ 6. Set APP_ENV=production in .env"
echo "□ 7. Upload files via FTP/SFTP or cPanel"
echo "□ 8. Move public/ contents to public_html/"
echo "□ 9. Update public_html/index.php paths"
echo "□ 10. Run: php artisan migrate --force"
echo "□ 11. Run: php artisan storage:link"
echo "□ 12. Test the application"
echo ""

if [ "$ALL_PRESENT" = true ]; then
    print_success "All critical files present!"
    echo ""
    print_success "Deployment package ready: $ARCHIVE_NAME"
    echo ""
    print_warning "Remember to:"
    echo "  1. Copy .env.production to .env on the server"
    echo "  2. Update .env with your production values"
    echo "  3. Generate new APP_KEY on production"
    echo ""
else
    print_error "Some critical files are missing. Please resolve before deploying."
    exit 1
fi

echo "========================================="
echo "For detailed deployment instructions, see:"
echo "DEPLOYMENT_GUIDE.md"
echo "========================================="
