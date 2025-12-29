<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Create Snap transaction
     * 
     * @param array $params Transaction parameters
     * @return string Snap token
     */
    public function createTransaction(array $params)
    {
        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Midtrans transaction: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction status
     * 
     * @param string $orderId Order ID
     * @return object Transaction status
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return $status;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Handle notification from Midtrans
     * 
     * @param array $notificationData Notification data from Midtrans
     * @return object Notification object
     */
    public function handleNotification(array $notificationData)
    {
        try {
            $notification = new Notification($notificationData);
            return $notification;
        } catch (\Exception $e) {
            throw new \Exception('Failed to handle notification: ' . $e->getMessage());
        }
    }

    /**
     * Build transaction parameters for Snap
     * 
     * @param object $booking Booking model
     * @param object $user User model
     * @return array Transaction parameters
     */
    public function buildTransactionParams($booking, $user)
    {
        $orderId = 'LIVORA-' . $booking->id . '-' . time();

        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->final_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $booking->room_id,
                    'price' => (int) $booking->final_amount,
                    'quantity' => 1,
                    'name' => $booking->room->boardingHouse->name . ' - ' . $booking->room->name,
                ]
            ],
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
                'gopay',
                'shopeepay',
                'qris',
            ],
        ];
    }

    /**
     * Map Midtrans transaction status to payment status
     * 
     * @param string $transactionStatus Midtrans transaction status
     * @return string Payment status
     */
    public function mapTransactionStatus($transactionStatus)
    {
        $statusMap = [
            'capture' => 'settlement',
            'settlement' => 'settlement',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
        ];

        return $statusMap[$transactionStatus] ?? 'pending';
    }
}
