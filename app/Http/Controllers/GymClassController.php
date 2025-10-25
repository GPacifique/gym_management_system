<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{GymClass, Trainer};

class GymClassController extends Controller
{
    public function index()
    {
        $classes = GymClass::with('trainer')->orderBy('scheduled_at', 'desc')->paginate(15);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $trainers = Trainer::all();
        return view('classes.create', compact('trainers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'trainer_id' => ['required', 'exists:trainers,id', new \App\Rules\BelongsToCurrentGym('trainers')],
            'location' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        GymClass::create($validated);
        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    public function show(GymClass $class)
    {
        $class->load(['trainer', 'attendances.member']);
        return view('classes.show', compact('class'));
    }

    public function edit(GymClass $class)
    {
        $trainers = Trainer::all();
        return view('classes.edit', compact('class', 'trainers'));
    }

    public function update(Request $request, GymClass $class)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'trainer_id' => ['required', 'exists:trainers,id', new \App\Rules\BelongsToCurrentGym('trainers')],
            'location' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        $class->update($validated);
        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(GymClass $class)
    {
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
