# 🎨 Landing Page Template Setup Guide

This is a **professional, customizable landing page template** for multi-service companies. Follow this guide to personalize it for your business.

## Quick Customization (2 Steps)

### Step 1: Update Your Company Info in `.env`

Open `.env` file in your project root and update these values:

```env
# Change these to your company details
COMPANY_NAME=Your Company Name
COMPANY_TAGLINE=Your Company Tagline
COMPANY_DESCRIPTION=What your company does
COMPANY_PHONE=+250 700 000 000
COMPANY_EMAIL=hello@yourcompany.com
COMPANY_LOCATION=Your City, Country
COMPANY_HOURS=Monday - Friday: 9:00 AM - 5:00 PM
COMPANY_WHATSAPP=+250700000000
```

### Step 2: Customize Services, Features & Content in `config/landing.php`

Edit `/config/landing.php` to customize:
- **Services** - What you offer (update 6 service items)
- **Hero Section** - Main headline and tagline
- **About Section** - Your value proposition
- **How It Works** - Your 3-step process
- **Testimonials** - Customer success stories
- **Contact Info** - Support channels

## File Structure

```
resources/views/
├── welcome.blade.php                 ← Main landing page
├── layouts/
│   └── landing.blade.php            ← Layout wrapper
└── components/landing/
    ├── navbar.blade.php              ← Top navigation
    ├── footer.blade.php              ← Footer section
    ├── section-title.blade.php       ← Section headers
    ├── card.blade.php                ← Service/feature cards
    ├── feature-card.blade.php        ← Hero feature highlight
    ├── testimonial-card.blade.php    ← Testimonial display
    └── stat-card.blade.php           ← Statistics display

config/
└── landing.php                        ← Configuration file (edit this!)
```

## Customization Options

### 1. Company Information

Edit `/config/landing.php`:

```php
'company' => [
    'name' => env('COMPANY_NAME', 'Your Business Name'),
    'email' => env('COMPANY_EMAIL', 'contact@company.com'),
    'phone' => env('COMPANY_PHONE', '+250 700 000 000'),
    // ... more fields
],
```

### 2. Colors

Change the primary and accent colors:

```php
'colors' => [
    'primary' => 'emerald',    // blue, purple, red, orange, cyan, yellow, pink
    'accent' => 'teal',
],
```

Available colors: `emerald`, `blue`, `purple`, `red`, `orange`, `cyan`, `yellow`, `pink`

### 3. Hero Section

```php
'hero' => [
    'badge' => 'Your Badge Text',
    'main_heading' => 'Your Main Heading',
    'subheading' => 'Your subheading text',
    'cta_primary_text' => 'Primary Button Text',
    'cta_secondary_text' => 'Secondary Button Text',
    'stats' => [
        ['value' => '100%', 'label' => 'Label'],
        // Add more stats
    ],
],
```

### 4. Services

Update your 6 main services:

```php
'services' => [
    'items' => [
        [
            'title' => 'Your Service Name',
            'description' => 'What this service does...',
            'icon_color' => 'emerald',  // emerald, red, blue, purple, cyan, orange
        ],
        // Add up to 6 services
    ],
],
```

### 5. How It Works (Process Steps)

```php
'process' => [
    'steps' => [
        [
            'number' => '1',
            'title' => 'Step One',
            'description' => 'What happens in step one...',
        ],
        // 3 steps total
    ],
],
```

### 6. Testimonials

```php
'testimonials' => [
    'items' => [
        [
            'quote' => 'What the customer said...',
            'author' => 'Customer Name',
            'role' => 'Job Title',
            'company' => 'Company Name',
            'rating' => 5,
        ],
        // Add up to 6 testimonials
    ],
],
```

## Sections Included

✅ **Hero Section** - Eye-catching header with CTA buttons and key stats
✅ **About Section** - Value proposition with trust indicators
✅ **Services Section** - Your 6 main offerings
✅ **How It Works** - 3-step process explanation
✅ **Testimonials** - Social proof from customers
✅ **Call-to-Action** - Final conversion push
✅ **Contact Section** - Contact form + info (frontend only)
✅ **Responsive Design** - Works on mobile, tablet, desktop
✅ **Accessible** - WCAG compliant, semantic HTML

## Design Features

🎨 **Beautiful UI**
- Tailwind CSS with customizable color schemes
- Glass-morphism effects
- Gradient backgrounds
- Smooth animations

📱 **Fully Responsive**
- Mobile-first design
- Optimized for all screen sizes
- Touch-friendly buttons

♿ **Accessible**
- Semantic HTML
- ARIA labels
- Keyboard navigation
- Screen reader friendly

⚡ **Performance**
- Lightweight
- Alpine.js for interactivity
- Optimized images
- Fast loading

## How to Use

1. **Install Laravel** (if you haven't already)
   ```bash
   composer install
   ```

2. **Set up your database**
   ```bash
   php artisan migrate
   ```

3. **Update `.env`** with your company details

4. **Edit `config/landing.php`** with your services and content

5. **View your landing page**
   Visit `http://yoursite.com/` to see your customized landing page

## Backend Integration

The landing page is **completely separated** from your backend logic:
- ✅ Your existing controllers, models, routes work as-is
- ✅ Contact form is frontend-only (customize as needed)
- ✅ Navigation buttons link to `route('register')` and `route('login')`
- ✅ All business logic remains untouched

### Custom Routes

If you want to customize where buttons link to, edit `resources/views/layouts/landing.blade.php`:

```blade
<!-- Primary CTA button -->
<a href="{{ route('register') }}" class="...">Start Now</a>

<!-- Secondary button -->
<a href="#services" class="...">Learn More</a>
```

## Contact Form Setup (Optional)

The contact form is frontend-only by default. To handle submissions:

1. Create a controller:
   ```bash
   php artisan make:controller ContactController
   ```

2. Add the route:
   ```php
   Route::post('/contact', [ContactController::class, 'store']);
   ```

3. Update the form action in `welcome.blade.php`:
   ```blade
   <form action="{{ route('contact.store') }}" method="POST">
   ```

## Customizing Fonts

The template uses:
- **Body text**: Inter font
- **Headings**: Poppins font

Both are loaded from Bunny CDN. To change fonts, edit `resources/views/layouts/landing.blade.php`:

```html
<!-- Change the href URLs in the <head> section -->
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900">
<link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900">
```

## Adding Social Links

Edit `resources/views/components/landing/footer.blade.php` to add your social media links:

```blade
<a href="https://facebook.com/yourpage" target="_blank" class="...">
    <!-- Facebook icon -->
</a>
```

## Support & Troubleshooting

**Q: How do I change the colors?**
A: Edit `config/landing.php` and change the `colors` section.

**Q: Can I add more services?**
A: Yes! The template displays 6 services by default, but you can add more by editing `config/landing.php`.

**Q: How do I change the hero image/background?**
A: Edit the gradient in `resources/views/welcome.blade.php` section id="hero".

**Q: Can I customize the navbar?**
A: Yes! Edit `resources/views/components/landing/navbar.blade.php`.

## License

This template is included with your purchase. You are free to customize and use it for your clients.

---

**Happy customizing! 🚀**

For questions or issues, refer to the Laravel documentation: https://laravel.com/docs
