<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BoardingHouse;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get owner dashboard statistics
     */
    public function ownerStats(Request $request)
    {
        $user = $request->user();

        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'data' => null
            ], 401);
        }

        // Check if user is mitra or owner
        if (!in_array($user->role, ['owner', 'mitra'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only property owners can access this endpoint.',
                'data' => null
            ], 403);
        }

        try {
            // Get owner's boarding houses
            $boardingHouses = BoardingHouse::where('user_id', $user->id)->pluck('id');

            // Total properties
            $totalProperties = $boardingHouses->count();

            // Total rooms
            $totalRooms = DB::table('rooms')
                ->whereIn('boarding_house_id', $boardingHouses)
                ->count();

            // Available rooms
            $availableRooms = DB::table('rooms')
                ->whereIn('boarding_house_id', $boardingHouses)
                ->where('is_available', true)
                ->count();

            // Total bookings
            $totalBookings = Booking::whereHas('room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })->count();

            // Bookings by status
            $pendingBookings = Booking::whereHas('room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })->where('status', 'pending')->count();

            $activeBookings = Booking::whereHas('room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })->where('status', 'active')->count();

            $completedBookings = Booking::whereHas('room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })->where('status', 'completed')->count();

            // Pending payments (need verification)
            $pendingPayments = Payment::whereHas('booking.room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })->where('status', 'pending')->count();

            // Total revenue (from verified payments)
            $totalRevenue = Payment::whereHas('booking.room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })->where('status', 'verified')->sum('amount');

            // Revenue this month
            $revenueThisMonth = Payment::whereHas('booking.room', function ($q) use ($boardingHouses) {
                $q->whereIn('boarding_house_id', $boardingHouses);
            })
                ->where('status', 'verified')
                ->whereMonth('verified_at', now()->month)
                ->whereYear('verified_at', now()->year)
                ->sum('amount');

            // Recent bookings
            $recentBookings = Booking::with([
                'room.boardingHouse',
                'user',
                'payments'
            ])
                ->whereHas('room', function ($q) use ($boardingHouses) {
                    $q->whereIn('boarding_house_id', $boardingHouses);
                })
                ->latest()
                ->take(5)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Dashboard statistics retrieved successfully',
                'data' => [
                    'properties' => [
                        'total' => $totalProperties,
                    ],
                    'rooms' => [
                        'total' => $totalRooms,
                        'available' => $availableRooms,
                        'occupied' => $totalRooms - $availableRooms,
                    ],
                    'bookings' => [
                        'total' => $totalBookings,
                        'pending' => $pendingBookings,
                        'active' => $activeBookings,
                        'completed' => $completedBookings,
                    ],
                    'payments' => [
                        'pending_verification' => $pendingPayments,
                    ],
                    'revenue' => [
                        'total' => $totalRevenue,
                        'this_month' => $revenueThisMonth,
                        'total_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                        'this_month_formatted' => 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'),
                    ],
                    'recent_bookings' => $recentBookings->map(function ($booking) {
                        return [
                            'id' => $booking->id,
                            'user_name' => $booking->user->name ?? 'N/A',
                            'boarding_house_name' => $booking->room->boardingHouse->name ?? 'N/A',
                            'room_name' => $booking->room->name ?? 'N/A',
                            'status' => $booking->status,
                            'total_price' => $booking->final_amount,
                            'total_price_formatted' => 'Rp ' . number_format($booking->final_amount, 0, ',', '.'),
                            'start_date' => $booking->check_in_date,
                            'created_at' => $booking->created_at,
                        ];
                    }),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard statistics: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
