<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GymAccountController extends Controller
{
    /**
     * Display a listing of all gyms.
     */
    public function index(Request $request)
    {
        $query = Gym::with(['owner', 'approver', 'members']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        // Filter by subscription tier
        if ($request->filled('tier')) {
            $query->where('subscription_tier', $request->tier);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $gyms = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => Gym::count(),
            'approved' => Gym::where('approval_status', 'approved')->count(),
            'pending' => Gym::where('approval_status', 'pending')->count(),
            'rejected' => Gym::where('approval_status', 'rejected')->count(),
            'on_trial' => Gym::where('subscription_tier', 'trial')->count(),
        ];

        return view('super-admin.gyms.index', compact('gyms', 'stats'));
    }

    /**
     * Display the specified gym.
     */
    public function show(Gym $gym)
    {
        $gym->load(['owner', 'approver', 'members', 'assignedUsers']);
        
        // Get statistics for this gym
        $stats = [
            'total_members' => $gym->members()->count(),
            'active_members' => $gym->members()->where('status', 'active')->count(),
            'total_users' => $gym->assignedUsers()->count(),
            'pending_payments' => \App\Models\Payment::where('gym_id', $gym->id)
                ->where('status', 'pending')
                ->count(),
        ];

        return view('super-admin.gyms.show', compact('gym', 'stats'));
    }

    /**
     * Approve a gym.
     */
    public function approve(Gym $gym)
    {
        $gym->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Gym approved successfully!');
    }

    /**
     * Reject a gym.
     */
    public function reject(Request $request, Gym $gym)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $gym->update([
            'approval_status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'Gym rejected.');
    }

    /**
     * Suspend a gym.
     */
    public function suspend(Request $request, Gym $gym)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        $gym->update([
            'approval_status' => 'suspended',
            'rejection_reason' => $request->suspension_reason,
        ]);

        return redirect()->back()->with('success', 'Gym suspended successfully.');
    }

    /**
     * Update gym subscription tier.
     */
    public function updateSubscription(Request $request, Gym $gym)
    {
        $request->validate([
            'subscription_tier' => 'required|in:trial,basic,premium,enterprise',
            'trial_ends_at' => 'nullable|date',
        ]);

        $gym->update([
            'subscription_tier' => $request->subscription_tier,
            'trial_ends_at' => $request->trial_ends_at,
        ]);

        return redirect()->back()->with('success', 'Subscription updated successfully!');
    }

    /**
     * Delete a gym and all its related data.
     */
    public function destroy(Gym $gym)
    {
        DB::transaction(function () use ($gym) {
            // Delete gym logo
            if ($gym->logo) {
                Storage::disk('public')->delete($gym->logo);
            }

            // Delete all related records
            $gym->members()->delete();
            $gym->assignedUsers()->update(['gym_id' => null, 'default_gym_id' => null]);
            $gym->users()->detach();
            
            // Delete the gym
            $gym->delete();
        });

        return redirect()->route('super-admin.gyms.index')
            ->with('success', 'Gym and all related data deleted successfully.');
    }

    /**
     * Export gyms data.
     */
    public function export(Request $request)
    {
        $query = Gym::with(['owner', 'members']);

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        $gyms = $query->get();

        $csv = fopen('php://temp', 'w');
        
        // Headers
        fputcsv($csv, [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Status',
            'Subscription',
            'Members Count',
            'Owner Name',
            'Owner Email',
            'Created At',
            'Trial Ends At',
        ]);

        // Data
        foreach ($gyms as $gym) {
            fputcsv($csv, [
                $gym->id,
                $gym->name,
                $gym->email,
                $gym->phone,
                $gym->address,
                $gym->approval_status,
                $gym->subscription_tier,
                $gym->members->count(),
                $gym->owner?->name ?? 'N/A',
                $gym->owner?->email ?? 'N/A',
                $gym->created_at->format('Y-m-d H:i:s'),
                $gym->trial_ends_at?->format('Y-m-d') ?? 'N/A',
            ]);
        }

        rewind($csv);
        $output = stream_get_contents($csv);
        fclose($csv);

        return response($output, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="gyms_export_' . date('Y-m-d') . '.csv"',
        ]);
    }
}
