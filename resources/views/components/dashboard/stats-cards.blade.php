@props(['memberCount' => 0, 'trainerCount' => 0, 'activeSubscriptions' => 0, 'totalRevenue' => 0, 'currency' => 'RWF'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Members -->
    <a href="{{ route('members.index') }}" title="View all members" class="block">
        <div class="p-4 rounded-lg bg-indigo-600 text-white shadow stat-card hover:shadow-lg transition-transform transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Total Members</div>
                    <div class="text-2xl font-bold mt-2">{{ $memberCount }}</div>
                    <div class="text-xs mt-2 opacity-80">View Details →</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-people-fill text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Trainers -->
    <a href="{{ route('trainers.index') }}" title="View all trainers" class="block">
        <div class="p-4 rounded-lg bg-green-600 text-white shadow stat-card hover:shadow-lg transition-transform transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Total Trainers</div>
                    <div class="text-2xl font-bold mt-2">{{ $trainerCount }}</div>
                    <div class="text-xs mt-2 opacity-80">View Details →</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-person-workspace text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Active Subscriptions -->
    <a href="{{ route('subscriptions.index') }}" title="View all subscriptions" class="block">
        <div class="p-4 rounded-lg bg-yellow-500 text-white shadow stat-card hover:shadow-lg transition-transform transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Active Subscriptions</div>
                    <div class="text-2xl font-bold mt-2">{{ $activeSubscriptions }}</div>
                    <div class="text-xs mt-2 opacity-80">View Details →</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-calendar-check-fill text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Revenue -->
    <a href="{{ route('payments.index') }}" title="View payment history" class="block">
        <div class="p-4 rounded-lg bg-red-600 text-white shadow stat-card hover:shadow-lg transition-transform transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium opacity-90">Total Revenue</div>
                    <div class="text-2xl font-bold mt-2">{{ $currency }} {{ number_format($totalRevenue, 2) }}</div>
                    <div class="text-xs mt-2 opacity-80">View Details →</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-cash-stack text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>
