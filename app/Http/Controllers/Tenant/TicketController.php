<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = Ticket::with(['room.boardingHouse', 'room'])
            ->where('user_id', $userId);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $stats = [
            'total' => Ticket::where('user_id', $userId)->count(),
            'open' => Ticket::where('user_id', $userId)->where('status', 'open')->count(),
            'in_progress' => Ticket::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('user_id', $userId)->where('status', 'resolved')->count(),
        ];

        return view('tenant.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        // Get user's current bookings to select room (pending or confirmed)
        $bookings = Booking::with(['room.boardingHouse'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->route('tenant.tickets.index')
                ->with('error', 'Anda belum memiliki booking untuk membuat tiket. Silakan buat booking terlebih dahulu.');
        }

        return view('tenant.tickets.create', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent'
        ], [
            'room_id.required' => 'Pilih kamar yang berkaitan dengan keluhan.',
            'room_id.exists' => 'Kamar yang dipilih tidak valid.',
            'subject.required' => 'Subjek tiket harus diisi.',
            'subject.max' => 'Subjek tidak boleh lebih dari 255 karakter.',
            'message.required' => 'Pesan tiket harus diisi.',
            'message.max' => 'Pesan tidak boleh lebih dari 1000 karakter.',
            'priority.required' => 'Pilih tingkat prioritas tiket.',
            'priority.in' => 'Tingkat prioritas yang dipilih tidak valid.'
        ]);

        // Verify user has booking for selected room (pending or confirmed)
        $hasBooking = Booking::where('user_id', Auth::id())
            ->where('room_id', $request->room_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if (!$hasBooking) {
            return redirect()->back()
                ->withErrors(['room_id' => 'Anda tidak memiliki booking untuk kamar ini.'])
                ->withInput();
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'open'
        ]);

        return redirect()->route('tenant.tickets.index')
            ->with('success', 'Tiket berhasil dibuat. Mitra akan segera merespon keluhan Anda.');
    }

    public function show(Ticket $ticket)
    {
        // Check authorization
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $ticket->load(['room.boardingHouse']);

        return view('tenant.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        // Check authorization and ticket status
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        if ($ticket->status !== 'open') {
            return redirect()->route('tenant.tickets.show', $ticket)
                ->with('error', 'Tiket yang sudah diproses tidak dapat diedit.');
        }

        // Get user's current bookings to get rooms
        $bookings = Booking::with(['room.boardingHouse'])
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->get();

        // Extract rooms from bookings for dropdown
        $rooms = $bookings->map(function($booking) {
            return $booking->room;
        })->unique('id');

        return view('tenant.tickets.edit', compact('ticket', 'rooms'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Check authorization and ticket status
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        if ($ticket->status !== 'open') {
            return redirect()->route('tenant.tickets.show', $ticket)
                ->with('error', 'Tiket yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent'
        ], [
            'room_id.required' => 'Pilih kamar yang berkaitan dengan keluhan.',
            'room_id.exists' => 'Kamar yang dipilih tidak valid.',
            'subject.required' => 'Subjek tiket harus diisi.',
            'subject.max' => 'Subjek tidak boleh lebih dari 255 karakter.',
            'message.required' => 'Pesan tiket harus diisi.',
            'message.max' => 'Pesan tidak boleh lebih dari 1000 karakter.',
            'priority.required' => 'Pilih tingkat prioritas tiket.',
            'priority.in' => 'Tingkat prioritas yang dipilih tidak valid.'
        ]);

        // Verify user has active booking for selected room
        $hasActiveBooking = Booking::where('user_id', Auth::id())
            ->where('room_id', $request->room_id)
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasActiveBooking) {
            return redirect()->back()
                ->withErrors(['room_id' => 'Anda tidak memiliki booking aktif untuk kamar ini.'])
                ->withInput();
        }

        $ticket->update([
            'room_id' => $request->room_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority
        ]);

        return redirect()->route('tenant.tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    public function destroy(Ticket $ticket)
    {
        // Check authorization and ticket status
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        if ($ticket->status !== 'open') {
            return redirect()->route('tenant.tickets.index')
                ->with('error', 'Tiket yang sudah diproses tidak dapat dihapus.');
        }

        $ticket->delete();

        return redirect()->route('tenant.tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }
}
