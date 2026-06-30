<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        // Mark all as read
        Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        $total = $notifications->count();

        return view('notifications.index',
            compact('notifications', 'total'));
    }
}