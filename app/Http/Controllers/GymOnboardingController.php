<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymOnboardingController extends Controller
{
    /**
     * Show onboarding welcome page
     */
    public function welcome()
    {
        $gym = Gym::find(session('gym_id'));

        if (!$gym || $gym->owner_user_id !== Auth::id()) {
            return redirect()->route('dashboard');
        }

        // Check if onboarding is complete
        if (session('onboarding_completed')) {
            return redirect()->route('dashboard');
        }

        return view('gym-onboarding.welcome', compact('gym'));
    }

    /**
     * Show add trainer step
     */
    public function addTrainer()
    {
        $gym = Gym::find(session('gym_id'));

        if (!$gym || $gym->owner_user_id !== Auth::id()) {
            return redirect()->route('dashboard');
        }

        return view('gym-onboarding.add-trainer', compact('gym'));
    }

    /**
     * Store trainer
     */
    public function storeTrainer(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:trainers,email',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
        ]);

        $gym = Gym::find(session('gym_id'));

        if (!$gym || $gym->owner_user_id !== Auth::id()) {
            return redirect()->route('dashboard');
        }

        $validated['gym_id'] = $gym->id;
        $validated['hire_date'] = now()->toDateString();

        // Ensure the trainers table 'name' column is populated by combining
        // the provided first and last name. The trainers table requires
        // a non-null name, so create it here before inserting.
        $validated['name'] = trim((($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? '')));

        Trainer::create($validated);

        return redirect()->route('gym.onboarding.membership-plans')
            ->with('success', 'Trainer added successfully!');
    }

    /**
     * Skip adding trainer
     */
    public function skipTrainer()
    {
        return redirect()->route('gym.onboarding.membership-plans');
    }

    /**
     * Show membership plans step
     */
    public function membershipPlans()
    {
        $gym = Gym::find(session('gym_id'));

        if (!$gym || $gym->owner_user_id !== Auth::id()) {
            return redirect()->route('dashboard');
        }

        return view('gym-onboarding.membership-plans', compact('gym'));
    }

    /**
     * Store membership plan
     */
    public function storeMembershipPlan(Request $request)
    {
        // This would typically create subscription plans
        // For now, we'll just mark onboarding as complete

        return redirect()->route('gym.onboarding.complete');
    }

    /**
     * Skip membership plans
     */
    public function skipMembershipPlans()
    {
        return redirect()->route('gym.onboarding.complete');
    }

    /**
     * Complete onboarding
     */
    public function complete()
    {
        $gym = Gym::find(session('gym_id'));

        if (!$gym || $gym->owner_user_id !== Auth::id()) {
            return redirect()->route('dashboard');
        }

        // Mark onboarding as complete
        session(['onboarding_completed' => true]);

        return view('gym-onboarding.complete', compact('gym'));
    }

    /**
     * Finish onboarding and go to dashboard
     */
    public function finish()
    {
        return redirect()->route('dashboard')->with('success',
            'Welcome to your gym dashboard! You can now start managing your gym.'
        );
    }
}
