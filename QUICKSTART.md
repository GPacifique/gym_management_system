# ⚡ Quick Start Guide (5 Minutes)

Get your customized landing page up and running in just 5 minutes!

## 1. Install Dependencies (2 min)

```bash
composer install
npm install
```

## 2. Set Up Database (1 min)

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## 3. Customize Your Landing Page (2 min)

### Edit `.env` file and update:

```env
COMPANY_NAME=Your Business Name
COMPANY_PHONE=+250 700 000 000
COMPANY_EMAIL=hello@yourcompany.com
COMPANY_LOCATION=Your City, Country
```

### Edit `config/landing.php` and customize:

1. **Your Services** (6 items)
2. **Hero Section** (main heading, badge)
3. **About Section** (why choose you)
4. **How It Works** (3-step process)
5. **Testimonials** (customer quotes)

## 4. View Your Landing Page

```bash
php artisan serve
```

Then visit: **http://localhost:8000**

## That's it! 🎉

Your beautiful, professional landing page is now live!

---

## What's Included?

✅ Hero section with CTA buttons  
✅ Services showcase (6 items)  
✅ About / Why Choose Us section  
✅ How It Works (3-step process)  
✅ Testimonials with ratings  
✅ Contact section + form  
✅ Responsive design (mobile, tablet, desktop)  
✅ Fully accessible (WCAG)  
✅ Tailwind CSS styling  
✅ Alpine.js interactivity  

## Next Steps

1. **Customize Colors** → Edit `config/landing.php` → change `'primary'` color
2. **Add Your Logo** → Replace in navbar (`resources/views/components/landing/navbar.blade.php`)
3. **Customize Contact Form** → Edit `config/landing.php` contact section
4. **Add More Content** → Edit `config/landing.php` to add more services/testimonials
5. **Deploy** → Follow Laravel deployment guide

## Need Help?

- **Full Setup Guide**: Read `TEMPLATE_SETUP.md`
- **Customize Content**: Edit `config/landing.php`
- **Customize Styles**: Edit Tailwind classes in blade files
- **Laravel Docs**: https://laravel.com/docs

---

**Enjoy your new landing page!** 🚀
