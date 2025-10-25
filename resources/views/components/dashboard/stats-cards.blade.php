@props(['memberCount' => 0, 'trainerCount' => 0, 'activeSubscriptions' => 0, 'totalRevenue' => 0])

<div class="row">
    <!-- Members -->
    <div class="col-md-3">
        <a href="{{ route('members.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="View all members">
            <div class="card text-white bg-primary mb-3 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-3">Total Members</h5>
                            <h2 class="mb-0">{{ $memberCount }}</h2>
                            <div class="mt-3 view-details">
                                <small>View Details <i class="bi bi-arrow-right"></i></small>
                            </div>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded p-3">
                            <i class="bi bi-people-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Trainers -->
    <div class="col-md-3">
        <a href="{{ route('trainers.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="View all trainers">
            <div class="card text-white bg-success mb-3 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-3">Total Trainers</h5>
                            <h2 class="mb-0">{{ $trainerCount }}</h2>
                            <div class="mt-3 view-details">
                                <small>View Details <i class="bi bi-arrow-right"></i></small>
                            </div>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded p-3">
                            <i class="bi bi-person-workspace fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Active Subscriptions -->
    <div class="col-md-3">
        <a href="{{ route('subscriptions.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="View all subscriptions">
            <div class="card text-white bg-warning mb-3 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-3">Active Subscriptions</h5>
                            <h2 class="mb-0">{{ $activeSubscriptions }}</h2>
                            <div class="mt-3 view-details">
                                <small>View Details <i class="bi bi-arrow-right"></i></small>
                            </div>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded p-3">
                            <i class="bi bi-calendar-check-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Revenue -->
    <div class="col-md-3">
        <a href="{{ route('payments.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="View payment history">
            <div class="card text-white bg-danger mb-3 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-3">Total Revenue</h5>
                            <h2 class="mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                            <div class="mt-3 view-details">
                                <small>View Details <i class="bi bi-arrow-right"></i></small>
                            </div>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded p-3">
                            <i class="bi bi-cash-stack fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>