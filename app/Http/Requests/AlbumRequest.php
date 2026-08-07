<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
        ];

        // Cover image: nullable, must be an image file when provided
        $rules['cover_image'] = [
            'nullable',
            'image',
            'mimes:jpeg,png,jpg,gif,webp',
            'max:3012'
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'cover_image.image' => 'Cover harus berupa gambar.',
            'cover_image.mimes' => 'Cover harus berformat: jpeg, png, jpg, gif, atau webp.',
            'cover_image.max' => 'Ukuran cover maksimal 3 MB.',
        ];
    }
}