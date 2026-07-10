<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BdgsNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = auth()->user()
            ->notifications()
            ->latest('created_at');

        if ($request->expectsJson()) {
            $notifications = (clone $query)->limit(20)->get();

            return response()->json([
                'unread' => $notifications->whereNull('read_at')->count(),
                'items' => $notifications,
            ]);
        }

        return view('dashboard.notifications', [
            'notifications' => $query->paginate(20),
        ]);
    }

    public function markRead(BdgsNotification $notification): JsonResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllRead(): JsonResponse
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
