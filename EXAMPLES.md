# 📋 Customer Customization Examples

This file shows **real examples** of how to customize the landing page for different business types.

## Example 1: Digital Marketing Agency

**`.env` customization:**
```env
COMPANY_NAME=Digital Growth Agency
COMPANY_TAGLINE=Expert Digital Marketing Solutions
COMPANY_DESCRIPTION=We help businesses grow online through strategic digital marketing
COMPANY_PHONE=+250 790 123 456
COMPANY_EMAIL=hello@digitalgrowth.rw
COMPANY_LOCATION=Kigali, Rwanda
COMPANY_WHATSAPP=+250790123456
```

**`config/landing.php` customization:**
```php
'services' => [
    'items' => [
        ['title' => 'Social Media Marketing', 'description' => 'Strategic social media campaigns...', 'icon_color' => 'blue'],
        ['title' => 'SEO Optimization', 'description' => 'Improve your search engine rankings...', 'icon_color' => 'emerald'],
        ['title' => 'Content Marketing', 'description' => 'Engaging content that converts...', 'icon_color' => 'purple'],
        ['title' => 'PPC Advertising', 'description' => 'Targeted paid campaigns...', 'icon_color' => 'red'],
        ['title' => 'Email Marketing', 'description' => 'Automated email campaigns...', 'icon_color' => 'orange'],
        ['title' => 'Analytics & Reporting', 'description' => 'Data-driven insights...', 'icon_color' => 'cyan'],
    ],
],
'testimonials' => [
    'items' => [
        [
            'quote' => 'Our online presence increased 300% in just 6 months. Highly recommended!',
            'author' => 'Jean Habimana',
            'role' => 'CEO',
            'company' => 'E-Commerce Rwanda',
            'rating' => 5,
        ],
        // ... more testimonials
    ],
],
```

---

## Example 2: Web Development Agency

**`.env` customization:**
```env
COMPANY_NAME=TechBuild Solutions
COMPANY_TAGLINE=Custom Web & Mobile Development
COMPANY_DESCRIPTION=We build stunning, high-performance web and mobile applications
COMPANY_PHONE=+250 788 456 789
COMPANY_EMAIL=info@techbuild.rw
COMPANY_LOCATION=Kigali Tech Hub
COMPANY_WHATSAPP=+250788456789
```

**`config/landing.php` customization:**
```php
'hero' => [
    'badge' => 'Custom Development',
    'main_heading' => 'Build Your Digital Future',
    'subheading' => 'We create custom web and mobile apps that drive your business forward.',
],
'services' => [
    'items' => [
        ['title' => 'Web Development', 'description' => 'Laravel, React, Vue.js...', 'icon_color' => 'blue'],
        ['title' => 'Mobile Apps', 'description' => 'iOS and Android development...', 'icon_color' => 'emerald'],
        ['title' => 'UI/UX Design', 'description' => 'Beautiful, user-focused design...', 'icon_color' => 'purple'],
        ['title' => 'Cloud Solutions', 'description' => 'AWS, Azure, GCP deployment...', 'icon_color' => 'red'],
        ['title' => 'API Development', 'description' => 'RESTful and GraphQL APIs...', 'icon_color' => 'orange'],
        ['title' => 'Maintenance & Support', 'description' => '24/7 technical support...', 'icon_color' => 'cyan'],
    ],
],
```

---

## Example 3: Real Estate Company

**`.env` customization:**
```env
COMPANY_NAME=Kigali Homes Real Estate
COMPANY_TAGLINE=Your Dream Property Awaits
COMPANY_DESCRIPTION=Premium residential and commercial properties in Rwanda
COMPANY_PHONE=+250 787 654 321
COMPANY_EMAIL=sales@kiglihomes.rw
COMPANY_LOCATION=Kigali, Rwanda
COMPANY_WHATSAPP=+250787654321
```

**`config/landing.php` customization:**
```php
'hero' => [
    'badge' => 'Property Solutions',
    'main_heading' => 'Find Your Perfect Home',
    'subheading' => 'Discover premium properties in the most desirable locations in Rwanda.',
],
'services' => [
    'items' => [
        ['title' => 'Residential Properties', 'description' => 'Modern apartments and houses...', 'icon_color' => 'emerald'],
        ['title' => 'Commercial Space', 'description' => 'Office and retail locations...', 'icon_color' => 'blue'],
        ['title' => 'Property Management', 'description' => 'Professional property care...', 'icon_color' => 'purple'],
        ['title' => 'Investment Opportunities', 'description' => 'Grow your wealth with real estate...', 'icon_color' => 'red'],
        ['title' => 'Rentals', 'description' => 'Flexible short and long-term rentals...', 'icon_color' => 'orange'],
        ['title' => 'Consulting', 'description' => 'Expert real estate advice...', 'icon_color' => 'cyan'],
    ],
],
'testimonials' => [
    'items' => [
        [
            'quote' => 'Found my perfect home in just two weeks. The team was professional and helpful!',
            'author' => 'Marie Uwamahoro',
            'role' => 'Homeowner',
            'company' => 'Nyarugenge, Kigali',
            'rating' => 5,
        ],
        // ... more testimonials
    ],
],
```

---

## Example 4: Fitness & Wellness Center

**`.env` customization:**
```env
COMPANY_NAME=FitLife Wellness Center
COMPANY_TAGLINE=Transform Your Body, Transform Your Life
COMPANY_DESCRIPTION=Premium gym and fitness services for all fitness levels
COMPANY_PHONE=+250 785 321 987
COMPANY_EMAIL=info@fitliferwanda.rw
COMPANY_LOCATION=Kimihurura, Kigali
COMPANY_WHATSAPP=+250785321987
```

**`config/landing.php` customization:**
```php
'colors' => [
    'primary' => 'orange',
    'accent' => 'red',
],
'services' => [
    'items' => [
        ['title' => 'Group Fitness Classes', 'description' => 'Yoga, Zumba, CrossFit, Pilates...', 'icon_color' => 'orange'],
        ['title' => 'Personal Training', 'description' => 'One-on-one coaching sessions...', 'icon_color' => 'red'],
        ['title' => 'Nutrition Coaching', 'description' => 'Customized meal planning...', 'icon_color' => 'blue'],
        ['title' => 'Gym Membership', 'description' => 'State-of-the-art equipment...', 'icon_color' => 'purple'],
        ['title' => 'Spa & Wellness', 'description' => 'Massage and relaxation services...', 'icon_color' => 'pink'],
        ['title' => 'Corporate Wellness', 'description' => 'Fitness programs for companies...', 'icon_color' => 'cyan'],
    ],
],
'process' => [
    'steps' => [
        [
            'number' => '1',
            'title' => 'Book a Consultation',
            'description' => 'Meet with our wellness experts to assess your goals.',
        ],
        [
            'number' => '2',
            'title' => 'Create Your Plan',
            'description' => 'Get a personalized fitness and nutrition plan.',
        ],
        [
            'number' => '3',
            'title' => 'Achieve Your Goals',
            'description' => 'Transform your body with our expert guidance.',
        ],
    ],
],
```

---

## Example 5: Consulting Firm

**`.env` customization:**
```env
COMPANY_NAME=Strategy Consulting Group
COMPANY_TAGLINE=Business Excellence Through Strategic Consulting
COMPANY_DESCRIPTION=Expert consulting services for business growth and transformation
COMPANY_PHONE=+250 782 111 222
COMPANY_EMAIL=contact@strategyconsulting.rw
COMPANY_LOCATION=Kigali Business District
COMPANY_WHATSAPP=+250782111222
```

**`config/landing.php` customization:**
```php
'colors' => [
    'primary' => 'blue',
    'accent' => 'cyan',
],
'services' => [
    'items' => [
        ['title' => 'Business Strategy', 'description' => 'Strategic planning and analysis...', 'icon_color' => 'blue'],
        ['title' => 'Operational Excellence', 'description' => 'Process optimization and improvement...', 'icon_color' => 'emerald'],
        ['title' => 'Financial Planning', 'description' => 'CFO advisory and financial planning...', 'icon_color' => 'cyan'],
        ['title' => 'Digital Transformation', 'description' => 'Transform your business operations...', 'icon_color' => 'purple'],
        ['title' => 'Leadership Coaching', 'description' => 'Executive coaching programs...', 'icon_color' => 'red'],
        ['title' => 'Market Research', 'description' => 'Competitive analysis and insights...', 'icon_color' => 'orange'],
    ],
],
'testimonials' => [
    'items' => [
        [
            'quote' => 'Their strategic insights increased our revenue by 45% in one year. Outstanding results!',
            'author' => 'Vincent Rwigema',
            'role' => 'CEO',
            'company' => 'Rwanda Industries Ltd',
            'rating' => 5,
        ],
        // ... more testimonials
    ],
],
```

---

## Example 6: Restaurant/Cafe

**`.env` customization:**
```env
COMPANY_NAME=Brew & Bites Cafe
COMPANY_TAGLINE=Specialty Coffee & Delicious Food
COMPANY_DESCRIPTION=Premium cafe experience with locally sourced ingredients
COMPANY_PHONE=+250 789 555 666
COMPANY_EMAIL=hello@brewandbites.rw
COMPANY_LOCATION=Kacyiru, Kigali
COMPANY_WHATSAPP=+250789555666
```

**`config/landing.php` customization:**
```php
'colors' => [
    'primary' => 'orange',
    'accent' => 'emerald',
],
'services' => [
    'items' => [
        ['title' => 'Specialty Coffee', 'description' => 'Single-origin, artisanal coffee...', 'icon_color' => 'orange'],
        ['title' => 'Breakfast Menu', 'description' => 'Fresh pastries and breakfast items...', 'icon_color' => 'red'],
        ['title' => 'Lunch Specials', 'description' => 'Daily lunch combinations...', 'icon_color' => 'emerald'],
        ['title' => 'Desserts', 'description' => 'Homemade cakes and pastries...', 'icon_color' => 'pink'],
        ['title' => 'Catering Services', 'description' => 'Events and corporate catering...', 'icon_color' => 'purple'],
        ['title' => 'Private Events', 'description' => 'Host your celebration with us...', 'icon_color' => 'cyan'],
    ],
],
```

---

## How to Use These Examples

1. Copy the relevant `.env` customization for your business type
2. Copy the corresponding `config/landing.php` customization
3. Paste into your files
4. Run: `php artisan config:cache`
5. Visit your landing page

That's it! Your landing page is now customized for your business. 🎉

---

## Tips for Best Results

✅ **Keep service titles short** (2-4 words)  
✅ **Write descriptions benefit-focused** (not feature-focused)  
✅ **Use realistic testimonials** (or template ones if just launching)  
✅ **Choose colors that match your brand**  
✅ **Use your actual contact information**  
✅ **Test on mobile** before deploying  

---

For more details, see [TEMPLATE_SETUP.md](TEMPLATE_SETUP.md)
