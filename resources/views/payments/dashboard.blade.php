@extends('layouts.app')

@section('content')
<div class="px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">Payments Dashboard</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg shadow text-center">
            <div class="text-sm text-gray-500">Total Payments</div>
            <div class="text-2xl font-bold mt-2">{{ $totalPayments }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow text-center">
            <div class="text-sm text-gray-500">Total Revenue</div>
            <div class="text-2xl font-bold mt-2">${{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow text-center">
            <div class="text-sm text-gray-500">Payments This Month</div>
            <div class="text-2xl font-bold mt-2">{{ $paymentsThisMonth }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow text-center">
            <div class="text-sm text-gray-500">Revenue This Month</div>
            <div class="text-2xl font-bold mt-2">${{ number_format($revenueThisMonth, 2) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-sm font-medium">Recent Payments</h3>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr>
                        <th class="py-2 px-3">Date</th>
                        <th class="py-2 px-3">Member</th>
                        <th class="py-2 px-3">Amount</th>
                        <th class="py-2 px-3">Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($recentPayments as $payment)
                    <tr>
                        <td class="py-2 px-3">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td class="py-2 px-3">{{ $payment->member->first_name }} {{ $payment->member->last_name }}</td>
                        <td class="py-2 px-3">${{ number_format($payment->amount, 2) }}</td>
                        <td class="py-2 px-3">{{ $payment->method }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
