<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Attendance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $gymId = current_gym();

        // Ensure tenant context
        if (!$gymId && !$user->isSuperAdmin()) {
            abort(403, 'No gym selected');
        }

        // Resolve role (multi-tenant aware)
        $role = $user->isSuperAdmin()
            ? 'super_admin'
            : $user->roleInGym($gymId);

        // Cache key (tenant-safe)
        $cacheKey = "dashboard_{$gymId}_{$role}";

        try {

            // Load from cache if available
            if (Cache::has($cacheKey . '_stats') && Cache::has($cacheKey . '_chart')) {
                $stats = Cache::get($cacheKey . '_stats');
                $chart = Cache::get($cacheKey . '_chart');
                $lastUpdate = Cache::get($cacheKey . '_last_update');

                return $this->response($request, $stats, $chart, $role, $lastUpdate, false);
            }

            // =========================
            // STATS (TENANT FILTERED)
            // =========================

            $stats = [
                'memberCount' => Member::where('gym_id', $gymId)->count(),
                'trainerCount' => Trainer::where('gym_id', $gymId)->count(),
                'activeSubscriptions' => Subscription::where('gym_id', $gymId)
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->count(),
                'totalRevenue' => Payment::where('gym_id', $gymId)
                    ->where('amount', '>', 0)
                    ->sum('amount'),
                'todayRevenue' => Payment::where('gym_id', $gymId)
                    ->whereDate('payment_date', now())
                    ->sum('amount'),
                'todayCheckIns' => Attendance::where('gym_id', $gymId)
                    ->whereDate('check_in_time', now())
                    ->count(),
            ];

            // =========================
            // CHART DATA (LAST 6 MONTHS)
            // =========================

            $months = collect(range(5, 0))->map(function ($i) {
                $date = now()->subMonths($i);
                return [
                    'month' => $date->format('m'),
                    'year' => $date->format('Y'),
                    'label' => $date->format('M'),
                ];
            });

            $chart = [
                'labels' => $months->pluck('label')->toArray(),
                'members' => [],
                'revenue' => [],
            ];

            foreach ($months as $m) {
                $chart['members'][] = Member::where('gym_id', $gymId)
                    ->whereYear('created_at', $m['year'])
                    ->whereMonth('created_at', $m['month'])
                    ->count();

                $chart['revenue'][] = Payment::where('gym_id', $gymId)
                    ->whereYear('payment_date', $m['year'])
                    ->whereMonth('payment_date', $m['month'])
                    ->sum('amount');
            }

            // Cache results
            Cache::put($cacheKey . '_stats', $stats, now()->addMinutes(5));
            Cache::put($cacheKey . '_chart', $chart, now()->addMinutes(5));
            Cache::put($cacheKey . '_last_update', now(), now()->addMinutes(5));

            Log::info('Dashboard loaded', [
                'gym_id' => $gymId,
                'role' => $role,
                'stats' => $stats
            ]);

            return $this->response($request, $stats, $chart, $role, now(), false);

        } catch (\Exception $e) {

            Log::error('Dashboard error', [
                'message' => $e->getMessage()
            ]);

            // Fallback to cache
            $stats = Cache::get($cacheKey . '_stats', []);
            $chart = Cache::get($cacheKey . '_chart', []);
            $lastUpdate = Cache::get($cacheKey . '_last_update');

            return $this->response(
                $request,
                $stats,
                $chart,
                $role,
                $lastUpdate,
                true,
                $e->getMessage()
            );
        }
    }

    private function response($request, $stats, $chart, $role, $lastUpdate, $isStale = false, $error = null)
    {
        // JSON (API / AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'stats' => $stats,
                'chart' => $chart,
                'role' => $role,
                'lastUpdate' => optional($lastUpdate)->toDateTimeString(),
                'isStale' => $isStale,
                'error' => $error,
            ]);
        }

        // Role-based views
        $view = match ($role) {
            'super_admin' => 'dashboards.super_admin',
            'admin' => 'dashboards.admin',
            'manager' => 'dashboards.manager',
            'receptionist' => 'dashboards.receptionist',
            'trainer' => 'dashboards.trainer',
            'member' => 'dashboards.member',
            default => 'dashboard',
        };

        return view(view()->exists($view) ? $view : 'dashboard', [
            ...$stats,
            'chartLabels' => $chart['labels'] ?? [],
            'membersChartData' => $chart['members'] ?? [],
            'revenueChartData' => $chart['revenue'] ?? [],
            'role' => $role,
            'lastUpdate' => optional($lastUpdate)->toDateTimeString(),
            'isStale' => $isStale,
            'error' => $error,
        ]);
    }
}