<?php

namespace App\Http\Middleware;

use App\Models\Gym;
use App\Support\GymContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureGymApproved
{
    /**
     * Handle an incoming request.
     * Blocks access for non-approved gyms for non-super-admin users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Super Admin bypass
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Determine current gym id from session or context
        $gymId = (int) ($request->session()->get('gym_id') ?? GymContext::id());
        if (!$gymId) {
            return $next($request);
        }

        $gym = Gym::find($gymId);
        if (!$gym) {
            return redirect()->route('welcome')->with('warning', 'Gym not found.');
        }

        if ($gym->approval_status !== 'approved') {
            // Allow access to a limited set of routes (profile, logout, pending page)
            if ($request->routeIs('profile.*') || $request->routeIs('logout') || $request->routeIs('gym.pending')) {
                return $next($request);
            }
            
            return redirect()->route('gym.pending')->with('warning', 'Your gym account is currently '.$gym->approval_status.'. Access will be granted after approval.');
        }

        return $next($request);
    }
}
