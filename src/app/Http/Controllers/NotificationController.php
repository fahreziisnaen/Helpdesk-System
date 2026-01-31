<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function count()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('ticket')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca.');
    }

    /**
     * Get latest notifications (for polling)
     */
    public function latest()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->with('ticket')
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }
}
