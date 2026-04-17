# 🚀 Professional Landing Page Template

A beautiful, customizable landing page template for multi-service companies built with **Laravel**, **Blade**, and **Tailwind CSS**.

![Status](https://img.shields.io/badge/status-ready%20to%20use-green) ![Laravel](https://img.shields.io/badge/Laravel-11.x-red) ![Tailwind](https://img.shields.io/badge/Tailwind-3.x-blue)

## ✨ Features

- 🎨 **Beautiful Design** - Modern, professional landing page out of the box
- 🎯 **Fully Customizable** - Change colors, content, services, testimonials in minutes
- 📱 **Responsive** - Perfect on mobile, tablet, and desktop
- ♿ **Accessible** - WCAG compliant, semantic HTML, keyboard navigation
- ⚡ **Fast** - Optimized performance, lightweight, Alpine.js interactivity
- 🔒 **Secure** - Your backend remains completely intact and private
- 🌐 **Multi-language Ready** - Built with Laravel's localization support

## 🎯 What's Inside

**Landing Page Sections:**
- ✅ Hero with CTA buttons and animations
- ✅ About/Why Choose Us
- ✅ 6 Service Showcase
- ✅ How It Works (3-step process)
- ✅ Testimonials with ratings
- ✅ Call-to-Action section
- ✅ Contact form + information
- ✅ Responsive navbar with mobile menu
- ✅ Footer with social links

**Backend Intact:**
- All your Laravel controllers, models, migrations, routes remain **untouched**
- Perfect for selling to clients - they get a beautiful frontend with your full backend

## 🚀 Quick Start

**For customers (5 minutes setup):**

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate

# 3. Customize (edit .env file)
COMPANY_NAME=Your Company
COMPANY_EMAIL=contact@company.com
COMPANY_PHONE=+250 700 000 000

# 4. Run server
php artisan serve
```

Visit: **http://localhost:8000**

**Full customization guide:** See [TEMPLATE_SETUP.md](TEMPLATE_SETUP.md)  
**Quick reference:** See [QUICKSTART.md](QUICKSTART.md)

## 📝 Customization

Everything is customizable through simple config files:

### Change Company Info (`.env`)
```env
COMPANY_NAME=Your Business
COMPANY_EMAIL=hello@company.com
COMPANY_PHONE=+250 700 000 000
```

### Customize Content (`config/landing.php`)
```php
'services' => [
    'items' => [
        ['title' => 'Your Service', 'description' => '...'],
        // Add your 6 services here
    ],
],
'testimonials' => [
    'items' => [
        ['quote' => '...', 'author' => '...', 'rating' => 5],
        // Add customer testimonials
    ],
],
```

### Change Colors
```php
'colors' => [
    'primary' => 'blue',    // emerald, blue, purple, red, orange, etc.
    'accent' => 'purple',
],
```

## 📁 File Structure

```
resources/views/
├── welcome.blade.php                 ← Main landing page
├── layouts/
│   └── landing.blade.php            ← Layout wrapper
└── components/landing/
    ├── navbar.blade.php
    ├── footer.blade.php
    ├── section-title.blade.php
    ├── card.blade.php
    ├── feature-card.blade.php
    ├── testimonial-card.blade.php
    └── stat-card.blade.php

config/
└── landing.php                       ← Configuration (edit this!)
```

## 🎨 Customization Options

| Element | Where to Change | Options |
|---------|-----------------|---------|
| Company Name | `.env` | Any text |
| Colors | `config/landing.php` | emerald, blue, purple, red, orange, cyan, yellow, pink |
| Services | `config/landing.php` | Up to 6 items with custom titles & descriptions |
| Testimonials | `config/landing.php` | Up to 6 customer reviews with ratings |
| Hero Text | `config/landing.php` | Custom heading, badge, description |
| Contact Info | `.env` + `config/landing.php` | Phone, email, location, hours |

## 🔧 For Developers

### Adding Custom Services
Edit `config/landing.php`:
```php
'services' => [
    'items' => [
        [
            'title' => 'Your Service',
            'description' => 'What it does...',
            'icon_color' => 'emerald',
        ],
    ],
],
```

### Customizing Styles
All styling uses **Tailwind CSS**. Edit blade files to customize:
- Colors: Use Tailwind color classes (`text-emerald-600`, etc.)
- Layout: Modify grid/flex classes
- Animations: Adjust animation classes

### Extending Components
Components are modular and easy to extend:
```blade
<x-landing.card 
    title="Your Title"
    description="Your description"
    iconColor="blue"
>
    <x-slot name="icon">
        <!-- Your SVG icon -->
    </x-slot>
</x-landing.card>
```

## 🌐 Responsive Design

- **Mobile** (640px) - Single column, optimized touch targets
- **Tablet** (768px) - 2-column layouts
- **Desktop** (1024px) - Full 3-column layouts

Every section is fully responsive and tested.

## ♿ Accessibility

- ✅ Semantic HTML5 structure
- ✅ ARIA labels on interactive elements
- ✅ Skip-to-content link
- ✅ Keyboard navigation support
- ✅ Color contrast ratios (WCAG AA)
- ✅ Alt text on images
- ✅ Form labels properly associated

## 🔐 Backend Security

Your backend is **completely separate** and secure:
- ✅ All Laravel routes, controllers, models intact
- ✅ Landing page is frontend-only
- ✅ Contact form is frontend-only (customize as needed)
- ✅ Perfect for selling templates to clients
- ✅ You keep your proprietary code

## 📦 Dependencies

- **Laravel 11.x** - PHP framework
- **Tailwind CSS 3.x** - Utility-first CSS
- **Alpine.js** - Lightweight JavaScript
- **Blade** - Laravel templating

## 🚢 Deployment

Ready to deploy:

```bash
# Build production assets
npm run build

# Deploy to your server (follow Laravel deployment guide)
```

## 📚 Documentation

1. **Quick Start** → [QUICKSTART.md](QUICKSTART.md) (5 minute setup)
2. **Full Setup Guide** → [TEMPLATE_SETUP.md](TEMPLATE_SETUP.md) (Detailed customization)
3. **Laravel Docs** → [laravel.com/docs](https://laravel.com/docs)

## 💡 Tips

- Use `php artisan config:cache` after changing `config/landing.php`
- Test on mobile devices before deploying
- Keep your `.env` file secure (never commit to git)
- Backup your customizations in `config/landing.php`

## 🤝 Support

For issues or questions:
1. Check [TEMPLATE_SETUP.md](TEMPLATE_SETUP.md) for solutions
2. Review Laravel documentation
3. Check component files for implementation details

## 📄 License

This template is yours to use and customize. You can sell templates built with this to your clients.

---

## Quick Stats

- 📄 **8 Landing Page Sections** (Hero, About, Services, Process, Testimonials, CTA, Contact, Footer)
- 🎨 **7 Reusable Components** (Navbar, Footer, Cards, Section Title, etc.)
- 🎯 **100% Customizable** via config files
- ⚡ **Fully Responsive** (mobile-first)
- ♿ **Accessibility Compliant** (WCAG)
- 🚀 **Production Ready** (can deploy today)

---

**Built with ❤️ for developers who want beautiful landing pages without the complexity.**

[Get Started →](QUICKSTART.md)
