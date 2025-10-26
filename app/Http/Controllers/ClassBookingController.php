<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\GymClass;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassBookingController extends Controller
{
    /**
     * Display a listing of available classes for booking.
     */
    public function index(Request $request)
    {
        $gymId = session('gym_id');
        
        $classes = GymClass::with(['trainer', 'confirmedBookings'])
            ->where('gym_id', $gymId)
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->paginate(12);

        return view('bookings.index', compact('classes'));
    }

    /**
     * Show booking form for a specific class.
     */
    public function create(GymClass $class)
    {
        if (!$class->canBeBooked()) {
            return redirect()->route('bookings.index')
                ->with('error', 'This class is no longer available for booking.');
        }

        $members = Member::where('gym_id', session('gym_id'))
            ->orderBy('first_name')
            ->get();

        return view('bookings.create', compact('class', 'members'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request, GymClass $class)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        // Check if class can still be booked
        if (!$class->canBeBooked()) {
            return back()->with('error', 'This class is no longer available for booking.');
        }

        // Check if member already has a booking for this class
        $existingBooking = ClassBooking::where('class_id', $class->id)
            ->where('member_id', $request->member_id)
            ->whereIn('status', ['confirmed'])
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'This member already has a booking for this class.');
        }

        try {
            DB::beginTransaction();

            $booking = ClassBooking::create([
                'gym_id' => $class->gym_id,
                'class_id' => $class->id,
                'member_id' => $request->member_id,
                'user_id' => auth()->id(),
                'status' => 'confirmed',
                'booked_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Class booked successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to book class. Please try again.');
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(ClassBooking $booking)
    {
        $booking->load(['gymClass.trainer', 'member']);
        
        return view('bookings.show', compact('booking'));
    }

    /**
     * Display member's bookings.
     */
    public function myBookings()
    {
        $gymId = session('gym_id');
        
        $upcomingBookings = ClassBooking::with(['gymClass.trainer'])
            ->where('gym_id', $gymId)
            ->where('status', 'confirmed')
            ->whereHas('gymClass', function($query) {
                $query->where('scheduled_at', '>=', now());
            })
            ->orderBy('booked_at', 'desc')
            ->get();

        $pastBookings = ClassBooking::with(['gymClass.trainer'])
            ->where('gym_id', $gymId)
            ->whereIn('status', ['attended', 'no_show', 'cancelled'])
            ->orWhere(function($query) {
                $query->where('status', 'confirmed')
                    ->whereHas('gymClass', function($q) {
                        $q->where('scheduled_at', '<', now());
                    });
            })
            ->orderBy('booked_at', 'desc')
            ->get();

        return view('bookings.my-bookings', compact('upcomingBookings', 'pastBookings'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, ClassBooking $booking)
    {
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->cancel($request->input('reason'));

        return redirect()->route('bookings.my-bookings')
            ->with('success', 'Booking cancelled successfully.');
    }
}
