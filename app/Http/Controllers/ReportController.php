<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceBooking;
use App\Models\LeaveRequest;

class ReportController extends Controller
{
    public function index()
    {
        // System Overview
        $overview = [
            'total_users'     => User::count(),
            'total_students'  => User::where('user_type','student')->count(),
            'total_lecturers' => User::where('user_type','lecturer')->count(),
            'total_projects'  => Project::count(),
            'total_bookings'  => ResourceBooking::count(),
            'total_leaves'    => LeaveRequest::count(),
        ];

        // Pending Approvals
        $pending = [
            'bookings' => ResourceBooking::where('status','pending')->count(),
            'leaves'   => LeaveRequest::where('status','pending')->count(),
            'projects' => Project::whereNull('supervisor_id')->count(),
        ];

        // Project Status Counts
        $projectStats = Project::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        // All Projects
        $allProjects = Project::with(['student','supervisor'])
            ->latest()->get();

        // Resource Utilization
        $resources = Resource::withCount(['bookings as total_bookings',
            'bookings as approved_bookings' => fn($q) =>
                $q->where('status','approved'),
            'bookings as pending_bookings' => fn($q) =>
                $q->where('status','pending'),
        ])->get();

        // Staff Leave Report
        $leaveStats = User::where('user_type','lecturer')
            ->withCount(['leaveRequests as total_requests'])
            ->get()
            ->map(function($lecturer) {
                $daysTaken = LeaveRequest::where('lecturer_id', $lecturer->id)
                    ->where('status','approved')
                    ->where('leave_type','annual')
                    ->whereYear('start_date', date('Y'))
                    ->get()
                    ->sum(fn($l) =>
                        \Carbon\Carbon::parse($l->start_date)
                            ->diffInDays($l->end_date) + 1);

                $pending = LeaveRequest::where('lecturer_id', $lecturer->id)
                    ->where('status','pending')->count();

                $lecturer->days_taken  = $daysTaken;
                $lecturer->remaining   = 20 - $daysTaken;
                $lecturer->pending_count = $pending;
                return $lecturer;
            });

        return view('reports.index', compact(
            'overview','pending','projectStats',
            'allProjects','resources','leaveStats'
        ));
    }
}