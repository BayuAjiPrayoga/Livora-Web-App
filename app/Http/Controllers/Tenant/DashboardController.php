<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get tenant's bookings with relationships
        $bookings = Booking::where('user_id', $user->id)
            ->with(['room.boardingHouse', 'payments', 'tickets'])
            ->latest()
            ->get();

        // Calculate statistics
        $statistics = $this->calculateStatistics($user, $bookings);
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($user);
        
        // Get current/active booking
        $activeBooking = $bookings->where('status', 'checked_in')->first();
        
        // Get upcoming booking
        $upcomingBooking = $bookings->where('status', 'confirmed')
            ->where('check_in_date', '>=', Carbon::now())
            ->sortBy('check_in_date')
            ->first();
        
        // Get pending payments
        $pendingPayments = Payment::whereHas('booking', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'pending')
        ->with(['booking.room.boardingHouse'])
        ->latest()
        ->take(3)
        ->get();
        
        // Get open tickets
        $openTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'open')
            ->with(['room.boardingHouse'])
            ->latest()
            ->take(3)
            ->get();

        return view('tenant.dashboard', compact(
            'statistics',
            'recentActivities',
            'activeBooking',
            'upcomingBooking',
            'pendingPayments',
            'openTickets',
            'bookings'
        ));
    }

    private function calculateStatistics($user, $bookings)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_bookings' => $bookings->count(),
            'active_bookings' => $bookings->where('status', 'checked_in')->count(),
            'completed_bookings' => $bookings->where('status', 'checked_out')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'total_payments' => Payment::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'verified_payments' => Payment::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'verified')->count(),
            'pending_payments' => Payment::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'pending')->count(),
            'total_spent' => Payment::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'verified')->sum('amount'),
            'monthly_spent' => Payment::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'verified')
            ->where('created_at', '>=', $currentMonth)
            ->sum('amount'),
            'open_tickets' => Ticket::where('user_id', $user->id)
                ->where('status', 'open')
                ->count(),
            'resolved_tickets' => Ticket::where('user_id', $user->id)
                ->where('status', 'resolved')
                ->count()
        ];
    }

    private function getRecentActivities($user)
    {
        $activities = collect();
        
        // Recent bookings
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['room.boardingHouse'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($booking) {
                $roomName = $booking->room?->name ?? 'N/A';
                $bhName = $booking->room?->boardingHouse?->name ?? 'N/A';
                
                return [
                    'type' => 'booking',
                    'icon' => 'calendar',
                    'title' => 'Booking ' . ($booking->status === 'pending' ? 'dibuat' : 'diupdate'),
                    'description' => 'Kamar ' . $roomName . ' di ' . $bhName,
                    'time' => $booking->updated_at,
                    'status' => $booking->status,
                    'link' => '#' // Could link to booking detail
                ];
            });

        // Recent payments
        $recentPayments = Payment::whereHas('booking', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->latest()
        ->take(5)
        ->with(['booking.room.boardingHouse'])
        ->get()
        ->map(function($payment) {
            $statusText = match($payment->status) {
                'pending' => 'menunggu verifikasi',
                'verified', 'settlement' => 'diverifikasi',
                'rejected' => 'ditolak',
                'expired' => 'kadaluarsa',
                'cancelled' => 'dibatalkan',
                'failed' => 'gagal',
                'refund' => 'refund',
                default => 'menunggu verifikasi'
            };
            
            return [
                'type' => 'payment',
                'icon' => 'credit-card',
                'title' => 'Pembayaran ' . $statusText,
                'description' => 'Rp ' . number_format($payment->amount, 0, ',', '.') . ' untuk booking #' . ($payment->booking?->id ?? 'N/A'),
                'time' => $payment->updated_at,
                'status' => $payment->status,
                'link' => $payment->booking ? route('tenant.payments.show', $payment) : '#'
            ];
        });

        // Recent tickets
        $recentTickets = Ticket::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->with(['room.boardingHouse'])
            ->get()
            ->map(function($ticket) {
                $statusText = match($ticket->status) {
                    'open' => 'dibuka',
                    'in_progress' => 'sedang diproses',
                    'resolved' => 'diselesaikan',
                    'closed' => 'ditutup',
                    default => 'dibuka'
                };
                
                return [
                    'type' => 'ticket',
                    'icon' => 'chat',
                    'title' => 'Tiket ' . $statusText,
                    'description' => $ticket->title ?? 'N/A',
                    'time' => $ticket->updated_at,
                    'status' => $ticket->status,
                    'link' => route('tenant.tickets.show', $ticket)
                ];
            });

        // Combine and sort by time
        $activities = $activities
            ->merge($recentBookings)
            ->merge($recentPayments)
            ->merge($recentTickets)
            ->sortByDesc('time')
            ->take(10);

        return $activities;
    }
}
