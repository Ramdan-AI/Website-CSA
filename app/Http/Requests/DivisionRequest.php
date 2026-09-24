<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DivisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $divisionId = $this->route('division')?->id;

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('divisions', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($divisionId),
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'skills' => [
                'nullable',
                'string',
                'max:255',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.max' => 'Nama divisi maksimal 255 karakter.',
            'name.unique' => 'Nama divisi sudah digunakan.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
