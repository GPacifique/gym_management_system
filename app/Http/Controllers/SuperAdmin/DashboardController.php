<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use App\Models\Member;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the super admin dashboard.
     */
    public function index(): View
    {
        // Get statistics
        $stats = [
            'total_gyms' => Gym::count(),
            'total_members' => Member::count(),
            'pending_gyms' => Gym::where('approval_status', 'pending')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
        ];

        // Get recent gyms
        $recentGyms = Gym::with('owner')
            ->latest()
            ->take(10)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentGyms'));
    }
}
