<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BookingService handles all booking-related business logic
 * 
 * This service encapsulates complex booking operations including:
 * - Date calculations
 * - Amount calculations
 * - Room availability checks
 * - File uploads
 * - Transaction management
 */
class BookingService
{
    /**
     * Create a new booking with all related data
     *
     * @param array $data Validated booking data
     * @param UploadedFile|null $ktpImage KTP image file
     * @param int $userId User ID creating the booking
     * @return Booking
     * @throws \Exception
     */
    public function createBooking(array $data, ?UploadedFile $ktpImage, int $userId): Booking
    {
        return DB::transaction(function () use ($data, $ktpImage, $userId) {
            $room = Room::findOrFail($data['room_id']);
            
            // Calculate dates
            $dates = $this->calculateBookingDates($data);
            
            // Check availability
            if (!$room->isAvailableForBooking($dates['start_date'], $dates['end_date'])) {
                throw new \Exception('Kamar tidak tersedia pada periode yang dipilih.');
            }
            
            // Upload KTP if provided
            $ktpPath = $ktpImage ? $this->uploadKtpImage($ktpImage) : null;
            
            // Calculate amounts
            $amounts = $this->calculateBookingAmounts($room, $dates['duration']);
            
            // Generate booking code
            $bookingCode = $this->generateBookingCode();
            
            // Prepare booking data
            $bookingData = array_merge([
                'user_id' => $userId,
                'room_id' => $data['room_id'],
                'boarding_house_id' => $room->boarding_house_id,
                'booking_code' => $bookingCode,
                'check_in_date' => $dates['start_date'],
                'check_out_date' => $dates['end_date'],
                'duration_months' => $dates['duration'],
                'duration_days' => 0,
                'monthly_price' => $room->price,
                'status' => Booking::STATUS_PENDING,
                'booking_type' => 'monthly',
                'tenant_identity_number' => $data['tenant_identity_number'] ?? null,
                'ktp_image' => $ktpPath,
                'notes' => $data['notes'] ?? null,
            ], $amounts);
            
            $booking = Booking::create($bookingData);
            
            Log::info('Booking created', [
                'booking_id' => $booking->id,
                'user_id' => $userId,
                'room_id' => $room->id
            ]);
            
            return $booking;
        });
    }

    /**
     * Update an existing booking
     *
     * @param Booking $booking
     * @param array $data
     * @return Booking
     * @throws \Exception
     */
    public function updateBooking(Booking $booking, array $data): Booking
    {
        // Only allow updating pending bookings
        if (!$booking->isPending()) {
            throw new \Exception('Booking tidak dapat diubah karena sudah dikonfirmasi.');
        }

        return DB::transaction(function () use ($booking, $data) {
            $room = Room::findOrFail($data['room_id']);
            
            // Calculate new dates
            $dates = $this->calculateBookingDatesFromRange($data);
            
            // Check availability (excluding current booking)
            if (!$room->isAvailableForBooking($dates['start_date'], $dates['end_date'], $booking->id)) {
                throw new \Exception('Kamar tidak tersedia pada periode yang dipilih.');
            }
            
            // Recalculate amounts if room or duration changed
            $amounts = $this->calculateBookingAmounts($room, $dates['duration']);
            
            // Update booking data
            $booking->update(array_merge([
                'room_id' => $data['room_id'],
                'boarding_house_id' => $room->boarding_house_id,
                'check_in_date' => $dates['start_date'],
                'check_out_date' => $dates['end_date'],
                'duration_months' => $dates['duration'],
                'monthly_price' => $room->price,
                'notes' => $data['notes'] ?? $booking->notes,
            ], $amounts));
            
            Log::info('Booking updated', [
                'booking_id' => $booking->id,
                'room_id' => $room->id
            ]);
            
            return $booking->fresh();
        });
    }

    /**
     * Cancel a booking
     *
     * @param Booking $booking
     * @param string|null $reason
     * @return Booking
     * @throws \Exception
     */
    public function cancelBooking(Booking $booking, ?string $reason = null): Booking
    {
        if ($booking->status === Booking::STATUS_CANCELLED) {
            throw new \Exception('Booking sudah dibatalkan sebelumnya.');
        }

        if ($booking->status === Booking::STATUS_COMPLETED) {
            throw new \Exception('Booking yang sudah selesai tidak dapat dibatalkan.');
        }

        return DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'notes' => $reason ? ($booking->notes . "\nAlasan pembatalan: " . $reason) : $booking->notes
            ]);

            Log::info('Booking cancelled', [
                'booking_id' => $booking->id,
                'reason' => $reason
            ]);

            return $booking;
        });
    }

    /**
     * Calculate booking dates from start_date and duration
     *
     * @param array $data
     * @return array
     */
    private function calculateBookingDates(array $data): array
    {
        $startDate = Carbon::parse($data['start_date']);
        $duration = (int) $data['duration'];
        $endDate = $startDate->copy()->addMonths($duration);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $duration
        ];
    }

    /**
     * Calculate booking dates from start_date and end_date
     *
     * @param array $data
     * @return array
     */
    private function calculateBookingDatesFromRange(array $data): array
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $duration = $startDate->diffInMonths($endDate);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => max(1, $duration) // Minimum 1 month
        ];
    }

    /**
     * Calculate all booking amounts
     *
     * @param Room $room
     * @param int $durationMonths
     * @return array
     */
    private function calculateBookingAmounts(Room $room, int $durationMonths): array
    {
        $totalAmount = $durationMonths * $room->price;
        $depositAmount = 0; // Can be configured
        $adminFee = 0;      // Can be configured
        $discountAmount = 0; // Can be configured

        return [
            'deposit_amount' => $depositAmount,
            'admin_fee' => $adminFee,
            'discount_amount' => $discountAmount,
            'final_amount' => $totalAmount + $depositAmount + $adminFee - $discountAmount
        ];
    }

    /**
     * Upload KTP image to storage
     *
     * @param UploadedFile $file
     * @return string Path to uploaded file
     */
    private function uploadKtpImage(UploadedFile $file): string
    {
        return $file->store('ktp-images', 'public');
    }

    /**
     * Generate unique booking code
     *
     * @return string
     */
    private function generateBookingCode(): string
    {
        return 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Get bookings for a specific user with filters
     *
     * @param int $userId
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserBookings(int $userId, array $filters = [])
    {
        $query = Booking::where('user_id', $userId)
            ->with(['room.boardingHouse', 'payments']);

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply date range filter
        if (!empty($filters['start_date'])) {
            $query->where('check_in_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('check_in_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
