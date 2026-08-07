<?php

namespace App\Http\Requests;

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
                'string',
                'max:255'
            ],
            'description' => [
                'required',
                'string'
            ],
            'location' => [
                'nullable',
                'string',
                'max:255'
            ],
            'event_date' => [
                'required',
                'date'
            ],
            'banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
            'registration_link' => [
                'nullable',
                'url',
                'max:255'
            ],
            'status' => [
                'required',
                'in:draft,published,closed'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status kegiatan harus draft, published, atau closed.',
            'registration_link.url' => 'Link pendaftaran harus berupa URL yang valid.',
        ];
    }
}