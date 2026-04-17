# ✅ Customization Checklist

Use this checklist to customize your landing page. It should take about **15-20 minutes**.

## Phase 1: Installation & Setup (2 min)

- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Run `cp .env.example .env`
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan serve`
- [ ] Visit `http://localhost:8000` and verify landing page loads

## Phase 2: Company Information (5 min)

Edit `.env` file and fill in YOUR information:

- [ ] `COMPANY_NAME` - Your business name
- [ ] `COMPANY_TAGLINE` - Your company tagline (2-3 words)
- [ ] `COMPANY_DESCRIPTION` - What your company does
- [ ] `COMPANY_PHONE` - Your phone number
- [ ] `COMPANY_EMAIL` - Your email address
- [ ] `COMPANY_LOCATION` - Your physical location
- [ ] `COMPANY_HOURS` - Your business hours
- [ ] `COMPANY_WHATSAPP` - Your WhatsApp number (for floating button)

**After editing `.env`, run:**
```bash
php artisan config:cache
```

## Phase 3: Customize Content (10 min)

Edit `config/landing.php` and customize:

### Hero Section
- [ ] Update `hero.badge` - Your main badge/category
- [ ] Update `hero.main_heading` - Your main headline
- [ ] Update `hero.subheading` - Tagline description
- [ ] Update `hero.cta_primary_text` - Main button text (e.g., "Get Started")
- [ ] Update `hero.cta_secondary_text` - Secondary button text (e.g., "Learn More")
- [ ] Update `hero.stats` - 3 key statistics about your business

### About Section
- [ ] Update `about.badge` - Badge text (e.g., "Why Choose Us")
- [ ] Update `about.heading` - Main heading
- [ ] Update `about.subheading` - Description
- [ ] Update `about.benefits[].title` - Each benefit title (3 items)
- [ ] Update `about.benefits[].description` - Each benefit description
- [ ] Update `about.cta_text` - Button text

### Services Section (IMPORTANT)
- [ ] Update `services.badge` - Section badge
- [ ] Update `services.heading` - Section heading
- [ ] Update `services.subheading` - Section description
- [ ] Update all 6 `services.items`:
  - [ ] Service 1 - title, description, icon_color
  - [ ] Service 2 - title, description, icon_color
  - [ ] Service 3 - title, description, icon_color
  - [ ] Service 4 - title, description, icon_color
  - [ ] Service 5 - title, description, icon_color
  - [ ] Service 6 - title, description, icon_color

### How It Works Section
- [ ] Update `process.steps[1]` - Step 1 title and description
- [ ] Update `process.steps[2]` - Step 2 title and description
- [ ] Update `process.steps[3]` - Step 3 title and description
- [ ] Update `process.highlights` - 4 feature highlights

### Testimonials Section
- [ ] Update `testimonials.badge` - Section badge
- [ ] Update `testimonials.heading` - Section heading
- [ ] Add/update all 6 testimonials:
  - [ ] Testimonial 1 - quote, author, role, company, rating
  - [ ] Testimonial 2 - quote, author, role, company, rating
  - [ ] Testimonial 3 - quote, author, role, company, rating
  - [ ] Testimonial 4 - quote, author, role, company, rating
  - [ ] Testimonial 5 - quote, author, role, company, rating
  - [ ] Testimonial 6 - quote, author, role, company, rating

### Call-to-Action Section
- [ ] Update `cta.heading` - Main CTA heading
- [ ] Update `cta.subheading` - Subheading
- [ ] Update `cta.cta_text` - Button text
- [ ] Update `cta.benefits` - 3 key benefits

### Contact Section
- [ ] Update `contact.badge` - Section badge
- [ ] Update `contact.heading` - Section heading
- [ ] Update `contact.subheading` - Description
- [ ] Update `contact.form_heading` - Form title

## Phase 4: Styling & Colors (3 min)

Edit `config/landing.php`:

- [ ] Choose your `colors.primary` color:
  - Options: `emerald` (default), `blue`, `purple`, `red`, `orange`, `cyan`, `yellow`, `pink`
- [ ] Choose your `colors.accent` color:
  - Should complement your primary color

## Phase 5: Testing (3 min)

Run in browser (http://localhost:8000):

- [ ] Hero section displays correctly
- [ ] All text is properly formatted
- [ ] Images/graphics load
- [ ] Buttons are clickable
- [ ] Test on mobile device or mobile view
- [ ] Test on tablet size
- [ ] Test on desktop size
- [ ] Verify all links work:
  - [ ] "Get Started" button
  - [ ] "Learn More" button
  - [ ] "Contact Us" button
  - [ ] Footer links

## Phase 6: Final Checks (2 min)

Before deploying:

- [ ] All company info is accurate
- [ ] All services/products are listed
- [ ] Testimonials are real or represent your business well
- [ ] Contact information is current
- [ ] No spelling/grammar errors
- [ ] Color scheme matches your brand

## Phase 7: Deployment (Optional)

When ready to go live:

- [ ] Update `APP_ENV=production` in `.env`
- [ ] Run `php artisan config:cache`
- [ ] Run `npm run build`
- [ ] Deploy to your hosting
- [ ] Test on live URL
- [ ] Monitor for any issues

---

## 📋 Quick Reference

**Edit these two files:**
1. `.env` - Company information
2. `config/landing.php` - Content, services, testimonials, colors

**Run after editing `.env`:**
```bash
php artisan config:cache
```

**Run server:**
```bash
php artisan serve
```

**View your page:**
- http://localhost:8000

---

## 💡 Tips

✅ Keep service descriptions **short and benefit-focused** (not feature-focused)
✅ Use **real testimonials** (with real names and companies)
✅ Pick colors that **match your brand**
✅ Use **active, action-oriented** button text
✅ Keep **headings concise** (5-8 words max)
✅ Test on **actual mobile device**, not just browser dev tools

---

## 🎨 Icon Color Options

When setting `icon_color` for services, choose from:
- `emerald` - Green (trust, growth)
- `blue` - Blue (professional, tech)
- `red` - Red (energy, action)
- `purple` - Purple (premium, creative)
- `orange` - Orange (friendly, warm)
- `cyan` - Cyan (modern, tech)
- `yellow` - Yellow (optimism, energy)
- `pink` - Pink (modern, friendly)

**Tip:** Mix colors for visual interest! Don't use all the same color.

---

## ✨ Common Customizations

### For a Tech Startup
```php
'colors.primary' => 'blue',
'colors.accent' => 'cyan',
'hero.badge' => 'Cutting-edge Technology',
```

### For a Wellness Business
```php
'colors.primary' => 'emerald',
'colors.accent' => 'cyan',
'hero.badge' => 'Health & Wellness',
```

### For a Creative Agency
```php
'colors.primary' => 'purple',
'colors.accent' => 'pink',
'hero.badge' => 'Creative Excellence',
```

---

## ❓ Need Help?

1. Check `TEMPLATE_SETUP.md` for detailed explanations
2. Check `EXAMPLES.md` for real-world examples
3. Check the code comments in `config/landing.php`

---

**You're all set! Your customized landing page is ready to go.** 🚀

Track your progress above and check off each item as you complete it.
