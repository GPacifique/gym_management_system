<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\GymClassController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Example Eloquent model
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

// Lightweight health check (no middleware)
Route::get('/healthz', function () {
    return response()->json([
        'ok' => true,
        'php' => PHP_VERSION,
        'laravel' => app()->version(),
        'env' => app()->environment(),
        'time' => now()->toDateTimeString(),
    ]);
});

Route::get('/', function () {
    $trainers = \App\Models\Trainer::with('gym')
        ->take(6)
        ->get();
    return view('welcome', compact('trainers'));
})->name('welcome');

// Gym Registration Routes (Guest accessible)
Route::middleware('guest')->group(function () {
    Route::get('/gym/register', [\App\Http\Controllers\GymRegistrationController::class, 'create'])
        ->name('gym.register');
    Route::post('/gym/register', [\App\Http\Controllers\GymRegistrationController::class, 'store'])
        ->name('gym.register.store');
});

// Gym Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/gym/verify/{gym}/{hash}', [\App\Http\Controllers\GymVerificationController::class, 'verify'])
        ->name('gym.verify.email')
        ->middleware('signed');
    Route::post('/gym/verification/resend', [\App\Http\Controllers\GymVerificationController::class, 'resend'])
        ->name('gym.verification.resend');
});

// Gym Onboarding Routes
Route::middleware('auth')->prefix('gym/onboarding')->name('gym.onboarding.')->group(function () {
    Route::get('/welcome', [\App\Http\Controllers\GymOnboardingController::class, 'welcome'])
        ->name('welcome');
    Route::get('/add-trainer', [\App\Http\Controllers\GymOnboardingController::class, 'addTrainer'])
        ->name('add-trainer');
    Route::post('/add-trainer', [\App\Http\Controllers\GymOnboardingController::class, 'storeTrainer'])
        ->name('store-trainer');
    Route::post('/skip-trainer', [\App\Http\Controllers\GymOnboardingController::class, 'skipTrainer'])
        ->name('skip-trainer');
    Route::get('/membership-plans', [\App\Http\Controllers\GymOnboardingController::class, 'membershipPlans'])
        ->name('membership-plans');
    Route::post('/membership-plans', [\App\Http\Controllers\GymOnboardingController::class, 'storeMembershipPlan'])
        ->name('store-membership-plan');
    Route::post('/skip-membership-plans', [\App\Http\Controllers\GymOnboardingController::class, 'skipMembershipPlans'])
        ->name('skip-membership-plans');
    Route::get('/complete', [\App\Http\Controllers\GymOnboardingController::class, 'complete'])
        ->name('complete');
    Route::post('/finish', [\App\Http\Controllers\GymOnboardingController::class, 'finish'])
        ->name('finish');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Pending approval page (must be accessible when blocked)
Route::middleware(['auth'])->get('/gym/pending', function () {
    return view('gym.pending');
})->name('gym.pending');

// Super Admin only routes
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/gyms', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'index'])
        ->name('gyms.index');
    Route::get('/gyms/{gym}', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'show'])
        ->name('gyms.show');
    Route::patch('/gyms/{gym}/approve', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'approve'])
        ->name('gyms.approve');
    Route::patch('/gyms/{gym}/reject', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'reject'])
        ->name('gyms.reject');
    Route::patch('/gyms/{gym}/suspend', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'suspend'])
        ->name('gyms.suspend');
    Route::patch('/gyms/{gym}/update-subscription', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'updateSubscription'])
        ->name('gyms.update-subscription');
    Route::delete('/gyms/{gym}', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'destroy'])
        ->name('gyms.destroy');
    Route::get('/gyms-export', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'export'])
        ->name('gyms.export');
});

// Admin only routes
Route::middleware(['auth', 'role:admin', \App\Http\Middleware\EnsureGymApproved::class])->group(function () {
    Route::resource('trainers', TrainerController::class);
    Route::resource('gyms', \App\Http\Controllers\GymController::class)->only(['index','create','store']);
    // User management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', AdminUserController::class)->except(['show']);
        
        // Gym approval management
        Route::get('gym-approvals', [\App\Http\Controllers\Admin\GymApprovalController::class, 'index'])
            ->name('gym-approvals.index');
        Route::get('gym-approvals/{gym}', [\App\Http\Controllers\Admin\GymApprovalController::class, 'show'])
            ->name('gym-approvals.show');
        Route::post('gym-approvals/{gym}/approve', [\App\Http\Controllers\Admin\GymApprovalController::class, 'approve'])
            ->name('gym-approvals.approve');
        Route::post('gym-approvals/{gym}/reject', [\App\Http\Controllers\Admin\GymApprovalController::class, 'reject'])
            ->name('gym-approvals.reject');
    });
});

// Gym profile - accessible by admin and managers
Route::middleware(['auth', 'role:admin,manager', \App\Http\Middleware\EnsureGymApproved::class])->group(function () {
    Route::get('/gyms/{gym}', [\App\Http\Controllers\GymController::class, 'show'])->name('gyms.show');
    Route::get('/gyms/{gym}/edit', [\App\Http\Controllers\GymController::class, 'edit'])->name('gyms.edit');
    Route::put('/gyms/{gym}', [\App\Http\Controllers\GymController::class, 'update'])->name('gyms.update');
});

// Admin and Manager routes
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('members', MemberController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('workout-plans', WorkoutPlanController::class);
    Route::resource('classes', GymClassController::class);
});

// Admin, Manager, and Receptionist routes (limited CRUD)
Route::middleware(['auth', 'role:admin,manager,receptionist', \App\Http\Middleware\EnsureGymApproved::class])->group(function () {
    // Receptionist can view and create payments; edit/delete are restricted below
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);

    // Receptionist can view and record attendance; edit/delete are restricted below
    Route::resource('attendances', AttendanceController::class)->only(['index', 'show', 'store']);

    // Scan-to-check-in routes (RFID/microchip)
    Route::get('attendances/scan', [AttendanceController::class, 'scanForm'])->name('attendances.scan');
    Route::post('attendances/scan', [AttendanceController::class, 'scanCheck'])->name('attendances.scan.check');

    // Shared utilities
    Route::get('/dashboard/payments', [PaymentController::class, 'dashboard'])->name('payments.dashboard');
    Route::patch('/attendances/{attendance}/checkout', [AttendanceController::class, 'checkout'])
        ->name('attendances.checkout');
    Route::get('/attendances/export', [AttendanceController::class, 'export'])
        ->name('attendances.export');
    Route::get('/attendances/stats/daily', [AttendanceController::class, 'getDailyStats'])
        ->name('attendances.daily-stats');
});

// Admin and Manager only routes for destructive/maintenance actions
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('payments', PaymentController::class)->only(['edit', 'update', 'destroy']);
    Route::resource('attendances', AttendanceController::class)->only(['edit', 'update', 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Switch current gym
    Route::get('/gyms/switch/{gym}', [\App\Http\Controllers\GymController::class, 'switch'])->name('gyms.switch');
    // Switch current branch
    Route::get('/branches/switch/{branch}', [\App\Http\Controllers\BranchController::class, 'switch'])->name('branches.switch');
    
    // Class Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ClassBookingController::class, 'index'])->name('index');
        Route::get('/my-bookings', [\App\Http\Controllers\ClassBookingController::class, 'myBookings'])->name('my-bookings');
        Route::get('/classes/{class}/book', [\App\Http\Controllers\ClassBookingController::class, 'create'])->name('create');
        Route::post('/classes/{class}/book', [\App\Http\Controllers\ClassBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [\App\Http\Controllers\ClassBookingController::class, 'show'])->name('show');
        Route::post('/{booking}/cancel', [\App\Http\Controllers\ClassBookingController::class, 'cancel'])->name('cancel');
    });
});

// AJAX route for refreshing dashboard data
Route::get('/dashboard/refresh', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.refresh');

require __DIR__.'/auth.php';
