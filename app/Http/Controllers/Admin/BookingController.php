<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BoardingHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room.boardingHouse', 'payments'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by boarding house
        if ($request->filled('boarding_house_id')) {
            $query->whereHas('room.boardingHouse', function ($q) use ($request) {
                $q->where('id', $request->boarding_house_id);
            });
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        // Search by user name or booking ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(15);
        
        $boardingHouses = BoardingHouse::select('id', 'name')->get();
        
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'active' => Booking::where('status', 'active')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'boardingHouses', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.boardingHouse.owner', 'payments']);
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $booking->load(['user', 'room.boardingHouse']);
        
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.bookings.show', $booking)
                        ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Only allow deletion of cancelled or pending bookings
        if (!in_array($booking->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Cannot delete this booking.');
        }

        $booking->delete();

        return redirect()->route('admin.bookings.index')
                        ->with('success', 'Booking deleted successfully.');
    }

    /**
     * Approve a booking.
     */
    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be approved.');
        }

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking approved successfully.');
    }

    /**
     * Reject a booking.
     */
    public function reject(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Cannot reject this booking.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking rejected successfully.');
    }

    /**
     * Bulk approve bookings.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id'
        ]);

        $updated = Booking::whereIn('id', $request->booking_ids)
                         ->where('status', 'pending')
                         ->update(['status' => 'confirmed']);

        return back()->with('success', "{$updated} bookings approved successfully.");
    }

    /**
     * Bulk reject bookings.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id'
        ]);

        $updated = Booking::whereIn('id', $request->booking_ids)
                         ->whereIn('status', ['pending', 'confirmed'])
                         ->update(['status' => 'cancelled']);

        return back()->with('success', "{$updated} bookings rejected successfully.");
    }

    /**
     * Export bookings data.
     */
    public function export(Request $request)
    {
        $bookings = Booking::with(['user', 'room.boardingHouse'])
                          ->when($request->status, function ($query, $status) {
                              return $query->where('status', $status);
                          })
                          ->when($request->start_date, function ($query, $startDate) {
                              return $query->where('start_date', '>=', $startDate);
                          })
                          ->when($request->end_date, function ($query, $endDate) {
                              return $query->where('start_date', '<=', $endDate);
                          })
                          ->get();

        $filename = 'bookings_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'User Name',
                'User Email', 
                'Boarding House',
                'Room Number',
                'Start Date',
                'End Date',
                'Duration (months)',
                'Total Price',
                'Status',
                'Created At'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->user->name,
                    $booking->user->email,
                    $booking->room->boardingHouse->name,
                    $booking->room->room_number,
                    $booking->start_date,
                    $booking->end_date,
                    $booking->duration_months,
                    $booking->total_price,
                    $booking->status,
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}