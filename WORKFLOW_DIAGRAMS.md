# Class Booking System - Workflow Diagrams

## 📊 System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      GYM MANAGEMENT SYSTEM                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐    │
│  │   WELCOME    │    │   BOOKING    │    │     USER     │    │
│  │     PAGE     │    │    SYSTEM    │    │  DASHBOARD   │    │
│  ├──────────────┤    ├──────────────┤    ├──────────────┤    │
│  │              │    │              │    │              │    │
│  │  Trainers    │    │  Browse      │    │  My          │    │
│  │  Showcase    │    │  Classes     │    │  Bookings    │    │
│  │              │    │              │    │              │    │
│  │  - Photos    │    │  - Filter    │    │  - Upcoming  │    │
│  │  - Bios      │    │  - Capacity  │    │  - Past      │    │
│  │  - Gyms      │    │  - Book      │    │  - Cancel    │    │
│  │              │    │              │    │              │    │
│  └──────────────┘    └──────────────┘    └──────────────┘    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Booking Flow Diagram

```
START
  │
  ├─> [Visit Welcome Page]
  │     │
  │     └─> View Trainers & Gyms
  │
  ├─> [Login/Register]
  │     │
  │     └─> Authentication Required
  │
  ├─> [Browse Classes]
  │     │
  │     ├─> Filter Future Classes
  │     ├─> Check Capacity
  │     └─> View Details
  │           │
  │           ├─> Class Full? 
  │           │     └─> YES: Show "Class Full" Badge
  │           │     └─> NO:  Show "Book Now" Button
  │           │
  │           └─> Click "Book Now"
  │
  ├─> [Booking Form]
  │     │
  │     ├─> View Class Details
  │     ├─> Select Member
  │     ├─> Read Booking Policy
  │     └─> Submit Form
  │           │
  │           ├─> Validation
  │           │     ├─> Member Selected?
  │           │     ├─> Class Available?
  │           │     └─> Not Duplicate?
  │           │
  │           ├─> FAIL: Show Errors
  │           │
  │           └─> SUCCESS:
  │                 ├─> Create Booking
  │                 ├─> Set Status: "confirmed"
  │                 ├─> Decrease Available Spots
  │                 └─> Redirect to Details
  │
  ├─> [Booking Details]
  │     │
  │     ├─> View Full Info
  │     ├─> Class Details
  │     ├─> Member Details
  │     └─> Booking Info
  │           │
  │           └─> Can Cancel?
  │                 ├─> Status = "confirmed"?
  │                 └─> Class in Future?
  │                       │
  │                       ├─> YES: Show Cancel Button
  │                       └─> NO:  Hide Cancel Button
  │
  ├─> [My Bookings Dashboard]
  │     │
  │     ├─> Upcoming Bookings Section
  │     │     ├─> Status: "confirmed"
  │     │     ├─> Class Date >= Today
  │     │     └─> Cancel Available
  │     │
  │     └─> Past Bookings Section
  │           ├─> Status: "cancelled", "attended", "no_show"
  │           ├─> Class Date < Today
  │           └─> View Only (No Actions)
  │
  └─> [Cancel Booking]
        │
        ├─> Enter Cancellation Reason
        ├─> Minimum 3 Characters
        └─> Confirm Cancellation
              │
              ├─> Update Status: "cancelled"
              ├─> Set Cancelled_At Timestamp
              ├─> Save Cancellation Reason
              ├─> Increase Available Spots
              └─> Move to Past Bookings
                    │
                    └─> END
```

---

## 🗄️ Database Schema Relationships

```
┌──────────────┐         ┌──────────────┐         ┌──────────────┐
│     GYMS     │────────<│   TRAINERS   │>───────<│   CLASSES    │
└──────────────┘         └──────────────┘         └──────────────┘
       │                        │                         │
       │                        │                         │
       │                        │                         │
       ↓                        ↓                         ↓
┌──────────────┐         ┌──────────────┐         ┌──────────────┐
│   MEMBERS    │         │    USERS     │         │   BOOKINGS   │
└──────────────┘         └──────────────┘         └──────────────┘
       │                        │                         │
       │                        │                         │
       └────────────────────────┴─────────────────────────┘
                                │
                                │
                        (All connected via 
                         foreign keys)
```

### Detailed Relationships

```
GYM (1) ──── (Many) TRAINERS
  │                    │
  │                    └──> name, specialization, bio, photo_path
  │                         certifications, phone, email
  │
  └──── (Many) CLASSES
  │                    │
  │                    └──> class_name, scheduled_at, duration,
  │                         capacity, location, description
  │
  └──── (Many) MEMBERS
  │                    │
  │                    └──> name, email, phone, address,
  │                         membership details
  │
  └──── (Many) BOOKINGS
                       │
                       └──> status (confirmed/cancelled/attended/no_show)
                            booked_at, cancelled_at, cancellation_reason

CLASS (1) ──── (Many) BOOKINGS
MEMBER (1) ──── (Many) BOOKINGS
USER (1) ──── (Many) BOOKINGS (nullable)

UNIQUE CONSTRAINT: [class_id, member_id]
(One member cannot book same class twice)
```

---

## 🎯 User Journey Map

### Journey 1: New Member Books First Class

```
Step 1: Discovery
[Visit Website] → [See Trainers] → [Get Excited] → [Want to Join]
     │
     └─> Sees: Professional trainers, Their gyms, Specializations

Step 2: Registration
[Create Account] → [Verify Email] → [Login] → [Access Dashboard]
     │
     └─> Becomes: Authenticated User

Step 3: Browse
[Click "Book Classes"] → [View Available Classes] → [Filter by Date/Trainer]
     │
     └─> Sees: Class cards, Capacity status, Trainer info, Schedule

Step 4: Selection
[Choose Class] → [Click "Book Now"] → [View Class Details]
     │
     └─> Checks: Date, Time, Location, Trainer, Capacity

Step 5: Booking
[Select Member Profile] → [Review Policy] → [Confirm Booking]
     │
     └─> Gets: Confirmation, Booking ID, Booking details

Step 6: Management
[Go to "My Bookings"] → [See Upcoming Booking] → [Prepare for Class]
     │
     └─> Can: View details, Cancel if needed, See booking status

Step 7: Attendance
[Attend Class] → [Check-in] → [Status Updates to "attended"]
     │
     └─> Booking: Moves to past bookings, Shows as attended
```

### Journey 2: Trainer Profile Discovery

```
[Visit Homepage]
     │
     ↓
[Scroll to Trainers Section]
     │
     ├─> See 6 Professional Trainers
     │
     ├─> Each Card Shows:
     │     ├─> Professional Photo
     │     ├─> Name & Nickname
     │     ├─> Specialization Badge
     │     ├─> Gym Affiliation
     │     ├─> Bio Excerpt
     │     └─> Contact Button
     │
     ↓
[Hover Over Card]
     │
     └─> Card Elevates with Shadow
         (Visual Feedback)
     │
     ↓
[Click "Contact" or Learn More]
     │
     └─> Opens Email Client OR
         Goes to Trainer Profile (future feature)
```

---

## 🔐 Security Flow

```
┌─────────────────────────────────────────────────────────────┐
│                      SECURITY LAYERS                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Layer 1: AUTHENTICATION                                    │
│  ┌───────────────────────────────────────────────────┐    │
│  │ - All booking routes require login               │    │
│  │ - Session management                              │    │
│  │ - Remember me functionality                       │    │
│  └───────────────────────────────────────────────────┘    │
│                          ↓                                  │
│  Layer 2: AUTHORIZATION                                     │
│  ┌───────────────────────────────────────────────────┐    │
│  │ - User can only view their own bookings           │    │
│  │ - User can only cancel their own bookings         │    │
│  │ - Role-based access control                       │    │
│  └───────────────────────────────────────────────────┘    │
│                          ↓                                  │
│  Layer 3: VALIDATION                                        │
│  ┌───────────────────────────────────────────────────┐    │
│  │ - Server-side input validation                    │    │
│  │ - CSRF token verification                         │    │
│  │ - Data type checking                              │    │
│  └───────────────────────────────────────────────────┘    │
│                          ↓                                  │
│  Layer 4: BUSINESS RULES                                    │
│  ┌───────────────────────────────────────────────────┐    │
│  │ - Duplicate booking prevention                    │    │
│  │ - Capacity limit enforcement                      │    │
│  │ - Date/time validation                            │    │
│  └───────────────────────────────────────────────────┘    │
│                          ↓                                  │
│  Layer 5: DATABASE                                          │
│  ┌───────────────────────────────────────────────────┐    │
│  │ - Foreign key constraints                         │    │
│  │ - Unique constraints                              │    │
│  │ - Transaction safety                              │    │
│  │ - Cascade delete protection                       │    │
│  └───────────────────────────────────────────────────┘    │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📱 Responsive Design Flow

```
Desktop View (≥992px)
┌────────────────────────────────────────────────────────┐
│  [Navbar]                                              │
├────────┬───────────────────────────────────────────────┤
│        │  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│ [Side  │  │  Class   │  │  Class   │  │  Class   │   │
│  bar]  │  │  Card 1  │  │  Card 2  │  │  Card 3  │   │
│        │  └──────────┘  └──────────┘  └──────────┘   │
│        │                                               │
│        │  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│        │  │  Class   │  │  Class   │  │  Class   │   │
│        │  │  Card 4  │  │  Card 5  │  │  Card 6  │   │
│        │  └──────────┘  └──────────┘  └──────────┘   │
└────────┴───────────────────────────────────────────────┘

Tablet View (768px - 991px)
┌────────────────────────────────┐
│  [Navbar]                      │
├────────────────────────────────┤
│  ┌──────────┐  ┌──────────┐   │
│  │  Class   │  │  Class   │   │
│  │  Card 1  │  │  Card 2  │   │
│  └──────────┘  └──────────┘   │
│                                │
│  ┌──────────┐  ┌──────────┐   │
│  │  Class   │  │  Class   │   │
│  │  Card 3  │  │  Card 4  │   │
│  └──────────┘  └──────────┘   │
└────────────────────────────────┘

Mobile View (<768px)
┌──────────────────┐
│  [Navbar]        │
├──────────────────┤
│  ┌────────────┐  │
│  │   Class    │  │
│  │   Card 1   │  │
│  └────────────┘  │
│                  │
│  ┌────────────┐  │
│  │   Class    │  │
│  │   Card 2   │  │
│  └────────────┘  │
│                  │
│  ┌────────────┐  │
│  │   Class    │  │
│  │   Card 3   │  │
│  └────────────┘  │
└──────────────────┘
```

---

## 🔄 Status State Machine

```
                    ┌─────────────┐
                    │   BOOKING   │
                    │   CREATED   │
                    └──────┬──────┘
                           │
                           ↓
                    ┌─────────────┐
              ┌────>│  CONFIRMED  │<────┐
              │     └──────┬──────┘     │
              │            │            │
              │            ↓            │
    [Cancel]  │     ┌─────────────┐    │  [Undo Cancel]
    with      │     │  CANCELLED  │    │  (Not implemented)
    reason    │     └─────────────┘    │
              │                         │
              │                         │
    [Class    │     ┌─────────────┐    │
    Happens]  └────>│   ATTENDED  │    │
                    └─────────────┘    │
                           │            │
                           │            │
                    ┌─────────────┐    │
                    │   NO_SHOW   │────┘
                    └─────────────┘

Status Rules:
- CONFIRMED: Default state after booking
- CANCELLED: User cancels before class (requires reason)
- ATTENDED: User checked in (manual/automated)
- NO_SHOW: User didn't attend (automated after class)

Transitions:
- CONFIRMED → CANCELLED: Allowed (if class in future)
- CONFIRMED → ATTENDED: Allowed (check-in)
- CONFIRMED → NO_SHOW: Allowed (after class time)
- CANCELLED → CONFIRMED: Not allowed (would need new booking)
- ATTENDED → *: Not allowed (final state)
- NO_SHOW → *: Not allowed (final state)
```

---

## 📊 Capacity Management Flow

```
Class Created: Capacity = 15
     │
     ├─> Available Spots = 15
     │   Progress Bar: ▱▱▱▱▱▱▱▱▱▱ 0% (GREEN)
     │
     ↓
Booking 1-9 Created (Confirmed)
     │
     ├─> Available Spots = 6
     │   Progress Bar: ████████▱▱ 60% (GREEN)
     │   Status: "Good availability"
     │
     ↓
Booking 10-13 Created (Confirmed)
     │
     ├─> Available Spots = 2
     │   Progress Bar: █████████▱ 86% (YELLOW)
     │   Status: "Filling up fast"
     │
     ↓
Booking 14 Created (Confirmed)
     │
     ├─> Available Spots = 1
     │   Progress Bar: █████████▱ 93% (RED)
     │   Status: "Almost full - 1 spot left"
     │
     ↓
Booking 15 Created (Confirmed)
     │
     ├─> Available Spots = 0
     │   Progress Bar: ██████████ 100% (RED)
     │   Status: "CLASS FULL"
     │   Action: "Book Now" button disabled
     │
     ↓
Booking 10 Cancelled
     │
     ├─> Available Spots = 1
     │   Progress Bar: █████████▱ 93% (RED)
     │   Status: "1 spot available"
     │   Action: "Book Now" button enabled
     │
     └─> Cycle Continues...

Color Coding:
- GREEN (0-60%): Plenty of space
- YELLOW (61-90%): Getting full
- RED (91-100%): Very limited/Full
```

---

## 🎨 UI Component Hierarchy

```
App Layout
│
├─> Navbar
│   ├─> Logo
│   ├─> Navigation Links
│   └─> User Menu
│
├─> Sidebar (Authenticated Users)
│   ├─> Dashboard Link
│   ├─> Book Classes Link ← NEW
│   ├─> My Bookings Link ← NEW
│   ├─> Other Menu Items
│   └─> User Profile
│
└─> Main Content
    │
    ├─> Welcome Page
    │   ├─> Hero Section
    │   ├─> Features Section
    │   ├─> Trainers Section ← NEW
    │   │   └─> Trainer Cards (6)
    │   │       ├─> Photo
    │   │       ├─> Name
    │   │       ├─> Specialization Badge
    │   │       ├─> Gym Name
    │   │       ├─> Bio Excerpt
    │   │       └─> Contact Button
    │   └─> Gym Locations
    │
    ├─> Bookings Index Page
    │   ├─> Page Header
    │   ├─> Filters (Future)
    │   ├─> Class Cards Grid
    │   │   └─> Class Card
    │   │       ├─> Class Name
    │   │       ├─> Trainer Info
    │   │       ├─> Schedule
    │   │       ├─> Location
    │   │       ├─> Capacity Progress Bar
    │   │       └─> Book Now Button
    │   └─> Pagination
    │
    ├─> Booking Create Page
    │   ├─> Class Details Section
    │   ├─> Booking Form
    │   │   ├─> Member Selection
    │   │   └─> Submit Button
    │   └─> Booking Policy
    │
    ├─> My Bookings Page
    │   ├─> Upcoming Bookings Section
    │   │   └─> Booking Cards
    │   │       ├─> Class Info
    │   │       ├─> Status Badge
    │   │       ├─> View Details Button
    │   │       └─> Cancel Button
    │   │
    │   └─> Past Bookings Section
    │       └─> Booking Cards (Read-only)
    │           ├─> Class Info
    │           ├─> Status Badge
    │           └─> View Details Button
    │
    └─> Booking Details Page
        ├─> Back Button
        ├─> Class Information Card
        ├─> Member Information Card
        ├─> Booking Information Card
        ├─> Important Reminders
        └─> Cancel Button (if eligible)
            └─> Cancel Modal
                ├─> Reason Textarea
                ├─> Cancel Button
                └─> Close Button
```

---

**Visual Reference Complete!** 🎨

For implementation details, see `BOOKING_FEATURE_SUMMARY.md`
For quick testing steps, see `QUICK_START_GUIDE.md`
