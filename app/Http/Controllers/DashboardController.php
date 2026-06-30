<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    // Student Dashboard
    public function student()
    {
        $user = Auth::user();
        $stats = [
            'total_projects'  => \DB::table('projects')
                                    ->where('student_id', $user->id)->count(),
            'total_bookings'  => \DB::table('resource_bookings')
                                    ->where('user_id', $user->id)->count(),
            'pending_bookings'=> \DB::table('resource_bookings')
                                    ->where('user_id', $user->id)
                                    ->where('status', 'pending')->count(),
            'unread_notifs'   => \DB::table('notifications')
                                    ->where('user_id', $user->id)
                                    ->where('is_read', 0)->count(),
        ];
        return view('dashboard.student', compact('user', 'stats'));
    }

    // Lecturer Dashboard
    public function lecturer()
    {
        $user = Auth::user();
        $stats = [
            'supervised'      => \DB::table('projects')
                                    ->where('supervisor_id', $user->id)->count(),
            'total_leaves'    => \DB::table('leave_requests')
                                    ->where('lecturer_id', $user->id)->count(),
            'pending_leaves'  => \DB::table('leave_requests')
                                    ->where('lecturer_id', $user->id)
                                    ->where('status', 'pending')->count(),
            'unread_notifs'   => \DB::table('notifications')
                                    ->where('user_id', $user->id)
                                    ->where('is_read', 0)->count(),
        ];
        return view('dashboard.lecturer', compact('user', 'stats'));
    }

    // HOD Dashboard
    public function hod()
    {
        $user  = Auth::user();
        $stats = [
            'total_projects'   => \DB::table('projects')->count(),
            'total_students'   => User::where('user_type', 'student')->count(),
            'total_lecturers'  => User::where('user_type', 'lecturer')->count(),
            'total_resources'  => \DB::table('resources')->count(),
            'pending_bookings' => \DB::table('resource_bookings')
                                     ->where('status', 'pending')->count(),
            'pending_leaves'   => \DB::table('leave_requests')
                                     ->where('status', 'pending')->count(),
        ];
        return view('dashboard.hod', compact('user', 'stats'));
    }
}