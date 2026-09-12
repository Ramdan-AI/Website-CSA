<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => [
                'required',
                'exists:events,id',
            ],
            'member_id' => [
                'required',
                'exists:members,id',
                Rule::unique('event_registrations')->where(function ($query) {
                    return $query->where('event_id', $this->event_id);
                })->ignore($this->route('eventRegistration')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.unique' => 'Anggota ini sudah terdaftar di kegiatan tersebut.',
        ];
    }
}