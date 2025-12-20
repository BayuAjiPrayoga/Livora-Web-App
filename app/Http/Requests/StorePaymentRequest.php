<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
            'proof_image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'booking_id.required' => 'Pilih booking yang akan dibayar.',
            'booking_id.exists' => 'Booking yang dipilih tidak valid.',
            'amount.required' => 'Jumlah pembayaran harus diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'proof_image.required' => 'Bukti pembayaran harus diupload.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG.',
            'proof_image.max' => 'Ukuran file maksimal 2MB.'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->booking_id) {
                // Verify user owns the booking
                $booking = Booking::where('id', $this->booking_id)
                    ->where('tenant_name', Auth::user()->name)
                    ->where('status', 'confirmed')
                    ->first();

                if (!$booking) {
                    $validator->errors()->add('booking_id', 'Booking tidak valid atau tidak ditemukan.');
                    return;
                }

                // Check if there's already a pending/successful payment
                $existingPayment = Payment::where('booking_id', $booking->id)
                    ->whereIn('status', ['pending', 'settlement', 'capture'])
                    ->exists();

                if ($existingPayment) {
                    $validator->errors()->add('booking_id', 'Booking ini sudah memiliki pembayaran yang sedang diproses atau telah diverifikasi.');
                }
            }
        });
    }
}
