<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the super admin dashboard.
     */
    public function index(): View
    {
        // Get statistics
        // Compute total revenue safely: some environments/migrations may not have a 'status' column.
        if (Schema::hasColumn('payments', 'status')) {
            $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        } else {
            // Fallback: sum all payments that have a payment_date (assumed completed)
            $totalRevenue = Payment::whereNotNull('payment_date')->sum('amount');
        }

        $stats = [
            'total_gyms' => Gym::count(),
            'total_members' => Member::count(),
            'pending_gyms' => Gym::where('approval_status', 'pending')->count(),
            'total_revenue' => $totalRevenue,
        ];

        // Get recent gyms
        $recentGyms = Gym::with('owner')
            ->latest()
            ->take(10)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentGyms'));
    }
}
