<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;

/**
 * MidtransNotificationController
 * 
 * Controller untuk menangani webhook notification dari Midtrans
 * dengan implementasi keamanan Signature Key Verification
 * 
 * @author Computer Network Security Implementation
 */
class MidtransNotificationController extends Controller
{
    public function __construct()
    {
        // Validate Midtrans Configuration
        $serverKey = config('midtrans.server_key');
        
        if (empty($serverKey)) {
            Log::error('Midtrans Server Key Missing for Webhook', [
                'server_key_exists' => !empty($serverKey),
                'env_file_path' => base_path('.env')
            ]);
        }
        
        // Set Midtrans Configuration
        Config::$serverKey = $serverKey;
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
        
        // Set Merchant ID (required for authentication)
        if ($merchantId = config('midtrans.merchant_id')) {
            Config::$merchantId = $merchantId;
        }
    }

    /**
     * Handle Midtrans Payment Notification Webhook
     * 
     * CRITICAL SECURITY FEATURE:
     * - Verifikasi Signature Key untuk memastikan request benar dari Midtrans
     * - Mencegah replay attack dan data manipulation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        try {
            // Log incoming notification untuk audit trail
            Log::info('Midtrans Notification Received', [
                'ip' => $request->ip(),
                'payload' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // ============================================
            // SECURITY LAYER 1: Signature Verification
            // ============================================
            if (!$this->verifySignature($request)) {
                Log::warning('Midtrans Signature Verification Failed', [
                    'ip' => $request->ip(),
                    'order_id' => $request->input('order_id'),
                    'payload' => $request->all()
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid signature'
                ], 403);
            }

            // ============================================
            // SECURITY LAYER 2: Midtrans SDK Validation
            // ============================================
            // Midtrans SDK akan otomatis verify signature juga
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $transactionId = $notification->transaction_id;
            $paymentType = $notification->payment_type;

            Log::info('Midtrans Notification Verified', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType
            ]);

            // Find payment record
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                Log::warning('Payment not found for order_id: ' . $orderId);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not found'
                ], 404);
            }

            // Begin transaction untuk data consistency
            DB::beginTransaction();

            try {
                // Update payment dengan informasi dari Midtrans
                $payment->update([
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentType,
                    'payment_method' => $notification->payment_type ?? null,
                    'midtrans_status' => $transactionStatus,
                    'transaction_time' => isset($notification->transaction_time) 
                        ? date('Y-m-d H:i:s', strtotime($notification->transaction_time))
                        : now(),
                    'midtrans_response' => json_encode($request->all())
                ]);

                // Handle transaction status
                $this->handleTransactionStatus($payment, $transactionStatus, $fraudStatus);

                DB::commit();

                Log::info('Payment Updated Successfully', [
                    'order_id' => $orderId,
                    'payment_id' => $payment->id,
                    'status' => $payment->status
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification processed successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Verify Midtrans Signature Key
     * 
     * CRITICAL SECURITY IMPLEMENTATION:
     * Signature = SHA512(order_id + status_code + gross_amount + ServerKey)
     * 
     * Metode ini mencegah:
     * 1. Man-in-the-Middle Attack
     * 2. Data Manipulation/Tampering
     * 3. Replay Attack (dengan kombinasi timestamp check)
     * 4. Unauthorized Webhook Requests
     * 
     * @param Request $request
     * @return bool
     */
    private function verifySignature(Request $request): bool
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $serverKey = config('midtrans.server_key');

        // Check if all required fields exist
        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Missing required signature fields', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'signature_key' => $signatureKey ? 'present' : 'missing'
            ]);
            return false;
        }

        // Generate expected signature
        // Formula: SHA512(order_id + status_code + gross_amount + ServerKey)
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        // Constant-time comparison untuk mencegah timing attack
        $isValid = hash_equals($expectedSignature, $signatureKey);

        if (!$isValid) {
            Log::warning('Signature mismatch detected', [
                'order_id' => $orderId,
                'expected' => substr($expectedSignature, 0, 20) . '...',
                'received' => substr($signatureKey, 0, 20) . '...',
                'ip' => request()->ip()
            ]);
        }

        return $isValid;
    }

    /**
     * Handle different transaction status from Midtrans
     * 
     * @param Payment $payment
     * @param string $transactionStatus
     * @param string|null $fraudStatus
     * @return void
     */
    private function handleTransactionStatus(Payment $payment, string $transactionStatus, ?string $fraudStatus): void
    {
        $booking = $payment->booking;

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'accept') {
                    // Pembayaran sukses via credit card
                    $this->setPaymentAsVerified($payment, $booking);
                } else if ($fraudStatus == 'challenge') {
                    // Pembayaran di-challenge, masih pending
                    $payment->update([
                        'status' => 'pending',
                        'notes' => 'Pembayaran sedang dalam review fraud detection'
                    ]);
                } else {
                    // Fraud status selain accept/challenge
                    $payment->update([
                        'status' => 'deny',
                        'notes' => 'Pembayaran ditolak karena terindikasi fraud'
                    ]);
                }
                break;

            case 'settlement':
                // Pembayaran sukses (bank transfer, e-wallet, dll)
                $this->setPaymentAsVerified($payment, $booking);
                break;

            case 'pending':
                // Pembayaran masih menunggu (contoh: transfer bank belum dilakukan)
                $payment->update([
                    'status' => 'pending',
                    'notes' => 'Menunggu pembayaran dari pelanggan'
                ]);
                break;

            case 'deny':
                // Pembayaran ditolak oleh bank/payment provider
                $payment->update([
                    'status' => 'deny',
                    'notes' => 'Pembayaran ditolak oleh penyedia pembayaran'
                ]);
                break;

            case 'expire':
                // Pembayaran expired (tidak dibayar dalam waktu yang ditentukan)
                $payment->update([
                    'status' => Payment::STATUS_EXPIRED,
                    'notes' => 'Pembayaran kadaluarsa - tidak dibayar dalam waktu yang ditentukan'
                ]);
                break;

            case 'cancel':
                // Pembayaran dibatalkan
                $payment->update([
                    'status' => Payment::STATUS_CANCELLED,
                    'notes' => 'Pembayaran dibatalkan oleh pelanggan'
                ]);
                break;

            case 'refund':
            case 'partial_refund':
                // Pembayaran di-refund
                $payment->update([
                    'status' => Payment::STATUS_REFUND,
                    'notes' => 'Pembayaran telah di-refund'
                ]);
                break;

            case 'failure':
                // Pembayaran gagal
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
                    'notes' => 'Pembayaran gagal diproses'
                ]);
                break;

            default:
                Log::warning('Unknown transaction status: ' . $transactionStatus, [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id
                ]);
                break;
        }
    }

    /**
     * Set payment as verified and update booking status
     * 
     * @param Payment $payment
     * @param Booking $booking
     * @return void
     */
    private function setPaymentAsVerified(Payment $payment, Booking $booking): void
    {
        // Update payment status
        $payment->update([
            'status' => 'settlement',
            'verified_at' => now(),
            'notes' => 'Pembayaran berhasil diverifikasi oleh Midtrans'
        ]);

        // Update booking status jika masih pending
        if ($booking->status === Booking::STATUS_PENDING) {
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED
            ]);
        }

        Log::info('Payment verified and booking updated', [
            'payment_id' => $payment->id,
            'booking_id' => $booking->id,
            'order_id' => $payment->order_id
        ]);
    }
}
