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
        return [

            'title' => [
                'required',
                'max:255'
            ],

            'description' => [
                'nullable'
            ],

            'cover_image' => [
                'nullable',
                'max:3012'
            ]

        ];
    }
}
