<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string $address
 * @property string $city
 * @property string|null $description
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property array<array-key, mixed>|null $images
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardingHouse whereUserId($value)
 * @mixin \Eloquent
 */
class BoardingHouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'address', 'city', 'description',
        'latitude', 'longitude', 'images', 'is_active', 'is_verified',
        'price_range_start', 'price_range_end'
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'price_range_start' => 'decimal:2',
        'price_range_end' => 'decimal:2',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'boarding_house_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'boarding_house_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // Helper methods for booking statistics
    public function getTotalBookingsCount(): int
    {
        return $this->bookings()->count();
    }

    public function getActiveBookingsCount(): int
    {
        return $this->bookings()->where('status', 'checked_in')->count();
    }

    public function getPendingBookingsCount(): int
    {
        return $this->bookings()->where('status', 'pending')->count();
    }

    public function getMonthlyRevenue(): float
    {
        return $this->bookings()
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->whereMonth('created_at', now()->month)
            ->sum('final_amount');
    }

    // Property availability methods
    public function getTotalRoomsCount(): int
    {
        return $this->rooms()->count();
    }

    public function getAvailableRoomsCount(): int
    {
        return $this->rooms()->get()->filter(function($room) {
            return $room->getCurrentStatus() === 'available';
        })->count();
    }

    public function getOccupiedRoomsCount(): int
    {
        return $this->rooms()->get()->filter(function($room) {
            return in_array($room->getCurrentStatus(), ['occupied', 'reserved']);
        })->count();
    }

    public function isFullyOccupied(): bool
    {
        return $this->getAvailableRoomsCount() === 0 && $this->getTotalRoomsCount() > 0;
    }

    public function getPropertyStatus(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        
        if ($this->isFullyOccupied()) {
            return 'unavailable';
        }
        
        $availableCount = $this->getAvailableRoomsCount();
        $totalCount = $this->getTotalRoomsCount();
        
        if ($availableCount === $totalCount) {
            return 'fully_available';
        } elseif ($availableCount > 0) {
            return 'partially_available';
        }
        
        return 'unavailable';
    }

    public function getPropertyStatusLabel(): string
    {
        return match($this->getPropertyStatus()) {
            'fully_available' => 'Semua Kamar Tersedia',
            'partially_available' => $this->getAvailableRoomsCount() . ' dari ' . $this->getTotalRoomsCount() . ' kamar tersedia',
            'unavailable' => 'Semua Kamar Penuh',
            'inactive' => 'Tidak Aktif',
            default => 'Unknown'
        };
    }
}
