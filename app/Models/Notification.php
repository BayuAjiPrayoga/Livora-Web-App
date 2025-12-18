<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'is_email_sent',
        'is_push_sent',
        'priority',
        'action_url',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'is_email_sent' => 'boolean',
        'is_push_sent' => 'boolean',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if the notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if the notification is unread.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for filtering by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by priority.
     */
    public function scopeOfPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Get the time ago format for created_at.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'high' => 'bg-red-100 text-red-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the notification icon based on type.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'booking_created' => 'calendar',
            'booking_confirmed' => 'check-circle',
            'booking_cancelled' => 'x-circle',
            'payment_verified' => 'credit-card',
            'payment_rejected' => 'x-circle',
            'ticket_created' => 'chat-bubble-left-ellipsis',
            'ticket_updated' => 'chat-bubble-left-right',
            'ticket_resolved' => 'check-badge',
            'property_approved' => 'home',
            'property_rejected' => 'home-modern',
            'welcome' => 'gift',
            'reminder' => 'bell',
            default => 'information-circle',
        };
    }

    /**
     * Create a notification for a user.
     */
    public static function createForUser(
        User $user, 
        string $type, 
        string $title, 
        string $message, 
        array $data = [], 
        string $priority = 'medium',
        ?string $actionUrl = null
    ): self {
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $priority,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Create booking notification.
     */
    public static function bookingCreated(Booking $booking): self
    {
        return self::createForUser(
            $booking->user,
            'booking_created',
            'Booking Created Successfully',
            "Your booking for {$booking->room->boardingHouse->name} has been created and is awaiting confirmation.",
            ['booking_id' => $booking->id],
            'medium',
            route('tenant.bookings.show', $booking)
        );
    }

    /**
     * Create payment verification notification.
     */
    public static function paymentVerified(Payment $payment): self
    {
        return self::createForUser(
            $payment->booking->user,
            'payment_verified',
            'Payment Verified',
            "Your payment of Rp " . number_format($payment->amount) . " has been verified successfully.",
            ['payment_id' => $payment->id, 'booking_id' => $payment->booking_id],
            'high',
            route('tenant.payments.show', $payment)
        );
    }

    /**
     * Create ticket update notification.
     */
    public static function ticketUpdated(Ticket $ticket): self
    {
        return self::createForUser(
            $ticket->user,
            'ticket_updated',
            'Ticket Updated',
            "Your support ticket '#{$ticket->id}' has been updated to {$ticket->status} status.",
            ['ticket_id' => $ticket->id],
            'medium',
            route('tenant.tickets.show', $ticket)
        );
    }
}
