<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\User;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for all properties owned by the user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');
        $property_id = $request->get('property_id');
        
        $query = Booking::with(['user', 'room.boardingHouse'])
            ->whereHas('room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by property
        if ($property_id) {
            $query->whereHas('room.boardingHouse', function($q) use ($property_id) {
                $q->where('id', $property_id);
            });
        }

        $bookings = $query->latest()->paginate(15);

        // Get user's properties for filter
        $properties = BoardingHouse::where('user_id', $user->id)
            ->select('id', 'name')
            ->get();

        // Get booking statistics
        $stats = [
            'total' => Booking::whereHas('room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'pending' => Booking::whereHas('room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'confirmed' => Booking::whereHas('room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'confirmed')->count(),
            'active' => Booking::whereHas('room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'active')->count(),
            'completed' => Booking::whereHas('room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'completed')->count(),
        ];

        return view('mitra.bookings.index', compact('bookings', 'properties', 'stats', 'status'));
    }

    /**
     * Display bookings for a specific property
     */
    public function propertyBookings(BoardingHouse $property)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        $bookings = $property->bookings()
            ->with(['user', 'room'])
            ->latest()
            ->paginate(10);

        return view('mitra.bookings.property', compact('property', 'bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create(Request $request)
    {
        $room_id = $request->get('room_id');
        $property_id = $request->get('property_id');

        $room = null;
        $property = null;
        $rooms = collect(); // Initialize as empty collection
        
        // Always get all user's properties for the dropdown with their rooms
        $boardingHouses = BoardingHouse::where('user_id', Auth::id())->with('rooms')->get();
        
        // Get all rooms for JavaScript filtering
        $allRooms = [];
        foreach ($boardingHouses as $property) {
            foreach ($property->rooms as $room) {
                $allRooms[] = [
                    'id' => $room->id,
                    'boarding_house_id' => $room->boarding_house_id,
                    'name' => $room->name,
                    'price' => $room->price,
                    'price_formatted' => number_format($room->price, 0, ',', '.'),
                    'capacity' => $room->capacity ?? 1,
                    'size' => $room->size ?? 'N/A',
                    'is_available' => $room->is_available ?? true,
                ];
            }
        }

        if ($room_id) {
            $room = Room::with('boardingHouse')->findOrFail($room_id);
            $property = $room->boardingHouse;
            
            // Check if the property belongs to the authenticated user
            if ($property->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this room.');
            }
        } elseif ($property_id) {
            $property = BoardingHouse::findOrFail($property_id);
            
            // Check if the property belongs to the authenticated user
            if ($property->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this property.');
            }
            
            $rooms = $property->rooms()->where('is_available', true)->get();
        }

        return view('mitra.bookings.create', compact('room', 'property', 'rooms', 'boardingHouses', 'allRooms'));
    }

    /**
     * Store a newly created booking in storage
     */
    public function store(StoreBookingRequest $request)
    {
        \Log::info('Booking store method called', $request->all());
        
        try {
            $validatedData = $request->validated();
            \Log::info('Validation passed', $validatedData);
            
            $room = Room::with('boardingHouse')->findOrFail($validatedData['room_id']);
        
            // Check if the property belongs to the authenticated user
            if ($room->boardingHouse->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this room.');
            }

            // Check room availability
            $checkIn = Carbon::parse($validatedData['check_in_date']);
            $checkOut = Carbon::parse($validatedData['check_out_date']);
            
            // Validate room availability for the booking period
            if (!$room->isAvailableForBooking($checkIn, $checkOut)) {
                return back()->withErrors(['room_id' => 'Kamar tidak tersedia pada periode yang dipilih. Kamar sudah dibooking atau sedang ditempati.'])->withInput();
            }
            
            DB::beginTransaction();
            
            // Find or create tenant user
            $tenant = User::where('email', $validatedData['tenant_email'])->first();
            
            if (!$tenant) {
                // Create new tenant user
                $tenant = User::create([
                    'name' => $validatedData['tenant_name'],
                    'email' => $validatedData['tenant_email'],
                    'phone' => $validatedData['tenant_phone'],
                    'address' => $validatedData['tenant_address'],
                    'password' => Hash::make(Str::random(16)), // Random password
                    'role' => 'tenant',
                    'email_verified_at' => now(),
                ]);
                
                \Log::info('Created new tenant user', ['id' => $tenant->id, 'email' => $tenant->email]);
            }
            
            // Calculate duration based on booking type
            $duration = 1; // Default
            if ($validatedData['booking_type'] === 'daily') {
                $duration = $checkIn->diffInDays($checkOut);
            } elseif ($validatedData['booking_type'] === 'monthly') {
                $duration = max(1, $checkIn->diffInMonths($checkOut));
            } elseif ($validatedData['booking_type'] === 'yearly') {
                $duration = max(1, $checkIn->diffInYears($checkOut));
            }
            
            // Calculate total price
            $basePrice = $room->price;
            $totalPrice = $basePrice * $duration;
            
            // Add admin fee and deposit if provided
            if (isset($validatedData['admin_fee']) && $validatedData['admin_fee'] > 0) {
                $totalPrice += $validatedData['admin_fee'];
            }
            if (isset($validatedData['deposit_amount']) && $validatedData['deposit_amount'] > 0) {
                $totalPrice += $validatedData['deposit_amount'];
            }
            // Subtract discount
            if (isset($validatedData['discount_amount']) && $validatedData['discount_amount'] > 0) {
                $totalPrice -= $validatedData['discount_amount'];
            }
            
            // Prepare additional notes with emergency contact info
            $additionalNotes = [];
            if (!empty($validatedData['tenant_identity_number'])) {
                $additionalNotes[] = "KTP: " . $validatedData['tenant_identity_number'];
            }
            if (!empty($validatedData['emergency_contact_name'])) {
                $additionalNotes[] = "Kontak Darurat: " . $validatedData['emergency_contact_name'];
                if (!empty($validatedData['emergency_contact_phone'])) {
                    $additionalNotes[] = "No. Kontak Darurat: " . $validatedData['emergency_contact_phone'];
                }
                if (!empty($validatedData['emergency_contact_relation'])) {
                    $additionalNotes[] = "Hubungan: " . $validatedData['emergency_contact_relation'];
                }
            }
            if (!empty($validatedData['special_requests'])) {
                $additionalNotes[] = "Permintaan Khusus: " . $validatedData['special_requests'];
            }
            if (!empty($validatedData['notes'])) {
                $additionalNotes[] = "Catatan: " . $validatedData['notes'];
            }
            
            // Prepare booking data
            $bookingData = [
                'user_id' => $tenant->id,
                'room_id' => $validatedData['room_id'],
                'check_in_date' => $checkIn->format('Y-m-d'),
                'check_out_date' => $checkOut->format('Y-m-d'),
                'duration_months' => $duration,
                'total_amount' => $totalPrice,
                'final_amount' => $totalPrice, // Same as total_amount for now
                'status' => Booking::STATUS_PENDING,
                'notes' => implode("\n", $additionalNotes),
            ];

            \Log::info('Creating booking with data', $bookingData);
            $booking = Booking::create($bookingData);
            \Log::info('Booking created', ['id' => $booking->id]);

            DB::commit();

            return redirect()
                ->route('mitra.bookings.show', $booking->id)
                ->with('success', 'Booking berhasil dibuat! Tenant baru telah didaftarkan.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        $booking->load(['user', 'room.boardingHouse', 'payments']);

        return view('mitra.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking
     */
    public function edit(Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Only allow editing of pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dapat diedit karena statusnya sudah ' . $booking->status_label);
        }

        return view('mitra.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Only allow editing of pending or confirmed bookings
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dapat diedit karena statusnya sudah ' . $booking->status_label);
        }

        $validatedData = $request->validated();

        // Map check_in_date and check_out_date if provided
        if (isset($validatedData['check_in_date'])) {
            $checkIn = Carbon::parse($validatedData['check_in_date']);
            unset($validatedData['check_in_date']);
        } else {
            $checkIn = $booking->check_in_date ? Carbon::parse($booking->check_in_date) : null;
        }

        if (isset($validatedData['check_out_date'])) {
            $checkOut = Carbon::parse($validatedData['check_out_date']);
            unset($validatedData['check_out_date']);
        } else {
            $checkOut = $booking->check_out_date ? Carbon::parse($booking->check_out_date) : null;
        }

        // Calculate duration and total price if dates are available
        if ($checkIn && $checkOut) {
            $bookingType = $validatedData['booking_type'] ?? $booking->booking_type;
            
            switch ($bookingType) {
                case 'daily':
                    $duration = max(1, $checkIn->diffInDays($checkOut));
                    break;
                case 'monthly':
                    $duration = max(1, $checkIn->diffInMonths($checkOut));
                    break;
                case 'yearly':
                    $duration = max(1, $checkIn->diffInYears($checkOut));
                    break;
                default:
                    $duration = max(1, $checkIn->diffInMonths($checkOut));
            }
            
            $validatedData['check_in_date'] = $checkIn->format('Y-m-d');
            $validatedData['check_out_date'] = $checkOut->format('Y-m-d');
            $validatedData['duration'] = $duration;
            
            // Recalculate total price
            $basePrice = $booking->room->price * $duration;
            $adminFee = $validatedData['admin_fee'] ?? $booking->admin_fee ?? 0;
            $depositAmount = $validatedData['deposit_amount'] ?? $booking->deposit_amount ?? 0;
            $discountAmount = $validatedData['discount_amount'] ?? $booking->discount_amount ?? 0;
            
            $validatedData['total_price'] = $basePrice + $adminFee + $depositAmount - $discountAmount;
        }

        $booking->update($validatedData);

        return redirect()
            ->route('mitra.bookings.show', $booking)
            ->with('success', 'Booking berhasil diupdate!');
    }

    /**
     * Confirm a booking
     */
    public function confirm(Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        if (!$booking->canBeConfirmed()) {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dapat dikonfirmasi.');
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        return redirect()
            ->route('mitra.bookings.show', $booking)
            ->with('success', 'Booking berhasil dikonfirmasi!');
    }

    /**
     * Check-in a booking
     */
    public function checkIn(Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        if (!$booking->canBeCheckedIn()) {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dapat di-check-in saat ini.');
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'active',
            ]);

            // Mark room as occupied
            $booking->room->update(['is_available' => false]);

            DB::commit();

            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('success', 'Check-in berhasil! Penyewa sudah menempati kamar.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check-out a booking
     */
    public function checkOut(Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        if (!$booking->canBeCheckedOut()) {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dapat di-check-out.');
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'completed',
            ]);

            // Mark room as available if no other active bookings
            $hasOtherActiveBookings = $booking->room->bookings()
                ->where('id', '!=', $booking->id)
                ->where('status', 'active')
                ->exists();

            if (!$hasOtherActiveBookings) {
                $booking->room->update(['is_available' => true]);
            }

            DB::commit();

            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('success', 'Check-out berhasil! Kamar sudah tersedia untuk booking baru.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        if (!$booking->canBeCancelled()) {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        $reason = $request->input('reason', 'Dibatalkan oleh owner');

        $booking->update([
            'status' => 'cancelled',
            'notes' => $reason,
        ]);

        return redirect()
            ->route('mitra.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Remove the specified booking from storage
     */
    public function destroy(Booking $booking)
    {
        // Check if the booking belongs to a property owned by the authenticated user
        if ($booking->room->boardingHouse->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // Only allow deletion of cancelled bookings
        if ($booking->status !== 'cancelled') {
            return redirect()
                ->route('mitra.bookings.show', $booking)
                ->with('error', 'Hanya booking yang sudah dibatalkan yang dapat dihapus.');
        }

        $booking->delete();

        return redirect()
            ->route('mitra.bookings.index')
            ->with('success', 'Booking berhasil dihapus!');
    }

    /**
     * Get rooms for a property (AJAX)
     */
    public function getRooms(BoardingHouse $boardingHouse)
    {
        // Check if the property belongs to the authenticated user
        if ($boardingHouse->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rooms = $boardingHouse->rooms()
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'price' => $room->price,
                    'price_formatted' => number_format($room->price, 0, ',', '.'),
                    'capacity' => $room->capacity ?? 1,
                    'size' => $room->size ?? 'N/A',
                    'is_available' => $room->is_available ?? true,
                    'facilities' => $room->facilities ?? [],
                ];
            });

        return response()->json(['rooms' => $rooms]);
    }
}