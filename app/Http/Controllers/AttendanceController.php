<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\GymClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::query()
            ->with(['member', 'class'])
            ->latest('check_in_time');

        // Apply filters
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('check_in_time', $date);
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Get data for dropdowns
        $members = Member::orderBy('first_name')->orderBy('last_name')->get();
        $classes = GymClass::orderBy('class_name')->get();

        return view('attendances.index', [
            'attendances' => $query->paginate(15),
            'members' => $members,
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created attendance record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'class_id' => ['nullable', 'exists:classes,id', new \App\Rules\BelongsToCurrentGym('classes')],
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if member has an active attendance
        $activeAttendance = Attendance::where('member_id', $request->member_id)
            ->whereNull('check_out_time')
            ->first();

        if ($activeAttendance) {
            return back()
                ->withErrors(['member_id' => 'This member already has an active attendance record.'])
                ->withInput();
        }

        // Create attendance record
        $attendance = new Attendance();
        $attendance->member_id = $validated['member_id'];
        $attendance->class_id = $validated['class_id'] ?? null;
        $attendance->notes = $validated['notes'] ?? null;
        $attendance->check_in_time = now();
        $attendance->save();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified attendance.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['member', 'class.trainer']);
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance.
     */
    public function edit(Attendance $attendance)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }
        $members = Member::orderBy('first_name')->orderBy('last_name')->get();
        $classes = GymClass::orderBy('class_name')->get();
        return view('attendances.edit', compact('attendance', 'members', 'classes'));
    }

    /**
     * Update the specified attendance in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', new \App\Rules\BelongsToCurrentGym('members')],
            'class_id' => ['nullable', 'exists:classes,id', new \App\Rules\BelongsToCurrentGym('classes')],
            'notes' => 'nullable|string|max:500',
            'check_in_time' => 'required|date',
            'check_out_time' => 'nullable|date|after_or_equal:check_in_time',
        ]);

        $attendance->update($validated);

        return redirect()
            ->route('attendances.show', $attendance)
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified attendance from storage.
     */
    public function destroy(Attendance $attendance)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'manager'])) {
            abort(403);
        }
        $attendance->delete();
        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    /**
     * Record checkout time for an attendance.
     */
    public function checkout(Attendance $attendance)
    {
        if ($attendance->check_out_time) {
            return back()->withErrors(['error' => 'This attendance record is already checked out.']);
        }

        $attendance->check_out_time = now();
        $attendance->save();

        return back()->with('success', 'Checkout recorded successfully.');
    }

    /**
     * Get today's attendance statistics.
     */
    public function getDailyStats()
    {
        $today = Carbon::today();
        
        return [
            'total_visits' => Attendance::whereDate('check_in_time', $today)->count(),
            'active_members' => Attendance::whereDate('check_in_time', $today)
                ->whereNull('check_out_time')
                ->count(),
            'completed_visits' => Attendance::whereDate('check_in_time', $today)
                ->whereNotNull('check_out_time')
                ->count(),
            'class_attendance' => Attendance::whereDate('check_in_time', $today)
                ->whereNotNull('class_id')
                ->count(),
        ];
    }

    /**
     * Display scan page for microchip/keyboard-wedge readers.
     */
    public function scanForm()
    {
        return view('attendances.scan');
    }

    /**
     * Handle scan submission (toggle check-in/check-out by chip_id).
     */
    public function scanCheck(Request $request)
    {
        $data = $request->validate([
            'chip_id' => 'required|string|max:191',
        ]);

        $member = Member::where('chip_id', $data['chip_id'])->first();
        if (!$member) {
            return back()->withErrors(['chip_id' => 'No member found for this chip ID'])->withInput();
        }

        // If there's an active attendance, check out; otherwise, check in
        $active = Attendance::where('member_id', $member->id)->whereNull('check_out_time')->first();
        if ($active) {
            $active->check_out_time = now();
            $active->save();
            return back()->with('success', 'Checked out: '.$member->name);
        }

        $attendance = new Attendance();
        $attendance->member_id = $member->id;
        $attendance->check_in_time = now();
        $attendance->save();

        return back()->with('success', 'Checked in: '.$member->name);
    }

    /**
     * Export attendance records.
     */
    public function export(Request $request)
    {
        $query = Attendance::with(['member', 'class']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('check_in_time', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $attendances = $query->get();

        return response()->streamDownload(function() use ($attendances) {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, [
                'Date',
                'Member Name',
                'Class',
                'Check-in Time',
                'Check-out Time',
                'Duration',
                'Notes'
            ]);

            // Data
            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->check_in_time->format('Y-m-d'),
                    $attendance->member->name,
                    $attendance->class ? $attendance->class->name : 'Regular Visit',
                    $attendance->check_in_time->format('H:i:s'),
                    $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : 'Not checked out',
                    $attendance->check_out_time ? $attendance->check_in_time->diffForHumans($attendance->check_out_time, true) : 'N/A',
                    $attendance->notes
                ]);
            }

            fclose($handle);
        }, 'attendance_records.csv');
    }
}
