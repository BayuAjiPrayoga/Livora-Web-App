<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $booking = $this->route('booking');
        
        return [
            // Date fields (only allow updates if booking is pending or confirmed)
            'check_in_date' => [
                'sometimes',
                'date',
                $booking && in_array($booking->status, ['pending', 'confirmed']) 
                    ? 'after_or_equal:today' 
                    : Rule::in([$booking->check_in_date->format('Y-m-d')])
            ],
            'check_out_date' => [
                'sometimes',
                'date',
                'after:check_in_date'
            ],
            'booking_type' => [
                'sometimes',
                Rule::in(['monthly', 'yearly', 'daily'])
            ],
            
            // Tenant information (always updatable)
            'tenant_name' => 'sometimes|string|max:255',
            'tenant_phone' => 'sometimes|string|max:20',
            'tenant_email' => 'sometimes|email|max:255',
            'tenant_identity_number' => 'sometimes|string|max:16',
            'tenant_address' => 'sometimes|string|max:1000',
            
            // Emergency contact (always updatable)
            'emergency_contact_name' => 'sometimes|nullable|string|max:255',
            'emergency_contact_phone' => 'sometimes|nullable|string|max:20',
            'emergency_contact_relation' => 'sometimes|nullable|string|max:100',
            
            // Pricing (only if booking is pending or confirmed)
            'admin_fee' => 'sometimes|numeric|min:0|max:999999.99',
            'deposit_amount' => 'sometimes|numeric|min:0|max:999999.99',
            'discount_amount' => 'sometimes|numeric|min:0|max:999999.99',
            
            // Additional fields (always updatable)
            'special_requests' => 'sometimes|nullable|string|max:1000',
            'notes' => 'sometimes|nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'check_in_date.date' => 'Format tanggal check-in tidak valid.',
            'check_in_date.after_or_equal' => 'Tanggal check-in tidak boleh kurang dari hari ini.',
            'check_in_date.in' => 'Tanggal check-in tidak dapat diubah untuk booking dengan status ini.',
            'check_out_date.date' => 'Format tanggal check-out tidak valid.',
            'check_out_date.after' => 'Tanggal check-out harus setelah tanggal check-in.',
            'booking_type.in' => 'Tipe booking tidak valid.',
            'tenant_name.string' => 'Nama penyewa harus berupa teks.',
            'tenant_name.max' => 'Nama penyewa maksimal 255 karakter.',
            'tenant_phone.string' => 'Nomor telepon harus berupa teks.',
            'tenant_phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'tenant_email.email' => 'Format email tidak valid.',
            'tenant_email.max' => 'Email maksimal 255 karakter.',
            'tenant_identity_number.string' => 'Nomor identitas harus berupa teks.',
            'tenant_identity_number.max' => 'Nomor identitas maksimal 50 karakter.',
            'emergency_contact_name.string' => 'Nama kontak darurat harus berupa teks.',
            'emergency_contact_name.max' => 'Nama kontak darurat maksimal 255 karakter.',
            'emergency_contact_phone.string' => 'Nomor kontak darurat harus berupa teks.',
            'emergency_contact_phone.max' => 'Nomor kontak darurat maksimal 20 karakter.',
            'admin_fee.numeric' => 'Biaya admin harus berupa angka.',
            'admin_fee.min' => 'Biaya admin tidak boleh negatif.',
            'admin_fee.max' => 'Biaya admin terlalu besar.',
            'deposit_amount.numeric' => 'Jumlah deposit harus berupa angka.',
            'deposit_amount.min' => 'Jumlah deposit tidak boleh negatif.',
            'deposit_amount.max' => 'Jumlah deposit terlalu besar.',
            'discount_amount.numeric' => 'Jumlah diskon harus berupa angka.',
            'discount_amount.min' => 'Jumlah diskon tidak boleh negatif.',
            'discount_amount.max' => 'Jumlah diskon terlalu besar.',
            'special_requests.string' => 'Permintaan khusus harus berupa teks.',
            'special_requests.max' => 'Permintaan khusus maksimal 1000 karakter.',
            'notes.string' => 'Catatan harus berupa teks.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'check_in_date' => 'tanggal check-in',
            'check_out_date' => 'tanggal check-out',
            'booking_type' => 'tipe booking',
            'tenant_name' => 'nama penyewa',
            'tenant_phone' => 'nomor telepon penyewa',
            'tenant_email' => 'email penyewa',
            'tenant_identity_number' => 'nomor identitas',
            'emergency_contact_name' => 'nama kontak darurat',
            'emergency_contact_phone' => 'nomor kontak darurat',
            'admin_fee' => 'biaya admin',
            'deposit_amount' => 'jumlah deposit',
            'discount_amount' => 'jumlah diskon',
            'special_requests' => 'permintaan khusus',
            'notes' => 'catatan',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $booking = $this->route('booking');
            
            // Prevent certain updates based on booking status
            if ($booking && !in_array($booking->status, ['pending', 'confirmed'])) {
                $restrictedFields = ['check_in_date', 'check_out_date', 'booking_type', 'admin_fee', 'deposit_amount'];
                
                foreach ($restrictedFields as $field) {
                    if ($this->has($field)) {
                        $validator->errors()->add($field, "Field {$field} tidak dapat diubah untuk booking dengan status {$booking->status_label}.");
                    }
                }
            }
            
            // Additional validation for date range based on booking type
            if ($this->check_in_date && $this->check_out_date && $this->booking_type) {
                $checkIn = Carbon::parse($this->check_in_date);
                $checkOut = Carbon::parse($this->check_out_date);
                $diffInDays = $checkIn->diffInDays($checkOut);
                
                switch ($this->booking_type) {
                    case 'daily':
                        if ($diffInDays < 1 || $diffInDays > 30) {
                            $validator->errors()->add('check_out_date', 'Untuk booking harian, durasi minimal 1 hari dan maksimal 30 hari.');
                        }
                        break;
                    case 'monthly':
                        if ($diffInDays < 30) {
                            $validator->errors()->add('check_out_date', 'Untuk booking bulanan, durasi minimal 30 hari.');
                        }
                        break;
                    case 'yearly':
                        if ($diffInDays < 365) {
                            $validator->errors()->add('check_out_date', 'Untuk booking tahunan, durasi minimal 365 hari.');
                        }
                        break;
                }
            }
        });
    }
}
