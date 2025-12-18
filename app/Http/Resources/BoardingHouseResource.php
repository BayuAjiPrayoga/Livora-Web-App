<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BoardingHouseResource extends JsonResource
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
            'slug' => $this->slug,
            'address' => $this->address,
            'city' => $this->city,
            'description' => $this->description,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'price_range' => [
                'start' => $this->price_range_start ? (float) $this->price_range_start : null,
                'end' => $this->price_range_end ? (float) $this->price_range_end : null,
                'formatted' => $this->price_range_start && $this->price_range_end 
                    ? 'Rp ' . number_format($this->price_range_start, 0, ',', '.') . ' - Rp ' . number_format($this->price_range_end, 0, ',', '.') 
                    : null,
            ],
            'thumbnail' => $this->getThumbnailUrl(),
            'images' => $this->getImagesUrl(),
            'is_active' => (bool) $this->is_active,
            'is_verified' => (bool) ($this->is_verified ?? false),
            'rooms_count' => $this->when(isset($this->rooms_count), $this->rooms_count),
            'available_rooms_count' => $this->when(isset($this->available_rooms_count), $this->available_rooms_count),
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
            'owner' => [
                'id' => $this->user_id,
                'name' => $this->when($this->relationLoaded('owner'), $this->owner?->name),
            ],
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
