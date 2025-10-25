<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\User;
use App\Notifications\GymEmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class GymRegistrationController extends Controller
{
    /**
     * Show the gym registration form.
     */
    public function create()
    {
        return view('gym-registration.create');
    }

    /**
     * Handle gym registration submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Gym information
            'gym_name' => 'required|string|max:255',
            'gym_address' => 'required|string|max:500',
            'gym_phone' => 'required|string|max:20',
            'gym_email' => 'required|email|max:255',
            'gym_timezone' => 'required|string|max:64',
            'gym_description' => 'nullable|string|max:1000',
            'gym_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
            // Owner information
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'owner_password' => ['required', 'confirmed', Password::defaults()],
            
            // Terms acceptance
            'terms' => 'required|accepted',
        ], [
            'gym_name.required' => 'Please enter your gym name.',
            'gym_address.required' => 'Please enter your gym address.',
            'gym_phone.required' => 'Please enter your gym phone number.',
            'gym_email.required' => 'Please enter your gym email address.',
            'gym_timezone.required' => 'Please select your timezone.',
            'owner_name.required' => 'Please enter your full name.',
            'owner_email.required' => 'Please enter your email address.',
            'owner_email.unique' => 'This email is already registered.',
            'owner_password.required' => 'Please create a password.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ]);

        try {
            DB::beginTransaction();

            // Generate unique slug for gym
            $slug = Str::slug($validated['gym_name']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Gym::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('gym_logo')) {
                $logoPath = $request->file('gym_logo')->store('gym-logos', 'public');
            }

            // Create the gym
            $gym = Gym::create([
                'name' => $validated['gym_name'],
                'slug' => $slug,
                'address' => $validated['gym_address'],
                'phone' => $validated['gym_phone'],
                'email' => $validated['gym_email'],
                'timezone' => $validated['gym_timezone'],
                'description' => $validated['gym_description'] ?? null,
                'logo' => $logoPath,
                'is_verified' => false,
                'approval_status' => 'pending',
                'subscription_tier' => 'trial',
                'trial_ends_at' => now()->addDays(30), // 30-day trial
            ]);

            // Create the owner user account
            $owner = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make($validated['owner_password']),
                'role' => 'admin',
                'gym_id' => $gym->id,
                'default_gym_id' => $gym->id,
            ]);

            // Update gym with owner reference
            $gym->update(['owner_user_id' => $owner->id]);

            // Attach owner to gym with admin role
            $owner->gyms()->attach($gym->id, ['role' => 'admin']);

            // Send email verification
            $owner->notify(new GymEmailVerification($gym));

            DB::commit();

            // Log the owner in
            Auth::login($owner);

            // Set gym context in session
            session(['gym_id' => $gym->id]);

            // Redirect to onboarding or dashboard
            return redirect()->route('gym.onboarding.welcome')->with('success', 
                'Welcome! Your gym has been registered. Please check your email to verify your account. You have a 30-day free trial.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput($request->except('owner_password', 'owner_password_confirmation'))
                ->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }
    }
}
