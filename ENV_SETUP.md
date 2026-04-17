# 📋 .env Setup Checklist

Use this when setting up your `.env` file to avoid errors.

## Golden Rule

> **If your value contains spaces or special characters, WRAP IT IN QUOTES!**

---

## Quick Setup

Copy this template and fill in YOUR information:

```env
APP_NAME=YourCompanyName
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# ===== LANDING PAGE CUSTOMIZATION =====
COMPANY_NAME="Your Company Name Here"
COMPANY_TAGLINE="Your company tagline with & symbols if needed"
COMPANY_DESCRIPTION="What your company does in a sentence or two"
COMPANY_PHONE="+250 700 000 000"
COMPANY_EMAIL=yourcompany@example.com
COMPANY_LOCATION="Kigali, Rwanda"
COMPANY_HOURS="Monday - Friday: 9:00 AM - 5:00 PM"
COMPANY_WHATSAPP=+250700000000

# ... rest of .env file
```

---

## Checklist

### Field Format Examples

- [ ] **COMPANY_NAME** - `"Your Business Name"` ✅
  - Example: `"Alice's Boutique"` (has apostrophe)
  - Use quotes: YES

- [ ] **COMPANY_TAGLINE** - `"Tagline with & symbols"` ✅
  - Example: `"Quality Service & Great Prices"`
  - Use quotes: YES (has & symbol)

- [ ] **COMPANY_DESCRIPTION** - `"Multiple words here"` ✅
  - Example: `"We provide IT solutions for growing businesses"`
  - Use quotes: YES (has spaces)

- [ ] **COMPANY_PHONE** - `"+250 700 000 000"` ✅
  - Example: `"+250 786 163 963"`
  - Use quotes: YES (has spaces/hyphens)

- [ ] **COMPANY_EMAIL** - `yourcompany@example.com` (no quotes needed)
  - Example: `hello@mycompany.rw`
  - Use quotes: NO (no spaces)

- [ ] **COMPANY_LOCATION** - `"City, Country"` ✅
  - Example: `"Kigali, Rwanda"`
  - Use quotes: YES (has comma and spaces)

- [ ] **COMPANY_HOURS** - `"Monday - Friday: 9:00 AM - 5:00 PM"` ✅
  - Use quotes: YES (has multiple spaces, hyphens, colons)

- [ ] **COMPANY_WHATSAPP** - `+250700000000` (no quotes needed)
  - Example: `+250786163963`
  - Use quotes: NO (no spaces)

---

## Common Mistakes

### ❌ Wrong Format (Will Cause Errors)

```env
COMPANY_TAGLINE=Smart Income & Expense Management
COMPANY_LOCATION=Kigali, Rwanda
COMPANY_HOURS=Monday - Friday: 9:00 AM - 5:00 PM
```

**Why this fails:**
- `&` symbol breaks parsing
- Comma breaks parsing
- Spaces confuse the parser

---

### ✅ Correct Format (Will Work)

```env
COMPANY_TAGLINE="Smart Income & Expense Management"
COMPANY_LOCATION="Kigali, Rwanda"
COMPANY_HOURS="Monday - Friday: 9:00 AM - 5:00 PM"
```

**Why this works:**
- Quotes protect special characters
- Parser treats content as single value
- All spaces and symbols preserved

---

## Character Rules

### Always Quote These:

| Character | Example | Quote? |
|-----------|---------|--------|
| `&` | "Fish & Chips" | ✅ YES |
| `@` | "Contact@company" | ✅ YES |
| `-` | "Mon - Fri" | ✅ YES |
| `:` | "9:00 AM" | ✅ YES |
| `,` | "Kigali, Rwanda" | ✅ YES |
| Spaces | "Multiple words" | ✅ YES |
| `/` | "Dir/SubDir" | ✅ YES |
| `!` | "Great!" | ✅ YES |
| `#` | "Category #1" | ✅ YES |
| `'` | "John's Shop" | ✅ YES |

### Don't Need Quotes:

| Content | Example | Quote? |
|---------|---------|--------|
| Single word | BusinessLedger | ❌ NO |
| Email only | hello@company.rw | ❌ NO |
| Phone no spaces | +250786163963 | ❌ NO |
| URL | https://example.com | ❌ NO |

---

## Step-by-Step

1. **Open `.env` file** in your editor
2. **Find each COMPANY_ variable**
3. **Ask:** "Does this value have spaces or special chars?"
   - YES → Add quotes: `"value here"`
   - NO → Leave as is: `value`
4. **Save file**
5. **Run:** `php artisan config:cache`
6. **Start server:** `php artisan serve`
7. **Visit:** `http://localhost:8000`

---

## Testing Your Setup

After editing `.env`, run:

```bash
# Clear config cache
php artisan config:cache

# Check if config loads
php artisan config:show landing

# Start server
php artisan serve

# Visit page
# http://localhost:8000
```

If you see your company info on the landing page → ✅ SUCCESS!

---

## If You Get An Error

### Error Message: "The environment file is invalid"

**Fix:**
1. Check `.env` file for unquoted values with:
   - Spaces
   - `&`, `@`, `-`, `:`, `,`, `/`, `!`, `#`, `'` characters
2. Add quotes around those values
3. Save file
4. Run: `php artisan config:cache`
5. Restart server

---

## Example Setups

### Digital Agency
```env
COMPANY_NAME="Digital Growth Agency"
COMPANY_TAGLINE="Web Design & Digital Marketing Services"
COMPANY_LOCATION="Kigali, Rwanda"
COMPANY_HOURS="Monday - Friday: 9:00 AM - 6:00 PM"
```

### Coffee Shop
```env
COMPANY_NAME="Brew & Bites Cafe"
COMPANY_TAGLINE="Quality Coffee & Fresh Pastries"
COMPANY_LOCATION="Downtown Kigali, Rwanda"
COMPANY_HOURS="Daily: 7:00 AM - 9:00 PM"
```

### Real Estate
```env
COMPANY_NAME="Kigali Homes & Properties"
COMPANY_TAGLINE="Find Your Dream Home in Rwanda"
COMPANY_LOCATION="Business District, Kigali, Rwanda"
COMPANY_HOURS="Monday - Saturday: 8:00 AM - 6:00 PM"
```

---

## Remember

✅ **Spaces?** → Quote it  
✅ **Special chars?** → Quote it  
✅ **& symbol?** → Quote it  
✅ **Comma?** → Quote it  
✅ **When in doubt?** → Quote it!

---

**Pro Tip:** If it's a sentence or has more than one word, just quote it. Better safe than sorry! 😊
