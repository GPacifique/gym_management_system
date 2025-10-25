<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\Attendance;


class DashboardController extends Controller
{
    private function validateData($data)
    {
        return [
            'isValid' => $data >= 0,
            'message' => $data < 0 ? 'Invalid negative value detected' : null
        ];
    }

    public function index(Request $request)
    {
        $errors = [];
        $role = auth()->check() ? auth()->user()->role : 'member';
        $stats = [
            'memberCount' => 0,
            'trainerCount' => 0,
            'activeSubscriptions' => 0,
            'totalRevenue' => 0,
            'todayRevenue' => 0,
            'todayCheckIns' => 0,
        ];
        $chart = [
            'labels' => [],
            'members' => [],
            'revenue' => [],
        ];

        try {

            // Database connection check
            try {
                \DB::connection()->getPdo();
            } catch (\Exception $e) {
                throw new \Exception('Database connection failed. Please try again later.');
            }

            // Members count with validation
            $stats['memberCount'] = Member::query()->count();
            $validation = $this->validateData($stats['memberCount']);
            if (!$validation['isValid']) {
                $errors[] = 'Member count: ' . $validation['message'];
            }

            // Trainers count with validation
            $stats['trainerCount'] = Trainer::query()->count();
            $validation = $this->validateData($stats['trainerCount']);
            if (!$validation['isValid']) {
                $errors[] = 'Trainer count: ' . $validation['message'];
            }

            // Active subscriptions with date validation
            $stats['activeSubscriptions'] = Subscription::query()
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                })
                ->count();
            $validation = $this->validateData($stats['activeSubscriptions']);
            if (!$validation['isValid']) {
                $errors[] = 'Subscription count: ' . $validation['message'];
            }

            // Revenue with validation
            $stats['totalRevenue'] = Payment::query()
                ->whereNotNull('payment_date')
                ->where('amount', '>', 0)
                ->sum('amount');
            $validation = $this->validateData($stats['totalRevenue']);
            if (!$validation['isValid']) {
                $errors[] = 'Revenue calculation: ' . $validation['message'];
            }

            // Today's revenue and check-ins
            $stats['todayRevenue'] = (float) Payment::whereDate('payment_date', now()->toDateString())
                ->sum('amount');
            $stats['todayCheckIns'] = (int) Attendance::whereDate('check_in_time', now()->toDateString())
                ->count();

            // Build last 6 months labels and datasets
            $months = collect(range(5, 0))->map(function ($i) {
                $start = now()->copy()->subMonths($i)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                return [
                    'label' => $start->format('M'),
                    'year' => (int) $start->format('Y'),
                    'month' => (int) $start->format('m'),
                    'start' => $start,
                    'end' => $end,
                ];
            });

            $chart['labels'] = $months->pluck('label')->all();
            // New members per month (created_at)
            $chart['members'] = $months->map(function ($m) {
                return Member::whereYear('created_at', $m['year'])
                    ->whereMonth('created_at', $m['month'])
                    ->count();
            })->all();
            // Revenue per month (payment_date)
            $chart['revenue'] = $months->map(function ($m) {
                return (float) Payment::whereYear('payment_date', $m['year'])
                    ->whereMonth('payment_date', $m['month'])
                    ->sum('amount');
            })->all();

            // Cache the stats and charts for 5 minutes
            \Cache::put('dashboard_stats_'.$role, $stats, now()->addMinutes(5));
            \Cache::put('dashboard_chart_'.$role, $chart, now()->addMinutes(5));
            \Cache::put('dashboard_last_update_'.$role, now(), now()->addMinutes(5));

            // Debug logging
            \Log::info("Dashboard Stats Refreshed", [
                'stats' => $stats,
                'errors' => $errors,
                'timestamp' => now()->toDateTimeString()
            ]);

            // JSON response for refresh endpoints
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'stats' => $stats,
                    'chart' => $chart,
                    'hasError' => !empty($errors),
                    'errors' => $errors,
                    'lastUpdate' => now()->toDateTimeString(),
                    'isStale' => false,
                    'role' => $role,
                ]);
            }

            $view = match ($role) {
                'admin' => 'dashboards.admin',
                'manager' => 'dashboards.manager',
                'receptionist' => 'dashboards.receptionist',
                'trainer' => 'dashboards.trainer',
                'member' => 'dashboards.member',
                default => 'dashboard',
            };

            \Log::info('Dashboard view selected', [
                'role' => $role,
                'view' => view()->exists($view) ? $view : 'dashboard',
            ]);

            return view(view()->exists($view) ? $view : 'dashboard', [
                ...$stats,
                'chartLabels' => $chart['labels'],
                'membersChartData' => $chart['members'],
                'revenueChartData' => $chart['revenue'],
                'hasError' => !empty($errors),
                'errors' => $errors,
                'lastUpdate' => now()->toDateTimeString(),
                'isStale' => false,
                'role' => $role,
            ]);

        } catch (\Exception $e) {
            \Log::error("Dashboard Error", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Try to get cached data if available
            $cachedStats = \Cache::get('dashboard_stats_'.$role);
            $cachedChart = \Cache::get('dashboard_chart_'.$role);
            $lastUpdate = \Cache::get('dashboard_last_update_'.$role);
            
            if ($cachedStats) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'stats' => $cachedStats,
                        'chart' => $cachedChart,
                        'hasError' => true,
                        'errors' => [$e->getMessage()],
                        'lastUpdate' => optional($lastUpdate)->toDateTimeString(),
                        'isStale' => true,
                        'errorMessage' => $lastUpdate ? ('Showing cached data from ' . $lastUpdate->diffForHumans()) : null,
                        'role' => $role,
                    ]);
                }

                $view = match ($role) {
                    'admin' => 'dashboards.admin',
                    'manager' => 'dashboards.manager',
                    'receptionist' => 'dashboards.receptionist',
                    'trainer' => 'dashboards.trainer',
                    'member' => 'dashboards.member',
                    default => 'dashboard',
                };

                \Log::info('Dashboard view selected (cached after error)', [
                    'role' => $role,
                    'view' => view()->exists($view) ? $view : 'dashboard',
                    'error' => $e->getMessage(),
                ]);

                return view(view()->exists($view) ? $view : 'dashboard', [
                    ...$cachedStats,
                    'chartLabels' => $cachedChart['labels'] ?? [],
                    'membersChartData' => $cachedChart['members'] ?? [],
                    'revenueChartData' => $cachedChart['revenue'] ?? [],
                    'hasError' => true,
                    'errors' => [$e->getMessage()],
                    'lastUpdate' => optional($lastUpdate)->toDateTimeString(),
                    'isStale' => true,
                    'errorMessage' => $lastUpdate ? ('Showing cached data from ' . $lastUpdate->diffForHumans()) : null,
                    'role' => $role,
                ]);
            }

            return view('dashboard', [
                'memberCount' => 0,
                'trainerCount' => 0,
                'activeSubscriptions' => 0,
                'totalRevenue' => 0,
                'todayRevenue' => 0,
                'todayCheckIns' => 0,
                'chartLabels' => [],
                'membersChartData' => [],
                'revenueChartData' => [],
                'hasError' => true,
                'errors' => [config('app.debug') ? $e->getMessage() : 'Error loading dashboard data.'],
                'lastUpdate' => null,
                'isStale' => false,
                'role' => $role,
            ]);
        }
}
}