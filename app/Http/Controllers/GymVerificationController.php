<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Notifications\GymEmailVerification;
use Illuminate\Http\Request;

class GymVerificationController extends Controller
{
    /**
     * Mark the gym email as verified.
     */
    public function verify(Request $request, Gym $gym)
    {
        // Verify the signature
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }

        // Verify the hash matches
        if (sha1($gym->email) !== $request->hash) {
            abort(403, 'Invalid verification link.');
        }

        // Check if already verified
        if ($gym->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('info', 'Your gym email is already verified.');
        }

        // Mark as verified
        $gym->markEmailAsVerified();

        return redirect()->route('dashboard')->with('success', 'Your gym email has been verified successfully!');
    }

    /**
     * Resend the verification email.
     */
    public function resend(Request $request)
    {
        $user = $request->user();
        
        // Get user's gym(s)
        $gym = Gym::where('owner_user_id', $user->id)
                   ->whereNull('email_verified_at')
                   ->first();

        if (!$gym) {
            return back()->with('info', 'No unverified gym found.');
        }

        if ($gym->hasVerifiedEmail()) {
            return back()->with('info', 'Your gym email is already verified.');
        }

        // Send verification email
        $user->notify(new GymEmailVerification($gym));

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
}
