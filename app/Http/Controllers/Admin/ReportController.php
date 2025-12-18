<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\BoardingHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show revenue reports.
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Default date range based on period
        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'week':
                    $startDate = now()->startOfWeek()->format('Y-m-d');
                    $endDate = now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'year':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->endOfYear()->format('Y-m-d');
                    break;
                default: // month
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        }

        // Revenue statistics
        $totalRevenue = Payment::where('status', 'verified')
                              ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                              ->sum('amount');

        $previousPeriodRevenue = Payment::where('status', 'verified')
                                       ->whereBetween('created_at', [
                                           Carbon::parse($startDate)->subDays(Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate)) + 1),
                                           Carbon::parse($startDate)->subDay()
                                       ])
                                       ->sum('amount');

        $revenueGrowth = $previousPeriodRevenue > 0 ? 
            (($totalRevenue - $previousPeriodRevenue) / $previousPeriodRevenue) * 100 : 0;

        // Revenue by boarding house
        $revenueByProperty = Payment::select('boarding_houses.name', DB::raw('SUM(payments.amount) as total_revenue'))
                                   ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                                   ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                                   ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
                                   ->where('payments.status', 'verified')
                                   ->whereBetween('payments.created_at', [$startDate, $endDate . ' 23:59:59'])
                                   ->groupBy('boarding_houses.id', 'boarding_houses.name')
                                   ->orderBy('total_revenue', 'desc')
                                   ->get();

        // Daily revenue chart data
        $dailyRevenue = Payment::select(
                                DB::raw('DATE(created_at) as date'),
                                DB::raw('SUM(amount) as total')
                            )
                            ->where('status', 'verified')
                            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                            ->groupBy(DB::raw('DATE(created_at)'))
                            ->orderBy('date')
                            ->get();

        return view('admin.reports.revenue', compact(
            'totalRevenue', 'revenueGrowth', 'revenueByProperty', 'dailyRevenue',
            'period', 'startDate', 'endDate'
        ));
    }

    /**
     * Show occupancy reports.
     */
    public function occupancy(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Default date range
        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'week':
                    $startDate = now()->startOfWeek()->format('Y-m-d');
                    $endDate = now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'year':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->endOfYear()->format('Y-m-d');
                    break;
                default:
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        }

        // Overall occupancy statistics
        $totalRooms = DB::table('rooms')
                        ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
                        ->where('boarding_houses.is_active', true)
                        ->count();

        $occupiedRooms = Booking::whereIn('status', ['active', 'confirmed'])
                               ->whereBetween('start_date', [$startDate, $endDate])
                               ->distinct('room_id')
                               ->count();

        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Occupancy by boarding house
        $occupancyByProperty = DB::table('boarding_houses')
                                ->select(
                                    'boarding_houses.name',
                                    DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
                                    DB::raw('COUNT(DISTINCT CASE WHEN bookings.status IN ("active", "confirmed") 
                                                  AND bookings.start_date BETWEEN "' . $startDate . '" AND "' . $endDate . '" 
                                                  THEN bookings.room_id END) as occupied_rooms')
                                )
                                ->leftJoin('rooms', 'boarding_houses.id', '=', 'rooms.boarding_house_id')
                                ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
                                ->where('boarding_houses.is_active', true)
                                ->groupBy('boarding_houses.id', 'boarding_houses.name')
                                ->get()
                                ->map(function ($item) {
                                    $item->occupancy_rate = $item->total_rooms > 0 ? 
                                        ($item->occupied_rooms / $item->total_rooms) * 100 : 0;
                                    return $item;
                                });

        return view('admin.reports.occupancy', compact(
            'totalRooms', 'occupiedRooms', 'occupancyRate', 'occupancyByProperty',
            'period', 'startDate', 'endDate'
        ));
    }

    /**
     * Show performance reports.
     */
    public function performance(Request $request)
    {
        $period = $request->get('period', 'month');

        // User growth
        $userGrowth = User::select(
                            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                            DB::raw('COUNT(*) as count')
                        )
                        ->where('created_at', '>=', now()->subYear())
                        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                        ->orderBy('month')
                        ->get();

        // Booking trends
        $bookingTrends = Booking::select(
                                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                                DB::raw('COUNT(*) as count'),
                                'status'
                            )
                            ->where('created_at', '>=', now()->subYear())
                            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'status')
                            ->orderBy('month')
                            ->get()
                            ->groupBy('month');

        // Top performing properties
        $topProperties = BoardingHouse::select(
                                'boarding_houses.name',
                                DB::raw('COUNT(bookings.id) as total_bookings'),
                                DB::raw('SUM(payments.amount) as total_revenue'),
                                DB::raw('AVG(CASE WHEN bookings.status = "completed" THEN 5 ELSE 0 END) as avg_rating')
                            )
                            ->leftJoin('rooms', 'boarding_houses.id', '=', 'rooms.boarding_house_id')
                            ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
                            ->leftJoin('payments', 'bookings.id', '=', 'payments.booking_id')
                            ->where('boarding_houses.is_active', true)
                            ->groupBy('boarding_houses.id', 'boarding_houses.name')
                            ->orderBy('total_revenue', 'desc')
                            ->limit(10)
                            ->get();

        return view('admin.reports.performance', compact(
            'userGrowth', 'bookingTrends', 'topProperties', 'period'
        ));
    }

    /**
     * Export report data.
     */
    public function export(Request $request, $type)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        switch ($type) {
            case 'revenue':
                return $this->exportRevenue($startDate, $endDate);
            case 'occupancy':
                return $this->exportOccupancy($startDate, $endDate);
            case 'performance':
                return $this->exportPerformance($startDate, $endDate);
            default:
                return back()->with('error', 'Invalid report type.');
        }
    }

    /**
     * Export revenue report.
     */
    private function exportRevenue($startDate, $endDate)
    {
        $data = Payment::select(
                        'payments.id',
                        'payments.amount',
                        'payments.created_at',
                        'boarding_houses.name as property_name',
                        'users.name as user_name'
                    )
                    ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
                    ->join('users', 'bookings.user_id', '=', 'users.id')
                    ->where('payments.status', 'verified')
                    ->whereBetween('payments.created_at', [$startDate, $endDate . ' 23:59:59'])
                    ->orderBy('payments.created_at', 'desc')
                    ->get();

        return $this->generateCSV($data, 'revenue_report', [
            'Payment ID', 'Amount', 'Date', 'Property', 'User'
        ]);
    }

    /**
     * Export occupancy report.
     */
    private function exportOccupancy($startDate, $endDate)
    {
        $data = Booking::select(
                        'bookings.id',
                        'boarding_houses.name as property_name',
                        'rooms.room_number',
                        'users.name as user_name',
                        'bookings.start_date',
                        'bookings.end_date',
                        'bookings.status'
                    )
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->join('boarding_houses', 'rooms.boarding_house_id', '=', 'boarding_houses.id')
                    ->join('users', 'bookings.user_id', '=', 'users.id')
                    ->whereBetween('bookings.start_date', [$startDate, $endDate])
                    ->orderBy('bookings.start_date', 'desc')
                    ->get();

        return $this->generateCSV($data, 'occupancy_report', [
            'Booking ID', 'Property', 'Room', 'User', 'Start Date', 'End Date', 'Status'
        ]);
    }

    /**
     * Show user reports.
     */
    public function users(Request $request)
    {
        $period = $request->get('period', 'month');

        // User registration trends
        $userRegistrations = User::select(
                                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                                DB::raw('COUNT(*) as count'),
                                'role'
                            )
                            ->where('created_at', '>=', now()->subYear())
                            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'role')
                            ->orderBy('month')
                            ->get()
                            ->groupBy('month');

        // User activity statistics
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'owner_count' => User::where('role', 'owner')->count(),
            'tenant_count' => User::where('role', 'tenant')->count(),
        ];

        // Total users
        $totalUsers = User::count();

        // Daily active users (users with activity in last 24 hours)
        $dailyActiveUsers = User::where('updated_at', '>=', now()->subDay())->count();

        // Weekly active users (users with activity in last 7 days)
        $weeklyActiveUsers = User::where('updated_at', '>=', now()->subWeek())->count();

        // Monthly active users (users with activity in last 30 days)
        $monthlyActiveUsers = User::where('updated_at', '>=', now()->subMonth())->count();

        // Most active users by bookings
        $topUsers = User::select(
                            'users.id',
                            'users.name',
                            'users.email',
                            'users.role',
                            'users.created_at',
                            DB::raw('COUNT(bookings.id) as bookings_count'),
                            DB::raw('SUM(payments.amount) as total_spent')
                        )
                        ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
                        ->leftJoin('payments', 'bookings.id', '=', 'payments.booking_id')
                        ->where('users.role', 'tenant')
                        ->groupBy('users.id', 'users.name', 'users.email', 'users.role', 'users.created_at')
                        ->orderBy('bookings_count', 'desc')
                        ->limit(10)
                        ->get();

        // Calculate role statistics for cards
        $propertyOwners = $userStats['owner_count'];
        $tenants = $userStats['tenant_count'];
        $ownerPercentage = $totalUsers > 0 ? round(($propertyOwners / $totalUsers) * 100, 1) : 0;
        $tenantPercentage = $totalUsers > 0 ? round(($tenants / $totalUsers) * 100, 1) : 0;

        // Calculate user growth (this month vs last month)
        $currentMonthUsers = User::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();
        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
                              ->whereYear('created_at', now()->subMonth()->year)
                              ->count();
        $userGrowth = $lastMonthUsers > 0 ? round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;

        // Prepare registration chart data (last 12 months)
        $registrationChartLabels = [];
        $registrationChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $registrationChartLabels[] = $date->format('M Y');
            $registrationChartData[] = isset($userRegistrations[$monthKey]) 
                ? $userRegistrations[$monthKey]->sum('count') 
                : 0;
        }

        // Prepare role distribution data
        $roleDistributionData = [
            $userStats['tenant_count'],
            $userStats['owner_count'],
            $userStats['admin_count']
        ];

        return view('admin.reports.users', compact(
            'userRegistrations', 'userStats', 'topUsers', 'period',
            'totalUsers', 'dailyActiveUsers', 'weeklyActiveUsers', 'monthlyActiveUsers',
            'propertyOwners', 'tenants', 'ownerPercentage', 'tenantPercentage', 'userGrowth',
            'registrationChartLabels', 'registrationChartData', 'roleDistributionData'
        ));
    }

    /**
     * Export performance report.
     */
    private function exportPerformance($startDate, $endDate)
    {
        $data = BoardingHouse::select(
                        'boarding_houses.name',
                        DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
                        DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
                        DB::raw('SUM(payments.amount) as total_revenue')
                    )
                    ->leftJoin('rooms', 'boarding_houses.id', '=', 'rooms.boarding_house_id')
                    ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
                    ->leftJoin('payments', function($join) use ($startDate, $endDate) {
                        $join->on('bookings.id', '=', 'payments.booking_id')
                             ->where('payments.status', 'verified')
                             ->whereBetween('payments.created_at', [$startDate, $endDate . ' 23:59:59']);
                    })
                    ->where('boarding_houses.is_active', true)
                    ->groupBy('boarding_houses.id', 'boarding_houses.name')
                    ->get();

        return $this->generateCSV($data, 'performance_report', [
            'Property Name', 'Total Bookings', 'Total Rooms', 'Total Revenue'
        ]);
    }

    /**
     * Export revenue report as CSV.
     */
    public function revenueExport(Request $request)
    {
        return $this->export($request, 'revenue');
    }

    /**
     * Export occupancy report as CSV.
     */
    public function occupancyExport(Request $request)
    {
        return $this->export($request, 'occupancy');
    }

    /**
     * Export performance report as CSV.
     */
    public function performanceExport(Request $request)
    {
        return $this->export($request, 'performance');
    }

    /**
     * Export users report as CSV.
     */
    public function usersExport(Request $request)
    {
        return $this->export($request, 'users');
    }

    /**
     * Generate CSV file.
     */
    private function generateCSV($data, $filename, $headers)
    {
        $filename = $filename . '_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $csvHeaders = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($data as $row) {
                fputcsv($file, $row->toArray());
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $csvHeaders);
    }
}