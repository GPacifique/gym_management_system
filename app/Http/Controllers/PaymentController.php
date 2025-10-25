<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Payment, Member, Subscription};

class PaymentController extends Controller
{
    public function dashboard()
    {
        $totalPayments = Payment::count();
        $totalRevenue = Payment::sum('amount');
        $paymentsThisMonth = Payment::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->count();
        $revenueThisMonth = Payment::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount');
        $recentPayments = Payment::with('member')->orderByDesc('payment_date')->take(10)->get();
        return view('payments.dashboard', compact('totalPayments', 'totalRevenue', 'paymentsThisMonth', 'revenueThisMonth', 'recentPayments'));
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $payments = Payment::with(['member', 'subscription'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('method', 'like', "%{$q}%")
                        ->orWhere('amount', 'like', "%{$q}%")
                        ->orWhere('payment_date', 'like', "%{$q}%")
                        ->orWhereHas('member', function ($mq) use ($q) {
                            $mq->where('first_name', 'like', "%{$q}%")
                               ->orWhere('last_name', 'like', "%{$q}%")
                               ->orWhere('email', 'like', "%{$q}%");
                        })
                        ->orWhereHas('subscription', function ($sq) use ($q) {
                            $sq->where('plan_name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('payment_date')
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $members = Member::all();
        $subscriptions = Subscription::all();
        return view('payments.create', compact('members', 'subscriptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'subscription_id' => ['nullable', 'exists:subscriptions,id', new \App\Rules\BelongsToCurrentGym('subscriptions')],
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        Payment::create($validated);
        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }
        $members = Member::all();
        $subscriptions = Subscription::all();
        return view('payments.edit', compact('payment', 'members', 'subscriptions'));
    }

    public function update(Request $request, Payment $payment)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'subscription_id' => ['nullable', 'exists:subscriptions,id', new \App\Rules\BelongsToCurrentGym('subscriptions')],
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        $payment->update($validated);
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted.');
    }
}
