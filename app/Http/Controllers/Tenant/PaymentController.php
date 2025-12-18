<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.room.boardingHouse'])
            ->whereHas('booking', function ($q) {
                $q->where('user_id', Auth::id());
            });

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $baseQuery = Payment::whereHas('booking', function ($q) {
            $q->where('user_id', Auth::id());
        });

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', Payment::STATUS_PENDING)->count(),
            'verified' => (clone $baseQuery)->where('status', Payment::STATUS_VERIFIED)->count(),
            'rejected' => (clone $baseQuery)->where('status', Payment::STATUS_REJECTED)->count(),
            'total_amount' => (clone $baseQuery)->sum('amount'),
        ];

        return view('tenant.payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        // Get user's active bookings that don't have pending/verified payments
        $availableBookings = Booking::with(['room.boardingHouse'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereDoesntHave('payments', function ($query) {
                $query->whereIn('status', [Payment::STATUS_PENDING, Payment::STATUS_VERIFIED]);
            })
            ->get();

        if ($availableBookings->isEmpty()) {
            return redirect()->route('tenant.payments.index')
                ->with('error', 'Tidak ada booking aktif yang memerlukan pembayaran.');
        }

        return view('tenant.payments.create', compact('availableBookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
            'proof_image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ], [
            'booking_id.required' => 'Pilih booking yang akan dibayar.',
            'booking_id.exists' => 'Booking yang dipilih tidak valid.',
            'amount.required' => 'Jumlah pembayaran harus diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'proof_image.required' => 'Bukti pembayaran harus diupload.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG.',
            'proof_image.max' => 'Ukuran file maksimal 2MB.'
        ]);

        // Verify user owns the booking
        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'pending'])
            ->first();

        if (!$booking) {
            return redirect()->back()
                ->withErrors(['booking_id' => 'Booking tidak valid atau tidak ditemukan.'])
                ->withInput();
        }

        // Check if there's already a pending/verified payment for this booking
        $existingPayment = Payment::where('booking_id', $booking->id)
            ->whereIn('status', [Payment::STATUS_PENDING, Payment::STATUS_VERIFIED])
            ->exists();

        if ($existingPayment) {
            return redirect()->back()
                ->withErrors(['booking_id' => 'Booking ini sudah memiliki pembayaran yang sedang diproses atau telah diverifikasi.'])
                ->withInput();
        }

        // Upload proof image
        $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $request->amount,
            'proof_image' => $proofPath,
            'status' => Payment::STATUS_PENDING
        ]);

        return redirect()->route('tenant.payments.index')
            ->with('success', 'Pembayaran berhasil disubmit. Menunggu verifikasi dari mitra.');
    }

    public function show(Payment $payment)
    {
        // Check authorization
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $payment->load(['booking.room.boardingHouse']);

        return view('tenant.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        // Check authorization and payment status
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return redirect()->route('tenant.payments.show', $payment)
                ->with('error', 'Pembayaran yang sudah diproses tidak dapat diedit.');
        }

        $booking = $payment->booking;
        $booking->load(['room.boardingHouse']);

        return view('tenant.payments.edit', compact('payment', 'booking'));
    }

    public function update(Request $request, Payment $payment)
    {
        // Check authorization and payment status
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return redirect()->route('tenant.payments.show', $payment)
                ->with('error', 'Pembayaran yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'proof_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ], [
            'amount.required' => 'Jumlah pembayaran harus diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG.',
            'proof_image.max' => 'Ukuran file maksimal 2MB.'
        ]);

        $updateData = ['amount' => $request->amount];

        // Handle new proof image upload
        if ($request->hasFile('proof_image')) {
            // Delete old image
            if ($payment->proof_image && Storage::disk('public')->exists($payment->proof_image)) {
                Storage::disk('public')->delete($payment->proof_image);
            }
            
            // Upload new image
            $updateData['proof_image'] = $request->file('proof_image')->store('payment-proofs', 'public');
        }

        $payment->update($updateData);

        return redirect()->route('tenant.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        // Check authorization and payment status
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return redirect()->route('tenant.payments.index')
                ->with('error', 'Pembayaran yang sudah diproses tidak dapat dihapus.');
        }

        // Delete proof image
        if ($payment->proof_image && Storage::disk('public')->exists($payment->proof_image)) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $payment->delete();

        return redirect()->route('tenant.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function downloadReceipt(Payment $payment)
    {
        // Check authorization
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke bukti pembayaran ini.');
        }

        if ($payment->status !== Payment::STATUS_VERIFIED) {
            return redirect()->back()->with('error', 'Kwitansi hanya tersedia untuk pembayaran yang sudah diverifikasi.');
        }

        // Generate receipt view and return as PDF
        return view('tenant.payments.receipt', compact('payment'));
    }
}
