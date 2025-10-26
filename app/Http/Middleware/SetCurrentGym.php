<?php

namespace App\Http\Middleware;

use App\Models\Gym;
use App\Support\GymContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetCurrentGym
{
    /**
     * Handle an incoming request.
     * Ensure there's a gym in session and context, and verify user has access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip gym context for public/guest routes
        if (!Auth::check() || $request->routeIs('welcome', 'login', 'register', 'password.*')) {
            return $next($request);
        }

        $user = Auth::user();

        // Super admins bypass gym context entirely - they have platform-wide access
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        $gymId = $request->session()->get('gym_id');

        if (!$gymId) {
            // Prefer user's default gym
            if ($user && $user->default_gym_id) {
                $gymId = $user->default_gym_id;
            } else {
                // Fallback: first gym user has access to, or create/use first gym
                if ($user) {
                    $userGym = $user->gyms()->first();
                    if ($userGym) {
                        $gymId = $userGym->id;
                    }
                }
                
                if (!$gymId) {
                    $gymId = Gym::query()->value('id');
                    if (!$gymId) {
                        $gym = Gym::create([
                            'name' => 'Main Gym',
                            'slug' => 'main-gym',
                            'timezone' => config('app.timezone', 'UTC'),
                        ]);
                        $gymId = $gym->id;
                        
                        // Assign current user to this gym if authenticated
                        if ($user) {
                            $user->gyms()->attach($gymId, ['role' => $user->role ?? 'member']);
                        }
                    }
                }
            }
            $request->session()->put('gym_id', $gymId);
        }

        // Verify authenticated user has access to this gym
        if ($user && !$user->isAdmin() && !$user->hasGymAccess($gymId)) {
            // If user has no gym assignments yet (e.g., newly registered), attach to current gym as their default
            if ($user->gyms()->count() === 0) {
                try {
                    $user->gyms()->attach($gymId, ['role' => $user->role ?? 'member']);
                    $user->default_gym_id = (int) $gymId;
                    $user->save();
                    $request->session()->put('gym_id', $gymId);
                } catch (\Throwable $e) {
                    // Fallback to welcome if something unexpected happens
                    return redirect()->route('welcome')->with('warning', 'Your account is being initialized. Please try again.');
                }
            } else {
                // User has gyms but not this one: switch to their first accessible gym
                $firstGym = $user->gyms()->first();
                if ($firstGym) {
                    $request->session()->put('gym_id', $firstGym->id);
                    GymContext::set($firstGym->id);
                    return redirect()->route('dashboard')->with('warning', 'Switched to your accessible gym location.');
                }
                // No accessible gym found (defensive)
                return redirect()->route('welcome')->with('warning', 'No accessible gym found for your account.');
            }
        }

        // Set context for downstream usage
        GymContext::set((int) $gymId);

        return $next($request);
    }
}
