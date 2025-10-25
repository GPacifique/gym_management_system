<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\Request;

class GymApprovalController extends Controller
{
    public function index()
    {
        $pendingGyms = Gym::where('approval_status', 'pending')
            ->with('owner')
            ->latest()
            ->paginate(10);

        $approvedGyms = Gym::where('approval_status', 'approved')
            ->with(['owner', 'approver'])
            ->latest('approved_at')
            ->paginate(10);

        $rejectedGyms = Gym::where('approval_status', 'rejected')
            ->with(['owner', 'approver'])
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.gym-approvals.index', compact('pendingGyms', 'approvedGyms', 'rejectedGyms'));
    }

    public function show(Gym $gym)
    {
        $gym->load(['owner', 'approver']);

        return view('admin.gym-approvals.show', compact('gym'));
    }

    public function approve(Request $request, Gym $gym)
    {
        if (!$gym->isPending()) {
            return redirect()->route('admin.gym-approvals.index')
                ->with('error', 'Only pending gyms can be approved.');
        }

        $gym->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // TODO: Send approval notification to gym owner

        return redirect()->route('admin.gym-approvals.index')
            ->with('success', "Gym '{$gym->name}' has been approved!");
    }

    public function reject(Request $request, Gym $gym)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if (!$gym->isPending()) {
            return redirect()->route('admin.gym-approvals.index')
                ->with('error', 'Only pending gyms can be rejected.');
        }

        $gym->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // TODO: Send rejection notification to gym owner

        return redirect()->route('admin.gym-approvals.index')
            ->with('success', "Gym '{$gym->name}' has been rejected.");
    }
}
