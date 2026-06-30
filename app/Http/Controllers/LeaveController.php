<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Project;
use App\Models\Notification;

class LeaveController extends Controller
{
    // Lecturer — Apply Leave form
    public function applyForm()
    {
        $user = Auth::user();

        // Leave balance
        $usedDays = LeaveRequest::where('lecturer_id', $user->id)
            ->where('leave_type', 'annual')
            ->where('status', 'approved')
            ->whereYear('start_date', date('Y'))
            ->get()
            ->sum(fn($l) => \Carbon\Carbon::parse($l->start_date)
                ->diffInDays($l->end_date) + 1);

        $totalDays     = 20;
        $remainingDays = $totalDays - $usedDays;

        $myLeaves = LeaveRequest::where('lecturer_id', $user->id)
            ->latest()->take(5)->get();

        return view('leave.apply', compact(
            'usedDays', 'totalDays', 'remainingDays', 'myLeaves'
        ));
    }

    // Lecturer — Submit Leave
    public function apply(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:annual,sick,emergency',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string',
        ]);

        // Check overlapping
        $overlap = LeaveRequest::where('lecturer_id', Auth::id())
            ->where('status', '!=', 'rejected')
            ->where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($overlap) {
            return back()->with('error',
                'You already have a leave request overlapping these dates.');
        }

        $days = \Carbon\Carbon::parse($request->start_date)
                    ->diffInDays($request->end_date) + 1;

        LeaveRequest::create([
            'lecturer_id' => Auth::id(),
            'leave_type'  => $request->leave_type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        // Notify HOD
        $hod = User::where('user_type', 'hod')->first();
        Notification::create([
            'user_id' => $hod->id,
            'message' => Auth::user()->name
                         . ' submitted a ' . $request->leave_type
                         . ' leave request for ' . $days . ' day(s).',
        ]);

        return back()->with('success',
            'Leave request submitted! Waiting for HOD approval.');
    }

    // Lecturer — My Leaves
    public function myLeaves()
    {
        $leaves = LeaveRequest::where('lecturer_id', Auth::id())
            ->latest()->get();
        return view('leave.my_leaves', compact('leaves'));
    }

    // HOD — Manage Leaves
    public function manage()
    {
        $leaves = LeaveRequest::with('lecturer')
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 ELSE 2 END")
            ->latest()->get();

        $lecturers = User::where('user_type', 'lecturer')->get();

        return view('leave.manage', compact('leaves', 'lecturers'));
    }

    // HOD — Approve/Reject
    public function handle(Request $request, $id)
    {
        $leave   = LeaveRequest::with('lecturer')->findOrFail($id);
        $action  = $request->action;

        if ($action === 'approve') {
            $leave->update(['status' => 'approved']);

            // Find affected active projects
            $affected = Project::where('supervisor_id', $leave->lecturer_id)
                ->where('status', '!=', 'completed')
                ->get();

            // Assign temp supervisor if selected
            $tempSup = $request->temp_supervisor_id;
            if ($tempSup && $affected->count() > 0) {
                $tempName = User::find($tempSup)?->name;
                foreach ($affected as $proj) {
                    $proj->update(['temp_supervisor_id' => $tempSup]);

                    // Notify student
                    Notification::create([
                        'user_id' => $proj->student_id,
                        'message' => 'Your supervisor '
                                     . $leave->lecturer->name
                                     . ' is on leave from '
                                     . $leave->start_date . ' to '
                                     . $leave->end_date
                                     . '. Temporary supervisor: '
                                     . $tempName,
                    ]);
                }

                // Notify temp supervisor
                Notification::create([
                    'user_id' => $tempSup,
                    'message' => 'You have been assigned as temporary '
                                 . 'supervisor for ' . $affected->count()
                                 . ' project(s) while '
                                 . $leave->lecturer->name . ' is on leave.',
                ]);
            }

            // Notify lecturer
            Notification::create([
                'user_id' => $leave->lecturer_id,
                'message' => 'Your ' . $leave->leave_type
                             . ' leave request ('
                             . $leave->start_date . ' to '
                             . $leave->end_date
                             . ') has been approved!',
            ]);

        } else {
            $leave->update(['status' => 'rejected']);

            Notification::create([
                'user_id' => $leave->lecturer_id,
                'message' => 'Your ' . $leave->leave_type
                             . ' leave request ('
                             . $leave->start_date . ' to '
                             . $leave->end_date
                             . ') has been rejected.',
            ]);
        }

        return back()->with('success', 'Leave request ' . $action . 'd!');
    }

    // Leave Calendar
    public function calendar(Request $request)
    {
        $month = $request->month ?? date('m');
        $year  = $request->year  ?? date('Y');

        if ($month < 1)  { $month = 12; $year--; }
        if ($month > 12) { $month = 1;  $year++; }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDay    = date('N', mktime(0, 0, 0, $month, 1, $year));
        $monthName   = date('F', mktime(0, 0, 0, $month, 1, $year));
        $monthStart  = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $monthEnd    = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT)
                       . "-$daysInMonth";

        $leaves = LeaveRequest::with('lecturer')
            ->where('status', 'approved')
            ->where('start_date', '<=', $monthEnd)
            ->where('end_date', '>=', $monthStart)
            ->get();

        // Build leave days array
        $leaveDays = [];
        foreach ($leaves as $l) {
            $start = max(strtotime($l->start_date), strtotime($monthStart));
            $end   = min(strtotime($l->end_date),   strtotime($monthEnd));
            for ($d = $start; $d <= $end; $d += 86400) {
                $dayNum = intval(date('j', $d));
                $leaveDays[$dayNum][] = [
                    'name' => $l->lecturer?->name,
                    'type' => $l->leave_type,
                ];
            }
        }

        $leavesList = LeaveRequest::with('lecturer')
            ->where('status', 'approved')
            ->where('start_date', '<=', $monthEnd)
            ->where('end_date', '>=', $monthStart)
            ->get();

        return view('leave.calendar', compact(
            'month','year','monthName','daysInMonth',
            'firstDay','leaveDays','leavesList'
        ));
    }
}