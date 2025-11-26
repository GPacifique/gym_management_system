@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full bg-white shadow rounded-lg overflow-hidden">
        <div class="p-8 text-center">
            <div class="flex items-center justify-center mb-6">
                <span class="inline-flex items-center justify-center p-4 rounded-full bg-red-100 text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6v3a4 4 0 004 4h4a4 4 0 004-4V8a6 6 0 00-6-6zM8 9a2 2 0 114 0v1a2 2 0 11-4 0V9z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>

            <h1 class="text-5xl font-extrabold text-red-600 mb-2">403</h1>
            <p class="text-xl font-semibold text-gray-700 mb-4">Access Denied</p>

            <div class="prose prose-sm text-left max-w-none text-gray-600 mb-6">
                @if(request()->is('members*'))
                    <p><strong>You attempted to access:</strong> Member Management</p>
                @elseif(request()->is('subscriptions*'))
                    <p><strong>You attempted to access:</strong> Subscription Management</p>
                @elseif(request()->is('classes*'))
                    <p><strong>You attempted to access:</strong> Class Management</p>
                @elseif(request()->is('trainers*'))
                    <p><strong>You attempted to access:</strong> Trainer Management</p>
                @elseif(request()->is('workout-plans*'))
                    <p><strong>You attempted to access:</strong> Workout Plans</p>
                @elseif(request()->is('admin*'))
                    <p><strong>You attempted to access:</strong> Admin Panel</p>
                @elseif(request()->is('gyms*'))
                    <p><strong>You attempted to access:</strong> Gym Management</p>
                @elseif(request()->is('payments/*/edit'))
                    <p><strong>You attempted to:</strong> Edit a Payment</p>
                @elseif(request()->is('attendances/*/edit'))
                    <p><strong>You attempted to:</strong> Edit an Attendance Record</p>
                @endif

                <p>Sorry — you don't have permission to access this resource.</p>

                @auth
                    <p>Your current role <strong>{{ ucfirst(Auth::user()->role ?? 'user') }}</strong> does not have the required permissions.</p>

                    @if(optional(Auth::user())->role === 'receptionist')
                        <div class="mt-3">
                            <p class="font-medium text-gray-800">As a <strong>Receptionist</strong>, you can:</p>
                            <ul class="list-disc pl-5 mt-2 text-gray-600">
                                <li>View and record payments</li>
                                <li>Track attendance (check-in/check-out)</li>
                                <li>Export attendance reports</li>
                            </ul>
                            <p class="text-sm text-gray-500 mt-2">You cannot edit members, subscriptions, or delete records.</p>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <i class="bi bi-speedometer2 mr-2" aria-hidden="true"></i> Dashboard
                    </a>

                    @if(optional(Auth::user())->role === 'receptionist')
                        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="bi bi-cash mr-2" aria-hidden="true"></i> Payments
                        </a>
                        <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700">
                            <i class="bi bi-calendar-check mr-2" aria-hidden="true"></i> Attendance
                        </a>
                    @endif

                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-house mr-2" aria-hidden="true"></i> Home
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        <i class="bi bi-box-arrow-in-right mr-2" aria-hidden="true"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="bi bi-person-plus mr-2" aria-hidden="true"></i> Register
                    </a>
                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-house mr-2" aria-hidden="true"></i> Home
                    </a>
                @endauth

                <a href="javascript:history.back()" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-arrow-left mr-2" aria-hidden="true"></i> Go Back
                </a>
            </div>

            @auth
                <div class="mt-6 bg-gray-50 p-4 rounded-md text-left text-sm text-gray-600">
                    <h3 class="text-sm font-medium text-gray-800">Need access?</h3>
                    <p class="mt-1">Contact your system administrator to request permissions for your role, or open a support ticket describing what you need to access.</p>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
