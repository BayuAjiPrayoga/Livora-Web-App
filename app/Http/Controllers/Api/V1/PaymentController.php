<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Store payment proof
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'notes' => 'nullable|string',
        ]);

        // Verify booking ownership
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found or you do not have permission',
                'data' => null
            ], 404);
        }

        // Check if booking is cancelled or completed
        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot upload payment for cancelled or completed booking',
                'data' => null
            ], 400);
        }

        // Validate amount doesn't exceed booking total
        if ($request->amount > $booking->final_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount cannot exceed booking total',
                'data' => null
            ], 400);
        }

        try {
            // Upload proof image
            $proofPath = null;
            if ($request->hasFile('proof_image')) {
                $proofPath = $request->file('proof_image')->store('payments', 'public');
            }

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $request->amount,
                'proof_image' => $proofPath,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Update booking status to confirmed if still pending
            if ($booking->status === 'pending') {
                $booking->update(['status' => 'confirmed']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment proof uploaded successfully. Waiting for verification.',
                'data' => [
                    'id' => $payment->id,
                    'booking_id' => $payment->booking_id,
                    'amount' => (float) $payment->amount,
                    'amount_formatted' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                    'status' => $payment->status,
                    'proof_image' => $payment->proof_image ? url('storage/' . $payment->proof_image) : null,
                    'notes' => $payment->notes,
                    'created_at' => $payment->created_at->toISOString(),
                ]
            ], 201);

        } catch (\Exception $e) {
            // Delete uploaded file if payment creation fails
            if (isset($proofPath) && $proofPath) {
                Storage::disk('public')->delete($proofPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload payment proof: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get payment history for a booking
     */
    public function index(Request $request)
    {
        $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        $query = Payment::query()->whereHas('booking', function ($q) {
            $q->where('user_id', auth()->id());
        });

        // Filter by booking
        if ($request->filled('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->with('booking.room.boardingHouse')
            ->latest()
            ->paginate($request->get('per_page', 15));

        $data = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'booking_reference' => $payment->booking?->room?->boardingHouse?->name . ' - ' . $payment->booking?->room?->name,
                'amount' => (float) $payment->amount,
                'amount_formatted' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'status' => $payment->status,
                'status_label' => ucfirst($payment->status),
                'proof_image' => $payment->proof_image ? url('storage/' . $payment->proof_image) : null,
                'notes' => $payment->notes,
                'verified_at' => $payment->verified_at?->toISOString(),
                'created_at' => $payment->created_at->toISOString(),
                'created_at_formatted' => $payment->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
            ]
        ], 200);
    }

    /**
     * Get payment detail
     * FIX: This method was missing, causing 404 error
     */
    public function show($id)
    {
        $payment = Payment::whereHas('booking', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('booking.room.boardingHouse')->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment retrieved successfully',
            'data' => [
                'id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'booking_reference' => $payment->booking?->room?->boardingHouse?->name . ' - ' . $payment->booking?->room?->name,
                'amount' => (float) $payment->amount,
                'amount_formatted' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'status' => $payment->status,
                'status_label' => ucfirst($payment->status),
                'proof_image' => $payment->proof_image ? url('storage/' . $payment->proof_image) : null,
                'payment_type' => $payment->payment_type,
                'midtrans_order_id' => $payment->midtrans_order_id,
                'notes' => $payment->notes,
                'verified_at' => $payment->verified_at?->toISOString(),
                'created_at' => $payment->created_at->toISOString(),
                'created_at_formatted' => $payment->created_at->format('d M Y H:i'),
            ]
        ], 200);
    }
}
