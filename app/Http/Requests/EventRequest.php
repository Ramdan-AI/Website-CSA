<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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

            'slug' => [
                'required',
                'max:255'
            ],

            'description' => [
                'required'
            ],

            'location' => [
                'nullable',
                'max:255'
            ],

            'event_date' => [
                'required',
                'date'
            ],

            'banner' => [
                'nullable',
                'max:255'
            ],

            'registration_link' => [
                'nullable',
                'max:255'
            ],

            'status' => [
                'required'
            ]

        ];
    }
}
