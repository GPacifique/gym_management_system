<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::paginate(10);
        return view('trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email',
            'phone' => 'nullable|string',
            'specialization' => 'nullable|string',
            'salary' => 'nullable|numeric',
        ]);

        Trainer::create($validated);

        return redirect()->route('trainers.index')->with('success', 'Trainer added successfully.');
    }

    public function show(Trainer $trainer)
    {
        return view('trainers.show', compact('trainer'));
    }

    public function edit(Trainer $trainer)
    {
        return view('trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:trainers,email,'.$trainer->id,
            'phone' => 'nullable|string',
            'specialization' => 'nullable|string',
            'salary' => 'nullable|numeric',
        ]);

        $trainer->update($validated);

        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully.');
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        return redirect()->route('trainers.index')->with('success', 'Trainer deleted.');
    }
}
