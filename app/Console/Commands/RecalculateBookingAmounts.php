<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class RecalculateBookingAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:recalculate-amounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate final_amount for bookings that have null or zero values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to recalculate booking amounts...');

        // Get bookings with null or 0 final_amount
        $bookings = Booking::where(function ($query) {
            $query->whereNull('final_amount')
                  ->orWhere('final_amount', 0);
        })->get();

        if ($bookings->isEmpty()) {
            $this->info('No bookings found with null or zero final_amount.');
            return 0;
        }

        $this->info("Found {$bookings->count()} bookings to recalculate.");

        $updated = 0;
        $errors = 0;

        foreach ($bookings as $booking) {
            try {
                // Calculate final_amount based on booking type
                if ($booking->booking_type === 'daily') {
                    // Daily booking: room price per day * duration_days
                    $roomPrice = $booking->room->price_per_day ?? 0;
                    $duration = $booking->duration_days ?? 1;
                    $subtotal = $roomPrice * $duration;
                } else {
                    // Monthly booking: monthly_price * duration_months
                    $roomPrice = $booking->monthly_price ?? 0;
                    $duration = $booking->duration_months ?? 1;
                    $subtotal = $roomPrice * $duration;
                }

                // Add deposit and admin fee
                $deposit = $booking->deposit_amount ?? 0;
                $adminFee = $booking->admin_fee ?? 0;
                $discount = $booking->discount_amount ?? 0;

                // Calculate final amount
                $finalAmount = $subtotal + $deposit + $adminFee - $discount;

                // Update booking
                $booking->final_amount = $finalAmount;
                $booking->save();

                $this->line("✓ Booking #{$booking->id}: Rp " . number_format($finalAmount, 0, ',', '.'));
                $updated++;

            } catch (\Exception $e) {
                $this->error("✗ Booking #{$booking->id}: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->newLine();
        $this->info("Recalculation completed!");
        $this->info("Updated: {$updated} bookings");
        if ($errors > 0) {
            $this->warn("Errors: {$errors} bookings");
        }

        return 0;
    }
}
