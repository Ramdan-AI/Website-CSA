<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'max:255'
            ],

            'slug' => [
                'required',
                'max:255'
            ],

            'description' => [
                'nullable'
            ]

        ];
    }
}
