<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom Blade directives for role checking
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('hasAnyRole', function (...$roles) {
            return auth()->check() && auth()->user()->hasAnyRole($roles);
        });

        // Scoped route model binding for multi-gym isolation
        $this->registerGymScopedBindings();
    }

    /**
     * Register scoped route model bindings to ensure users can only access
     * resources belonging to their current gym.
     */
    protected function registerGymScopedBindings(): void
    {
        $models = [
            'member' => \App\Models\Member::class,
            'trainer' => \App\Models\Trainer::class,
            'subscription' => \App\Models\Subscription::class,
            'payment' => \App\Models\Payment::class,
            'workoutPlan' => \App\Models\WorkoutPlan::class,
            'class' => \App\Models\GymClass::class,
            'attendance' => \App\Models\Attendance::class,
        ];

        foreach ($models as $key => $model) {
            \Illuminate\Support\Facades\Route::bind($key, function ($value) use ($model) {
                // Admin users can access all gyms' data
                if (auth()->check() && auth()->user()->isAdmin()) {
                    return $model::withoutGlobalScope(\App\Models\Scopes\GymScope::class)
                        ->findOrFail($value);
                }

                // Regular users: scoped to their current gym (GymScope applies automatically)
                return $model::findOrFail($value);
            });
        }
    }
}
