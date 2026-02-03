<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Start building query for payments that belong to mitra's properties
        $mitraId = Auth::id();

        $query = Payment::with(['booking.room.boardingHouse', 'booking.user'])
            ->whereHas('booking.room.boardingHouse', function ($q) use ($mitraId) {
                $q->where('user_id', $mitraId);
            });

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('property') && $request->property !== 'all') {
            $query->whereHas('booking.room.boardingHouse', function ($q) use ($request) {
                $q->where('id', $request->property);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('amount', 'like', '%' . $search . '%')
                    ->orWhereHas('booking', function ($bq) use ($search) {
                        $bq->where('tenant_name', 'like', '%' . $search . '%')
                            ->orWhere('tenant_phone', 'like', '%' . $search . '%');
                    });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get mitra's properties for filter dropdown
        $properties = BoardingHouse::where('user_id', $mitraId)
            ->select('id', 'name')
            ->get();

        // Calculate statistics
        $baseQuery = Payment::whereHas('booking.room.boardingHouse', function ($q) use ($mitraId) {
            $q->where('user_id', $mitraId);
        });

        $statistics = [
            'total_payments' => (clone $baseQuery)->count(),
            'pending_payments' => (clone $baseQuery)->where('status', Payment::STATUS_PENDING)->count(),
            'verified_payments' => (clone $baseQuery)->whereIn('status', [Payment::STATUS_VERIFIED, Payment::STATUS_SETTLEMENT, 'capture'])->count(),
            'rejected_payments' => (clone $baseQuery)->whereIn('status', [Payment::STATUS_REJECTED, Payment::STATUS_FAILED, Payment::STATUS_CANCELLED, Payment::STATUS_EXPIRED, 'deny', 'cancel', 'expire'])->count(),
            'total_amount' => (clone $baseQuery)->whereIn('status', [Payment::STATUS_VERIFIED, Payment::STATUS_SETTLEMENT, 'capture'])->sum('amount'),
            'pending_amount' => (clone $baseQuery)->where('status', Payment::STATUS_PENDING)->sum('amount'),
        ];

        return view('mitra.payments.index', compact('payments', 'properties', 'statistics'));
    }

    public function show(Payment $payment)
    {
        // Check authorization
        if ($payment->booking->room->boardingHouse->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $payment->load(['booking.room.boardingHouse', 'booking.tenant']);

        return view('mitra.payments.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        // Check authorization
        if ($payment->booking->room->boardingHouse->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string|max:500'
        ], [
            'status.required' => 'Status pembayaran harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'notes.max' => 'Catatan tidak boleh lebih dari 500 karakter.'
        ]);

        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes
        ];

        if ($request->status === Payment::STATUS_VERIFIED) {
            $updateData['verified_at'] = now();

            // Update booking status to confirmed when payment is verified
            $payment->booking->update(['status' => 'confirmed']);

            // Mark room as unavailable since payment is verified
            $payment->booking->room->update(['is_available' => false]);
        } elseif ($request->status === Payment::STATUS_REJECTED) {
            $updateData['verified_at'] = null;

            // If payment rejected, cancel the booking
            $payment->booking->update(['status' => 'cancelled']);
        } else {
            $updateData['verified_at'] = null;
        }

        $payment->update($updateData);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function verify(Payment $payment)
    {
        // Check authorization
        if ($payment->booking->room->boardingHouse->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses.'], 403);
        }

        $payment->update([
            'status' => Payment::STATUS_VERIFIED,
            'verified_at' => now()
        ]);

        // Update booking status to confirmed when payment is verified
        $payment->booking->update([
            'status' => 'confirmed'
        ]);

        // Mark room as unavailable since payment is verified (room is now effectively occupied)
        $payment->booking->room->update([
            'is_available' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi. Kamar sekarang tidak tersedia untuk booking lain.'
        ]);
    }

    public function reject(Request $request, Payment $payment)
    {
        // Check authorization
        if ($payment->booking->room->boardingHouse->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Tidak memiliki akses.'], 403);
        }

        $request->validate([
            'notes' => 'required|string|max:500'
        ], [
            'notes.required' => 'Alasan penolakan harus diisi.',
            'notes.max' => 'Alasan penolakan tidak boleh lebih dari 500 karakter.'
        ]);

        $payment->update([
            'status' => Payment::STATUS_REJECTED,
            'notes' => $request->notes,
            'verified_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditolak.'
        ]);
    }

    public function downloadProof(Payment $payment)
    {
        // Check authorization
        if ($payment->booking->room->boardingHouse->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke bukti pembayaran ini.');
        }

        if (!$payment->proof_image) {
            abort(404, 'Bukti pembayaran belum diupload.');
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($payment->proof_image)) {
            abort(404, 'File bukti pembayaran tidak ditemukan.');
        }

        // Get file path
        $filePath = $disk->path($payment->proof_image);

        // Get mime type, default to image/jpeg if detection fails
        try {
            $mimeType = $disk->mimeType($payment->proof_image);
        } catch (\Exception $e) {
            $mimeType = 'image/jpeg';
        }

        // Return file response to display in browser
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="bukti-pembayaran-' . $payment->id . '"'
        ]);
    }

    public function downloadReceipt(Payment $payment)
    {
        // Check authorization
        if ($payment->booking->room->boardingHouse->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kwitansi ini.');
        }

        // Load necessary relationships
        $payment->load(['booking.room.boardingHouse', 'booking.user']);

        // Return receipt view that can be printed
        return view('mitra.payments.receipt', compact('payment'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,reject',
            'payment_ids' => 'required|array|min:1',
            'payment_ids.*' => 'exists:payments,id',
            'notes' => 'required_if:action,reject|nullable|string|max:500'
        ], [
            'action.required' => 'Aksi harus dipilih.',
            'action.in' => 'Aksi yang dipilih tidak valid.',
            'payment_ids.required' => 'Pilih minimal satu pembayaran.',
            'payment_ids.min' => 'Pilih minimal satu pembayaran.',
            'notes.required_if' => 'Alasan penolakan harus diisi.',
            'notes.max' => 'Alasan penolakan tidak boleh lebih dari 500 karakter.'
        ]);

        $mitraId = Auth::id();

        // Get payments that belong to mitra
        $payments = Payment::whereIn('id', $request->payment_ids)
            ->whereHas('booking.room.boardingHouse', function ($q) use ($mitraId) {
                $q->where('user_id', $mitraId);
            })
            ->get();

        if ($payments->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pembayaran yang valid untuk diproses.');
        }

        $count = 0;
        foreach ($payments as $payment) {
            if ($request->action === 'verify') {
                $payment->update([
                    'status' => Payment::STATUS_VERIFIED,
                    'verified_at' => now()
                ]);
                // Update booking status to confirmed when payment is verified
                $payment->booking->update([
                    'status' => 'confirmed'
                ]);
                // Mark room as unavailable since payment is verified
                $payment->booking->room->update([
                    'is_available' => false
                ]);
            } else {
                $payment->update([
                    'status' => Payment::STATUS_REJECTED,
                    'notes' => $request->notes,
                    'verified_at' => null
                ]);
                // If payment rejected, booking should be cancelled
                $payment->booking->update([
                    'status' => 'cancelled'
                ]);
            }
            $count++;
        }

        $message = $request->action === 'verify'
            ? "Berhasil memverifikasi {$count} pembayaran."
            : "Berhasil menolak {$count} pembayaran.";

        return redirect()->back()->with('success', $message);
    }
}
