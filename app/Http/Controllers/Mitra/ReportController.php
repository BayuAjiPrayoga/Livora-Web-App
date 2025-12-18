<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BoardingHouse;
use App\Models\Payment;
use App\Exports\ReportsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's properties
        $properties = BoardingHouse::where('user_id', $user->id)->get();
        
        // Get current month data
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        // Revenue statistics
        $currentRevenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', Payment::STATUS_VERIFIED)
        ->whereYear('created_at', $currentMonth->year)
        ->whereMonth('created_at', $currentMonth->month)
        ->sum('amount');
        
        $lastMonthRevenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', Payment::STATUS_VERIFIED)
        ->whereYear('created_at', $lastMonth->year)
        ->whereMonth('created_at', $lastMonth->month)
        ->sum('amount');
        
        // Booking statistics
        $currentBookings = Booking::whereHas('room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereYear('created_at', $currentMonth->year)
        ->whereMonth('created_at', $currentMonth->month)
        ->count();
        
        $lastMonthBookings = Booking::whereHas('room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereYear('created_at', $lastMonth->year)
        ->whereMonth('created_at', $lastMonth->month)
        ->count();
        
        $activeBookings = Booking::whereHas('room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', 'active')
        ->count();
        
        // Occupancy rate
        $totalRooms = DB::table('rooms')
            ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
            ->where('boarding_houses.user_id', $user->id)
            ->count();
            
        $occupiedRooms = DB::table('rooms')
            ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
            ->where('boarding_houses.user_id', $user->id)
            ->where('rooms.is_available', false)
            ->count();
            
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        // Revenue trend (last 6 months)
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', Payment::STATUS_VERIFIED)
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->sum('amount');
            
            $revenueTrend[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        // Top performing properties
        $topProperties = BoardingHouse::where('user_id', $user->id)
            ->withCount(['rooms as total_rooms'])
            ->with(['rooms.bookings.payments' => function($query) {
                $query->where('status', Payment::STATUS_VERIFIED);
            }])
            ->get()
            ->map(function($property) {
                $totalRevenue = 0;
                foreach($property->rooms as $room) {
                    foreach($room->bookings as $booking) {
                        $totalRevenue += $booking->payments->where('status', Payment::STATUS_VERIFIED)->sum('amount');
                    }
                }
                $property->total_revenue = $totalRevenue;
                
                // Count total bookings for this property
                $totalBookings = 0;
                foreach($property->rooms as $room) {
                    $totalBookings += $room->bookings->count();
                }
                $property->bookings_count = $totalBookings;
                
                return $property;
            })
            ->sortByDesc('total_revenue')
            ->take(5);
        
        $stats = [
            'current_revenue' => $currentRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'revenue_growth' => $lastMonthRevenue > 0 ? round((($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0,
            'current_bookings' => $currentBookings,
            'last_month_bookings' => $lastMonthBookings,
            'booking_growth' => $lastMonthBookings > 0 ? round((($currentBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1) : 0,
            'active_bookings' => $activeBookings,
            'total_properties' => $properties->count(),
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $occupancyRate
        ];
        
        // Ensure revenueTrend has data
        if (empty($revenueTrend)) {
            $revenueTrend = [
                'Jan 2025' => 0,
                'Feb 2025' => 0,
                'Mar 2025' => 0,
                'Apr 2025' => 0,
                'May 2025' => 0,
                'Nov 2025' => $currentRevenue
            ];
        }

        return view('mitra.reports.index', compact(
            'stats', 
            'revenueTrend', 
            'topProperties', 
            'properties'
        ));
    }
    
    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $propertyId = $request->get('property_id');
        
        // Build query
        $query = Payment::with(['booking.room.boardingHouse'])
            ->whereHas('booking.room.boardingHouse', function($q) use ($user, $propertyId) {
                $q->where('user_id', $user->id);
                if ($propertyId) {
                    $q->where('id', $propertyId);
                }
            })
            ->where('status', Payment::STATUS_VERIFIED)
            ->whereBetween('created_at', [$startDate, $endDate]);
            
        $payments = $query->orderByDesc('created_at')->paginate(15);
        
        // Statistics
        $totalRevenue = $query->sum('amount');
        $totalPayments = $query->count();
        $avgPayment = $totalPayments > 0 ? round($totalRevenue / $totalPayments, 0) : 0;
        
        // Daily revenue chart data
        $dailyRevenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user, $propertyId) {
                $q->where('user_id', $user->id);
                if ($propertyId) {
                    $q->where('id', $propertyId);
                }
            })
            ->where('status', Payment::STATUS_VERIFIED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $properties = BoardingHouse::where('user_id', $user->id)->get();
        
        $stats = [
            'total_revenue' => $totalRevenue,
            'total_payments' => $totalPayments,
            'avg_payment' => $avgPayment
        ];
        
        return view('mitra.reports.revenue', compact(
            'payments',
            'stats', 
            'dailyRevenue',
            'properties',
            'startDate',
            'endDate',
            'propertyId'
        ));
    }
    
    /**
     * Occupancy report
     */
    public function occupancy(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $propertyId = $request->get('property_id');
        
        // Get properties with room and booking statistics
        $propertiesQuery = BoardingHouse::where('user_id', $user->id);
        
        if ($propertyId) {
            $propertiesQuery->where('id', $propertyId);
        }
        
        $properties = $propertiesQuery->with(['rooms' => function($q) {
            $q->withCount(['bookings as active_bookings' => function($q) {
                $q->where('status', 'checked_in');
            }]);
        }])
        ->withCount('rooms')
        ->get();
        
        // Calculate occupancy statistics for each property
        $occupancyData = $properties->map(function($property) use ($startDate, $endDate) {
            $totalRooms = $property->rooms_count;
            $occupiedRooms = $property->rooms->sum('active_bookings');
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
            
            // Get booking trend for this property
            $bookingTrend = Booking::whereHas('room', function($q) use ($property) {
                $q->where('boarding_house_id', $property->id);
            })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as bookings')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            return [
                'property' => $property,
                'total_rooms' => $totalRooms,
                'occupied_rooms' => $occupiedRooms,
                'available_rooms' => $totalRooms - $occupiedRooms,
                'occupancy_rate' => $occupancyRate,
                'booking_trend' => $bookingTrend
            ];
        });
        
        // Overall statistics
        $totalRooms = $occupancyData->sum('total_rooms');
        $totalOccupied = $occupancyData->sum('occupied_rooms');
        $overallOccupancyRate = $totalRooms > 0 ? round(($totalOccupied / $totalRooms) * 100, 1) : 0;
        
        $stats = [
            'total_properties' => $properties->count(),
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $totalOccupied,
            'available_rooms' => $totalRooms - $totalOccupied,
            'occupancy_rate' => $overallOccupancyRate
        ];
        
        $allProperties = BoardingHouse::where('user_id', $user->id)->get();
        
        return view('mitra.reports.occupancy', compact(
            'occupancyData',
            'stats',
            'allProperties',
            'startDate',
            'endDate',
            'propertyId'
        ));
    }
    
    /**
     * Export report to PDF
     */
    public function exportPdf()
    {
        $user = Auth::user();
        
        // Get all the same data as index method
        $properties = BoardingHouse::where('user_id', $user->id)->get();
        
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        // Revenue statistics
        $currentRevenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', Payment::STATUS_VERIFIED)
        ->whereYear('created_at', $currentMonth->year)
        ->whereMonth('created_at', $currentMonth->month)
        ->sum('amount');
        
        $lastMonthRevenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', Payment::STATUS_VERIFIED)
        ->whereYear('created_at', $lastMonth->year)
        ->whereMonth('created_at', $lastMonth->month)
        ->sum('amount');
        
        $currentBookings = Booking::whereHas('room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereYear('created_at', $currentMonth->year)
        ->whereMonth('created_at', $currentMonth->month)
        ->count();
        
        $lastMonthBookings = Booking::whereHas('room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereYear('created_at', $lastMonth->year)
        ->whereMonth('created_at', $lastMonth->month)
        ->count();
        
        // Occupancy rate
        $totalRooms = DB::table('rooms')
            ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
            ->where('boarding_houses.user_id', $user->id)
            ->count();
            
        $occupiedRooms = DB::table('rooms')
            ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
            ->where('boarding_houses.user_id', $user->id)
            ->where('rooms.is_available', false)
            ->count();
            
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        // Revenue trend (last 6 months)
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', Payment::STATUS_VERIFIED)
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->sum('amount');
            
            $revenueTrend[$month->format('M Y')] = $revenue;
        }
        
        // Top performing properties
        $topProperties = BoardingHouse::where('user_id', $user->id)
            ->withCount(['rooms as total_rooms'])
            ->with(['rooms.bookings.payments' => function($query) {
                $query->where('status', Payment::STATUS_VERIFIED);
            }])
            ->get()
            ->map(function($property) {
                $totalRevenue = 0;
                foreach($property->rooms as $room) {
                    foreach($room->bookings as $booking) {
                        $totalRevenue += $booking->payments->where('status', Payment::STATUS_VERIFIED)->sum('amount');
                    }
                }
                $property->total_revenue = $totalRevenue;
                
                $totalBookings = 0;
                foreach($property->rooms as $room) {
                    $totalBookings += $room->bookings->count();
                }
                $property->bookings_count = $totalBookings;
                
                return $property;
            })
            ->sortByDesc('total_revenue')
            ->take(5);
        
        $stats = [
            'current_revenue' => $currentRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'revenue_growth' => $lastMonthRevenue > 0 ? round((($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0,
            'current_bookings' => $currentBookings,
            'last_month_bookings' => $lastMonthBookings,
            'booking_growth' => $lastMonthBookings > 0 ? round((($currentBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1) : 0,
            'total_properties' => $properties->count(),
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $occupancyRate
        ];
        
        $pdf = Pdf::loadView('mitra.reports.pdf', compact('stats', 'revenueTrend', 'topProperties'))
            ->setPaper('a4', 'portrait');
            
        return $pdf->download('laporan-livora-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Export report to Excel
     */
    public function exportExcel()
    {
        $user = Auth::user();
        
        // Get all the same data as index method
        $properties = BoardingHouse::where('user_id', $user->id)->get();
        
        $currentMonth = Carbon::now();
        
        // Revenue statistics
        $currentRevenue = Payment::whereHas('booking.room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', Payment::STATUS_VERIFIED)
        ->whereYear('created_at', $currentMonth->year)
        ->whereMonth('created_at', $currentMonth->month)
        ->sum('amount');
        
        $currentBookings = Booking::whereHas('room.boardingHouse', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereYear('created_at', $currentMonth->year)
        ->whereMonth('created_at', $currentMonth->month)
        ->count();
        
        // Occupancy rate
        $totalRooms = DB::table('rooms')
            ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
            ->where('boarding_houses.user_id', $user->id)
            ->count();
            
        $occupiedRooms = DB::table('rooms')
            ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
            ->where('boarding_houses.user_id', $user->id)
            ->where('rooms.is_available', false)
            ->count();
            
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        // Top performing properties
        $topProperties = BoardingHouse::where('user_id', $user->id)
            ->withCount(['rooms as total_rooms'])
            ->with(['rooms.bookings.payments' => function($query) {
                $query->where('status', Payment::STATUS_VERIFIED);
            }])
            ->get()
            ->map(function($property) {
                $totalRevenue = 0;
                foreach($property->rooms as $room) {
                    foreach($room->bookings as $booking) {
                        $totalRevenue += $booking->payments->where('status', Payment::STATUS_VERIFIED)->sum('amount');
                    }
                }
                $property->total_revenue = $totalRevenue;
                
                $totalBookings = 0;
                foreach($property->rooms as $room) {
                    $totalBookings += $room->bookings->count();
                }
                $property->bookings_count = $totalBookings;
                
                return $property;
            })
            ->sortByDesc('total_revenue');
        
        $stats = [
            'current_revenue' => $currentRevenue,
            'current_bookings' => $currentBookings,
            'total_properties' => $properties->count(),
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $occupancyRate
        ];
        
        return Excel::download(new ReportsExport($topProperties, $stats), 'laporan-livora-' . date('Y-m-d') . '.xlsx');
    }
}