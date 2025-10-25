<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Support\GymContext;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GymController extends Controller
{
    public function index()
    {
        $gyms = Gym::orderBy('name')->paginate(10);
        return view('gyms.index', compact('gyms'));
    }

    public function create()
    {
        return view('gyms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:64',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['timezone'] = $data['timezone'] ?? config('app.timezone', 'UTC');

        $gym = Gym::create($data);

        // If user has no default gym, set this
        if (auth()->user() && !auth()->user()->default_gym_id) {
            auth()->user()->update(['default_gym_id' => $gym->id]);
        }

        return redirect()->route('gyms.index')->with('success', 'Gym created successfully');
    }

    public function switch(Gym $gym)
    {
        session(['gym_id' => $gym->id]);
        GymContext::set($gym->id);
        if (auth()->user()) {
            // Don't overwrite explicit preferences unless empty
            if (!auth()->user()->default_gym_id) {
                auth()->user()->update(['default_gym_id' => $gym->id]);
            }
        }
        return redirect()->back()->with('success', 'Switched to gym: '.$gym->name);
    }

    public function show(Gym $gym)
    {
        // Check access
        if (!auth()->user()->isAdmin() && !auth()->user()->hasGymAccess($gym->id)) {
            abort(403, 'You do not have access to this gym.');
        }

        $gym->load(['members', 'users']);
        
        // Get statistics for this gym
        $stats = [
            'totalMembers' => $gym->members()->count(),
            'activeSubscriptions' => \App\Models\Subscription::withoutGlobalScope(\App\Models\Scopes\GymScope::class)
                ->where('gym_id', $gym->id)
                ->where('status', 'active')
                ->count(),
            'trainers' => \App\Models\Trainer::withoutGlobalScope(\App\Models\Scopes\GymScope::class)
                ->where('gym_id', $gym->id)
                ->count(),
            'classes' => \App\Models\GymClass::withoutGlobalScope(\App\Models\Scopes\GymScope::class)
                ->where('gym_id', $gym->id)
                ->count(),
            'todayCheckIns' => \App\Models\Attendance::withoutGlobalScope(\App\Models\Scopes\GymScope::class)
                ->where('gym_id', $gym->id)
                ->whereDate('check_in_time', today())
                ->count(),
            'monthlyRevenue' => \App\Models\Payment::withoutGlobalScope(\App\Models\Scopes\GymScope::class)
                ->where('gym_id', $gym->id)
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
        ];

        return view('gyms.show', compact('gym', 'stats'));
    }

    public function edit(Gym $gym)
    {
        // Check access - admin or managers of this gym
        if (!auth()->user()->isAdmin() && !auth()->user()->hasRoleInGym('manager', $gym->id)) {
            abort(403, 'You do not have permission to edit this gym.');
        }

        return view('gyms.edit', compact('gym'));
    }

    public function update(Request $request, Gym $gym)
    {
        // Check access
        if (!auth()->user()->isAdmin() && !auth()->user()->hasRoleInGym('manager', $gym->id)) {
            abort(403, 'You do not have permission to edit this gym.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'timezone' => 'nullable|string|max:64',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'opening_hours' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($gym->logo && \Storage::disk('public')->exists($gym->logo)) {
                \Storage::disk('public')->delete($gym->logo);
            }
            
            $data['logo'] = $request->file('logo')->store('gym-logos', 'public');
        }

        $data['slug'] = Str::slug($data['name']);
        $gym->update($data);

        return redirect()->route('gyms.show', $gym)->with('success', 'Gym profile updated successfully');
    }
}
