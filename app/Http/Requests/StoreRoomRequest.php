<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'capacity' => 'required|integer|min:1|max:10',
            'size' => 'nullable|numeric|min:0|max:999.99',
            'is_available' => 'required|boolean',
            'facilities' => 'required|array|min:1',
            'facilities.*' => 'exists:facilities,id',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kamar harus diisi.',
            'name.max' => 'Nama kamar maksimal 255 karakter.',
            'description.max' => 'Deskripsi kamar maksimal 1000 karakter.',
            'price.required' => 'Harga kamar harus diisi.',
            'price.numeric' => 'Harga kamar harus berupa angka.',
            'price.min' => 'Harga kamar tidak boleh negatif.',
            'price.max' => 'Harga kamar terlalu besar.',
            'capacity.required' => 'Kapasitas kamar harus diisi.',
            'capacity.integer' => 'Kapasitas kamar harus berupa angka bulat.',
            'capacity.min' => 'Kapasitas kamar minimal 1 orang.',
            'capacity.max' => 'Kapasitas kamar maksimal 10 orang.',
            'size.numeric' => 'Ukuran kamar harus berupa angka.',
            'size.min' => 'Ukuran kamar tidak boleh negatif.',
            'size.max' => 'Ukuran kamar terlalu besar.',
            'is_available.required' => 'Status ketersediaan harus dipilih.',
            'is_available.boolean' => 'Status ketersediaan tidak valid.',
            'facilities.required' => 'Pilih minimal 1 fasilitas kamar.',
            'facilities.array' => 'Format fasilitas tidak valid.',
            'facilities.min' => 'Pilih minimal 1 fasilitas kamar.',
            'facilities.*.exists' => 'Fasilitas yang dipilih tidak valid.',
            'images.array' => 'Format foto tidak valid.',
            'images.max' => 'Maksimal 10 foto per kamar.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format foto harus JPEG, JPG, atau PNG.',
            'images.*.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama kamar',
            'description' => 'deskripsi kamar',
            'price' => 'harga kamar',
            'capacity' => 'kapasitas kamar',
            'size' => 'ukuran kamar',
            'is_available' => 'status ketersediaan',
            'facilities' => 'fasilitas kamar',
            'images' => 'foto kamar',
        ];
    }
}
