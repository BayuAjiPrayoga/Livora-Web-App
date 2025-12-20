<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $payment = $this->route('payment');
        
        // User can only update their own payments that are still pending
        return Auth::check() && 
               $payment->booking->tenant_name === Auth::user()->name && 
               $payment->status === 'pending';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'proof_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Jumlah pembayaran harus diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG.',
            'proof_image.max' => 'Ukuran file maksimal 2MB.'
        ];
    }
}
