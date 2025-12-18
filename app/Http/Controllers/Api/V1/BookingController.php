<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard auth()
 */
class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $query = Booking::with([
            'room.boardingHouse',
            'user',
            'payments'
        ])
        ->where('user_id', $user->id)
        ->withCount('payments');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['created_at', 'start_date', 'total_price'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $bookings = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Bookings retrieved successfully',
            'data' => BookingResource::collection($bookings),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'from' => $bookings->firstItem(),
                'to' => $bookings->lastItem(),
            ]
        ], 200);
    }

    /**
     * Display bookings for owner's properties
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ownerBookings(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user is owner
        if ($user->role !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only owners can access this endpoint.',
                'data' => null
            ], 403);
        }

        // Get bookings from owner's boarding houses
        $query = Booking::with([
            'room.boardingHouse',
            'user',
            'payments'
        ])
        ->whereHas('room.boardingHouse', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->withCount('payments');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by boarding house
        if ($request->filled('boarding_house_id')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('boarding_house_id', $request->boarding_house_id);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['created_at', 'start_date', 'total_price'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $bookings = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Owner bookings retrieved successfully',
            'data' => BookingResource::collection($bookings),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'from' => $bookings->firstItem(),
                'to' => $bookings->lastItem(),
            ]
        ], 200);
    }

    /**
     * Store a newly created booking
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string',
            'tenant_identity_number' => 'required|string|size:16',
            'ktp_image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // Max 5MB
        ]);

        try {
            DB::beginTransaction();

            // Get room with boarding house
            $room = Room::with('boardingHouse')->findOrFail($request->room_id);

            // Check if boarding house is active
            if (!$room->boardingHouse || !$room->boardingHouse->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Boarding house is not active',
                    'data' => null
                ], 400);
            }

            // Calculate dates
            $startDate = Carbon::parse($request->start_date);
            $duration = (int) $request->duration; // Cast to integer
            $endDate = $startDate->copy()->addMonths($duration);

            // Check room availability
            $isAvailable = $room->isAvailableForBooking($startDate, $endDate);

            if (!$isAvailable) {
                // Debug: log conflicting bookings
                $conflicts = $room->bookings()
                    ->whereIn('status', ['confirmed', 'active'])
                    ->where(function($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function($subQ) use ($startDate, $endDate) {
                              $subQ->where('start_date', '<=', $startDate)
                                   ->where('end_date', '>=', $endDate);
                          });
                    })->get(['id', 'status', 'start_date', 'end_date']);
                
                \Log::warning('Room not available', [
                    'room_id' => $room->id,
                    'requested_dates' => [$startDate, $endDate],
                    'conflicting_bookings' => $conflicts
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Room is not available for the selected dates. Please choose different dates.',
                    'data' => null
                ], 400);
            }

            // Handle KTP image upload
            $ktpImagePath = null;
            if ($request->hasFile('ktp_image')) {
                $ktpImage = $request->file('ktp_image');
                $filename = 'ktp_' . $user->id . '_' . time() . '.' . $ktpImage->getClientOriginalExtension();
                $ktpImagePath = $ktpImage->storeAs('bookings/ktp', $filename, 'public');
            }

            // Calculate total price
            $totalPrice = $room->price * $duration;

            // Create booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration' => $duration, // Use casted integer
                'total_price' => $totalPrice,
                'final_amount' => $totalPrice,
                'status' => 'pending',
                'notes' => $request->notes,
                'tenant_identity_number' => $request->tenant_identity_number,
                'ktp_image' => $ktpImagePath,
            ]);

            // Note: Don't set is_available to false here
            // Availability is checked dynamically based on booking dates
            // Multiple tenants can book the same room for different date ranges

            // Load relationships
            $booking->load(['room.boardingHouse', 'user', 'payments']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully. Please upload payment proof to confirm your booking.',
                'data' => new BookingResource($booking)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Booking creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'request' => $request->except('ktp_image'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified booking
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $booking = Booking::with([
            'room.boardingHouse.owner',
            'room.facilities',
            'user',
            'payments'
        ])
        ->withCount('payments')
        ->where('user_id', $user->id)
        ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking detail retrieved successfully',
            'data' => new BookingResource($booking)
        ], 200);
    }

    /**
     * Cancel booking
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $booking = Booking::where('user_id', $user->id)
            ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
                'data' => null
            ], 404);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending or confirmed bookings can be cancelled',
                'data' => null
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update booking status
            $booking->update(['status' => 'cancelled']);

            // Make room available again
            $booking->room->update(['is_available' => true]);

            DB::commit();

            $booking->load(['room.boardingHouse', 'user', 'payments']);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'data' => new BookingResource($booking)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Verify payment and activate booking (Owner only)
     */
    public function verifyPayment($bookingId, $paymentId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user is owner
        if ($user->role !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only owners can verify payments.',
                'data' => null
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Get booking with relationships
            $booking = Booking::with(['room.boardingHouse', 'payments'])
                ->whereHas('room.boardingHouse', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->findOrFail($bookingId);

            // Find the payment
            $payment = $booking->payments()->findOrFail($paymentId);

            // Check if payment is already verified
            if ($payment->status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment is already verified',
                    'data' => null
                ], 400);
            }

            // Verify payment
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $user->id,
            ]);

            // Update booking status to active
            $booking->update(['status' => 'active']);

            DB::commit();

            $booking->load(['room.boardingHouse', 'user', 'payments']);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and booking activated successfully',
                'data' => new BookingResource($booking)
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Booking or payment not found',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Reject payment (Owner only)
     */
    public function rejectPayment(Request $request, $bookingId, $paymentId)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Check if user is owner
        if ($user->role !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only owners can reject payments.',
                'data' => null
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Get booking with relationships
            $booking = Booking::with(['room.boardingHouse', 'payments'])
                ->whereHas('room.boardingHouse', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->findOrFail($bookingId);

            // Find the payment
            $payment = $booking->payments()->findOrFail($paymentId);

            // Check if payment can be rejected
            if ($payment->status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reject verified payment',
                    'data' => null
                ], 400);
            }

            // Reject payment
            $payment->update([
                'status' => 'rejected',
                'notes' => $request->notes,
            ]);

            DB::commit();

            $booking->load(['room.boardingHouse', 'user', 'payments']);

            return response()->json([
                'success' => true,
                'message' => 'Payment rejected successfully',
                'data' => new BookingResource($booking)
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Booking or payment not found',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject payment: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
