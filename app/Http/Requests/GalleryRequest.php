<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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

            'album_id' => [
                'required',
                'exists:albums,id'
            ],

            'image' => [
                'required',
                'max:255'
            ],

            'caption' => [
                'nullable',
                'max:255'
            ]

        ];
    }
}