<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
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
        return [
            // Room selection
            'room_id' => 'required|exists:rooms,id',
            
            // Date fields (matching form field names)
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            
            // Booking type
            'booking_type' => 'required|in:daily,monthly,yearly',
            
            // Tenant information
            'tenant_name' => 'required|string|max:255',
            'tenant_phone' => 'required|string|max:20',
            'tenant_email' => 'required|email|max:255',
            'tenant_identity_number' => 'required|string|max:16',
            'tenant_address' => 'required|string|max:1000',
            
            // Emergency contact
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
            
            // Financial
            'admin_fee' => 'nullable|numeric|min:0|max:999999999',
            'deposit_amount' => 'nullable|numeric|min:0|max:999999999',
            'discount_amount' => 'nullable|numeric|min:0|max:999999999',
            
            // User (tenant) - can be existing user or just guest info
            'user_id' => 'nullable|exists:users,id',
            
            // Additional fields
            'special_requests' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'boarding_house_id.required' => 'Properti harus dipilih.',
            'boarding_house_id.exists' => 'Properti yang dipilih tidak valid.',
            'room_id.required' => 'Kamar harus dipilih.',
            'room_id.exists' => 'Kamar yang dipilih tidak valid.',
            'check_in_date.required' => 'Tanggal check-in harus diisi.',
            'check_in_date.date' => 'Format tanggal check-in tidak valid.',
            'check_in_date.after_or_equal' => 'Tanggal check-in tidak boleh kurang dari hari ini.',
            'check_out_date.required' => 'Tanggal check-out harus diisi.',
            'check_out_date.date' => 'Format tanggal check-out tidak valid.',
            'check_out_date.after' => 'Tanggal check-out harus setelah tanggal check-in.',
            'booking_type.required' => 'Tipe booking harus dipilih.',
            'booking_type.in' => 'Tipe booking tidak valid.',
            'tenant_name.required' => 'Nama penyewa harus diisi.',
            'tenant_name.max' => 'Nama penyewa maksimal 255 karakter.',
            'tenant_phone.required' => 'Nomor telepon penyewa harus diisi.',
            'tenant_phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'tenant_email.required' => 'Email penyewa harus diisi.',
            'tenant_email.email' => 'Format email tidak valid.',
            'tenant_email.max' => 'Email maksimal 255 karakter.',
            'tenant_identity_number.required' => 'Nomor KTP harus diisi.',
            'tenant_identity_number.max' => 'Nomor KTP maksimal 16 karakter.',
            'tenant_address.required' => 'Alamat penyewa harus diisi.',
            'tenant_address.max' => 'Alamat maksimal 1000 karakter.',
            'emergency_contact_name.max' => 'Nama kontak darurat maksimal 255 karakter.',
            'emergency_contact_phone.max' => 'Nomor kontak darurat maksimal 20 karakter.',
            'emergency_contact_relation.max' => 'Hubungan kontak darurat maksimal 100 karakter.',
            'admin_fee.numeric' => 'Biaya admin harus berupa angka.',
            'admin_fee.min' => 'Biaya admin tidak boleh negatif.',
            'admin_fee.max' => 'Biaya admin terlalu besar.',
            'deposit_amount.numeric' => 'Jumlah deposit harus berupa angka.',
            'deposit_amount.min' => 'Jumlah deposit tidak boleh negatif.',
            'deposit_amount.max' => 'Jumlah deposit terlalu besar.',
            'discount_amount.numeric' => 'Jumlah diskon harus berupa angka.',
            'discount_amount.min' => 'Jumlah diskon tidak boleh negatif.',
            'discount_amount.max' => 'Jumlah diskon terlalu besar.',
            'special_requests.max' => 'Permintaan khusus maksimal 1000 karakter.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'room_id' => 'kamar',
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
