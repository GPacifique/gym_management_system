<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Member, Trainer};

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('trainer');

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('first_name')->orderBy('last_name')->paginate(10)->withQueryString();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $trainers = Trainer::all();
        return view('members.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string',
            'chip_id' => 'nullable|string|max:191|unique:members,chip_id',
            'photo' => 'nullable|image|max:2048',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'trainer_id' => ['nullable', 'exists:trainers,id', new \App\Rules\BelongsToCurrentGym('trainers')],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('member-photos', 'public');
            $validated['photo_path'] = $path;
        }

        $member = Member::create($validated);

        // Send welcome SMS if phone is provided
        try {
            if (!empty($member->phone)) {
                $to = $member->phone;
                $message = sprintf(
                    'Welcome to GymMate, %s! Your membership has been created. See you at the gym!',
                    trim($member->first_name . ' ' . $member->last_name)
                );
                app(\App\Services\SmsService::class)->send($to, $message);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to send member welcome SMS', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $trainers = Trainer::all();
        return view('members.edit', compact('member', 'trainers'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:members,email,'.$member->id,
            'phone' => 'nullable|string',
            'chip_id' => 'nullable|string|max:191|unique:members,chip_id,'.$member->id,
            'photo' => 'nullable|image|max:2048',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'trainer_id' => ['nullable', 'exists:trainers,id', new \App\Rules\BelongsToCurrentGym('trainers')],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('member-photos', 'public');
            $validated['photo_path'] = $path;
        }

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}
