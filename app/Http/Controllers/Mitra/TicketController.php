<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Start building query for tickets that belong to mitra's properties
        $mitraId = Auth::id();
        
        $query = Ticket::with(['tenant', 'room.boardingHouse'])
            ->whereHas('room.boardingHouse', function ($q) use ($mitraId) {
                $q->where('user_id', $mitraId);
            });

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('property') && $request->property !== 'all') {
            $query->whereHas('room.boardingHouse', function ($q) use ($request) {
                $q->where('id', $request->property);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('message', 'like', '%' . $search . '%')
                  ->orWhereHas('tenant', function ($tq) use ($search) {
                      $tq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get mitra's properties for filter dropdown
        $properties = BoardingHouse::where('user_id', $mitraId)
            ->select('id', 'name')
            ->get();

        // Calculate statistics
        $baseQuery = Ticket::whereHas('room.boardingHouse', function ($q) use ($mitraId) {
            $q->where('user_id', $mitraId);
        });

        $stats = [
            'total' => $baseQuery->count(),
            'open' => $baseQuery->where('status', 'open')->count(),
            'in_progress' => $baseQuery->where('status', 'in_progress')->count(),
            'resolved' => $baseQuery->where('status', 'resolved')->count(),
        ];

        return view('mitra.tickets.index', compact('tickets', 'properties', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        // Check authorization
        if ($ticket->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $ticket->load(['tenant', 'room.boardingHouse']);

        return view('mitra.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        // Check authorization
        if ($ticket->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $ticket->load(['user', 'room.boardingHouse']);

        return view('mitra.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Check authorization
        if ($ticket->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
            'response' => 'nullable|string|max:1000',
            'priority' => 'sometimes|in:low,medium,high,urgent'
        ]);

        $updateData = ['status' => $request->status];

        if ($request->filled('response')) {
            $updateData['response'] = $request->response;
        }

        if ($request->filled('priority')) {
            $updateData['priority'] = $request->priority;
        }

        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        return redirect()->back()->with('success', 'Tiket berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Check authorization
        if ($ticket->room->boardingHouse->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses.'], 403);
        }

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved'
        ]);

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status tiket berhasil diperbarui.'
        ]);
    }

    public function updatePriority(Request $request, Ticket $ticket)
    {
        // Check authorization
        if ($ticket->room->boardingHouse->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses.'], 403);
        }

        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket->update(['priority' => $request->priority]);

        return response()->json([
            'success' => true,
            'message' => 'Prioritas tiket berhasil diperbarui.'
        ]);
    }
}
