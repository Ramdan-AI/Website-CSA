<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Division;
use App\Models\Position;

class MemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'division_id' => [
                'required',
                'exists:divisions,id'
            ],

            'position_id' => [
                'required',
                'exists:positions,id'
            ],

            'name' => [
                'required',
                'max:255'
            ],

            'nim' => [
                'nullable',
                'max:100'
            ],

            'phone' => [
                'nullable',
                'max:50'
            ],

            'bio' => [
                'nullable'
            ],

            'linkedin' => [
                'nullable'
            ],

            'instagram' => [
                'nullable'
            ],

            'github' => [
                'nullable'
            ],

            'is_active' => [
                'nullable'
            ],

            'user_id' => [
                'nullable',
                'exists:users,id'
            ],
        ];
        
    }
}
