<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Subscription, Member};

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('member')->paginate(10);
        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $members = Member::all();
        return view('subscriptions.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'plan_name' => 'required|string|max:100',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string',
        ]);

        Subscription::create($validated);
        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function show(Subscription $subscription)
    {
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $members = Member::all();
        return view('subscriptions.edit', compact('subscription', 'members'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'plan_name' => 'required|string|max:100',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string',
        ]);

        $subscription->update($validated);
        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted.');
    }
}
