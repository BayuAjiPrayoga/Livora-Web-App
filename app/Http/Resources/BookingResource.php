<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'room' => [
                'id' => $this->room_id,
                'name' => $this->room?->name,
                'price' => $this->room ? (float) $this->room->price : null,
            ],
            'boarding_house' => [
                'id' => $this->room?->boarding_house_id,
                'name' => $this->room?->boardingHouse?->name,
                'slug' => $this->room?->boardingHouse?->slug,
                'address' => $this->room?->boardingHouse?->address,
                'city' => $this->room?->boardingHouse?->city,
            ],
            'tenant' => [
                'id' => $this->user_id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'phone' => $this->user?->phone,
            ],
            'start_date' => $this->start_date?->format('Y-m-d'),
            'start_date_formatted' => $this->start_date?->format('d M Y'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'end_date_formatted' => $this->end_date?->format('d M Y'),
            'duration' => $this->duration,
            'duration_text' => $this->duration . ' bulan',
            'total_price' => (float) $this->total_price,
            'total_price_formatted' => 'Rp ' . number_format($this->total_price, 0, ',', '.'),
            'final_amount' => $this->final_amount ? (float) $this->final_amount : (float) $this->total_price,
            'final_amount_formatted' => 'Rp ' . number_format($this->final_amount ?? $this->total_price, 0, ',', '.'),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'status_color' => $this->getStatusColor(),
            'notes' => $this->notes,
            'tenant_identity_number' => $this->tenant_identity_number,
            'ktp_image' => $this->ktp_image ? url('storage/' . $this->ktp_image) : null,
            'payments' => $this->when(
                $this->relationLoaded('payments'),
                function () {
                    return $this->payments->map(function ($payment) {
                        return [
                            'id' => $payment->id,
                            'amount' => (float) $payment->amount,
                            'amount_formatted' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                            'status' => $payment->status,
                            'status_label' => ucfirst($payment->status),
                            'proof_image' => $payment->proof_image ? url('storage/' . $payment->proof_image) : null,
                            'notes' => $payment->notes,
                            'verified_at' => $payment->verified_at?->toISOString(),
                            'created_at' => $payment->created_at?->toISOString(),
                        ];
                    });
                }
            ),
            'payments_count' => $this->when(isset($this->payments_count), $this->payments_count),
            'has_verified_payment' => $this->when(
                $this->relationLoaded('payments'),
                function () {
                    return $this->payments->where('status', 'verified')->isNotEmpty();
                }
            ),
            'created_at' => $this->created_at?->toISOString(),
            'created_at_formatted' => $this->created_at?->format('d M Y H:i'),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get status label
     */
    private function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color
     */
    private function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'active' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'primary',
        };
    }
}
