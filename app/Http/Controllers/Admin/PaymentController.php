<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.room.boardingHouse'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by boarding house
        if ($request->filled('boarding_house_id')) {
            $query->whereHas('booking.room.boardingHouse', function ($q) use ($request) {
                $q->where('id', $request->boarding_house_id);
            });
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Search by user name or payment ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('booking.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(15);
        
        $boardingHouses = BoardingHouse::select('id', 'name')->get();
        
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'verified' => Payment::where('status', 'verified')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'total_amount' => Payment::where('status', 'verified')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'boardingHouses', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.room.boardingHouse.owner']);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.room.boardingHouse']);
        
        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.payments.show', $payment)
                        ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only allow deletion of rejected or pending payments
        if (!in_array($payment->status, ['pending', 'rejected'])) {
            return back()->with('error', 'Cannot delete this payment.');
        }

        // Delete payment proof file if exists
        if ($payment->payment_proof && Storage::exists($payment->payment_proof)) {
            Storage::delete($payment->payment_proof);
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
                        ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Only pending payments can be verified.');
        }

        $payment->update(['status' => 'verified']);

        return back()->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a payment.
     */
    public function reject(Payment $payment)
    {
        if (!in_array($payment->status, ['pending', 'verified'])) {
            return back()->with('error', 'Cannot reject this payment.');
        }

        $payment->update(['status' => 'rejected']);

        return back()->with('success', 'Payment rejected successfully.');
    }

    /**
     * Bulk verify payments.
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        $updated = Payment::whereIn('id', $request->payment_ids)
                         ->where('status', 'pending')
                         ->update(['status' => 'verified']);

        return back()->with('success', "{$updated} payments verified successfully.");
    }

    /**
     * Bulk reject payments.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        $updated = Payment::whereIn('id', $request->payment_ids)
                         ->whereIn('status', ['pending', 'verified'])
                         ->update(['status' => 'rejected']);

        return back()->with('success', "{$updated} payments rejected successfully.");
    }

    /**
     * Download payment proof.
     */
    public function downloadProof(Payment $payment)
    {
        if (!$payment->payment_proof || !Storage::exists($payment->payment_proof)) {
            return back()->with('error', 'Payment proof not found.');
        }

        return Storage::download($payment->payment_proof, 
            'payment_proof_' . $payment->id . '.' . pathinfo($payment->payment_proof, PATHINFO_EXTENSION)
        );
    }

    /**
     * Export payments data.
     */
    public function export(Request $request)
    {
        $payments = Payment::with(['booking.user', 'booking.room.boardingHouse'])
                          ->when($request->status, function ($query, $status) {
                              return $query->where('status', $status);
                          })
                          ->when($request->start_date, function ($query, $startDate) {
                              return $query->where('created_at', '>=', $startDate);
                          })
                          ->when($request->end_date, function ($query, $endDate) {
                              return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                          })
                          ->get();

        $filename = 'payments_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Booking ID',
                'User Name',
                'User Email', 
                'Boarding House',
                'Room Number',
                'Amount',
                'Status',
                'Verified At',
                'Created At'
            ]);

            foreach ($payments as $payment) {
                // Format verified_at date
                $verifiedDate = null;
                if ($payment->verified_at !== null) {
                    /** @var \Illuminate\Support\Carbon $verifiedAt */
                    $verifiedAt = $payment->verified_at;
                    $verifiedDate = $verifiedAt->format('Y-m-d H:i:s');
                }
                
                fputcsv($file, [
                    $payment->id,
                    $payment->booking_id,
                    $payment->booking->user->name ?? '',
                    $payment->booking->user->email ?? '',
                    $payment->booking->room->boardingHouse->name ?? '',
                    $payment->booking->room->name ?? (string)$payment->booking->room->id,
                    (string)$payment->amount,
                    $payment->status,
                    $verifiedDate,
                    $payment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}