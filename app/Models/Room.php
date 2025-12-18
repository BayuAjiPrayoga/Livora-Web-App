<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $boarding_house_id
 * @property string $name
 * @property string|null $description
 * @property numeric $price
 * @property int $capacity
 * @property numeric|null $size
 * @property array<array-key, mixed>|null $images
 * @property bool $is_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BoardingHouse $boardingHouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Facility> $facilities
 * @property-read int|null $facilities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereBoardingHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'boarding_house_id', 'name', 'description', 'price',
        'capacity', 'size', 'images', 'is_available'
    ];

    protected $casts = [
        'images' => 'array',
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'size' => 'decimal:2',
    ];

    public function boardingHouse(): BelongsTo
    {
        return $this->belongsTo(BoardingHouse::class, 'boarding_house_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class);
    }

    // Helper methods for booking availability
    public function isAvailableForBooking($checkIn, $checkOut, $excludeBookingId = null): bool
    {
        // Note: Don't check is_available flag here, check actual bookings instead
        // The is_available flag is just for manually marking rooms as unavailable
        
        // Check for overlapping bookings (confirmed, active status OR paid bookings)
        $query = $this->bookings()
            ->where(function($q) {
                $q->whereIn('status', ['confirmed', 'active'])
                  ->orWhere(function($subQ) {
                      // Also block if booking is confirmed and has verified payment
                      $subQ->where('status', 'confirmed')
                           ->whereHas('payments', function($payQuery) {
                               $payQuery->where('status', 'verified');
                           });
                  });
            });
            
        // Exclude specific booking if provided (for updates)
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $overlapping = $query->where(function ($query) use ($checkIn, $checkOut) {
            $query->where(function ($subQuery) use ($checkIn, $checkOut) {
                // New booking starts during existing booking
                $subQuery->where('check_in_date', '<=', $checkIn)
                         ->where('check_out_date', '>', $checkIn);
            })
            ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                // New booking ends during existing booking  
                $subQuery->where('check_in_date', '<', $checkOut)
                         ->where('check_out_date', '>=', $checkOut);
            })
            ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                // New booking completely contains existing booking
                $subQuery->where('check_in_date', '>=', $checkIn)
                         ->where('check_out_date', '<=', $checkOut);
            })
            ->orWhere(function ($subQuery) use ($checkIn, $checkOut) {
                // Existing booking completely contains new booking
                $subQuery->where('check_in_date', '<=', $checkIn)
                         ->where('check_out_date', '>=', $checkOut);
            });
        })->exists();

        return !$overlapping;
    }

    public function getActiveBooking()
    {
        return $this->bookings()
            ->where('status', 'active')
            ->first();
    }

    public function getNextBooking()
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', '>', now())
            ->orderBy('check_in_date')
            ->first();
    }

    public function getCurrentStatus(): string
    {
        // Check if room has active booking
        if ($this->getActiveBooking()) {
            return 'occupied';
        }
        
        // Check if room has confirmed booking with verified payment
        $paidBooking = $this->bookings()
            ->where('status', 'confirmed')
            ->whereHas('payments', function($query) {
                $query->where('status', 'verified');
            })
            ->where('check_in_date', '>', now())
            ->first();
            
        if ($paidBooking) {
            return 'occupied'; // Paid bookings are considered occupied
        }
        
        // Check if room has confirmed booking (not yet paid)
        if ($this->getNextBooking()) {
            return 'reserved';
        }
        
        // Check if room is available
        if ($this->is_available) {
            return 'available';
        }
        
        return 'unavailable';
    }

    public function getStatusLabel(): string
    {
        return match($this->getCurrentStatus()) {
            'occupied' => 'Ditempati',
            'reserved' => 'Sudah Dibooking',
            'available' => 'Tersedia',
            'unavailable' => 'Tidak Tersedia',
            default => 'Unknown'
        };
    }

    public function getStatusColor(): string
    {
        return match($this->getCurrentStatus()) {
            'occupied' => 'red',
            'reserved' => 'yellow', 
            'available' => 'green',
            'unavailable' => 'gray',
            default => 'gray'
        };
    }

    public function hasPaidBooking(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'active'])
            ->whereHas('payments', function($query) {
                $query->where('status', 'verified');
            })
            ->exists();
    }

    public function getPaidBooking()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'active'])
            ->whereHas('payments', function($query) {
                $query->where('status', 'verified');
            })
            ->first();
    }
}
