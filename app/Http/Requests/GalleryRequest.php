<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'album_id' => ['required', 'exists:albums,id'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:3020',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'album_id.required' => 'Album wajib dipilih.',
            'album_id.exists' => 'Album tidak valid.',
            'caption.max' => 'Keterangan maksimal 255 karakter.',
            'image.required' => 'Foto wajib diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}
