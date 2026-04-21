<x-app-layout>
    <div class="container-fluid py-4">

        <!-- 🔥 HERO / GYM HEADER -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1">
                            {{ \App\Support\GymContext::current()?->name ?? 'Gym Manager Dashboard' }}
                        </h3>
                        <p class="mb-0 opacity-75">
                            <i class="bi bi-geo-alt me-1"></i>
                            {{ \App\Support\GymContext::current()?->address ?? 'No gym selected' }}
                        </p>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-white text-dark px-3 py-2">
                            <i class="bi bi-person-badge me-1"></i>
                            Manager
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔢 STATS -->
        <div class="row g-3">
            <x-dashboard.stats-cards 
                :member-count="$memberCount ?? 0"
                :trainer-count="$trainerCount ?? 0"
                :active-subscriptions="$activeSubscriptions ?? 0"
                :total-revenue="$totalRevenue ?? 0"
            />
        </div>

        <!-- 💰 DAILY METRICS -->
        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Today's Revenue</small>
                            <h4 class="fw-bold mb-0 text-success">
                                RWF {{ number_format($todayRevenue ?? 0, 2) }}
                            </h4>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="bi bi-cash-stack fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Today's Check-ins</small>
                            <h4 class="fw-bold mb-0 text-primary">
                                {{ $todayCheckIns ?? 0 }}
                            </h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ⚡ QUICK ACTIONS -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Quick Actions
                </h5>

                <div class="row g-3">
                    @php
                        $actions = [
                            ['route'=>'members.create','icon'=>'bi-person-plus','text'=>'Add Member','color'=>'primary'],
                            ['route'=>'classes.create','icon'=>'bi-calendar-plus','text'=>'Schedule Class','color'=>'success'],
                            ['route'=>'subscriptions.create','icon'=>'bi-credit-card','text'=>'New Subscription','color'=>'info'],
                            ['route'=>'workout-plans.create','icon'=>'bi-clipboard-check','text'=>'Create Plan','color'=>'warning'],
                        ];
                    @endphp

                    @foreach($actions as $action)
                    <div class="col-md-3">
                        <a href="{{ route($action['route']) }}"
                           class="card border-0 shadow-sm h-100 text-center p-3 action-card">
                            <div class="mb-2 text-{{ $action['color'] }}">
                                <i class="bi {{ $action['icon'] }} fs-2"></i>
                            </div>
                            <div class="fw-semibold">{{ $action['text'] }}</div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 📊 CHARTS -->
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Membership Growth</h6>
                        <canvas id="membersChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Monthly Revenue</h6>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mt-3">  
        <x-dashboard.gym-analytics />
        </div>
        <!-- 📋 ACTIVITY -->
        <div class="row g-3 mt-3">
            <!-- Members -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Recent Members</h6>

                        @foreach(\App\Models\Member::latest()->take(5)->get() as $member)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar bg-primary text-white">
                                    {{ strtoupper(substr($member->first_name,0,1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $member->first_name }} {{ $member->last_name }}</div>
                                    <small class="text-muted">{{ $member->email }}</small>
                                </div>
                            </div>
                            <small class="text-muted">{{ $member->created_at->diffForHumans() }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Classes -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">Upcoming Classes</h6>

                        @foreach(\App\Models\GymClass::where('scheduled_at','>',now())->take(5)->get() as $class)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">{{ $class->class_name }}</div>
                                <small class="text-muted">{{ $class->trainer?->name }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold text-primary">{{ $class->scheduled_at->format('M d') }}</div>
                                <small class="text-muted">{{ $class->scheduled_at->format('h:i A') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('membersChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels ?? []),
                datasets: [{
                    label: 'Members',
                    data: @json($membersChartData ?? []),
                    borderColor: '#4f46e5',
                    tension: 0.4
                }]
            }
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabels ?? []),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenueChartData ?? []),
                    backgroundColor: '#22c55e'
                }]
            }
        });
    </script>

    <style>
        .action-card {
            transition: all 0.2s ease;
        }
        .action-card:hover {
            transform: translateY(-4px);
        }
        .avatar {
            width: 35px;
            height: 35px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            font-size:14px;
        }
    </style>
    <x-footer />
</x-app-layout>