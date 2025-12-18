<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'owner';
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
            'slug' => 'nullable|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string', 
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ];
    }

}
