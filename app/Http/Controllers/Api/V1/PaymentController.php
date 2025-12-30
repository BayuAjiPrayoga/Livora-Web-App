<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\MidtransService;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create Midtrans Payment (Snap Token)
     */
    public function create(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with('room.boardingHouse', 'user')
            ->where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found',
                'data' => null
            ], 404);
        }

        try {
            $user = auth()->user();
            // Create pending payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->final_amount ?? $booking->total_price,
                'status' => 'pending',
                'payment_type' => 'midtrans',
                'notes' => 'Menunggu pembayaran via Midtrans',
            ]);

            $params = $this->midtransService->buildTransactionParams($booking, $user);
            // Override order_id to include payment id to be unique
            $params['transaction_details']['order_id'] = 'ORDER-' . $payment->id . '-' . time();

            // Get Snap Token
            $snapToken = $this->midtransService->createTransaction($params);

            // Update payment with token
            $payment->update([
                'midtrans_token' => $snapToken,
                'midtrans_redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                'midtrans_order_id' => $params['transaction_details']['order_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment initiation success',
                'data' => [
                    'token' => $snapToken,
                    'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                    'payment' => $payment
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

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
        // Use total_price if final_amount is null
        $totalAmount = $booking->final_amount ?? $booking->total_price;
        if ($request->amount > $totalAmount) {
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
                'payment_type' => 'manual',
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
     */
    public function show($id)
    {
        $payment = Payment::with('booking.room.boardingHouse')->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
                'data' => null
            ], 404);
        }

        // Secure: ensure user owns the payment's booking
        if ($payment->booking->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], 403);
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
                'midtrans_token' => $payment->midtrans_token,
                'midtrans_redirect_url' => $payment->midtrans_redirect_url,
                'proof_image' => $payment->proof_image ? url('storage/' . $payment->proof_image) : null,
                'notes' => $payment->notes,
                'created_at' => $payment->created_at->toISOString(),
                'created_at_formatted' => $payment->created_at->format('d M Y H:i'),
            ]
        ], 200);
    }
}
