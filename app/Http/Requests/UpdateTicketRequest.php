<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');
        
        // User can only update their own tickets that are still open
        return Auth::check() && 
               $ticket->user_id === Auth::id() && 
               $ticket->status === 'open';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high,urgent'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'room_id.required' => 'Pilih kamar yang berkaitan dengan keluhan.',
            'room_id.exists' => 'Kamar yang dipilih tidak valid.',
            'subject.required' => 'Subjek tiket harus diisi.',
            'subject.max' => 'Subjek tidak boleh lebih dari 255 karakter.',
            'message.required' => 'Pesan tiket harus diisi.',
            'message.max' => 'Pesan tidak boleh lebih dari 1000 karakter.',
            'priority.required' => 'Pilih tingkat prioritas tiket.',
            'priority.in' => 'Tingkat prioritas yang dipilih tidak valid.'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if user has active booking for the selected room
            if ($this->room_id) {
                $hasActiveBooking = Booking::where('tenant_name', Auth::user()->name)
                    ->where('room_id', $this->room_id)
                    ->where('status', 'confirmed')
                    ->exists();

                if (!$hasActiveBooking) {
                    $validator->errors()->add('room_id', 'Anda tidak memiliki booking aktif untuk kamar ini.');
                }
            }
        });
    }
}
