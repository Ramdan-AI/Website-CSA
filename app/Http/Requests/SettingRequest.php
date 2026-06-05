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

            'site_name' => [
                'required',
                'max:255'
            ],

            'email' => [
                'nullable',
                'email'
            ],

            'phone' => [
                'nullable',
                'max:255'
            ],

            'address' => [
                'nullable'
            ],

            'instagram' => [
                'nullable',
                'max:255'
            ],

            'youtube' => [
                'nullable',
                'max:255'
            ],

            'linkedin' => [
                'nullable',
                'max:255'
            ],

            'about' => [
                'nullable'
            ]

        ];
    }
}
