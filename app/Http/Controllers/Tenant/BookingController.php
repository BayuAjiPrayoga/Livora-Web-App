<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\BoardingHouse;
use App\Services\BookingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use AuthorizesRequests;

    /**
     * BookingService instance
     */
    protected BookingService $bookingService;

    /**
     * Create a new controller instance.
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Booking::class);

        $bookings = $this->bookingService->getUserBookings(Auth::id());

        return view('tenant.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Booking::class);

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
        $this->authorize('create', Booking::class);

        $validated = $request->validate([
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

        try {
            $booking = $this->bookingService->createBooking(
                $validated,
                $request->file('ktp_image'),
                Auth::id()
            );

            return redirect()->route('tenant.bookings.show', $booking)
                ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['room.boardingHouse', 'payments', 'user']);

        return view('tenant.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

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
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $booking = $this->bookingService->updateBooking($booking, $validated);

            return redirect()->route('tenant.bookings.show', $booking)
                ->with('success', 'Booking berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

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
        $this->authorize('delete', $booking);

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        try {
            $this->bookingService->cancelBooking($booking, $request->cancellation_reason);

            return back()->with('success', 'Booking berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
