<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Middleware will be handled at route level

    public function index()
    {
        $user = Auth::user();
        
        // Get owner's boarding houses
        $boardingHouses = $user->boardingHouses()->with('rooms')->get();
        $roomIds = $boardingHouses->pluck('rooms')->flatten()->pluck('id');
        
        // Revenue Calculations
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $monthlyRevenue = Booking::whereIn('room_id', $roomIds)
            ->where('status', 'confirmed')
            ->where('created_at', '>=', $currentMonth)
            ->sum('final_amount');
            
        $lastMonthRevenue = Booking::whereIn('room_id', $roomIds)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$lastMonth, $currentMonth])
            ->sum('final_amount');
            
        $totalRevenue = Booking::whereIn('room_id', $roomIds)
            ->where('status', 'confirmed')
            ->sum('final_amount');
        
        // Statistics
        $totalRooms = Room::whereIn('boarding_house_id', $boardingHouses->pluck('id'))->count();
        $occupiedRooms = Room::whereIn('boarding_house_id', $boardingHouses->pluck('id'))
            ->where('is_available', false)->count();
        $activeBookings = Booking::whereIn('room_id', $roomIds)
            ->where('status', 'checked_in')->count();
        $pendingTickets = Ticket::whereIn('room_id', $roomIds)
            ->where('status', 'open')->count();
        
        // Recent Bookings
        $recentBookings = Booking::whereIn('room_id', $roomIds)
            ->with(['user', 'room.boardingHouse'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate revenue growth
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        
        return view('mitra.dashboard', compact(
            'monthlyRevenue',
            'totalRevenue', 
            'revenueGrowth',
            'totalRooms',
            'occupiedRooms',
            'activeBookings',
            'pendingTickets',
            'recentBookings'
        ));
    }
}
