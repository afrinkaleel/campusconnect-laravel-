<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models\ResourceBooking;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;

class ResourceController extends Controller
{
    // HOD — Manage Resources
    public function manage() {
        $resources = Resource::latest()->get();
        return view('resources.manage', compact('resources'));
    }

    // HOD — Add Resource
    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'location' => 'required|string|max:100',
        ]);

        Resource::create([
            'name'               => $request->name,
            'quantity_total'     => $request->quantity,
            'quantity_available' => $request->quantity,
            'location'           => $request->location,
        ]);

        return back()->with('success', 'Resource added successfully!');
    }

    // HOD — Delete Resource
    public function destroy($id) {
        Resource::findOrFail($id)->delete();
        return back()->with('success', 'Resource deleted!');
    }

    // Student/Lecturer — Book Resource form
    public function bookForm() {
        $resources = Resource::where('quantity_available', '>', 0)->get();
        $user      = Auth::user();
        if ($user->user_type === 'student') {
            $projects = Project::where('student_id', $user->id)->get();
        } else {
            $projects = Project::where('supervisor_id', $user->id)->get();
        }
        return view('resources.book', compact('resources', 'projects'));
    }

    // Student/Lecturer — Submit Booking
    public function book(Request $request) {
        $request->validate([
            'resource_id'  => 'required|exists:resources,resource_id',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot'    => 'required|string',
        ]);

        // Check double booking
        $exists = ResourceBooking::where('resource_id', $request->resource_id)
            ->where('booking_date', $request->booking_date)
            ->where('time_slot', $request->time_slot)
            ->whereIn('status', ['pending','approved'])
            ->exists();

        if ($exists) {
            return back()->with('error',
                'This resource is already booked for that date and time slot.');
        }

        // Check availability
        $resource = Resource::findOrFail($request->resource_id);
        if ($resource->quantity_available < 1) {
            return back()->with('error', 'Sorry, this resource is unavailable.');
        }

        ResourceBooking::create([
            'resource_id'  => $request->resource_id,
            'user_id'      => Auth::id(),
            'project_id'   => $request->project_id ?: null,
            'booking_date' => $request->booking_date,
            'time_slot'    => $request->time_slot,
            'status'       => 'pending',
        ]);

        // Notify HOD
        $hod = User::where('user_type', 'hod')->first();
        Notification::create([
            'user_id' => $hod->id,
            'message' => Auth::user()->name
                         . ' submitted a resource booking request for '
                         . $resource->name,
        ]);

        return back()->with('success',
            'Booking request submitted! Waiting for HOD approval.');
    }

    // Student/Lecturer — My Bookings
    public function myBookings() {
        $bookings = ResourceBooking::with(['resource','project'])
            ->where('user_id', Auth::id())
            ->latest()->get();
        return view('resources.my_bookings', compact('bookings'));
    }

    // Student/Lecturer — Return Resource
    public function returnResource($id) {
        $booking = ResourceBooking::findOrFail($id);
        $booking->update(['status' => 'returned']);
        Resource::where('resource_id', $booking->resource_id)
            ->increment('quantity_available');
        return back()->with('success', 'Resource marked as returned!');
    }

    // HOD — Manage Bookings
    public function manageBookings() {
        $bookings = ResourceBooking::with(['resource','user','project'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 ELSE 2 END")
            ->latest()->get();
        return view('resources.manage_bookings', compact('bookings'));
    }

    // HOD — Approve/Reject Booking
    public function handleBooking(Request $request, $id) {
        $booking  = ResourceBooking::findOrFail($id);
        $action   = $request->action;
        $resource = Resource::find($booking->resource_id);

        if ($action === 'approve') {
            $booking->update(['status' => 'approved']);
            $resource->decrement('quantity_available');

            Notification::create([
                'user_id' => $booking->user_id,
                'message' => 'Your booking for ' . $resource->name
                             . ' has been approved!',
            ]);
        } else {
            $booking->update(['status' => 'rejected']);
            Notification::create([
                'user_id' => $booking->user_id,
                'message' => 'Your booking for ' . $resource->name
                             . ' has been rejected.',
            ]);
        }

        return back()->with('success', 'Booking ' . $action . 'd!');
    }

    // Resource Calendar
    public function calendar(Request $request) {
        $month = $request->month ?? date('m');
        $year  = $request->year  ?? date('Y');

        if ($month < 1)  { $month = 12; $year--; }
        if ($month > 12) { $month = 1;  $year++; }

        $monthStart    = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
        $daysInMonth   = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $monthEnd      = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT)
                         . "-$daysInMonth";
        $firstDay      = date('N', mktime(0, 0, 0, $month, 1, $year));
        $monthName     = date('F', mktime(0, 0, 0, $month, 1, $year));
        $resources     = Resource::all();
        $filterResource = $request->resource_id ?? 0;

        $bookings = ResourceBooking::with(['resource','user'])
            ->where('status', 'approved')
            ->whereBetween('booking_date', [$monthStart, $monthEnd])
            ->when($filterResource, fn($q) =>
                $q->where('resource_id', $filterResource))
            ->get()
            ->groupBy(fn($b) => date('j', strtotime($b->booking_date)));

        return view('resources.calendar', compact(
            'month','year','monthName','daysInMonth',
            'firstDay','bookings','resources',
            'filterResource','monthStart','monthEnd'
        ));
    }
}