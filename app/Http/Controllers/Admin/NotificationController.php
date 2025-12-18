<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Notification::with(['user'])
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by title or message
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $notifications = $query->paginate(15);
        
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::whereNull('read_at')->count(),
            'read' => Notification::whereNotNull('read_at')->count(),
            'high_priority' => Notification::where('priority', 'high')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', 'name', 'email', 'role')->orderBy('name')->get();
        $boardingHouses = BoardingHouse::select('id', 'name')->orderBy('name')->get();
        
        return view('admin.notifications.create', compact('users', 'boardingHouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:single,role,all',
            'user_id' => 'required_if:recipient_type,single|exists:users,id',
            'role' => 'required_if:recipient_type,role|in:admin,owner,tenant',
            'type' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'action_url' => 'nullable|url',
        ]);

        // Determine recipients
        $recipients = collect();
        
        if ($request->recipient_type === 'single') {
            $recipients = collect([User::find($request->user_id)]);
        } elseif ($request->recipient_type === 'role') {
            $recipients = User::where('role', $request->role)->get();
        } else { // all
            $recipients = User::all();
        }

        // Create notifications for each recipient
        $createdCount = 0;
        foreach ($recipients as $user) {
            Notification::createForUser(
                $user,
                $request->type,
                $request->title,
                $request->message,
                [],
                $request->priority,
                $request->action_url
            );
            $createdCount++;
        }

        return redirect()->route('admin.notifications.index')
                        ->with('success', "Notification sent to {$createdCount} users successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        $notification->load(['user']);
        
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
                        ->with('success', 'Notification deleted successfully.');
    }

    /**
     * Bulk delete notifications.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id'
        ]);

        $deleted = Notification::whereIn('id', $request->notification_ids)->delete();

        return back()->with('success', "{$deleted} notifications deleted successfully.");
    }

    /**
     * Send test notification.
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        Notification::createForUser(
            $user,
            'test',
            'Test Notification',
            'This is a test notification sent from admin panel.',
            [],
            'low'
        );

        return back()->with('success', 'Test notification sent successfully.');
    }

    /**
     * Get notification statistics.
     */
    public function getStats()
    {
        $stats = [
            'total' => Notification::count(),
            'today' => Notification::whereDate('created_at', today())->count(),
            'unread' => Notification::whereNull('read_at')->count(),
            'by_type' => Notification::selectRaw('type, count(*) as count')
                                   ->groupBy('type')
                                   ->get()
                                   ->pluck('count', 'type'),
            'by_priority' => Notification::selectRaw('priority, count(*) as count')
                                       ->groupBy('priority')
                                       ->get()
                                       ->pluck('count', 'priority'),
        ];

        return response()->json($stats);
    }

    /**
     * Export notifications data.
     */
    public function export(Request $request)
    {
        $notifications = Notification::with(['user'])
                                   ->when($request->type, function ($query, $type) {
                                       return $query->where('type', $type);
                                   })
                                   ->when($request->priority, function ($query, $priority) {
                                       return $query->where('priority', $priority);
                                   })
                                   ->when($request->start_date, function ($query, $startDate) {
                                       return $query->where('created_at', '>=', $startDate);
                                   })
                                   ->when($request->end_date, function ($query, $endDate) {
                                       return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                                   })
                                   ->get();

        $filename = 'notifications_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($notifications) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'User Name',
                'User Email',
                'Type',
                'Title',
                'Priority',
                'Read Status',
                'Created At',
                'Read At'
            ]);

            foreach ($notifications as $notification) {
                fputcsv($file, [
                    $notification->id,
                    $notification->user->name ?? 'N/A',
                    $notification->user->email ?? 'N/A',
                    $notification->type,
                    $notification->title,
                    $notification->priority,
                    $notification->isRead() ? 'Read' : 'Unread',
                    $notification->created_at->format('Y-m-d H:i:s'),
                    $notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
