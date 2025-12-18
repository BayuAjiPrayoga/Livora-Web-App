<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update booking status from confirmed to active when check-in date arrives';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();
        
        // Get all confirmed bookings where check_in_date is today or earlier
        $bookings = Booking::where('status', 'confirmed')
            ->whereDate('check_in_date', '<=', $today)
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No bookings to update.');
            Log::info('Booking status check: No bookings found for activation.');
            return 0;
        }

        $updatedCount = 0;
        
        foreach ($bookings as $booking) {
            $booking->update(['status' => 'active']);
            $updatedCount++;
            
            $this->info("Booking #{$booking->booking_number} status changed to active.");
            Log::info("Booking #{$booking->booking_number} automatically activated.", [
                'booking_id' => $booking->id,
                'check_in_date' => $booking->check_in_date,
                'tenant' => $booking->user->name,
                'room' => $booking->room->name,
            ]);
        }

        $this->info("Total bookings updated: {$updatedCount}");
        Log::info("Booking status update completed. {$updatedCount} bookings activated.");
        
        return 0;
    }
}
