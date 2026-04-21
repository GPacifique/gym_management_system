<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkoutPlanController;
use App\Http\Controllers\GymClassController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\GymController;

Route::get('/attendances/export', [AttendanceController::class, 'export'])
    ->name('attendances.export');

Route::middleware(['auth'])->group(function () {

    Route::get('/gyms', [GymController::class, 'index'])
        ->name('gyms.index');

    Route::get('/gyms/{gym}', [GymController::class, 'show'])
        ->name('gyms.show');

    Route::get('/gyms/{gym}/edit', [GymController::class, 'edit'])
        ->name('gyms.edit');
Route::post('/gyms', [GymController::class, 'store'])
        ->name('gyms.store');
        Route::delete('/gyms/{gym}', [GymController::class, 'destroy'])
        ->name('gyms.destroy'); 
        route::get('/gyms/create', [GymController::class, 'create'])
        ->name('gyms.create');
    Route::put('/gyms/{gym}', [GymController::class, 'update'])
        ->name('gyms.update');

});
Route::get('/gyms/{gym}/switch', [GymController::class, 'switch'])->name('gyms.switch');
Route::get('/gyms/{gym}/switch-back', [GymController::class, 'switchBack'])->name('gyms.switch-back');
Route::get('/gyms/{gym}/switch-back-to-default', [GymController::class, 'switchBackToDefault'])->name('gyms.switch-back-default');  

/*
|--------------------------------------------------------------------------
| HEALTH CHECK
|--------------------------------------------------------------------------
*/
Route::get('/healthz', function () {
    return response()->json([
        'status' => true,
        'app' => config('app.name'),
        'laravel' => app()->version(),
        'time' => now()->toDateTimeString(),
    ]);
});

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $trainers = \App\Models\Trainer::with('gym')->take(6)->get();
    return view('welcome', compact('trainers'));
})->name('welcome');

/*
|--------------------------------------------------------------------------
| GUEST - GYM REGISTRATION
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/gym/register', [\App\Http\Controllers\GymRegistrationController::class, 'create'])->name('gym.register');
    Route::post('/gym/register', [\App\Http\Controllers\GymRegistrationController::class, 'store'])->name('gym.register.store');
});

/*
|--------------------------------------------------------------------------
| AUTH REQUIRED (BASE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |----------------------------
    | DASHBOARD
    |----------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [DashboardController::class, 'index'])->name('dashboard.refresh');

    /*
    |----------------------------
    | PROFILE
    |----------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------
    | GYM SWITCHING (MULTI-TENANT CORE)
    |----------------------------
    */
    Route::get('/gyms/switch/{gym}', [\App\Http\Controllers\GymController::class, 'switch'])->name('gyms.switch');

});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN (PLATFORM LEVEL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/gyms', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'index'])->name('gyms.index');
        Route::get('/gyms/{gym}', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'show'])->name('gyms.show');

        Route::patch('/gyms/{gym}/approve', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'approve'])->name('gyms.approve');
        Route::patch('/gyms/{gym}/reject', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'reject'])->name('gyms.reject');
        Route::patch('/gyms/{gym}/suspend', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'suspend'])->name('gyms.suspend');

        Route::delete('/gyms/{gym}', [\App\Http\Controllers\SuperAdmin\GymAccountController::class, 'destroy'])->name('gyms.destroy');
    });

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::resource('trainers', TrainerController::class);

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', AdminUserController::class)->except(['show']);
            Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])
                ->name('users.reset-password');
        });
    });

/*
|--------------------------------------------------------------------------
| ADMIN + MANAGER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,manager'])->group(function () {

    Route::resource('members', MemberController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('workout-plans', WorkoutPlanController::class);
    Route::resource('classes', GymClassController::class);

});

/*
|--------------------------------------------------------------------------
| ADMIN + MANAGER + RECEPTIONIST
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,manager,receptionist'])->group(function () {
Route::get('/gyms/{gym}/switch', [GymController::class, 'switch'])->name('gyms.switch');
Route::get('/gyms/{gym}/switch-back', [GymController::class, 'switchBack'])->name('gyms.switch-back');
Route::get('/gyms/{gym}/switch-back-to-default', [GymController::class, 'switchBackToDefault'])->name('gyms.switch-back-default');  
Route::get('/gyms/{gym}/edit', [GymController::class, 'edit'])
        ->name('gyms.edit');
Route::post('/gyms', [GymController::class, 'store'])
        ->name('gyms.store');
Route::delete('/gyms/{gym}', [GymController::class, 'destroy'])
        ->name('gyms.destroy');
route::get('/gyms/create', [GymController::class, 'create'])
        ->name('gyms.create');
Route::put('/gyms/{gym}', [GymController::class, 'update']) 
        ->name('gyms.update');

Route::resource('members', MemberController::class)->only(['index', 'show', 'create', 'store']);
route::resource('subscriptions', SubscriptionController::class)->only(['index', 'show']);   
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'create', 'store']);
    Route::resource('attendances', AttendanceController::class)->only(['index', 'show', 'create', 'store']);

    Route::get('attendances/scan', [AttendanceController::class, 'scanForm'])->name('attendances.scan');
    Route::post('attendances/scan', [AttendanceController::class, 'scanCheck'])->name('attendances.scan.check');
Route::get('attendances/{attendance}/checkout', [AttendanceController::class, 'checkoutForm'])->name('attendances.checkout.form');  
Route::post('attendances/{attendance}/checkin', [AttendanceController::class, 'checkin'])->name('attendances.checkin');
    Route::patch('attendances/{attendance}/checkout', [AttendanceController::class, 'checkout'])->name('attendances.checkout');

});

/*|--------------------------------------------------------------------------
| ADMIN + MANAGER (DESTRUCTIVE ACTIONS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,manager'])->group(function () {

    Route::resource('payments', PaymentController::class)->only(['edit', 'update', 'destroy']);
    Route::resource('attendances', AttendanceController::class)->only(['edit', 'update', 'destroy']);

});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/gyms', [GymController::class, 'index'])->name('gyms.index');
});

/*
|--------------------------------------------------------------------------
| BOOKINGS (ALL AUTH USERS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('bookings')->name('bookings.')->group(function () {

    Route::get('/', [\App\Http\Controllers\ClassBookingController::class, 'index'])->name('index');
    Route::get('/my-bookings', [\App\Http\Controllers\ClassBookingController::class, 'myBookings'])->name('my-bookings');

    Route::get('/classes/{class}/book', [\App\Http\Controllers\ClassBookingController::class, 'create'])->name('create');
    Route::post('/classes/{class}/book', [\App\Http\Controllers\ClassBookingController::class, 'store'])->name('store');

    Route::get('/{booking}', [\App\Http\Controllers\ClassBookingController::class, 'show'])->name('show');
    Route::post('/{booking}/cancel', [\App\Http\Controllers\ClassBookingController::class, 'cancel'])->name('cancel');

});

require __DIR__.'/auth.php';