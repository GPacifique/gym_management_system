<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Support\GymContext;
use App\Support\BranchContext;
use Illuminate\Http\RedirectResponse;

class BranchController extends Controller
{
    public function switch(Branch $branch): RedirectResponse
    {
        $user = auth()->user();

        // Super Admin can switch within any approved gym; others restricted to current gym
        $currentGymId = (int) (session('gym_id') ?? GymContext::id());
        if (!$currentGymId) {
            return back()->with('error', 'No current gym selected.');
        }

        if ($branch->gym_id !== $currentGymId && !$user->isSuperAdmin()) {
            abort(403, 'Branch does not belong to your current gym.');
        }

        session(['branch_id' => $branch->id]);
        BranchContext::set($branch->id);

        return back()->with('success', 'Switched to branch: '.$branch->name);
    }
}
