<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
                'exists:events,id'
            ],

            'member_id' => [
                'required',
                'exists:members,id'
            ],

            'status' => [
                'required',
                'in:present,permit,sick,absent'
            ]

        ];
    }
}
