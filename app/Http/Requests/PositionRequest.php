<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $positionId = $this->route('position')?->id;

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('positions', 'name')->ignore($positionId),
            ],
            'max_members' => [
                'required',
                'integer',
                'min:1',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.max' => 'Nama jabatan maksimal 255 karakter.',
            'name.unique' => 'Nama jabatan sudah digunakan.',
            'max_members.required' => 'Maksimal anggota wajib diisi.',
            'max_members.integer' => 'Maksimal anggota harus berupa angka.',
            'max_members.min' => 'Maksimal anggota minimal 1 orang.',
        ];
    }
}
