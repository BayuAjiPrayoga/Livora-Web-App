<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'room.boardingHouse'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by subject or ticket ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->paginate(15);
        
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'high_priority' => Ticket::where('priority', 'high')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'room.boardingHouse']);
        
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $ticket->load(['user', 'room.boardingHouse']);
        
        return view('admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'subject' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:open,in_progress,resolved,closed',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'response' => 'nullable|string',
        ]);

        $data = $request->only(['subject', 'message', 'status', 'priority', 'response']);
        
        // Set resolved_at when status changes to resolved
        if ($request->status === 'resolved' && $ticket->status !== 'resolved') {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        return redirect()->route('admin.tickets.show', $ticket)
                        ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        // Only allow deletion of closed tickets
        if ($ticket->status !== 'closed') {
            return back()->with('error', 'Only closed tickets can be deleted.');
        }

        $ticket->delete();

        return redirect()->route('admin.tickets.index')
                        ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_response' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'admin_response' => $request->admin_response,
        ]);

        return back()->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Update ticket priority.
     */
    public function updatePriority(Request $request, Ticket $ticket)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update(['priority' => $request->priority]);

        return back()->with('success', 'Ticket priority updated successfully.');
    }

    /**
     * Assign ticket to admin.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Verify the assigned user is an admin
        if ($request->assigned_to) {
            $admin = User::find($request->assigned_to);
            if (!$admin || $admin->role !== 'admin') {
                return back()->with('error', 'Invalid admin user selected.');
            }
        }

        $ticket->update(['assigned_to' => $request->assigned_to]);

        return back()->with('success', 'Ticket assignment updated successfully.');
    }

    /**
     * Resolve ticket.
     */
    public function resolve(Request $request, Ticket $ticket)
    {
        $ticket->update(['status' => 'resolved']);

        return back()->with('success', 'Ticket resolved successfully.');
    }

    /**
     * Bulk update ticket status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $updated = Ticket::whereIn('id', $request->ticket_ids)
                        ->update(['status' => $request->status]);

        return back()->with('success', "{$updated} tickets updated successfully.");
    }

    /**
     * Bulk assign tickets to admin.
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'assigned_to' => 'required|exists:users,id'
        ]);

        $updated = Ticket::whereIn('id', $request->ticket_ids)
                        ->update(['assigned_to' => $request->assigned_to]);

        return back()->with('success', "{$updated} tickets assigned successfully.");
    }

    /**
     * Bulk close tickets.
     */
    public function bulkClose(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id'
        ]);

        $updated = Ticket::whereIn('id', $request->ticket_ids)
                        ->update(['status' => 'closed']);

        return back()->with('success', "{$updated} tickets closed successfully.");
    }

    /**
     * Export tickets data.
     */
    public function export(Request $request)
    {
        $tickets = Ticket::with(['user', 'boardingHouse', 'assignedTo'])
                        ->when($request->status, function ($query, $status) {
                            return $query->where('status', $status);
                        })
                        ->when($request->priority, function ($query, $priority) {
                            return $query->where('priority', $priority);
                        })
                        ->when($request->category, function ($query, $category) {
                            return $query->where('category', $category);
                        })
                        ->when($request->start_date, function ($query, $startDate) {
                            return $query->where('created_at', '>=', $startDate);
                        })
                        ->when($request->end_date, function ($query, $endDate) {
                            return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                        })
                        ->get();

        $filename = 'tickets_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Title',
                'Status',
                'Priority',
                'Category',
                'User Name',
                'User Email',
                'Boarding House',
                'Assigned To',
                'Created At',
                'Updated At'
            ]);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->id,
                    $ticket->title,
                    $ticket->status,
                    $ticket->priority,
                    $ticket->category,
                    $ticket->user->name ?? 'N/A',
                    $ticket->user->email ?? 'N/A',
                    $ticket->boardingHouse->name ?? 'N/A',
                    $ticket->assignedTo->name ?? 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                    $ticket->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}