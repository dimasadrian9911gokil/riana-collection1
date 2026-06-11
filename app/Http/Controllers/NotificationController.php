<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications for the currently authenticated user (API)
     */
    public function unread()
    {
        if (!Auth::check()) {
            return response()->json(['unread' => []]);
        }

        $user = Auth::user();
        $notifications = $user->unreadNotifications()->take(10)->get();

        return response()->json([
            'count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark all unread notifications as read
     */
    public function markAsRead(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Delete a specific notification (optional)
     */
    public function destroy($id)
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($id);
            if ($notification) {
                $notification->delete();
            }
        }
        return back();
    }
}
