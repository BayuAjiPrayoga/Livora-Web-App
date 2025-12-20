<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property int|null $boarding_house_id
 * @property string|null $booking_code
 * @property string $check_in_date
 * @property string $check_out_date
 * @property int $duration_months
 * @property int $duration_days
 * @property string $monthly_price
 * @property string $total_amount
 * @property string $deposit_amount
 * @property string $admin_fee
 * @property string $discount_amount
 * @property string $final_amount
 * @property string $status
 * @property string $booking_type
 * @property string|null $tenant_name
 * @property string|null $tenant_phone
 * @property string|null $tenant_email
 * @property string|null $tenant_identity_number
 * @property string|null $tenant_address
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $ktp_image
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BoardingHouse|null $boardingHouse
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Room $room
 * @property-read \App\Models\User $tenant

 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking confirmed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUserId($value)
 * @mixin \Eloquent
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id', 'boarding_house_id', 'booking_code',
        'check_in_date', 'check_out_date', 'duration_months', 'duration_days',
        'monthly_price', 'deposit_amount', 'admin_fee', 
        'discount_amount', 'final_amount', 'status', 'booking_type',
        'tenant_name', 'tenant_phone', 'tenant_email', 'tenant_identity_number', 
        'tenant_address', 'emergency_contact_name', 'emergency_contact_phone',
        'ktp_image', 'notes'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'monthly_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'duration_months' => 'integer',
        'duration_days' => 'integer'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'room_id', 'room_id');
    }



    // Accessors & Mutators
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_ACTIVE => 'Sedang Menginap',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_CONFIRMED => 'blue',
            self::STATUS_ACTIVE => 'green',
            self::STATUS_COMPLETED => 'gray',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }



    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeConfirmed(): bool
    {
        return $this->isPending();
    }

    public function canBeCheckedIn(): bool
    {
        return $this->isConfirmed() && $this->getAttribute('check_in_date')->isToday();
    }

    public function canBeCheckedOut(): bool
    {
        return $this->isActive();
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function canEditDates(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function getDurationInDays(): int
    {
        return $this->getAttribute('check_in_date')->diffInDays($this->getAttribute('check_out_date'));
    }

    public function getRemainingDays(): int
    {
        if (!$this->isActive()) {
            return 0;
        }
        
        return max(0, Carbon::now()->diffInDays($this->getAttribute('check_out_date'), false));
    }

    public function getBookingTypeLabel(): string
    {
        // Simple booking type based on duration
        if ($this->duration_months >= 12) {
            return 'Sewa Tahunan';
        } elseif ($this->duration_months >= 3) {
            return 'Sewa Bulanan';
        } else {
            return 'Sewa Harian';
        }
    }

    public function getBookingCodeAttribute(): string
    {
        return 'BK-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }




}
