<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                          ->with(['room.boardingHouse', 'payments'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('tenant.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $boardingHouses = BoardingHouse::with(['rooms' => function($query) {
            $query->where('is_available', true);
        }])->get();

        return view('tenant.bookings.create', compact('boardingHouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1|max:12',
            'tenant_identity_number' => 'required|string|size:16',
            'ktp_image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'notes' => 'nullable|string|max:500'
        ], [
            'tenant_identity_number.required' => 'Nomor KTP wajib diisi',
            'tenant_identity_number.size' => 'Nomor KTP harus 16 digit',
            'ktp_image.required' => 'Foto KTP wajib diupload',
            'ktp_image.image' => 'File harus berupa gambar',
            'ktp_image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'ktp_image.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        $room = Room::findOrFail($request->room_id);
        
        // Calculate dates first
        $startDate = Carbon::parse($request->start_date);
        $duration = (int) $request->duration; // Duration in months
        $endDate = $startDate->copy()->addMonths($duration);
        
        // Check room availability including existing bookings
        if (!$room->isAvailableForBooking($startDate, $endDate)) {
            return back()->withErrors(['room_id' => 'Kamar tidak tersedia pada periode yang dipilih. Kamar sudah dibooking atau sedang ditempati.']);
        }
        
        // Upload KTP image
        $ktpPath = null;
        if ($request->hasFile('ktp_image')) {
            $ktpPath = $request->file('ktp_image')->store('ktp-images', 'public');
        }
        
        // Calculate total amount (monthly rate)
        $totalAmount = $duration * $room->price;

        // Generate booking code
        $bookingCode = 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'boarding_house_id' => $room->boarding_house_id,
            'booking_code' => $bookingCode,
            'check_in_date' => $startDate,
            'check_out_date' => $endDate,
            'duration_months' => $duration,
            'duration_days' => 0,
            'monthly_price' => $room->price,
            'total_amount' => $totalAmount,
            'deposit_amount' => 0,
            'admin_fee' => 0,
            'discount_amount' => 0,
            'final_amount' => $totalAmount,
            'status' => 'pending',
            'booking_type' => 'monthly',
            'tenant_identity_number' => $request->tenant_identity_number,
            'ktp_image' => $ktpPath,
            'notes' => $request->notes
        ]);

        return redirect()->route('tenant.bookings.show', $booking)
                        ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $booking->load(['room.boardingHouse', 'payments', 'user']);

        return view('tenant.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow editing if booking is still pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat diubah karena sudah dikonfirmasi.');
        }

        // Get all boarding houses with their rooms for selection
        $boardingHouses = BoardingHouse::with(['rooms' => function($query) {
            $query->where('is_available', true);
        }])
        ->where('is_active', true)
        ->get();

        // Ensure current booking's room is included even if not available
        $currentProperty = $booking->room->boardingHouse;
        if (!$boardingHouses->contains('id', $currentProperty->id)) {
            $currentProperty->load(['rooms']);
            $boardingHouses->push($currentProperty);
        }

        // Prepare rooms data for JavaScript
        $allRoomsData = [];
        foreach ($boardingHouses as $property) {
            foreach ($property->rooms as $room) {
                $allRoomsData[] = [
                    'id' => $room->id,
                    'boarding_house_id' => $room->boarding_house_id,
                    'name' => $room->name,
                    'price' => floatval($room->price),
                    'price_formatted' => number_format($room->price, 0, ',', '.'),
                    'capacity' => $room->capacity ?? 1,
                    'size' => $room->size ?? 'N/A',
                    'is_available' => $room->is_available ?? true,
                    'description' => $room->description ?? ''
                ];
            }
        }

        return view('tenant.bookings.edit', compact('booking', 'boardingHouses', 'allRoomsData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow updating if booking is still pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat diubah karena sudah dikonfirmasi.');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'duration_type' => 'required|in:daily,monthly,yearly',
            'notes' => 'nullable|string|max:500'
        ]);

        $room = Room::findOrFail($request->room_id);
        
        // Calculate dates and pricing
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Check room availability (skip current booking)
        if (!$room->isAvailableForBooking($startDate, $endDate, $booking->id)) {
            return back()->withErrors(['room_id' => 'Kamar tidak tersedia pada periode yang dipilih. Kamar sudah dibooking atau sedang ditempati.']);
        }
        
        // Calculate duration based on type
        $duration = match($request->duration_type) {
            'daily' => $startDate->diffInDays($endDate) + 1,
            'monthly' => $startDate->diffInMonths($endDate),
            'yearly' => $startDate->diffInYears($endDate),
        };

        // Calculate total amount
        $pricePerUnit = match($request->duration_type) {
            'daily' => $room->price / 30, // Daily rate from monthly
            'monthly' => $room->price,    // Monthly rate
            'yearly' => $room->price * 12, // Yearly rate
        };

        $totalAmount = $duration * $pricePerUnit;

        // Update booking
        $booking->update([
            'room_id' => $request->room_id,
            'check_in_date' => $startDate,
            'check_out_date' => $endDate,
            'duration_months' => $duration,
            'total_amount' => $totalAmount,
            'final_amount' => $totalAmount,
            'notes' => $request->notes
        ]);

        return redirect()->route('tenant.bookings.show', $booking)
                        ->with('success', 'Booking berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deletion if booking is pending or rejected
        if (!in_array($booking->status, ['pending', 'rejected', 'cancelled'])) {
            return back()->with('error', 'Booking tidak dapat dihapus.');
        }

        $booking->delete();

        return redirect()->route('tenant.bookings.index')
                        ->with('success', 'Booking berhasil dihapus!');
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        // Only allow cancellation for certain statuses
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan pada status saat ini.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now()
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Get rooms for a specific boarding house (AJAX).
     */
    public function getRooms($boardingHouseId)
    {
        $rooms = Room::where('boarding_house_id', $boardingHouseId)
                    ->where('is_available', true)
                    ->get(['id', 'name', 'description', 'price', 'capacity', 'size']);

        return response()->json($rooms);
    }
}