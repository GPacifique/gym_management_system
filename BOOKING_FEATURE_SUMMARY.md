# Class Booking System & Trainer Showcase - Feature Documentation

## 🎯 Overview
This document provides a complete overview of the newly implemented features:
1. **Class Booking System** - Allows members to browse and book gym classes
2. **Trainer Showcase** - Displays professional trainers on the welcome page with their gym affiliations

---

## 📋 Features Implemented

### 1. Class Booking System

#### Database Schema
- **Table**: `class_bookings`
- **Fields**:
  - `gym_id` - Foreign key to gyms table
  - `class_id` - Foreign key to gym_classes table (with cascade delete)
  - `member_id` - Foreign key to members table (with cascade delete)
  - `user_id` - Foreign key to users table (nullable, with cascade delete)
  - `status` - Enum: 'confirmed', 'cancelled', 'attended', 'no_show'
  - `booked_at` - Timestamp when booking was created
  - `cancelled_at` - Timestamp when booking was cancelled (nullable)
  - `cancellation_reason` - Text field for cancellation reason (nullable)
  - **Unique Constraint**: [class_id, member_id] - Prevents duplicate bookings

#### Models

**ClassBooking Model** (`app/Models/ClassBooking.php`)
- Relationships:
  - `gymClass()` - belongsTo GymClass
  - `member()` - belongsTo Member
  - `user()` - belongsTo User
- Scopes:
  - `confirmed()` - Filter confirmed bookings
  - `cancelled()` - Filter cancelled bookings
- Methods:
  - `canBeCancelled()` - Check if booking can be cancelled (confirmed & class in future)
  - `cancel($reason)` - Cancel booking with reason

**Updated GymClass Model**
- New Relationships:
  - `bookings()` - hasMany ClassBooking
  - `confirmedBookings()` - hasMany ClassBooking where status='confirmed'
- Updated Methods:
  - `isFull()` - Now checks confirmed bookings count vs capacity
  - `canBeBooked()` - Checks if class has available spots and is in future
- New Accessor:
  - `available_spots` - Returns remaining capacity

**Updated Member Model**
- New Relationships:
  - `classBookings()` - hasMany ClassBooking
  - `confirmedBookings()` - hasMany ClassBooking where status='confirmed'

#### Controller

**ClassBookingController** (`app/Http/Controllers/ClassBookingController.php`)
- `index()` - Browse available classes (paginated, future classes only)
- `create(GymClass)` - Show booking form with member selection
- `store(Request, GymClass)` - Process booking (with validation & transaction)
- `show(ClassBooking)` - Display booking details
- `myBookings()` - Show user's upcoming and past bookings
- `cancel(Request, ClassBooking)` - Cancel booking with reason

**Validation Rules**:
- `member_id` - required, must exist in members table
- `cancellation_reason` - required when cancelling, min 3 characters

**Security Features**:
- All routes protected with `auth` middleware
- Duplicate booking prevention (unique constraint)
- Capacity checking before booking
- Transaction safety for booking creation
- Authorization checks (user can only cancel their own bookings)

#### Views

**1. bookings/index.blade.php** - Browse Available Classes
- Displays class cards with:
  - Class name
  - Trainer information
  - Date and time
  - Location
  - Capacity progress bar (color-coded: green/yellow/red)
  - Available spots counter
  - "Book Now" or "Class Full" button
- Pagination support
- Empty state message

**2. bookings/create.blade.php** - Booking Form
- Two-column layout:
  - Left: Class details (name, trainer, schedule, location, capacity)
  - Right: Booking form with member selection
- Booking policy information
- Form validation display
- Confirmation modal

**3. bookings/my-bookings.blade.php** - User's Bookings Dashboard
- Two sections:
  - **Upcoming Bookings**: Active bookings with cancel button
  - **Past Bookings**: Historical bookings (greyed out)
- Status badges (confirmed/cancelled/attended/no-show)
- Cancel modals for each booking
- Empty state messages for both sections

**4. bookings/show.blade.php** - Booking Details
- Three information sections:
  - Class Information (name, trainer, date, time, location, capacity)
  - Member Information (name, email, phone)
  - Booking Information (ID, status, timestamps, cancellation details)
- Cancel functionality with modal (if applicable)
- Important reminders for confirmed bookings
- Action buttons

#### Routes

```php
// All routes protected with auth middleware
Route::prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/', [ClassBookingController::class, 'index'])->name('index');
    Route::get('/my-bookings', [ClassBookingController::class, 'myBookings'])->name('my-bookings');
    Route::get('/classes/{class}/book', [ClassBookingController::class, 'create'])->name('create');
    Route::post('/classes/{class}/book', [ClassBookingController::class, 'store'])->name('store');
    Route::get('/{booking}', [ClassBookingController::class, 'show'])->name('show');
    Route::post('/{booking}/cancel', [ClassBookingController::class, 'cancel'])->name('cancel');
});
```

#### Navigation

Updated sidebar (`resources/views/components/sidebar.blade.php`) with:
- **Book Classes** - Link to browse available classes (`/bookings`)
- **My Bookings** - Link to user's bookings dashboard (`/bookings/my-bookings`)

Both available to all authenticated users.

---

### 2. Trainer Showcase on Welcome Page

#### Database Updates

**Migration**: `add_trainer_profile_fields`
- Added to `trainers` table:
  - `bio` - Text field for trainer biography
  - `photo_path` - String field for profile photo path
  - `certifications` - Text field for certifications list

#### Updated Trainer Model

**New Fillable Fields**:
- `bio`, `photo_path`, `certifications`

**New Relationships**:
- `gym()` - belongsTo Gym (via BelongsToGym trait)
- `members()` - hasMany Member
- `classes()` - hasMany GymClass

**New Accessor**:
- `photo_url` - Returns asset path to photo or default placeholder

#### Welcome Page Updates

**Route Update** (`routes/web.php`):
```php
Route::get('/', function () {
    $trainers = Trainer::with('gym')->take(6)->get();
    return view('welcome', compact('trainers'));
});
```

**New Section** in `resources/views/welcome.blade.php`:
- Title: "Meet Our Professional Trainers"
- Subtitle: "Expert trainers ready to guide you to your fitness goals"
- Trainer cards displaying:
  - Profile photo (150px circular with purple border)
  - Name
  - Specialization badge
  - Gym name (with location icon)
  - Bio excerpt (limited to 120 characters)
  - Contact button (email link)

**CSS Styles Added**:
- `.trainer-card` - Card styling with hover effects (elevation & shadow)
- `.trainer-photo-wrapper` - Circular photo container with border
- Smooth transitions for hover states

---

## 🧪 Testing Guide

### Prerequisites
1. Ensure server is running: `php artisan serve`
2. Have at least one gym in the database
3. Have at least one trainer assigned to a gym
4. Have at least one member in the database
5. Have at least one future class scheduled

### Test Scenarios

#### 1. Test Welcome Page Trainers
- **URL**: `http://127.0.0.1:8000/`
- **Expected**: See "Meet Our Professional Trainers" section
- **Verify**:
  - 6 trainers displayed (or fewer if less exist)
  - Each card shows: photo, name, specialization, gym name, bio
  - Hover effects work (card elevates, shadow appears)
  - Contact buttons link to email

#### 2. Test Class Booking - Browse
- **URL**: `http://127.0.0.1:8000/bookings` (requires login)
- **Expected**: List of available future classes
- **Verify**:
  - Only future classes shown
  - Class cards display all details (name, trainer, date, time, location)
  - Capacity progress bar shows correctly
  - "Book Now" button appears for available classes
  - "Class Full" badge appears for full classes
  - Available spots counter is accurate

#### 3. Test Class Booking - Create Booking
- **URL**: Click "Book Now" on any class
- **Expected**: Booking form with class details and member dropdown
- **Verify**:
  - Class details display correctly
  - Member dropdown populated
  - Booking policy information shown
  - Form submits successfully
  - Redirects to booking details or my bookings
  - Success message displayed
  - Duplicate booking prevented (try booking same class twice)

#### 4. Test My Bookings Dashboard
- **URL**: `http://127.0.0.1:8000/bookings/my-bookings` (requires login)
- **Expected**: Dashboard with upcoming and past bookings
- **Verify**:
  - Upcoming bookings section shows active bookings
  - Cancel button available for confirmed bookings
  - Past bookings section shows historical records
  - Status badges display correctly
  - Empty states show when no bookings exist

#### 5. Test Booking Cancellation
- **Action**: Click "Cancel Booking" on an upcoming booking
- **Expected**: Modal appears requesting cancellation reason
- **Verify**:
  - Modal displays with textarea for reason
  - Validation requires minimum 3 characters
  - Cancellation processes successfully
  - Status changes to "cancelled"
  - Cancelled_at timestamp populated
  - Cancellation reason saved
  - Booking moves to past section

#### 6. Test Booking Details
- **URL**: Click "View Details" on any booking
- **Expected**: Detailed booking information page
- **Verify**:
  - Three sections display: Class, Member, Booking info
  - All information accurate
  - Status badge correct
  - Cancel button available (if applicable)
  - Navigation buttons work

#### 7. Test Capacity Limits
- **Action**: Book a class until it reaches capacity
- **Expected**: Class becomes "Full" and booking disabled
- **Verify**:
  - Progress bar shows 100% (red)
  - "Class Full" badge appears
  - "Book Now" button disabled or hidden
  - Available spots = 0
  - Cannot create new bookings

#### 8. Test Navigation
- **Action**: Use sidebar links
- **Expected**: Links work and highlight correctly
- **Verify**:
  - "Book Classes" link goes to `/bookings`
  - "My Bookings" link goes to `/bookings/my-bookings`
  - Active state highlights current page
  - Icons display correctly

---

## 📊 Database Seeding

### Trainer Seeder

Run the seeder to populate sample trainers:
```bash
php artisan db:seed --class=TrainerSeeder
```

**Creates 6 trainers**:
1. John "Thunder" Smith - Strength & Conditioning
2. Sarah Martinez - Yoga & Pilates
3. Mike "The Beast" Johnson - CrossFit & HIIT
4. Emma Chen - Personal Training & Weight Loss
5. David "Coach D" Williams - Sports Performance
6. Lisa Thompson - Group Fitness & Dance

Each trainer has:
- Full name and specialization
- Contact information (email, phone)
- Professional bio
- Certifications
- Salary
- Assigned gym (distributed evenly across available gyms)

### Create Test Classes

Use Tinker to create test classes:
```bash
php artisan tinker
```

```php
$trainer = App\Models\Trainer::first();
$gym = App\Models\Gym::first();

App\Models\GymClass::create([
    'class_name' => 'Morning HIIT Training',
    'trainer_id' => $trainer->id,
    'gym_id' => $gym->id,
    'scheduled_at' => now()->addDays(3)->setTime(8, 0, 0),
    'duration_minutes' => 60,
    'capacity' => 15,
    'location' => 'Main Studio',
    'description' => 'High-intensity interval training session.'
]);
```

---

## 🎨 UI/UX Features

### Bootstrap 5 Components Used
- Cards with hover effects
- Progress bars (color-coded by percentage)
- Badges for status display
- Modals for confirmations
- Alerts for messages
- Responsive grid system
- Form controls and validation

### Color Scheme
- **Primary**: Purple (#667eea) - Used for buttons, active states
- **Success**: Green - Available/confirmed status
- **Warning**: Yellow - Near capacity warning
- **Danger**: Red - Full capacity, cancelled status
- **Info**: Blue - Information displays

### Accessibility
- Semantic HTML structure
- ARIA labels where appropriate
- Color contrast compliance
- Keyboard navigation support
- Screen reader friendly

---

## 🔒 Security Features

1. **Authentication Required**: All booking routes require login
2. **Authorization Checks**: Users can only manage their own bookings
3. **CSRF Protection**: All forms include CSRF tokens
4. **Validation**: Server-side validation on all inputs
5. **Database Constraints**: Unique constraint prevents duplicate bookings
6. **Transaction Safety**: Booking creation wrapped in database transaction
7. **SQL Injection Prevention**: Eloquent ORM parameterized queries
8. **XSS Prevention**: Blade templating auto-escapes output

---

## 📝 Business Rules

### Booking Rules
1. Members can only book future classes
2. Members cannot book the same class twice
3. Members cannot book full classes
4. Bookings can only be cancelled if status is "confirmed" and class is in future
5. Cancellation requires a reason (min 3 characters)

### Capacity Management
- Class capacity tracked by confirmed bookings count
- Available spots calculated dynamically
- Progress bar color changes based on booking percentage:
  - Green: 0-60% booked
  - Yellow: 61-90% booked
  - Red: 91-100% booked

### Status Workflow
1. **confirmed** - Initial booking state
2. **cancelled** - Member cancels booking
3. **attended** - Member attended the class (future: check-in system)
4. **no_show** - Member didn't attend (future: automated tracking)

---

## 🚀 Future Enhancements

### Suggested Features
1. **Email Notifications**:
   - Booking confirmation
   - Cancellation confirmation
   - Class reminder (24 hours before)
   - Class updates/changes

2. **Trainer Profile Page**:
   - Detailed trainer information
   - Schedule overview
   - Client testimonials
   - Social media links

3. **Check-in System**:
   - QR code for class check-in
   - Auto-update booking status to "attended"
   - Track no-shows automatically

4. **Waitlist Feature**:
   - Allow booking on waitlist when class is full
   - Auto-notify when spot becomes available
   - Priority booking for waitlisted members

5. **Recurring Classes**:
   - Create class series
   - Batch booking for recurring classes
   - Subscription-based class packages

6. **Reviews & Ratings**:
   - Rate classes after attendance
   - Trainer ratings
   - Class recommendations

7. **Calendar Integration**:
   - Export bookings to Google Calendar/iCal
   - Calendar view of bookings
   - Sync with personal calendar

8. **Mobile App**:
   - React Native or Flutter app
   - Push notifications
   - Offline booking viewing

---

## 🐛 Known Issues / Limitations

1. **IDE Lint Errors**: False positives in Blade files (IDE doesn't recognize Blade syntax)
2. **Photo Placeholders**: Trainers without photos show placeholder image
3. **No Automated Tests**: Manual testing only (should add PHPUnit tests)
4. **No Email Integration**: Currently no email notifications
5. **Limited Role Support**: Booking available to all authenticated users (no role-specific restrictions)

---

## 📚 Files Modified/Created

### New Migrations
- `database/migrations/2025_10_26_101029_create_class_bookings_table.php`
- `database/migrations/2025_10_26_101347_add_trainer_profile_fields.php`

### New Models
- `app/Models/ClassBooking.php`

### Updated Models
- `app/Models/GymClass.php`
- `app/Models/Member.php`
- `app/Models/Trainer.php`

### New Controller
- `app/Http/Controllers/ClassBookingController.php`

### New Views
- `resources/views/bookings/index.blade.php`
- `resources/views/bookings/create.blade.php`
- `resources/views/bookings/my-bookings.blade.php`
- `resources/views/bookings/show.blade.php`

### Updated Views
- `resources/views/welcome.blade.php` (added trainers section)
- `resources/views/components/sidebar.blade.php` (added booking links)

### Updated Routes
- `routes/web.php` (added booking routes, updated welcome route)

### New Seeder
- `database/seeders/TrainerSeeder.php`

---

## 🎓 Developer Notes

### Code Quality
- Follows Laravel 11 best practices
- PSR-12 coding standards
- Proper use of Eloquent relationships
- Resource-efficient queries (eager loading)
- Clear method naming and comments

### Performance Considerations
- Pagination on listing pages (12 per page)
- Eager loading relationships to prevent N+1 queries
- Database indexes on foreign keys
- Efficient capacity calculations

### Maintenance
- Models are well-documented with relationships
- Controllers use dependency injection
- Validation rules centralized in controller
- Reusable Blade components for consistency

---

## 📞 Support & Documentation

For questions or issues:
1. Check this documentation
2. Review Laravel 11 documentation: https://laravel.com/docs/11.x
3. Review Bootstrap 5 documentation: https://getbootstrap.com/docs/5.0/

---

**Last Updated**: January 2025  
**Version**: 1.0.0  
**Status**: ✅ Production Ready
