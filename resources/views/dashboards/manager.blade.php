<x-app-layout>
    <div class="container-fluid mt-4">
        <!-- Gym Context Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">
                                    <i class="bi bi-building me-2"></i>
                                    {{ \App\Support\GymContext::current()?->name ?? 'Gym Manager Dashboard' }}
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ \App\Support\GymContext::current()?->address ?? 'No gym selected' }}
                                </p>
                            </div>
                            <div class="text-end">
                                <div class="badge bg-light text-primary fs-6 px-3 py-2">
                                    <i class="bi bi-person-badge me-1"></i>
                                    Manager
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-dashboard.header 
            :last-update="$lastUpdate ?? null"
            :is-stale="$isStale ?? false"
            :errors="$errors ?? []"
        />

        <x-dashboard.stats-cards 
            :member-count="$memberCount ?? 0"
            :trainer-count="$trainerCount ?? 0"
            :active-subscriptions="$activeSubscriptions ?? 0"
            :total-revenue="$totalRevenue ?? 0"
        />

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Revenue</div>
                            <div class="h4 mb-0">$ {{ number_format($todayRevenue ?? 0, 2) }}</div>
                        </div>
                        <div class="text-success display-6"><i class="bi bi-cash-stack"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Check-ins</div>
                            <div class="h4 mb-0">{{ $todayCheckIns ?? 0 }}</div>
                        </div>
                        <div class="text-primary display-6"><i class="bi bi-people"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions for Manager -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-lightning-charge-fill me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="{{ route('members.create') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-person-plus-fill d-block fs-3 mb-2"></i>
                                    <span>Add Member</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('classes.create') }}" class="btn btn-outline-success w-100 py-3">
                                    <i class="bi bi-calendar-plus-fill d-block fs-3 mb-2"></i>
                                    <span>Schedule Class</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('subscriptions.create') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="bi bi-credit-card-fill d-block fs-3 mb-2"></i>
                                    <span>New Subscription</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('workout-plans.create') }}" class="btn btn-outline-warning w-100 py-3">
                                    <i class="bi bi-clipboard-check-fill d-block fs-3 mb-2"></i>
                                    <span>Create Plan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Membership Growth</div>
                    <div class="card-body">
                        <canvas id="membersChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Monthly Revenue</div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Recent Members</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @php
                            $recentMembers = \App\Models\Member::latest()->take(5)->get();
                        @endphp
                        @forelse($recentMembers as $member)
                            <a href="{{ route('members.show', $member) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $member->first_name }} {{ $member->last_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                    <span class="badge bg-success">{{ $member->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="list-group-item text-center text-muted">No recent members</div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-primary">View All Members</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check-fill me-2"></i>Upcoming Classes</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @php
                            $upcomingClasses = \App\Models\GymClass::where('scheduled_at', '>', now())
                                ->orderBy('scheduled_at')
                                ->take(5)
                                ->with('trainer')
                                ->get();
                        @endphp
                        @forelse($upcomingClasses as $class)
                            <a href="{{ route('classes.show', $class) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $class->class_name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>{{ $class->trainer?->name ?? 'No trainer' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-primary">{{ $class->scheduled_at->format('M d') }}</div>
                                        <small class="text-muted">{{ $class->scheduled_at->format('h:i A') }}</small>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="list-group-item text-center text-muted">No upcoming classes</div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('classes.index') }}" class="btn btn-sm btn-outline-success">View All Classes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const mctx = document.getElementById('membersChart');
        const rctx = document.getElementById('revenueChart');
        if (mctx) new Chart(mctx, {
            type: 'line',
            data: { labels: @json($chartLabels ?? []), datasets: [{ label: 'New Members', data: @json($membersChartData ?? []), borderColor: '#0d6efd', tension: 0.3 }] }
        });
        if (rctx) new Chart(rctx, {
            type: 'bar',
            data: { labels: @json($chartLabels ?? []), datasets: [{ label: 'Revenue ($)', data: @json($revenueChartData ?? []), backgroundColor: '#dc3545' }] }
        });

        // Refresh support (per-role)
        const refreshBtn = document.getElementById('refreshDashboard');
        if (refreshBtn && mctx && rctx) {
            const membersChart = Chart.getChart(mctx);
            const revenueChart = Chart.getChart(rctx);
            refreshBtn.addEventListener('click', async function(){
                const btn = this; const icon = btn.querySelector('i'); btn.disabled = true; if(icon) icon.classList.add('spin');
                try {
                    const res = await fetch('{{ route('dashboard.refresh') }}', { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    membersChart.data.labels = data.chart.labels || [];
                    membersChart.data.datasets[0].data = data.chart.members || [];
                    membersChart.update();
                    revenueChart.data.labels = data.chart.labels || [];
                    revenueChart.data.datasets[0].data = data.chart.revenue || [];
                    revenueChart.update();
                } catch(e) { window.location.reload(); }
                finally { btn.disabled = false; if(icon) icon.classList.remove('spin'); }
            });
        }
    </script>
</x-app-layout>
