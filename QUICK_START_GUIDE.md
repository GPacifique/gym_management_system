# Quick Start Guide - Class Booking & Trainer Showcase

## 🚀 Quick Start (5 Minutes)

### Step 1: Database Setup
```bash
# Run migrations (if not already done)
php artisan migrate

# Seed trainers with sample data
php artisan db:seed --class=TrainerSeeder
```

### Step 2: Create Test Data
```bash
# Start server (if not running)
php artisan serve

# Create a test class (use Tinker)
php artisan tinker
```

In Tinker:
```php
$trainer = App\Models\Trainer::first();
$gym = App\Models\Gym::first();

App\Models\GymClass::create([
    'class_name' => 'Morning Yoga Session',
    'trainer_id' => $trainer->id,
    'gym_id' => $gym->id,
    'scheduled_at' => now()->addDays(2)->setTime(9, 0),
    'duration_minutes' => 60,
    'capacity' => 20,
    'location' => 'Yoga Studio',
    'description' => 'Relaxing morning yoga for all levels.'
]);
```

### Step 3: Test Features

#### A. View Trainers on Welcome Page
1. Open browser: `http://127.0.0.1:8000`
2. Scroll down to "Meet Our Professional Trainers" section
3. You should see 6 trainer cards with photos, names, specializations, and gym names

#### B. Book a Class
1. Log in to your account
2. Click "Book Classes" in the sidebar (or go to `/bookings`)
3. You'll see available future classes
4. Click "Book Now" on any class
5. Select a member from dropdown
6. Click "Confirm Booking"
7. Booking created! ✅

#### C. View Your Bookings
1. Click "My Bookings" in sidebar (or go to `/bookings/my-bookings`)
2. See your upcoming bookings in the first section
3. Past bookings appear in the second section

#### D. Cancel a Booking
1. Go to "My Bookings"
2. Find the booking you want to cancel
3. Click "Cancel Booking"
4. Enter a cancellation reason (min 3 characters)
5. Click "Yes, Cancel Booking"
6. Booking cancelled! ✅

---

## 🗺️ Navigation Map

```
├── / (Welcome Page)
│   └── Trainers Section (6 trainers with gym info)
│
├── /bookings (Browse Classes)
│   ├── View available classes
│   ├── See capacity status
│   └── Click "Book Now" → /bookings/classes/{class}/book
│
├── /bookings/classes/{class}/book (Booking Form)
│   ├── View class details
│   ├── Select member
│   └── Submit booking → /bookings/{booking}
│
├── /bookings/{booking} (Booking Details)
│   ├── View full booking info
│   └── Cancel booking (if eligible)
│
└── /bookings/my-bookings (My Bookings Dashboard)
    ├── Upcoming Bookings
    │   ├── View details
    │   └── Cancel booking
    └── Past Bookings
        └── View history
```

---

## 🎯 Key URLs

| Feature | URL | Access |
|---------|-----|--------|
| Welcome Page | `/` | Public |
| Browse Classes | `/bookings` | Authenticated |
| My Bookings | `/bookings/my-bookings` | Authenticated |
| Book Class | `/bookings/classes/{id}/book` | Authenticated |
| Booking Details | `/bookings/{id}` | Authenticated |

---

## 📋 Quick Reference

### Booking Statuses
- 🟢 **confirmed** - Active booking
- 🔴 **cancelled** - Cancelled by member
- ✅ **attended** - Member attended class
- ⚠️ **no_show** - Member didn't attend

### Capacity Indicators
- 🟢 **Green** (0-60%) - Plenty of spots
- 🟡 **Yellow** (61-90%) - Filling up
- 🔴 **Red** (91-100%) - Almost/completely full

### Sidebar Links (When Logged In)
- 📅 **Book Classes** - Browse and book available classes
- 📖 **My Bookings** - View and manage your bookings

---

## 🎨 UI Components Guide

### Class Card (Browse Page)
```
┌─────────────────────────────────┐
│ Morning HIIT Training           │
│ [Class Name]                    │
├─────────────────────────────────┤
│ 👤 Trainer: Mike Johnson        │
│ 📅 Date: Jan 15, 2025           │
│ 🕐 Time: 8:00 AM                │
│ 📍 Location: Main Studio        │
├─────────────────────────────────┤
│ [████████░░] 8/15 Booked        │
│ 7 spots available               │
├─────────────────────────────────┤
│        [Book Now Button]        │
└─────────────────────────────────┘
```

### Trainer Card (Welcome Page)
```
┌─────────────────────────────────┐
│        ╭─────────╮              │
│        │  Photo  │              │
│        ╰─────────╯              │
│                                 │
│    Mike "The Beast" Johnson     │
│    [CrossFit & HIIT]           │
│                                 │
│    🏢 Downtown Fitness Center   │
│                                 │
│    Former Marine, CrossFit...   │
│    (Bio excerpt)                │
│                                 │
│        [Contact Button]         │
└─────────────────────────────────┘
```

---

## 🔧 Common Tasks

### Add More Sample Classes
```php
// In Tinker or Seeder
$trainers = App\Models\Trainer::all();
$gym = App\Models\Gym::first();

$classes = [
    [
        'class_name' => 'Evening Yoga',
        'scheduled_at' => now()->addDays(1)->setTime(18, 0),
        'duration_minutes' => 60,
        'capacity' => 15,
        'location' => 'Yoga Studio',
    ],
    [
        'class_name' => 'Spin Class',
        'scheduled_at' => now()->addDays(2)->setTime(7, 0),
        'duration_minutes' => 45,
        'capacity' => 20,
        'location' => 'Cycling Room',
    ],
    // Add more...
];

foreach ($classes as $classData) {
    $classData['trainer_id'] = $trainers->random()->id;
    $classData['gym_id'] = $gym->id;
    $classData['description'] = 'Great workout session!';
    App\Models\GymClass::create($classData);
}
```

### Update Trainer with Photo
```php
// Upload photo to storage/app/public/trainers/
// Then update trainer
$trainer = App\Models\Trainer::find(1);
$trainer->photo_path = 'trainers/john-smith.jpg';
$trainer->save();
```

### Check Booking Statistics
```php
// Total bookings
App\Models\ClassBooking::count();

// Confirmed bookings
App\Models\ClassBooking::where('status', 'confirmed')->count();

// Bookings for specific class
$class = App\Models\GymClass::find(1);
$class->confirmedBookings()->count();

// Bookings by member
$member = App\Models\Member::find(1);
$member->classBookings()->count();
```

---

## 🐛 Troubleshooting

### Issue: No classes showing on booking page
**Solution**: Create future classes (scheduled_at > now())

### Issue: Can't book a class
**Possible causes**:
- Class is full (check capacity)
- Class is in the past
- Already booked this class
- Not logged in

### Issue: Trainers not showing on welcome page
**Solution**: Run `php artisan db:seed --class=TrainerSeeder`

### Issue: Photo not displaying
**Possible causes**:
- Photo path incorrect
- Storage link not created: `php artisan storage:link`
- File doesn't exist in storage/app/public/

### Issue: Can't cancel booking
**Possible causes**:
- Booking already cancelled
- Class already happened (in the past)
- Not your booking (authorization)

---

## 📊 Sample Test Scenario

```
1. ✅ Visit welcome page → See 6 trainers with gym names
2. ✅ Login as admin/member
3. ✅ Go to "Book Classes" → See available classes
4. ✅ Click "Book Now" on a class
5. ✅ Select member → Submit form
6. ✅ Redirect to booking details
7. ✅ See booking confirmation
8. ✅ Go to "My Bookings" → See new booking in upcoming
9. ✅ Try to book same class again → Error: duplicate booking
10. ✅ Cancel the booking with reason
11. ✅ Booking moves to past section with "cancelled" status
```

---

## 📞 Quick Commands Reference

```bash
# Create new class
php artisan tinker --execute="App\Models\GymClass::create([...])"

# Seed trainers
php artisan db:seed --class=TrainerSeeder

# Check bookings
php artisan tinker --execute="App\Models\ClassBooking::with('gymClass', 'member')->get()"

# Clear cancelled bookings
php artisan tinker --execute="App\Models\ClassBooking::where('status', 'cancelled')->delete()"

# View class capacity
php artisan tinker --execute="\$class = App\Models\GymClass::find(1); echo \$class->available_spots . ' spots left';"
```

---

**Need Help?** Check `BOOKING_FEATURE_SUMMARY.md` for detailed documentation.

**Ready to Deploy?** All features are production-ready! 🚀
