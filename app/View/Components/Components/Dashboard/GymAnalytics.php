<?php
namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use App\Models\Member;
use App\Models\Attendance;

class GymAnalytics extends Component
{
    public $totalMembers;
    public $retentionRate;
    public $yearlyMembers;

    public function __construct()
    {
        $gymId = current_gym();

        $this->totalMembers = Member::where('gym_id', $gymId)->count();

        $activeMembers = Attendance::where('gym_id', $gymId)
            ->whereMonth('check_in_time', now()->month)
            ->distinct('member_id')
            ->count('member_id');

        $this->retentionRate = $this->totalMembers > 0
            ? round(($activeMembers / $this->totalMembers) * 100, 2)
            : 0;

        $this->yearlyMembers = Member::where('gym_id', $gymId)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function render()
    {
        return view('components.dashboard.gym-analytics');
    }
}