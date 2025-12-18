<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'price_formatted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'capacity' => $this->capacity,
            'size' => $this->size ? (float) $this->size : null,
            'size_formatted' => $this->size ? $this->size . ' m²' : null,
            'is_available' => $this->isCurrentlyAvailable(),
            'thumbnail' => $this->getThumbnailUrl(),
            'images' => $this->getImagesUrl(),
            'facilities' => $this->when(
                $this->relationLoaded('facilities'),
                function () {
                    return $this->facilities->map(function ($facility) {
                        return [
                            'id' => $facility->id,
                            'name' => $facility->name,
                            'icon' => $facility->icon,
                            'description' => $facility->description,
                        ];
                    });
                }
            ),
            'boarding_house' => new BoardingHouseResource($this->whenLoaded('boardingHouse')),
            'boarding_house_id' => $this->boarding_house_id,
            'bookings_count' => $this->when(isset($this->bookings_count), $this->bookings_count),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get thumbnail image URL
     */
    private function getThumbnailUrl(): ?string
    {
        if (!$this->images || !is_array($this->images) || empty($this->images)) {
            return null;
        }

        $firstImage = $this->images[0];

        if (filter_var($firstImage, FILTER_VALIDATE_URL)) {
            return $firstImage;
        }

        return url('storage/' . $firstImage);
    }

    /**
     * Check if room is currently available (no active/confirmed bookings for current date)
     */
    private function isCurrentlyAvailable(): bool
    {
        // Check if room has any active or confirmed bookings for current date
        $now = now();
        
        $hasActiveBooking = $this->bookings()
            ->whereIn('status', ['confirmed', 'active'])
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->exists();
            
        return !$hasActiveBooking;
    }

    /**
     * Get all images URLs
     */
    private function getImagesUrl(): array
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return array_map(function ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return url('storage/' . $image);
        }, $this->images);
    }
}
