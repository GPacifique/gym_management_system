@extends('layouts.app')

@section('content')
<div class="px-4 py-6">
    {{-- Super Admin Header --}}
    <div class="rounded-lg bg-red-50 border border-red-100 p-4 mb-6 flex items-start gap-4">
        <div class="text-red-600 text-3xl">
            <i class="bi bi-shield-fill-check"></i>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Super Admin - Platform Manager</h3>
            <p class="text-sm text-gray-600">You have platform-wide access to all gym accounts and system settings.</p>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Super Admin Dashboard</h1>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-100 text-indigo-600 p-3 rounded">
                    <i class="bi bi-building text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Gyms</div>
                    <div class="text-xl font-bold">{{ $stats['total_gyms'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 text-green-600 p-3 rounded">
                    <i class="bi bi-people text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Members</div>
                    <div class="text-xl font-bold">{{ $stats['total_members'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded">
                    <i class="bi bi-clock-history text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Pending Approvals</div>
                    <div class="text-xl font-bold">{{ $stats['pending_gyms'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="bg-sky-100 text-sky-600 p-3 rounded">
                    <i class="bi bi-currency-dollar text-xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Revenue</div>
                    <div class="text-xl font-bold">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-6 bg-white rounded-lg shadow text-center">
            <div class="inline-flex items-center justify-center p-3 bg-indigo-100 text-indigo-600 rounded-full mb-3">
                <i class="bi bi-building text-2xl"></i>
            </div>
            <h4 class="font-semibold">Manage Gyms</h4>
            <p class="text-sm text-gray-500">View, approve, and manage all gym accounts</p>
            <a href="{{ route('super-admin.gyms.index') }}" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md">Go to Gyms</a>
        </div>

        <div class="p-6 bg-white rounded-lg shadow text-center">
            <div class="inline-flex items-center justify-center p-3 bg-yellow-100 text-yellow-600 rounded-full mb-3">
                <i class="bi bi-clock-history text-2xl"></i>
            </div>
            <h4 class="font-semibold">Pending Approvals</h4>
            <p class="text-sm text-gray-500">Review and approve gym registrations</p>
            <a href="{{ route('super-admin.gyms.index', ['status' => 'pending']) }}" class="mt-3 inline-block px-4 py-2 bg-yellow-600 text-white rounded-md">View Pending</a>
        </div>

        <div class="p-6 bg-white rounded-lg shadow text-center">
            <div class="inline-flex items-center justify-center p-3 bg-green-100 text-green-600 rounded-full mb-3">
                <i class="bi bi-download text-2xl"></i>
            </div>
            <h4 class="font-semibold">Export Data</h4>
            <p class="text-sm text-gray-500">Download gym data for analysis</p>
            <a href="{{ route('super-admin.gyms.export') }}" class="mt-3 inline-block px-4 py-2 bg-green-600 text-white rounded-md">Export CSV</a>
        </div>
    </div>

    <!-- Recent Gyms -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h5 class="text-lg font-medium">Recent Gym Registrations</h5>
        </div>
        <div class="p-4">
            @if($recentGyms->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y">
                        <thead>
                            <tr class="text-left text-sm text-gray-500">
                                <th class="py-2 px-3">Gym Name</th>
                                <th class="py-2 px-3">Owner</th>
                                <th class="py-2 px-3">Contact</th>
                                <th class="py-2 px-3">Registered</th>
                                <th class="py-2 px-3">Status</th>
                                <th class="py-2 px-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($recentGyms as $gym)
                                <tr class="text-sm">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-3">
                                            @if($gym->logo)
                                                <img src="{{ Storage::url($gym->logo) }}" alt="{{ $gym->name }}" class="w-10 h-10 rounded object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center text-gray-400">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                            @endif
                                            <div class="font-medium">{{ $gym->name }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">{{ $gym->owner->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-3">
                                        <div>{{ $gym->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $gym->phone }}</div>
                                    </td>
                                    <td class="py-3 px-3">{{ $gym->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-3">
                                        @if($gym->approval_status === 'approved')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800">Approved</span>
                                        @elseif($gym->approval_status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif($gym->approval_status === 'rejected')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">Suspended</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="{{ route('super-admin.gyms.show', $gym) }}" class="inline-flex items-center gap-2 px-3 py-1 border rounded text-sm">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No recent gym registrations.</p>
            @endif
        </div>
    </div>
</div>
@endsection
