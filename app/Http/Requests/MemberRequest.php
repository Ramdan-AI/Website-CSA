<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('member')?->id;
        $isCreating = $this->isMethod('post');
        $member = $this->route('member');

        $rules = [
            'division_id' => [
                'required',
                'exists:divisions,id',
            ],

            'position_id' => [
                'nullable',
                'exists:positions,id',
                function ($attribute, $value, $fail) {
                    $position = \App\Models\Position::find($value);
                    $uniquePositions = ['Ketua', 'Wakil Ketua', 'Sekretaris 1', 'Sekretaris 2', 'Bendahara 1', 'Bendahara 2'];

                    if ($position && in_array($position->name, $uniquePositions)) {
                        $exists = \App\Models\Member::where('position_id', $value)
                            ->when($this->route('member'), fn($q) => $q->where('id', '!=', $this->route('member')->id))
                            ->exists();

                        if ($exists) {
                            $fail('Jabatan ' . $position->name . ' sudah terisi oleh anggota lain.');
                        }
                    }
                },
            ],

            'name' => ['required', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('members', 'email')
                    ->whereNull('deleted_at')
                    ->ignore($memberId),
            ],

            'nim' => ['nullable', 'max:100'],
            'phone' => ['nullable', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'max:255'],
            'github' => ['nullable', 'max:255'],
            'is_active' => ['boolean'],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ];

        // Validasi password
        if ($isCreating) {
            // Create: password wajib
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        } else {
            // Edit: password selalu opsional
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'division_id.required' => 'Divisi wajib dipilih.',
            'division_id.exists' => 'Divisi tidak valid.',
            'position_id.exists' => 'Jabatan tidak valid.',
            'name.required' => 'Nama anggota wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
            'linkedin.url' => 'Format URL LinkedIn tidak valid.',
            'password.required' => 'Password wajib diisi untuk membuat akun.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
