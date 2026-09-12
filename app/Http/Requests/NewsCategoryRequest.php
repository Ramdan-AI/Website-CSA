<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('newsCategory');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('news_categories')->ignore($categoryId),
            ],
            'description' => [
                'nullable',
                'string'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}