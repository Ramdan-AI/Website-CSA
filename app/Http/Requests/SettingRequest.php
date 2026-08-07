<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'about' => ['nullable', 'string'],
            'registration_open' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:png,svg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'site_name.required' => 'Nama situs wajib diisi.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Format logo harus PNG atau SVG.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ];
    }
}
