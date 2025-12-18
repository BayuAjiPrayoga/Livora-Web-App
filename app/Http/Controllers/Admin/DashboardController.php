<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $statistics = $this->getSystemStatistics();
        $analytics = $this->getSystemAnalytics();
        $recentActivities = $this->getRecentActivities();
        $systemHealth = $this->getSystemHealth();

        // Provide safe fallback data for charts
        if (!isset($analytics['revenue_trends']) || $analytics['revenue_trends']->isEmpty()) {
            $analytics['revenue_trends'] = collect([
                (object)['month' => 'Jan', 'revenue' => 0],
                (object)['month' => 'Feb', 'revenue' => 0],
                (object)['month' => 'Mar', 'revenue' => 0],
            ]);
        }
        
        if (!isset($analytics['user_growth']) || $analytics['user_growth']->isEmpty()) {
            $analytics['user_growth'] = collect([
                (object)['month' => 'Jan', 'users' => 0],
                (object)['month' => 'Feb', 'users' => 0],
                (object)['month' => 'Mar', 'users' => 0],
            ]);
        }

        return view('admin.dashboard', compact(
            'statistics',
            'analytics', 
            'recentActivities',
            'systemHealth'
        ));
    }

    /**
     * Get comprehensive system statistics.
     */
    private function getSystemStatistics()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        return [
            'users' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'owners' => User::where('role', 'owner')->count(),
                'tenants' => User::where('role', 'tenant')->count(),
                'new_this_month' => User::where('created_at', '>=', $lastMonth)->count(),
                'growth_rate' => $this->calculateGrowthRate(
                    User::where('created_at', '>=', $lastMonth)->count(),
                    User::where('created_at', '>=', $lastMonth->copy()->subMonth())
                        ->where('created_at', '<', $lastMonth)->count()
                )
            ],
            'properties' => [
                'total' => BoardingHouse::count(),
                'active' => BoardingHouse::where('is_active', true)->count(),
                'pending' => 0, // Not applicable for current schema
                'suspended' => BoardingHouse::where('is_active', false)->count(),
            ],
            'rooms' => [
                'total' => Room::count(),
                'available' => Room::where('is_available', true)->count(),
                'occupied' => Room::where('is_available', false)->count(),
                'occupancy_rate' => Room::count() > 0 
                    ? round((Room::where('is_available', false)->count() / Room::count()) * 100, 1)
                    : 0
            ],
            'bookings' => [
                'total' => Booking::count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'active' => Booking::whereIn('status', ['checked_in', 'active'])->count(),
                'completed' => Booking::where('status', 'checked_out')->count(),
                'cancelled' => Booking::where('status', 'cancelled')->count(),
                'this_month' => Booking::where('created_at', '>=', $lastMonth)->count(),
            ],
            'payments' => [
                'total' => Payment::count(),
                'pending' => Payment::where('status', 'pending')->count(),
                'verified' => Payment::where('status', 'verified')->count(),
                'rejected' => Payment::where('status', 'rejected')->count(),
                'total_amount' => Payment::where('status', 'verified')->sum('amount'),
                'this_month_amount' => Payment::where('status', 'verified')
                    ->where('created_at', '>=', $lastMonth)->sum('amount'),
            ],
            'tickets' => [
                'total' => Ticket::count(),
                'open' => Ticket::where('status', 'open')->count(),
                'in_progress' => Ticket::where('status', 'in_progress')->count(),
                'resolved' => Ticket::where('status', 'resolved')->count(),
                'closed' => Ticket::where('status', 'closed')->count(),
                'avg_resolution_time' => $this->getAverageResolutionTime(),
            ]
        ];
    }

    /**
     * Get system analytics data.
     */
    private function getSystemAnalytics()
    {
        $last12Months = collect(range(0, 11))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        return [
            'user_growth' => $this->getUserGrowthData($last12Months),
            'booking_trends' => $this->getBookingTrendsData($last12Months),
            'revenue_trends' => $this->getRevenueTrendsData($last12Months),
            'popular_locations' => $this->getPopularLocations(),
            'booking_status_distribution' => $this->getBookingStatusDistribution(),
            'payment_method_distribution' => $this->getPaymentMethodDistribution(),
        ];
    }

    /**
     * Get recent system activities.
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent user registrations
        $recentUsers = User::with(['boardingHouses'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user_registered',
                    'title' => 'User Baru Terdaftar',
                    'description' => "{$user->name} ({$user->role}) bergabung dengan platform",
                    'time' => $user->created_at,
                    'icon' => 'user-plus',
                    'color' => 'blue'
                ];
            });

        // Recent bookings
        $recentBookings = Booking::with(['user', 'room.boardingHouse'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'type' => 'booking_created',
                    'title' => 'Booking Baru',
                    'description' => "{$booking->user->name} membuat booking di {$booking->room->boardingHouse->name}",
                    'time' => $booking->created_at,
                    'icon' => 'calendar-plus',
                    'color' => 'green'
                ];
            });

        // Recent payments
        $recentPayments = Payment::with(['booking.user', 'booking.room.boardingHouse'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment_received',
                    'title' => 'Pembayaran Diterima',
                    'description' => "Pembayaran Rp " . number_format($payment->amount, 0, ',', '.') . 
                                   " dari {$payment->booking->user->name}",
                    'time' => $payment->created_at,
                    'icon' => 'credit-card',
                    'color' => 'yellow'
                ];
            });

        // Recent tickets
        $recentTickets = Ticket::with(['user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($ticket) {
                return [
                    'type' => 'ticket_created',
                    'title' => 'Tiket Support Baru',
                    'description' => "Tiket #{$ticket->id} - {$ticket->subject} oleh {$ticket->user->name}",
                    'time' => $ticket->created_at,
                    'icon' => 'headphones',
                    'color' => 'red'
                ];
            });

        return $activities
            ->merge($recentUsers)
            ->merge($recentBookings)
            ->merge($recentPayments)
            ->merge($recentTickets)
            ->sortByDesc('time')
            ->take(15)
            ->values();
    }

    /**
     * Get system health metrics.
     */
    private function getSystemHealth()
    {
        $now = Carbon::now();
        $last24Hours = $now->copy()->subDay();

        return [
            'database_status' => $this->checkDatabaseHealth(),
            'storage_usage' => $this->getStorageUsage(),
            'active_users_24h' => User::where('updated_at', '>=', $last24Hours)->count(),
            'error_rate' => $this->calculateErrorRate(),
            'response_time' => $this->getAverageResponseTime(),
            'uptime' => '99.9%', // This would come from external monitoring
        ];
    }

    /**
     * Helper methods for analytics
     */
    private function calculateGrowthRate($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function getUserGrowthData($months)
    {
        return $months->map(function ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            
            return [
                'month' => $start->format('M Y'),
                'users' => User::whereBetween('created_at', [$start, $end])->count()
            ];
        });
    }

    private function getBookingTrendsData($months)
    {
        return $months->map(function ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            
            return [
                'month' => $start->format('M Y'),
                'bookings' => Booking::whereBetween('created_at', [$start, $end])->count()
            ];
        });
    }

    private function getRevenueTrendsData($months)
    {
        return $months->map(function ($month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            
            return [
                'month' => $start->format('M Y'),
                'revenue' => Payment::where('status', 'verified')
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('amount')
            ];
        });
    }

    private function getPopularLocations()
    {
        return BoardingHouse::withCount(['rooms as total_rooms', 'rooms as booked_rooms' => function ($query) {
                $query->where('is_available', false);
            }])
            ->having('total_rooms', '>', 0)
            ->orderByRaw('booked_rooms / total_rooms DESC')
            ->take(5)
            ->get()
            ->map(function ($property) {
                return [
                    'name' => $property->name,
                    'location' => $property->address,
                    'occupancy_rate' => $property->total_rooms > 0 
                        ? round(($property->booked_rooms / $property->total_rooms) * 100, 1)
                        : 0
                ];
            });
    }

    private function getBookingStatusDistribution()
    {
        return Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
    }

    private function getPaymentMethodDistribution()
    {
        // Since payment_method column doesn't exist, return dummy data based on status
        return Payment::select('status', DB::raw('count(*) as count'))
            ->whereNotNull('status')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
    }

    private function getAverageResolutionTime()
    {
        $resolvedTickets = Ticket::where('status', 'resolved')
            ->whereNotNull('resolved_at')
            ->get();

        if ($resolvedTickets->isEmpty()) {
            return 0;
        }

        $totalMinutes = $resolvedTickets->sum(function ($ticket) {
            return $ticket->created_at->diffInMinutes($ticket->resolved_at);
        });

        return round($totalMinutes / $resolvedTickets->count() / 60, 1); // Convert to hours
    }

    private function checkDatabaseHealth()
    {
        try {
            DB::select('SELECT 1');
            return 'healthy';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function getStorageUsage()
    {
        $path = storage_path();
        $bytes = disk_free_space($path);
        $total = disk_total_space($path);
        
        if ($total > 0) {
            return [
                'used_percentage' => round((($total - $bytes) / $total) * 100, 1),
                'free_space' => $this->formatBytes($bytes),
                'total_space' => $this->formatBytes($total)
            ];
        }
        
        return ['used_percentage' => 0, 'free_space' => 'Unknown', 'total_space' => 'Unknown'];
    }

    private function calculateErrorRate()
    {
        // This would typically come from log analysis
        return 0.1; // 0.1% error rate as example
    }

    private function getAverageResponseTime()
    {
        // This would typically come from application monitoring
        return 120; // 120ms as example
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
