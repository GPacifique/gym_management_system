<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{WorkoutPlan, Member, Trainer};

class WorkoutPlanController extends Controller
{
    public function index()
    {
        $workoutPlans = WorkoutPlan::with(['member', 'trainer'])->latest()->paginate(15);
        return view('workout-plans.index', compact('workoutPlans'));
    }

    public function create()
    {
        $members = Member::all();
        $trainers = Trainer::all();
        return view('workout-plans.create', compact('members', 'trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'trainer_id' => ['required', 'exists:trainers,id', new \App\Rules\BelongsToCurrentGym('trainers')],
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        WorkoutPlan::create($validated);
        return redirect()->route('workout-plans.index')->with('success', 'Workout plan created successfully.');
    }

    public function show(WorkoutPlan $workoutPlan)
    {
        $workoutPlan->load(['member', 'trainer']);
        return view('workout-plans.show', compact('workoutPlan'));
    }

    public function edit(WorkoutPlan $workoutPlan)
    {
        $members = Member::all();
        $trainers = Trainer::all();
        return view('workout-plans.edit', compact('workoutPlan', 'members', 'trainers'));
    }

    public function update(Request $request, WorkoutPlan $workoutPlan)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'trainer_id' => ['required', 'exists:trainers,id', new \App\Rules\BelongsToCurrentGym('trainers')],
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $workoutPlan->update($validated);
        return redirect()->route('workout-plans.index')->with('success', 'Workout plan updated successfully.');
    }

    public function destroy(WorkoutPlan $workoutPlan)
    {
        $workoutPlan->delete();
        return redirect()->route('workout-plans.index')->with('success', 'Workout plan deleted successfully.');
    }
}
