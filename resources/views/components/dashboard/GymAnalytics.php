<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use App\Models\Member;
use App\Models\Attendance;
use Carbon\Carbon;

class GymAnalytics extends Component
{
    public $totalMembers;
    public $monthlyAttendances;
    public $yearlyMembers;
    public $retentionRate;

    public function __construct()
    {
        $gymId = current_gym();

        // Total members
        $this->totalMembers = Member::where('gym_id', $gymId)->count();

        // Monthly attendance (last 12 months)
        $this->monthlyAttendances = Attendance::where('gym_id', $gymId)
            ->selectRaw('MONTH(check_in_time) as month, COUNT(*) as total')
            ->whereYear('check_in_time', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Yearly new members
        $this->yearlyMembers = Member::where('gym_id', $gymId)
            ->selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        // Retention rate (simple formula)
        $activeThisMonth = Attendance::where('gym_id', $gymId)
            ->whereMonth('check_in_time', now()->month)
            ->distinct('member_id')
            ->count('member_id');

        $this->retentionRate = $this->totalMembers > 0
            ? round(($activeThisMonth / $this->totalMembers) * 100, 2)
            : 0;
    }

    public function render()
    {
        return view('components.dashboard.gym-analytics');
    }
}